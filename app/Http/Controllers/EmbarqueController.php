<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Embarque;

class EmbarqueController extends Controller
{
    // Atualiza ou cria embarque para o usuário atual
    public function update(Request $request)
    {
        $request->validate([
            'local_embarque' => 'required|string|max:255',
            'local_desembarque' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        Embarque::updateOrCreate(
            ['user_id' => $user->id],
            [
                'local_embarque' => $request->local_embarque,
                'local_desembarque' => $request->local_desembarque,
            ]
        );

        return redirect()->back()->with('success', 'Locais atualizados com sucesso.');
    }
}