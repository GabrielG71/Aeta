<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $nivel = $user->admin;

        // Regras de visualização
        if ($nivel === 2) {
            // Master vê todos, menos ele mesmo
            $usuarios = User::where('id', '!=', $user->id)->get();
        } elseif ($nivel === 1) {
            // Admin comum vê apenas usuários comuns, exceto ele mesmo
            $usuarios = User::where('admin', 0)->where('id', '!=', $user->id)->get();
        } else {
            // Usuário comum não vê ninguém
            $usuarios = collect();
        }

        return view('dashboard', compact('usuarios', 'nivel'));
    }

    public function adicionarUsuario(Request $request)
    {
        $this->authorizeAdmin(); // Verifica se é admin ou master

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'cpf' => 'required|string|max:14',
            'password' => 'required|string|min:6',
            'admin' => 'required|integer'
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cpf' => $validated['cpf'],
            'password' => Hash::make($validated['password']),
            'admin' => $validated['admin']
        ]);

        return redirect()->route('dashboard')->with('success', 'Usuário adicionado com sucesso!');
    }

    public function editar(Request $request, $id)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'cpf' => 'required|string|max:14',
            'admin' => 'nullable|integer'
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cpf' => $validated['cpf'],
            'admin' => $validated['admin'] ?? $user->admin,
        ]);

        return redirect()->route('dashboard')->with('success', 'Usuário editado com sucesso.');
    }

    public function remover($id)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($id);

        // Impede exclusão de admins ou superiores
        if ($user->admin !== 0) {
            abort(403, 'Apenas usuários comuns podem ser removidos.');
        }

        $user->delete();

        return redirect()->route('dashboard')->with('success', 'Usuário removido com sucesso.');
    }

    // 🔒 Função de autorização extra
    protected function authorizeAdmin()
    {
        if (!in_array(Auth::user()->admin, [1, 2])) {
            abort(403, 'Acesso não autorizado.');
        }
    }
}