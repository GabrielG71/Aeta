@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Bem-vindo, {{ auth()->user()->name }}!</h1>

    @php
        $nivel = auth()->user()->admin;
    @endphp

    @if ($nivel === 1)
        <p class="text-green-700">Você é um <strong>Administrador</strong>.</p>
        {{-- Conteúdo exclusivo para admin --}}
    @elseif ($nivel === 2)
        <p class="text-blue-700">Você é um <strong>Master</strong>.</p>
        {{-- Conteúdo exclusivo para master --}}
    @else
        <p class="text-gray-700">Você é um <strong>usuário comum</strong>.</p>
        {{-- Conteúdo para usuários comuns --}}
    @endif

    {{-- Conteúdo comum para todos os usuários --}}
    <div class="mt-6">
        <p>Aqui vai o conteúdo comum da dashboard...</p>
    </div>
</div>
@endsection