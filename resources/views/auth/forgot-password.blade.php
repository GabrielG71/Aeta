@extends('layouts.master')

@section('title', 'Esqueceu a Senha?')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 shadow rounded">
    <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Recuperar Senha</h2>

    {{-- Mensagem de status da sessão (ex: "Link enviado") --}}
    @if (session('status'))
        <div class="mb-4 text-green-600">{{ session('status') }}</div>
    @endif

    {{-- Formulário de envio do e-mail --}}
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                   class="w-full border border-gray-300 p-2 rounded mt-1 shadow-sm focus:ring-blue-500 focus:border-blue-500">

            {{-- Erros de validação --}}
            @error('email')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Enviar link de redefinição
        </button>
    </form>
</div>
@endsection