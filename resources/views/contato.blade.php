@extends('layouts.master')

@section('title', 'Contato')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Fale Conosco</h2>
        <form method="POST" action="https://formsubmit.co/gabrielgoncalves2981@gmail.com" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="_next" value="https://gabrielg71.github.io/Prefeitura-taruma/obrigado.html">

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome:</label>
                <input type="text" id="name" name="name" placeholder="Seu nome completo"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" id="email" name="email" placeholder="Seu email"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="cpf" class="block text-sm font-medium text-gray-700">CPF:</label>
                <input type="text" id="cpf" name="cpf" placeholder="CPF"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">Assunto:</label>
                <textarea id="message" name="message" placeholder="Escreva sua mensagem"
                    rows="4"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" required></textarea>
            </div>

            <div>
                <label for="attachment" class="block text-sm font-medium text-gray-700">Anexar Arquivo (PNG ou JPG):</label>
                <input type="file" name="attachment" id="attachment" accept="image/png, image/jpeg"
                    class="mt-1 w-full text-gray-700">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
                Enviar
            </button>
        </form>
    </div>
@endsection