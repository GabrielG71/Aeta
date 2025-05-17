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
        $nivel = $user->admin; // 0 = comum, 1 = admin, 2 = master

        // Todos os usuários podem acessar, mas só admin/master veem a lista de usuários
        $usuarios = ($nivel >= 1) ? User::all() : collect();

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