<!-- DEFINIÇÃO: menu de navegação no topo do site, existem 2 diferentes (utilizador com login, utilizador sem login) -->

<!-- verificar se o utilizador está logado -->

<!-- menu com login -->


<!-- menu sem login -->
<nav class="navigation">
    <div class="navigation__left-wrapper">
        <a href="/" data-toggle="tooltip" data-placement="bottom" title="Início"><i class="navigation__icon fas fa-home"></i></a>
    </div>

    <div class="navigation__center-wrapper">
        <a href="/"><img src="{{ URL::asset('assets/images/logo.png') }}" class="navigation__logo"></a>
    </div>

    <div class="navigation__right-wrapper">
        <a href="/auth" data-toggle="tooltip" data-placement="bottom" title="Login / Signup"><i class="navigation__icon fas fa-user-plus"></i></a>

        <div class="navigation__dropdown">
            <button class="button-dropdown"><i class="navigation__icon fas fa-ellipsis-v"></i></button>
            <div class="navigation__dropdown__content-wrapper">
                <a class="navigation__dropdown__link" href="" data-toggle="modal" data-target="#about"><i class="navigation__dropdown__icon fas fa-info"></i>Sobre</a></span>
            </div>
        </div>
    </div>
</nav>
