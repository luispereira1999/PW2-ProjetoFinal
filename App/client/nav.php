<?php  // DEFINIÇÃO: menu de navegação pelo site, existem 2 diferentes (utilizador logado, guest)

// verificar se existe login iniciado 
if (isset($_SESSION["login"]) || isset($_COOKIE["login"])) : ?>

    <!-- mostrar nav com login -->
    <nav class="navigationBar">
        <div class="navLeft">
            <a href="../client/index.php" data-toggle="tooltip" data-placement="bottom" title="Início"><i class="navigationIcons fas fa-home"></i></a>
        </div>
        <div class="navCenter">
            <a href="../client/index.php"><img src="../server/assets/images/primary_logo.png" class="logo"></a>
        </div>
        <div class="navRight">
        <span data-toggle="tooltip" data-placement="bottom" title="Novo Post"><a href="" data-toggle="modal" data-target="#newPost"><i class="navigationIcons fas fa-plus"></i></a></span>
            <a href="user.php?userId=<?= $_SESSION["id"] ?>" data-toggle="tooltip" data-placement="bottom" title="<?= $_SESSION["username"] ?>"><i class="navigationIcons fas fa-user"></i></a>
            <div class="dropdown">
                <button class="buttonDropdown"><i class="navigationIcons fas fa-ellipsis-v"></i></button>
                <div class="contentDropdown">
                    <a href="" data-toggle="modal" data-target="#informations"><i class="dropdownIcons fas fa-info"></i>Informações</a>
                    <a href="settings.php"><i class="dropdownIcons fas fa-user-edit"></i>Editar Utilizador</a>
                    <a href="../server/session.php?logout"><i class="dropdownIcons fas fa-door-open "></i>Terminar Sessão</a>
                </div>
            </div>
        </div>
    </nav>

<?php else : ?>

    <!-- mostrar nav sem login -->
    <nav class="navigationBar">
        <div class="navLeft">
            <a href="index.php" data-toggle="tooltip" data-placement="bottom" title="Início"><i class="navigationIcons fas fa-home"></i></a>
        </div>
        <div class="navCenter">
            <a href="index.php"><img src="../server/assets/images/primary_logo.png" class="logo"></a>
        </div>
        <div class="navRight">
            <a href="loginSignup.php" data-toggle="tooltip" data-placement="bottom" title="Login / Signup"><i class="navigationIcons fas fa-user-plus"></i></a>
            <div class="dropdown">
                <button class="buttonDropdown"><i class="navigationIcons fas fa-ellipsis-v"></i></button>
                <div class="contentDropdown">
                    <a href="" data-toggle="modal" data-target="#informations"><i class="dropdownIcons fas fa-info"></i> Informações</a></span>
                </div>
            </div>
        </div>
    </nav>

<?php endif; ?>