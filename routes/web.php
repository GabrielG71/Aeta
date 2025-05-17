<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\DashboardUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contato', function () {
    return view('contato');
});

Route::get('/dashboard', function () {
    $usuarios = User::where('admin', 0)->get();
    return view('dashboard', compact('usuarios'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/relatorio', function () {
    if (auth()->user()->admin !== 2) {
        abort(403);
    }

    return view('relatorio');
})->middleware(['auth', 'verified'])->name('relatorio');

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