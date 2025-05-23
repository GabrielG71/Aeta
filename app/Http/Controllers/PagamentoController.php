<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\User;
use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PagamentoController extends Controller
{
    public function index()
    {
        $pagamentos = Pagamento::with('users')->orderBy('created_at', 'desc')->get();
        $usuarios = User::where('admin', 0)->get();
        
        return view('pagamento', compact('pagamentos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:1',
            'prazo_pagamento' => 'required|date',
        ]);

        // Verificar se o token do Mercado Pago está configurado
        $accessToken = env('MERCADO_PAGO_ACCESS_TOKEN');
        if (!$accessToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token do Mercado Pago não configurado. Verifique o arquivo .env'
            ], 500);
        }

        try {
            // Configurar o Mercado Pago
            MercadoPagoConfig::setAccessToken($accessToken);

            // URLs de retorno
            $baseUrl = config('app.url');
            $successUrl = $baseUrl . '/pagamento/sucesso';
            $failureUrl = $baseUrl . '/pagamento/falha';
            $pendingUrl = $baseUrl . '/pagamento/pendente';
            $webhookUrl = $baseUrl . '/webhook/mercadopago';

            // Preparar dados da preferência com estrutura corrigida
            $preferenceData = [
                "items" => [
                    [
                        "id" => "item_" . uniqid(),
                        "title" => $request->descricao,
                        "description" => "Pagamento: " . $request->descricao,
                        "category_id" => "services",
                        "quantity" => 1,
                        "currency_id" => "BRL",
                        "unit_price" => floatval($request->valor),
                    ]
                ],
                "payer" => [
                    "name" => "Cliente",
                    "email" => "cliente@exemplo.com"
                ],
                "back_urls" => [
                    "success" => $successUrl,
                    "failure" => $failureUrl,
                    "pending" => $pendingUrl
                ],
                "auto_return" => "approved",
                "payment_methods" => [
                    "installments" => 12,
                    "default_installments" => 1
                ],
                "notification_url" => $webhookUrl,
                "expires" => true,
                "expiration_date_from" => Carbon::now()->toISOString(),
                "expiration_date_to" => Carbon::parse($request->prazo_pagamento)->endOfDay()->toISOString(),
                "metadata" => [
                    "pagamento_descricao" => $request->descricao,
                    "valor" => strval($request->valor),
                    "prazo" => $request->prazo_pagamento,
                    "usuarios_count" => count($request->user_ids)
                ]
            ];

            // Log dos dados enviados para debug
            Log::info('Criando preferência no Mercado Pago:', [
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'users_count' => count($request->user_ids),
                'preference_data' => $preferenceData
            ]);

            // Criar cliente e preferência
            $client = new PreferenceClient();
            $preference = $client->create($preferenceData);

            // Log da resposta para debug
            Log::info('Resposta do Mercado Pago:', [
                'preference_id' => $preference->id ?? 'NULL',
                'init_point' => $preference->init_point ?? 'NULL',
                'sandbox_init_point' => $preference->sandbox_init_point ?? 'NULL'
            ]);

            // Verificar se a preferência foi criada com sucesso
            if (!$preference->id) {
                throw new \Exception('Preferência não foi criada - ID não retornado');
            }

            // Determinar qual link usar baseado no ambiente
            $checkoutLink = $preference->init_point;
            
            if (env('MERCADO_PAGO_SANDBOX', true)) {
                $checkoutLink = $preference->sandbox_init_point ?? $preference->init_point;
            }

            if (!$checkoutLink) {
                throw new \Exception('Link de checkout não foi gerado pela API');
            }

            // Criar o pagamento no banco de dados
            $pagamento = Pagamento::create([
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'prazo_pagamento' => $request->prazo_pagamento,
                'link_checkout' => $checkoutLink,
            ]);

            // Associar os usuários ao pagamento
            $userStatusData = [];
            foreach ($request->user_ids as $userId) {
                $userStatusData[$userId] = ['status' => 'pendente'];
            }
            $pagamento->users()->attach($userStatusData);

            Log::info('Pagamento criado com sucesso:', [
                'pagamento_id' => $pagamento->id,
                'checkout_url' => $checkoutLink,
                'users_count' => count($request->user_ids)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cobrança criada com sucesso para ' . count($request->user_ids) . ' usuário(s)!',
                'data' => [
                    'pagamento_id' => $pagamento->id,
                    'checkout_url' => $checkoutLink,
                    'preference_id' => $preference->id
                ]
            ]);

        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            // Log detalhado do erro da API
            Log::error('Erro da API Mercado Pago:', [
                'message' => $e->getMessage(),
                'status_code' => $e->getStatusCode(),
                'response_body' => $e->getApiResponse(),
                'request_data' => $preferenceData ?? 'N/A'
            ]);

            // Tentar extrair mensagem mais específica do erro
            $errorMessage = 'Erro da API Mercado Pago: ' . $e->getMessage();
            $apiResponse = $e->getApiResponse();
            
            if (is_array($apiResponse) && isset($apiResponse['message'])) {
                $errorMessage = 'Mercado Pago: ' . $apiResponse['message'];
            } elseif (is_array($apiResponse) && isset($apiResponse['cause'])) {
                if (is_array($apiResponse['cause'])) {
                    $causes = array_map(function($cause) {
                        return isset($cause['description']) ? $cause['description'] : json_encode($cause);
                    }, $apiResponse['cause']);
                    $errorMessage = 'Mercado Pago: ' . implode(', ', $causes);
                } else {
                    $errorMessage = 'Mercado Pago: ' . json_encode($apiResponse['cause']);
                }
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'details' => [
                    'status_code' => $e->getStatusCode(),
                    'api_response' => $apiResponse
                ]
            ], 500);

        } catch (\Exception $e) {
            Log::error('Erro geral ao criar pagamento:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:1',
            'prazo_pagamento' => 'required|date',
        ]);

        try {
            $pagamento = Pagamento::findOrFail($id);
            
            $pagamento->update([
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'prazo_pagamento' => $request->prazo_pagamento,
            ]);

            // Atualizar os usuários associados mantendo o status atual
            $currentUsers = $pagamento->users()->pluck('users.id')->toArray();
            $newUsers = $request->user_ids;
            
            // Remover usuários que não estão mais na lista
            $usersToRemove = array_diff($currentUsers, $newUsers);
            if (!empty($usersToRemove)) {
                $pagamento->users()->detach($usersToRemove);
            }
            
            // Adicionar novos usuários
            $usersToAdd = array_diff($newUsers, $currentUsers);
            if (!empty($usersToAdd)) {
                $newUserData = [];
                foreach ($usersToAdd as $userId) {
                    $newUserData[$userId] = ['status' => 'pendente'];
                }
                $pagamento->users()->attach($newUserData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pagamento atualizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar pagamento:', [
                'pagamento_id' => $id,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pagamento = Pagamento::findOrFail($id);
            $pagamento->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pagamento excluído com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao excluir pagamento:', [
                'pagamento_id' => $id,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verPagamentosDoUsuario()
    {
        $pagamentos = auth()->user()->pagamentos()->get();
        return view('menu', compact('pagamentos'));
    }

    public function sucesso()
    {
        return view('pagamento.sucesso');
    }

    public function falha()
    {
        return view('pagamento.falha');
    }

    public function pendente()
    {
        return view('pagamento.pendente');
    }

    // Método para receber webhooks do Mercado Pago
    public function webhook(Request $request)
    {
        Log::info('Webhook recebido do Mercado Pago:', $request->all());
        
        // Implementar lógica de processamento do webhook aqui
        // Para atualizar status dos pagamentos automaticamente
        
        return response()->json(['status' => 'ok'], 200);
    }
}