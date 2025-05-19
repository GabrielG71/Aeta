<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\PagamentoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contato', function () {
    return view('contato');
});

// Rota para o dashboard dos admins
Route::get('/dashboard', [DashboardUserController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Rota para o menu do usuário comum
Route::get('/menu', function () {
    return view('menu'); // menu.blade.php
})->middleware(['auth'])->name('menu');

Route::get('/relatorio', function () {
    if (auth()->user()->admin !== 2) {
        abort(403);
    }

    return view('relatorio');
})->middleware(['auth', 'verified'])->name('relatorio');

Route::get('/pagamento', function () {
    if (!in_array(auth()->user()->admin, [1, 2])) {
        abort(403);
    }

    return view('pagamento');
})->middleware(['auth', 'verified'])->name('pagamento');

// Para admin/master acessar página de criação
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/pagamento', [PagamentoController::class, 'index'])->name('pagamento');
    Route::post('/pagamento', [PagamentoController::class, 'store'])->name('pagamento.store');
});

// Para usuário comum visualizar suas cobranças
Route::get('/menu', [PagamentoController::class, 'verPagamentosDoUsuario'])->middleware(['auth'])->name('menu');

Route::get('/pagamento/sucesso', function () {
    return view('pagamento.sucesso');
})->name('pagamento.sucesso');

Route::get('/pagamento/falha', function () {
    return view('pagamento.falha');
})->name('pagamento.falha');

Route::get('/eventos', function () {
    if (!in_array(auth()->user()->admin, [1, 2])) {
        abort(403);
    }

    return view('eventos');
})->middleware(['auth', 'verified'])->name('eventos');

Route::get('/dashboard', [DashboardUserController::class, 'index'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/dashboard/adicionar', [DashboardUserController::class, 'adicionarUsuario'])->name('dashboard.adicionarUsuario');
    Route::post('/dashboard/editar/{id}', [DashboardUserController::class, 'editar'])->name('dashboard.editar');
    Route::delete('/dashboard/remover/{id}', [DashboardUserController::class, 'remover'])->name('dashboard.remover');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';