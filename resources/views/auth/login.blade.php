@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 shadow rounded">
    <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Login</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="cpf" class="block text-sm font-medium">CPF</label>
            <input type="text" name="cpf" id="cpf" maxlength="14" value="{{ old('cpf') }}" class="w-full border border-gray-300 p-2 rounded" required autofocus>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium">Senha</label>
            <div class="relative">
                <input type="password" name="password" id="password" class="w-full border border-gray-300 p-2 rounded pr-10" required>
                <button type="button" onclick="toggleSenha()" class="absolute inset-y-0 right-2 flex items-center text-gray-500">
                    👁️
                </button>
            </div>
        </div>

        <div class="text-right text-sm">
            <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">Esqueceu sua senha?</a>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Entrar
        </button>
    </form>
</div>

<script>
    document.getElementById('cpf').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = value;
    });

    function toggleSenha() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endsection