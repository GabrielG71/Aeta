@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="p-6 max-w-5xl mx-auto bg-white rounded shadow">
    {{-- Dar bem vindo ao usuario --}}
    <h1 class="text-2xl font-bold mb-4">Bem-vindo, {{ auth()->user()->name }}!</h1>

    {{-- Define o nível de permissão do usuário (0 = comum, 1 = admin, 2 = master) --}}
    @php
        $nivel = auth()->user()->admin;
    @endphp

    {{-- Painel para usuários comuns --}}
    @if ($nivel === 0)
        <p class="text-gray-700">Você está logado como. Bem-vindo ao sistema AETA.</p>
    @endif

    {{-- Painel administrativo: nível 1 (admin) ou 2 (master) --}}
    @if ($nivel === 1 || $nivel === 2)
        <div class="mt-6">
            {{-- Botão para exibir/ocultar painel de gestão de usuários --}}
            <button onclick="document.getElementById('user-management').classList.toggle('hidden')"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Gerenciar Usuários
            </button>

            {{-- Painel de gerenciamento de usuários --}}
            <div id="user-management" class="hidden mt-4 border p-4 rounded bg-gray-100">
                {{-- Tabela com todos os usuários --}}
                <h2 class="text-lg font-semibold mb-2">Usuários</h2>
                <table class="w-full text-left border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">Nome</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">CPF</th>
                            <th class="p-2">Admin</th>
                            <th class="p-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                            <tr class="border-t">
                                <td class="p-2">{{ $usuario->name }}</td>
                                <td class="p-2">{{ $usuario->email }}</td>
                                <td class="p-2">{{ $usuario->cpf }}</td>
                                <td class="p-2">
                                    @if ($usuario->admin === 0) Comum
                                    @elseif ($usuario->admin === 1) Admin
                                    @elseif ($usuario->admin === 2) Master
                                    @endif
                                </td>
                                <td class="p-2">
                                    {{-- Botão de excluir usuário --}}
                                    <form action="{{ route('dashboard.remover', $usuario->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover este usuário?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline mr-2">Excluir</button>
                                    </form>

                                    {{-- Botão de editar --}}
                                    <button onclick="toggleEditForm({{ $usuario->id }})" class="text-blue-600 hover:underline">Editar</button>

                                    {{-- Formulário para edição do usuário --}}
                                    <form id="edit-form-{{ $usuario->id }}" action="{{ route('dashboard.editar', $usuario->id) }}" method="POST" class="mt-2 hidden">
                                        @csrf
                                        <input type="text" name="name" value="{{ $usuario->name }}" required class="border p-1 rounded">
                                        <input type="email" name="email" value="{{ $usuario->email }}" required class="border p-1 rounded">
                                        <input type="text" name="cpf" value="{{ $usuario->cpf }}" required class="border p-1 rounded">
                                        @if ($nivel === 2)
                                            <select name="admin" class="border p-1 rounded">
                                                <option value="0" {{ $usuario->admin == 0 ? 'selected' : '' }}>Comum</option>
                                                <option value="1" {{ $usuario->admin == 1 ? 'selected' : '' }}>Admin</option>
                                                <option value="2" {{ $usuario->admin == 2 ? 'selected' : '' }}>Master</option>
                                            </select>
                                        @endif
                                        <button type="submit" class="bg-yellow-500 text-white px-2 py-1 rounded ml-2">Salvar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Formulário para adicionar novo usuário --}}
                <h2 class="text-lg font-semibold mt-6">Adicionar Novo Usuário</h2>
                <form action="{{ route('dashboard.adicionarUsuario') }}" method="POST" class="mt-2">
                    @csrf
                    <input type="text" name="name" placeholder="Nome" required class="border rounded p-1 mr-2">
                    <input type="email" name="email" placeholder="Email" required class="border rounded p-1 mr-2">
                    <input type="text" name="cpf" placeholder="CPF" required class="border rounded p-1 mr-2">
                    <input type="password" name="password" placeholder="Senha" required class="border rounded p-1 mr-2">
                    @if ($nivel === 2)
                        <select name="admin" class="border rounded p-1 mr-2">
                            <option value="0">Comum</option>
                            <option value="1">Admin</option>
                            <option value="2">Master</option>
                        </select>
                    @else
                        {{-- Usuário admin (nível 1) só pode criar outros usuários comuns --}}
                        <input type="hidden" name="admin" value="0">
                    @endif
                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Adicionar</button>
                </form>
            </div>
        </div>
    @endif

    {{-- Painel extra apenas para nível master --}}
    @if ($nivel === 2)
        <div class="mt-6">
            <a href="{{ route('relatorio') }}"
               class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                Relatório
            </a>
        </div>
    @endif
</div>

{{-- Script JS para alternar visibilidade dos formulários de edição --}}
<script>
    function toggleEditForm(id) {
        const form = document.getElementById('edit-form-' + id);
        if (form) {
            form.classList.toggle('hidden');
        }
    }
</script>
@endsection