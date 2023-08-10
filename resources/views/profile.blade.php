<!-- DEFINIÇÃO: página do perfil de um utilizador -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- TÍTULO DA PÁGINA -->
    <title>{{ $user->name; }}</title>

    <!-- METADADOS -->
    <meta charset="utf-8">
    <meta name="description" content="Uma rede social alternativa! Escreva os seus melhores posts.">
    <meta name="keywords" content="Rede Social">
    <meta name="author" content="Lara Ribeiro, Luís Pereira, Maria Costa">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('images/favicon.ico') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ URL::asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/footer.css') }}">

    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- JS -->
    <script type="text/javascript" src="{{ URL::asset('js/main.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/function.js') }}"></script>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <script src="https://kit.fontawesome.com/ed5c768cb2.js" crossorigin="anonymous"></script>

    <!-- FONT FAMILY -->
    <link href="https://fonts.googleapis.com/css2?family=Nova+Round&family=Nunito:wght@300;400&display=swap" rel="stylesheet">
</head>

<body>
    <!-- CABEÇALHO: menu de navegação, logótipo -->
    <header>
        @include("nav")
        @include("new-post")
        @include("about")
    </header>


    <!-- CONTEÚDO -->
    <div>
        <!-- INFORMAÇÕES DO UTILIZADOR -->
        <section class="profile__header-wrapper">
            <div>
                <i class="profile__avatar fas fa-user-circle"></i>
                <h2 class="profile__name">{{ $user->name; }}</h2>
            </div>
        </section>


        <!-- OPÇÕES DE LAYOUT -->
        <section class="layout-options">
            <i class="fas fa-th layout-options__icon layout-options__icon--active" data-grid="width3" data-toggle="tooltip" data-placement="bottom" title="Vista em 3x3"></i>
            <i class="fas fa-th-list layout-options__icon" data-grid="width1" data-toggle="tooltip" data-placement="bottom" title="Vista em Linha"></i>
        </section>


        <!-- PRINCIPAL: posts -->
        <main class="brief-posts">
            @if(count($posts) > 0)

            @php
            // contador auxiliar para saber quando já foram criados 3 posts
            $counter = 0;
            $current = 0;
            @endphp

            <!-- mostrar posts (3 em 3 por padrão) -->
            @foreach ($posts as $post)

            @php
            $counter++;
            @endphp

            @if ($current % 3 == 0)

            <section class="brief-posts__post">
                @endif

                @include("brief-post")

                @if ($counter == 3)
            </section>

            @php
            $counter = 0;
            @endphp

            @endif


            @php
            $current++;
            @endphp

            @endforeach

            @else
            <h3 class="brief-posts--not-found">Não foram encontrados posts.</h3>
            @endif
        </main>
    </div>


    <!-- RODAPÉ: copyright, autor -->
    <footer class="footer">
        @include("footer")
    </footer>
</body>

</html>