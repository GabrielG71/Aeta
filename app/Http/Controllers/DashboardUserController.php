<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardUserController extends Controller
{
    public function adicionar(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'admin' => 0
        ]);

        return redirect()->route('dashboard')->with('success', 'Usuário adicionado com sucesso.');
    }

    public function editar(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->admin !== 0) {
            abort(403);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('dashboard')->with('success', 'Usuário editado com sucesso.');
    }

    public function remover($id)
    {
        $user = User::findOrFail($id);

        if ($user->admin !== 0) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('dashboard')->with('success', 'Usuário removido com sucesso.');
    }
}