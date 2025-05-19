@extends('layouts.master')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h2 class="text-xl font-bold mb-4">Meus Pagamentos</h2>

    @if($pagamentos->isEmpty())
        <p>Você não tem cobranças pendentes.</p>
    @else
        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Descrição</th>
                    <th class="p-2 border">Valor</th>
                    <th class="p-2 border">Prazo</th>
                    <th class="p-2 border">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagamentos as $pagamento)
                    <tr>
                        <td class="border p-2">{{ $pagamento->descricao }}</td>
                        <td class="border p-2">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                        <td class="border p-2">{{ \Carbon\Carbon::parse($pagamento->prazo_pagamento)->format('d/m/Y') }}</td>
                        <td class="border p-2">
                            <a href="{{ $pagamento->link_checkout }}" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700" target="_blank">Pagar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
