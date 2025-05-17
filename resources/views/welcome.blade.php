@extends('layouts.master')

@section('title', 'Início')

@section('scripts')
    <script src="{{ asset('js/carrossel.js') }}"></script>
@endsection

@section('content')
    <div class="relative w-full max-w-4xl mx-auto mb-12">
        <div class="overflow-hidden rounded-2xl shadow-lg relative h-64" id="carousel">
            <div class="flex transition-transform duration-700 ease-in-out w-full h-full" id="carousel-images">
                <img src="/images/slide1.jpg" class="w-full object-cover flex-shrink-0" alt="Slide 1">
                <img src="/images/slide2.jpg" class="w-full object-cover flex-shrink-0" alt="Slide 2">
                <img src="/images/slide3.jpg" class="w-full object-cover flex-shrink-0" alt="Slide 3">
            </div>
            <button id="prev" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 text-gray-800 px-3 py-1 rounded-full shadow">
                ‹
            </button>
            <button id="next" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 text-gray-800 px-3 py-1 rounded-full shadow">
                ›
            </button>
        </div>
    </div>

    <div class="text-center space-y-6">
        <h2 class="text-3xl font-bold text-blue-600">Bem-vindo à Nova AETA</h2>
        <p>A Associação de Estudantes de Tarumã (AETA), fundada em 18 de abril de 1999, é uma entidade civil de direito privado, sem fins lucrativos, com sede no município de Tarumã, Estado de São Paulo, situada na Rua Jasmim, nº 296, Centro. Com caráter permanente, a AETA tem como missão fundamental a promoção da educação, da cultura e do bem-estar dos estudantes, buscando sempre a integração social e o fortalecimento do ambiente acadêmico e comunitário.</p>

                <p>A AETA dedica-se à promoção cultural, à defesa e à conservação do patrimônio histórico e artístico, além de fomentar o intercâmbio cultural entre seus associados. Com isso, a associação visa criar um ambiente de cooperação e sociabilidade, formando um verdadeiro espírito estudantil, com ênfase no desenvolvimento da prosperidade e do caráter. Em suas atividades, a AETA respeita os princípios da legalidade, impessoalidade, moralidade, publicidade, economicidade e eficiência, garantindo que todos os recursos obtidos sejam integralmente aplicados na consecução de seus objetivos sociais, sem qualquer distribuição de lucros ou dividendos entre seus membros ou colaboradores.</p>
                
                <p>Além disso, a AETA presta serviços essenciais à comunidade estudantil, como o transporte escolar, em parceria com a Prefeitura Municipal de Tarumã. Esse serviço tem como objetivo garantir a segurança e o conforto dos estudantes, oferecendo transporte adequado, com veículos apropriados e motoristas devidamente qualificados. A associação compromete-se a zelar pela manutenção dos padrões de qualidade e segurança estabelecidos pela parceria, assegurando que os usuários que estejam em dia com suas obrigações financeiras possam usufruir dos benefícios do transporte escolar, mediante a emissão da carteirinha de sócio.</p>
                
                <p>O contratante, no âmbito deste serviço, compromete-se a pagar uma taxa simbólica de utilização do transporte escolar, além de uma taxa de associação no valor de R$ 50,00, acrescida das despesas administrativas. Adicionalmente, o contratante se compromete a participar das atividades de cidadania promovidas pela Secretaria Municipal de Educação, Esportes e Cultura, em colaboração com a AETA, cumprindo uma carga mínima de 40% de participação nas atividades programadas ao longo do ano. Para os contratantes que se encontram no último ano de seu curso, a participação nas atividades de cidadania será obrigatória, conforme estipulado pelo Estatuto da associação.</p>
                
                <p>Além dessas responsabilidades, o contratante deve observar rigorosamente as normas de convivência durante o uso do transporte escolar, mantendo comportamento adequado, respeito aos demais passageiros e ao motorista, e abstendo-se de portar substâncias ilícitas ou prejudiciais, como drogas ou bebidas alcoólicas. O não cumprimento das normas poderá resultar no pagamento de contribuições adicionais, conforme estabelecido no contrato.</p>
                
                <p>A estrutura administrativa da AETA é composta pelos seguintes membros da diretoria: Presidente: Otávio Sérgio Varoto, Vice-presidente: Camila Pereira Mattos da Rocha, 1º Tesoureiro: Ana Clara Mazul Viana, 2º Tesoureiro: Marcos Vinicius Mascari de Oliveira, 1º Secretário: Lucas Oliveira de Almeida, 2º Secretário: Anelize Gomes Cardoso Rocha, Presidente do Conselho Fiscal: Giovana Renzi da Silva, Secretário do Conselho Fiscal: Gabriela Soares de Oliveira, e Membro do Conselho Fiscal: João Pedro Mossini Alcides.</p>
                
                <p>A AETA, com base em seu Regimento Interno, aprovado pela Assembleia Geral, reafirma seu compromisso com a excelência, transparência e responsabilidade em suas ações, buscando sempre o desenvolvimento integral de seus associados e a melhoria contínua dos serviços prestados. Para quaisquer informações adicionais ou esclarecimentos, a AETA pode ser contatada pelos seguintes meios de comunicação: Endereço: Rua Jasmim, nº 296, Centro de Tarumã-SP, E-mail: aetataruma@gmail.com, Telefone: +55 18 99646-4673.</p>
        <a href="/arquivos/informativo.pdf" download
           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition duration-300">
            Baixar PDF
        </a>
    </div>
@endsection