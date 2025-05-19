@extends('layouts.master')

@section('content')
<div class="max-w-xl mx-auto mt-10">
    <h2 class="text-xl font-bold mb-4">Criar Pagamento</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('pagamento.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block">Usuário</label>
            <select name="user_id" class="w-full border p-2 rounded">
                @foreach(\App\Models\User::where('admin', 0)->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} (CPF: {{ $user->cpf }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block">Descrição</label>
            <input type="text" name="descricao" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block">Valor (R$)</label>
            <input type="number" name="valor" step="0.01" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block">Prazo de Pagamento</label>
            <input type="date" name="prazo_pagamento" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
            Criar Pagamento
        </button>
    </form>
</div>
@endsection