<!-- DEFINIÇÃO: menu de navegação no topo do site (quando o utilizador está autenticado ou não está autenticado) -->

<!-- MENU: com login -->
@auth
<nav class="navigation">
    <div class="navigation__left-wrapper">
        <a href="{{ route('home') }}" data-toggle="tooltip" data-placement="bottom" title="Início"><i class="navigation__icon fas fa-home"></i></a>
    </div>

    <div class="navigation__center-wrapper">
        <a href="{{ route('home') }}"><img src="{{ URL::asset('images/full-logo.png') }}" class="navigation__logo"></a>
    </div>

    <div class="navigation__right-wrapper">
        <span data-toggle="tooltip" data-placement="bottom" title="Novo Post">
            <a href="" data-toggle="modal" data-target="#newPost"><i class="navigation__icon fas fa-plus"></i></a>
        </span>

        <a href="{{ route('profile', ['userId' => Auth::user()->id]) }}" data-toggle="tooltip" data-placement="bottom" title="{{ Auth::user()->first_name }}"><i class="navigation__icon fas fa-user"></i></a>

        <div class="navigation__dropdown">
            <button class="button-dropdown"><i class="navigation__icon fas fa-ellipsis-v"></i></button>

            <div class="navigation__dropdown__content-wrapper">
                <a class="navigation__dropdown__link" href="" data-toggle="modal" data-target="#about"><i class="navigation__dropdown__icon fas fa-info"></i>Sobre</a>
                <a class="navigation__dropdown__link" href="{{ route('account', ['userId' => Auth::user()->id]) }}"><i class="navigation__dropdown__icon fas fa-user-edit"></i>Editar Utilizador</a>
                <a class="navigation__dropdown__link" href="{{ route('auth.logout') }}"><i class="navigation__dropdown__icon fas fa-door-open "></i>Terminar Sessão</a>
            </div>
        </div>
    </div>
</nav>
@endauth

<!-- MENU: sem login -->
@guest
<nav class="navigation">
    <div class="navigation__left-wrapper">
        <a href="{{ route('home') }}" data-toggle="tooltip" data-placement="bottom" title="Início"><i class="navigation__icon fas fa-home"></i></a>
    </div>

    <div class="navigation__center-wrapper">
        <a href="{{ route('home') }}"><img src="{{ URL::asset('images/full-logo.png') }}" class="navigation__logo"></a>
    </div>

    <div class="navigation__right-wrapper">
        <a href="{{ route('auth') }}" data-toggle="tooltip" data-placement="bottom" title="Login / Signup"><i class="navigation__icon fas fa-user-plus"></i></a>

        <div class="navigation__dropdown">
            <button class="button-dropdown"><i class="navigation__icon fas fa-ellipsis-v"></i></button>
            <div class="navigation__dropdown__content-wrapper">
                <a class="navigation__dropdown__link" href="" data-toggle="modal" data-target="#about"><i class="navigation__dropdown__icon fas fa-info"></i>Sobre</a></span>
            </div>
        </div>
    </div>
</nav>
@endguest