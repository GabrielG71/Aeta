@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="p-6 max-w-4xl mx-auto bg-white rounded shadow">

    {{-- Mensagem de sucesso --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-4">Bem-vindo, {{ auth()->user()->name }}!</h1>

    @php
        $nivel = auth()->user()->admin;
    @endphp

    {{-- USUÁRIO COMUM --}}
    @if ($nivel === 0)
        <p class="text-gray-700">Bem-vindo ao <strong>AETA</strong>.</p>
    @endif

    {{-- ADMIN --}}
    @if ($nivel === 1 || $nivel === 2)
        <div class="mt-6">
            <button onclick="document.getElementById('user-management').classList.toggle('hidden')" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Gerenciar Usuários
            </button>

            <div id="user-management" class="hidden mt-4 border p-4 rounded bg-gray-100">
                <h2 class="text-lg font-semibold mb-2">Usuários</h2>

                {{-- Lista de usuários comuns --}}
                @foreach ($usuarios as $usuario)
                    <div class="mb-2 border-b pb-2">
                        <form action="{{ route('dashboard.editar', $usuario->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="text" name="name" value="{{ $usuario->name }}" required class="border p-1 rounded">
                            <input type="email" name="email" value="{{ $usuario->email }}" required class="border p-1 rounded">
                            <button type="submit" class="bg-yellow-500 text-white px-2 py-1 rounded">Editar</button>
                        </form>

                        <form action="{{ route('dashboard.remover', $usuario->id) }}" method="POST" class="inline ml-2">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Remover</button>
                        </form>
                    </div>
                @endforeach

                {{-- Formulário para adicionar novo usuário --}}
                <h2 class="text-lg font-semibold mt-6">Adicionar Novo Usuário</h2>
                <form action="{{ route('dashboard.adicionar') }}" method="POST" class="mt-2">
                    @csrf
                    <input type="text" name="name" placeholder="Nome" required class="border rounded p-1 mr-2">
                    <input type="email" name="email" placeholder="Email" required class="border rounded p-1 mr-2">
                    <input type="password" name="password" placeholder="Senha" required class="border rounded p-1 mr-2">
                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Adicionar</button>
                </form>
            </div>
        </div>
    @endif

    {{-- MASTER --}}
    @if ($nivel === 2)
        <div class="mt-6">
            <a href="{{ route('relatorio') }}" 
               class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                Relatório
            </a>
        </div>
    @endif
</div>
@endsection