@extends('master')

@section('title', 'Menu Admin')

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <h2 class="text-3xl font-semibold mb-6 text-blue-600">
            Lista de Presença – {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
        </h2>

        <form action="{{ route('registrar.presenca') }}" method="POST">
            @csrf
            <table class="w-full table-auto text-center border-collapse bg-gray-50 shadow rounded mb-6">
                <thead class="bg-blue-100 text-blue-800">
                    <tr>
                        <th class="p-3 border">Nome</th>
                        <th class="p-3 border">Presente</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach($usuarios as $usuario)
                        <tr class="hover:bg-blue-50">
                            <td class="p-3 border">{{ $usuario->nome }}</td>
                            <td class="p-3 border">
                                {{-- Hidden para garantir que mesmo se o checkbox estiver desmarcado, um valor seja enviado --}}
                                <input type="hidden" name="presenca[{{ $usuario->id }}]" value="0">
                                <input type="checkbox" name="presenca[{{ $usuario->id }}]" value="1" class="form-checkbox text-blue-600">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">
                    Salvar
                </button>
            </div>
        </form>

        <a href="{{ route('menu.presencas') }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded hover:bg-blue-200 inline-block mt-4">
            Gerenciar Presenças Gerais
        </a>
    </div>
@endsection