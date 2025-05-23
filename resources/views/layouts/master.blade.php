<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Minha Aplicação')</title>
    @vite('resources/css/app.css')
    @yield('scripts')
</head>
<body class="bg-gray-100 text-gray-800 font-sans min-h-screen flex flex-col">

    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">NOVA AETA</h1>
            <nav class="space-x-4 flex items-center">
                @auth
                    <span class="text-gray-700">Olá, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Deslogar</button>
                    </form>
                @else
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600">Início</a>
                    <a href="{{ url('/contato') }}" class="text-gray-700 hover:text-blue-600">Contato</a>
                    <a href="{{ url('/login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-10">
        @yield('content')
    </main>

    <footer class="bg-blue-500 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            <div>
                <h3 class="text-lg font-semibold mb-2">AETA</h3>
                <p>&copy; 2024 AETA Tarumã. Todos os direitos reservados.</p>
                <p>CNPJ: 03.148.712/0001-40</p>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-2">Contato</h3>
                <p>📧 E-mail: aetataruma@gmail.com</p>
                <p>📍 Rua Jasmin 296, Centro - Tarumã</p>
                <p>📞 (18) 99646-4673</p>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-2">Navegação</h3>
                <ul class="space-y-1">
                    <li><a href="{{ url('/') }}" class="hover:underline">Home</a></li>
                    <li><a href="{{ url('/contato') }}" class="hover:underline">Contato</a></li>
                    <li><a href="{{ url('/login') }}" class="hover:underline">Login</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>