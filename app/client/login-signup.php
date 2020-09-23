<?php
// verificar se existem cookies, para atribui-los a uma sessão quando o utilizador volta abrir a página depois de fechar o browser
session_start();
if (isset($_COOKIE["login"])) {
   $_SESSION["login"] = $_COOKIE["login"];
   $_SESSION["id"] = $_COOKIE["id"];
   $_SESSION["username"] = $_COOKIE["username"];
   $_SESSION["email"] = $_COOKIE["email"];
   header("location: index.php");
} else if (isset($_SESSION["login"])) {
   header("location: index.php");
}
?>

<!-- DEFINAÇÃO: Area de Login e de Registo -->

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
   <link rel="stylesheet" href="css/login-signup.css">

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

<body class="ls">
   <section class="lsContent">
      <div class="lsContentLogo">
         <img src="../server/assets/images/logo.png" class="lsLogoImage">
      </div>

      <div class="lsContentLinks">
         <a data-session="login">Log In</a>
         <a data-session="signup">Sign Up</a>
      </div>

      <div class="lsContentForm" id="div-login">
         <form method="post" action="../server/session.php">
            <input type="text" name="username" placeholder="Utilizador / Email" require>
            <input type="password" name="password" placeholder="Senha" require>

            <div class="row">
               <div class="col-8">
                  <input type="checkbox" name="dismember" value="rememberLogin" require>
                  <label id="rememberText">Lembrar login?</label>
               </div>
               <div class="col-4 my-auto">
                  <button class="button buttonPrimary" name="login">Entrar</button>
               </div>
            </div>
         </form>
      </div>

      <div class="lsContentForm" id="div-signup">
         <form method="post" action="../server/session.php">
            <input type="text" class="lsFieldForm" name="username" placeholder="Nome de Utilizador" require>
            <input type="email" class="lsFieldForm" name="email" placeholder="Email" require>
            <input type="password" class="lsFieldForm" name="password" placeholder="Senha" require>
            <input type="password" name="confirmPassword" placeholder="Confirmar Senha" require>

            <a data-toggle="tooltip" data-placement="bottom" title="Os restantes dados poderá preencher na área de utilizador!"><i class="fas fa-info-circle"></i></a>
            <button class="button buttonPrimary" name="signup">Registar</button>
         </form>
      </div>
   </section>

   <?php require("error.php"); ?>
   <?php require("../server/message.php"); ?>
</body>

</html>