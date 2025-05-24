<?php

// Importação dos controllers e classes necessárias
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\PagamentoController;

// Rota para a página inicial (welcome.blade.php)
Route::get('/', function () {
    return view('welcome');
});

// Rota para a página de contato (contato.blade.php)
Route::get('/contato', function () {
    return view('contato');
});

// Rota protegida que leva ao painel de administração
// Apenas usuários autenticados podem acessar
Route::get('/dashboard', [DashboardUserController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Rota protegida que leva ao menu do usuário comum
// Também exige autenticação
Route::get('/menu', [PagamentoController::class, 'verPagamentosDoUsuario'])
    ->middleware(['auth'])
    ->name('menu');

// Rota protegida para exibir o relatório
// Somente usuários com atributo "admin" igual a 2 podem acessar
Route::get('/relatorio', function () {
    if (auth()->user()->admin !== 2) {
        abort(403); // Acesso negado
    }

    return view('relatorio');
})->middleware(['auth', 'verified'])->name('relatorio');

// Agrupamento de rotas protegidas para operações de pagamento (somente usuários verificados e autenticados)
Route::middleware(['auth', 'verified'])->group(function () {
    // Exibe a página de pagamentos
    Route::get('/pagamento', [PagamentoController::class, 'index'])->name('pagamento');
    
    // Rota para criação de um novo pagamento
    Route::post('/pagamento', [PagamentoController::class, 'store'])->name('pagamento.store');
    
    // Rota para atualizar um pagamento existente
    Route::put('/pagamento/{id}', [PagamentoController::class, 'update'])->name('pagamento.update');
    
    // Rota para deletar um pagamento existente
    Route::delete('/pagamento/{id}', [PagamentoController::class, 'destroy'])->name('pagamento.destroy');
});

// Rotas públicas de retorno do Mercado Pago após uma tentativa de pagamento
Route::get('/pagamento/sucesso', [PagamentoController::class, 'sucesso'])->name('pagamento.sucesso');
Route::get('/pagamento/falha', [PagamentoController::class, 'falha'])->name('pagamento.falha');
Route::get('/pagamento/pendente', [PagamentoController::class, 'pendente'])->name('pagamento.pendente');

// Webhook público do Mercado Pago para receber notificações de pagamento
Route::post('/webhook/mercadopago', [PagamentoController::class, 'webhook'])->name('webhook.mercadopago');

// Rota protegida para visualizar eventos
// Somente usuários com nível de admin igual a 1 ou 2 podem acessar
Route::get('/eventos', function () {
    if (!in_array(auth()->user()->admin, [1, 2])) {
        abort(403); // Acesso negado
    }

    return view('eventos');
})->middleware(['auth', 'verified'])->name('eventos');

// Grupo de rotas para gerenciar usuários no dashboard (admin/master)
// Protegido por autenticação e verificação de e-mail
Route::middleware(['auth', 'verified'])->group(function () {
    // Adiciona um novo usuário
    Route::post('/dashboard/adicionar', [DashboardUserController::class, 'adicionarUsuario'])->name('dashboard.adicionarUsuario');
    
    // Edita os dados de um usuário existente
    Route::post('/dashboard/editar/{id}', [DashboardUserController::class, 'editar'])->name('dashboard.editar');
    
    // Remove um usuário
    Route::delete('/dashboard/remover/{id}', [DashboardUserController::class, 'remover'])->name('dashboard.remover');
});

// Grupo de rotas para edição do perfil do usuário autenticado
Route::middleware('auth')->group(function () {
    // Exibe o formulário de edição de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Atualiza os dados do perfil
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Exclui o perfil do usuário
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Importa as rotas de autenticação padrão do Laravel Breeze (login, registro, etc.)
require __DIR__.'/auth.php';