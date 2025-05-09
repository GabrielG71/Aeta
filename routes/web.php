<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PresencaController;


Route::get('/', [HomeController::class, 'index']);
Route::get('/contato', [HomeController::class, 'contato']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/menu', [PresencaController::class, 'indexUser'])->name('menu');
    Route::get('/menu_admin', [PresencaController::class, 'indexAdmin'])->name('menu_admin');
    Route::post('/registrar-presenca', [PresencaController::class, 'store'])->name('registrar.presenca');
    Route::get('/menu_presencas', [PresencaController::class, 'verPresencas'])->name('menu.presencas');
    Route::post('/menu_presencas/atualizar', [PresencaController::class, 'atualizarPresencas'])->name('menu.presencas.atualizar');
});