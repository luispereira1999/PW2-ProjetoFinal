<!-- DEFINIÇÃO: página de erro: 404 page not fond -->
<?php session_start(); ?>

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
    <link rel="stylesheet" href="css/ls.css">
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

<body class="nf404">
    <!-- logótipo do site -->
    <section class="lsContent">
        <div class="lsContentLogo">
            <img src="../server/assets/images/primary_logo.png" class="lsLogoImage">
        </div>

        <!-- aréa de erro -->
        <div>
            <h1>404</h1>
            <h3>Page Not Found!</h3>
            <p>Parece que algum dos developers anda a dormir!</p>
        </div>
    </section>

    <?php require("error.php"); ?>
    <?php require("../server/message.php"); ?>
</body>

</html>