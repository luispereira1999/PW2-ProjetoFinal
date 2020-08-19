<!-- DEFINIÇÃO: pagina individual de cada post -->

<?php
// verificar se existem cookies, para atribui-los a uma sessão quando o utilizador volta abrir a página depois de fechar o browser
session_start();
if (isset($_COOKIE["login"])) {
    $_SESSION["login"] = $_COOKIE["login"];
    $_SESSION["id"] = $_COOKIE["id"];
    $_SESSION["username"] = $_COOKIE["username"];
    $_SESSION["email"] = $_COOKIE["email"];
}

require("../server/connectDB.php");
require("../server/postController.php");
require("../server/commentController.php");


// verificar se algum utilizador está logado, para obter o id
if (isset($_GET["postId"])) {
    $postId = $_GET["postId"];
} else {
    die();
}

// verificar se algum utilizador está logado, para obter o id
if (isset($_SESSION["login"])) {
    $userLoggedId = $_SESSION["id"];
} else {
    $userLoggedId = -1;
}

// obter post
$postId = $_GET["postId"];
$post = getPost($postId, $userLoggedId);

// guardar atual post para acessá-lo ao manipular esta página
$_SESSION["post"] = $post;

// obter comentários do post
$comments = getComments($postId, $userLoggedId);
?>

<!DOCTYPE html>
<html>

<head>
    <!-- metadados -->
    <title><?= $post["titulo"]; ?></title>
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
                <h2><?= $post["titulo"]; ?></h2>
                <h3><?= $post["nomeUtilizador"]; ?></h3>
                <h3><?= $post["data"]; ?></h3>
            </div>
        </section>
    </header>

    <main>
        <?php showPost($post, $userLoggedId); ?>

        <section class="commentsArea">
            <span data-toggle="modal" data-target="#newComment"><i class="fas fa-plus"></i>Adicionar Novo Comentário</span>
            <hr>

            <?php if (!isset($_SESSION["error"])) { ?>
                <div class="comments">
                    <ul>
                        <?php showComments($comments, $userLoggedId); ?>
                    </ul>
                </div>
            <?php } else {
                unset($_SESSION["error"]);
            } ?>
        </section>
    </main>

    <!-- alertas -->
    <?php require("error.php"); ?>
    <?php require("success.php"); ?>
    <?php require("informations.php"); ?>
    <?php require("newPost.php"); ?>
    <?php require("editPost.php"); ?>
    <?php require("newComment.php"); ?>
    <?php require("editComment.php"); ?>
    <?php require("../server/message.php"); ?>

    <footer class="footer">
        <?php require("footer.php") ?>
    </footer>
</body>

</html>