<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Rules\CpfValido;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'cpf' => ['required', 'string', new CpfValido],
            'senha' => ['required', 'string'],
        ]);

        $cpfLimpo = preg_replace('/\D/', '', $request->cpf);

        $user = User::where('cpf', $cpfLimpo)->first();

        if ($user && Hash::check($request->senha, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            return $user->admin ? redirect('/menu_admin') : redirect('/menu');
        }

        return back()->withErrors(['cpf' => 'CPF ou senha incorretos.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}