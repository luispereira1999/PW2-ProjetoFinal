<?php
// verificar se a sessão está vazia para redirecionar para a página principal
session_start();
if (empty($_COOKIE["login"]) && empty($_SESSION["login"])) {
    header("location: index.php");
}


require("../server/connectDB.php");
require("../client/error.php");
require("../server/message.php");

// selecionar utilizador
if ($query = $connection->prepare("SELECT primeiroNome, ultimoNome, cidade, pais FROM utilizadores WHERE id = ?")) {
    // executar query
    $query->bind_param("i", $_SESSION["id"]);
    $query->execute();

    // obter resultado da query
    $result = $query->get_result();

    // se o utilizador existe
    if ($result->num_rows > 0) {
        // obter dados dos posts
        $post = $result->fetch_assoc();

        // fechar ligações
        $query->close();
        $connection->close();
    } else {
        $_SESSION["messageError"] = "O utilizador não existe.";
        header("location: ../client/index.php");
    }
} else {
   $_SESSION["messageError"] = "Erro: Algo deu errado com a base de dados.";
   header("location: ../client/404.php");
}
?>

<!-- DEFINIÇÃO: página para editar os dados de cada utilizador -->

<!DOCTYPE html>
<html>

<head>
    <!-- metadados -->
    <title>KLL</title>
    <meta charset="utf-8">
    <meta name="description" content="Uma rede social nova e alternativa!">
    <meta name="keywords" content="IPCA, Programação Web 2, Projeto Final, Rede Social">
    <meta name="author" content="Lara Ribeiro | Luís Pereira | Maria Francisca Costa">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="../server/assets/images/favicon.ico">

    <!-- CSS -->
    <link rel="stylesheet" href="css/main.css">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- JS -->
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/function.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <script src="https://kit.fontawesome.com/ed5c768cb2.js" crossorigin="anonymous"></script>

    <!-- Font Family -->
    <link href="https://fonts.googleapis.com/css2?family=Nova+Round&family=Nunito:wght@300;400&display=swap" rel="stylesheet">
</head>

<body>

    <header>
        <?php require("nav.php") ?>
        <section class="uHeader">
            <div>
                <i class="fas fa-user-circle"></i>
                <h2><?= $_SESSION["username"]; ?></h2>
            </div>
        </section>
    </header>

    <main>
        <form method="post" action="../server/settingsController.php" class="ueForm">
            <ul>
                <h4>Detalhes Básicos</h4>
                <li>
                    <input type="text" name="firstName" class="field-style field-split align-left" placeholder="Primeiro Nome" value="<?= $post["primeiroNome"] ?>">
                    <input type="text" name="lastName" class="field-style field-split align-right" placeholder="Último Nome" value="<?= $post["ultimoNome"] ?>">
                </li>
                <li>
                    <input type="text" name="city" class="field-style field-split align-left" placeholder="Cidade" value="<?= $post["cidade"] ?>">
                    <input type="text" name="country" class="field-style field-split align-right" placeholder="País" value="<?= $post["pais"] ?>">
                </li>
                <li>
                    <input type="email" name="email" class="field-style field-full align-none" placeholder="Email" value="<?= $_SESSION["email"] ?>" require>
                </li>
            </ul>

            <ul>
                <div class="changePass">
                    <h4>Alterar Password</h4>
                    <img src="../server/assets/images/switch-off.png" name="notUpdate" id="updatePassword">
                </div>
                <li>
                    <input type="password" name="currentPassword" class="field-style field-full align-left" placeholder="Senha Atual" require disabled>
                </li>
                <li>
                    <input type="password" name="newPassword" class="field-style field-split align-left" placeholder="Nova Senha" require disabled>
                    <input type="password" name="confirmNewPassword" class="field-style field-split align-right" placeholder="Confirmar Senha" require disabled>
                </li>
                <li class="ueButtons">
                    <button name="save" value="save" class="button buttonPrimary">Salvar</button>
                    <button name="cancel" value="cancel" class="button buttonCancel">Cancelar</button>
                </li>
            </ul>
        </form>
    </main>

    <?php require("informations.php"); ?>
    <?php require("newPost.php"); ?>
    <?php require("error.php"); ?>
    <?php require("success.php"); ?>
    <?php require("../server/message.php"); ?>
    
    <footer class="footer">
        <?php require("footer.php") ?>
    </footer>
</body>

</html>