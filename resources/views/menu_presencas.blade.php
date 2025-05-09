@extends('master')

@section('title', 'Presenças Gerais')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-blue-600 mb-2">Presenças Total</h2>
        <table class="w-full bg-white shadow-md rounded overflow-hidden">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-4 py-2 text-left">Nome</th>
                    <th class="px-4 py-2 text-left">Presenças</th>
                    <th class="px-4 py-2 text-left">Faltas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($presencasTotais as $p)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $p->nome }}</td>
                        <td class="px-4 py-2">{{ $p->presencas }}</td>
                        <td class="px-4 py-2">{{ $p->faltas }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('menu_admin') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Voltar</a>
    </div>
@endsection