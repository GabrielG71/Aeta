@extends('master')

@section('title', 'Menu')

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <h2 class="text-3xl font-semibold mb-6 text-blue-600">Bem-vindo, {{ auth()->user()->nome }}!</h2>

        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-2 text-gray-700">Suas Presenças</h3>
            <table class="w-full table-auto text-center border-collapse bg-gray-50 shadow rounded">
                <thead class="bg-blue-100 text-blue-800">
                    <tr>
                        <th class="p-3 border">Presenças</th>
                        <th class="p-3 border">Faltas</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <tr>
                        <td class="p-3 border">{{ $totalPresencas }}</td>
                        <td class="p-3 border">{{ $totalFaltas }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div>
            <h3 class="text-xl font-semibold mb-2 text-gray-700">Local de Embarque / Desembarque</h3>
            <table class="w-full table-auto text-center border-collapse bg-gray-50 shadow rounded">
                <thead class="bg-blue-100 text-blue-800">
                    <tr>
                        <th class="p-3 border">Embarque</th>
                        <th class="p-3 border">Desembarque</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <tr>
                        <td class="p-3 border">{{ $embarque->local_embarque ?? 'Não informado' }}</td>
                        <td class="p-3 border">{{ $embarque->local_desembarque ?? 'Não informado' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection