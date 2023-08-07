<!-- DEFINIÇÃO: página de definições da conta do utilizador -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- TÍTULO DA PÁGINA -->
    <title>Definições da Conta</title>

    <!-- METADADOS -->
    <meta charset="utf-8">
    <meta name="description" content="Uma rede social alternativa. Escreva os seus melhores posts.">
    <meta name="keywords" content="Rede Social">
    <meta name="author" content="Lara Ribeiro, Luís Pereira, Maria Costa">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('assets/images/favicon.ico') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ URL::asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/account.css') }}">
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


    <!-- PRINCIPAL: post -->
    <main>
        <section class="account__header-wrapper">
            <div>
                <i class="account__avatar fas fa-user-circle"></i>
                <h2 class="account__name">{{ $user->name }}</h2>
            </div>
        </section>

        <form id="formEditData" class="account__form" method="post" action="{{ route('account.edit-data', ['userId' => $user->id]) }}">
            @csrf
            @method('PATCH')

            <h4>Dados Básicos</h4>

            <!-- mostrar erros -->
            <div class="errors errors--edit-data alert alert-danger">
                <ul class="errors__list"></ul>
            </div>

            <ul class="account__items">
                <li class="account__item">
                    <input class="account__text" type="text" name="name" placeholder="Nome de Utilizador" value="{{ $user->name }}" require>
                    <input class="account__email" type="email" name="email" placeholder="Email" value="{{ $user->email }}" require>
                </li>
                <li class="account__item">
                    <input class="account__text" type="text" name="first_name" placeholder="Primeiro Nome" value="{{ $user->first_name }}">
                    <input class="account__text" type="text" name="last_name" placeholder="Último Nome" value="{{ $user->last_name }}">
                </li>
                <li class="account__item">
                    <input class="account__text" type="text" name="city" placeholder="Cidade" value="{{ $user->city }}">
                    <input class="account__text" type="text" name="country" placeholder="País" value="{{ $user->country }}">
                </li>

                <li class="account__actions">
                    <button class="button button-primary" type="submit" form="formEditData">Atualizar</button>
                    <a href="{{ route('account', ['userId' => $user->id]) }}"><button class="button button-cancel" type="button">Cancelar</button></a>
                </li>
            </ul>
        </form>

        <form id="formEditPassword" class="account__form" method="post" action="{{ route('account.edit-password', ['userId' => $user->id]) }}">
            @csrf
            @method('PATCH')

            <h4>Palavra-passe</h4>

            <div class="errors errors--edit-password alert alert-danger">
                <ul class="errors__list"></ul>
            </div>

            <ul class="account__items">
                <li class="account__item">
                    <input class="account__password" type="password" name="current_password" placeholder="Palavra-passe Atual" require>
                </li>
                <li class="account__item">
                    <input class="account__password" type="password" name="new_password" placeholder="Nova Palavra-passe" require>
                    <input class="account__password" type="password" name="new_password_confirm" placeholder="Confirmar Palavra-passe" require>
                </li>

                <li class="account__actions">
                    <button class="button button-primary" type="submit" form="formEditPassword" name="isEditPassword">Atualizar</button>
                    <a href="{{ route('account', ['userId' => $user->id]) }}"><button class="button button-cancel" type="button">Cancelar</button></a>
                </li>
            </ul>
        </form>

        <div id="formDeleteUser" class="account__form">
            <h4>Remover conta</h4>
            <a class="brief-posts__link__delete" data-toggle="modal" data-target="#deleteUser{{ $user->id }}"><button class="button button-danger" type="submit" form="formDeleteUser">Remover</button></a>
        </div>

        @include('delete-user')
    </main>


    <!-- RODAPÉ: copyright, autor -->
    <footer class="footer">
        @include("footer")
    </footer>
</body>

</html>