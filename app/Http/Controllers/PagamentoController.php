<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

class PagamentoController extends Controller
{
    public function index()
    {
        return view('pagamento');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:1',
            'prazo_pagamento' => 'required|date',
        ]);

        // Define o token do Mercado Pago
        MercadoPagoConfig::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN'));

        // Define URLs de retorno manualmente para garantir que são públicas
        $successUrl = 'https://ff83-187-73-202-18.ngrok-free.app/pagamento/sucesso';
        $failureUrl = 'https://ff83-187-73-202-18.ngrok-free.app/pagamento/falha';

        try {
            // Cria o cliente e preferência de pagamento
            $client = new PreferenceClient();
            $preference = $client->create([
                "items" => [
                    [
                        "title" => $request->descricao,
                        "quantity" => 1,
                        "unit_price" => (float) $request->valor,
                    ]
                ],
                "back_urls" => [
                    "success" => $successUrl,
                    "failure" => $failureUrl,
                ],
                "auto_return" => "approved"
            ]);

            // Salva no banco
            Pagamento::create([
                'user_id' => $request->user_id,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'prazo_pagamento' => $request->prazo_pagamento,
                'link_checkout' => $preference->init_point,
            ]);

            return redirect()->back()->with('success', 'Cobrança criada com sucesso!');
        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            // Mostra resposta da API Mercado Pago para debug
            dd('Erro da API Mercado Pago:', $e->getApiResponse());
        } catch (\Exception $e) {
            // Mostra erro geral
            dd('Erro geral:', $e->getMessage());
        }
    }

    public function verPagamentosDoUsuario()
    {
        $pagamentos = Pagamento::where('user_id', auth()->id())->get();
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
}