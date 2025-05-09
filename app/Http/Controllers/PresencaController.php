<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class PresencaController extends Controller
{
    public function indexUser()
    {
        $usuario = Auth::user();

        // Total de presenças e faltas do usuário logado
        $totalPresencas = DB::table('presencas')
            ->where('user_id', $usuario->id)
            ->where('presente', 1)
            ->count();

        $totalFaltas = DB::table('presencas')
            ->where('user_id', $usuario->id)
            ->where('presente', 0)
            ->count();

        // Buscar embarque do usuário (opcional, se tiver tabela 'embarques')
        $embarque = DB::table('embarques')
            ->where('user_id', $usuario->id)
            ->first();

        return view('menu', compact('usuario', 'totalPresencas', 'totalFaltas', 'embarque'));
    }

    public function indexAdmin()
    {
        // Exibir apenas usuários comuns
        $usuarios = User::where('admin', 0)->get();
        return view('menu_admin', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $dataAtual = Carbon::now()->toDateString();

        foreach ($request->presenca as $userId => $valor) {
            DB::table('presencas')->updateOrInsert(
                ['user_id' => $userId, 'data' => $dataAtual],
                [
                    'presente' => $valor == 1 ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        return redirect()->back()->with('success', 'Presenças registradas com sucesso.');
    }

    public function verPresencas()
    {
        // Apenas usuários comuns
        $usuarios = User::where('admin', 0)->get();

        $presencasTotais = [];

        foreach ($usuarios as $usuario) {
            $totalPresencas = DB::table('presencas')
                ->where('user_id', $usuario->id)
                ->where('presente', 1)
                ->count();

            $totalFaltas = DB::table('presencas')
                ->where('user_id', $usuario->id)
                ->where('presente', 0)
                ->count();

            $presencasTotais[] = (object)[
                'nome' => $usuario->nome,
                'presencas' => $totalPresencas,
                'faltas' => $totalFaltas
            ];
        }

        return view('menu_presencas', compact('presencasTotais'));
    }

    public function atualizarPresencas(Request $request)
    {
        foreach ($request->presencas as $id => $presente) {
            DB::table('presencas')->where('id', $id)->update([
                'presente' => $presente == 1 ? 1 : 0,
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Presenças atualizadas.');
    }
}