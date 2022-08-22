<!-- DEFINIÇÃO: página de login ou signup do site -->

<?php
session_start();
$error = "";

if (!empty($_SESSION["error"])) {
   $error = $_SESSION["error"];
   unset($_SESSION["error"]);
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
   <!-- TÍTULO DA PÁGINA -->
   <title>Login ou Signup</title>

   <!-- METADADOS -->
   <meta charset="utf-8">
   <meta name="description" content="Uma rede social nova e alternativa!">
   <meta name="keywords" content="IPCA, Programação Web 2, Projeto Final, Rede Social">
   <meta name="author" content="Lara Ribeiro, Luís Pereira, Maria Costa">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!-- FAVICON -->
   <link rel="shortcut icon" type="image/x-icon" href="../public/assets/images/favicon.ico">

   <!-- CSS -->
   <link rel="stylesheet" href="../public/css/main.css">
   <link rel="stylesheet" href="../public/css/nav.css">
   <link rel="stylesheet" href="../public/css/footer.css">
   <link rel="stylesheet" href="../public/css/authentication.css">

   <!-- JQUERY -->
   <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

   <!-- JS -->
   <script type="text/javascript" src="../public/js/main.js"></script>
   <script type="text/javascript" src="../public/js/function.js"></script>

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
   <main class="content">
      <div class="content__logo-wrapper">
         <a href="/">
            <img src="../public/assets/images/logo.png" class="content__logo">
         </a>
      </div>

      <div class="content__links-wrapper">
         <a class="content__link" data-session="login">Log In</a>
         <a class="content__link" data-session="signup">Sign Up</a>
      </div>

      <!-- formulário de login -->
      <div class="content__form-wrapper" id="content__login">
         <form method="post" action="/authentication/login">
            <input class="content__text" type="text" name="name" placeholder="Nome de Utilizador ou Email" require>
            <input class="content__password" type="password" name="password" placeholder="Senha" require>

            <div class="row">
               <div class="col-8">
                  <input class="content__checkbox" type="checkbox" name="dismember" value="rememberLogin" require>
                  <label class="content__label" id="rememberText"><?= $error ?></label>
               </div>
               <div class="col-4 my-auto">
                  <button class="button button-primary" name="login">Entrar</button>
               </div>
            </div>
         </form>
      </div>

      <!-- formulário de signup -->
      <div class="content__form-wrapper" id="content__signup">
         <form method="post" action="/authentication/signup">
            <input class="content__text" type="text" class="lsFieldForm" name="name" placeholder="Nome de Utilizador" require>
            <input class="content__email" type="email" class="lsFieldForm" name="email" placeholder="Email" require>
            <input class="content__password" type="password" class="lsFieldForm" name="password" placeholder="Senha" require>
            <input class="content__password" type="password" name="confirmPassword" placeholder="Confirmar Senha" require>

            <a class="content__link" data-toggle="tooltip" data-placement="bottom" title="Os restantes dados poderá preencher na área de utilizador!"><i class="fas fa-info-circle content__icon"></i></a>
            <button class="button button-primary" name="signup">Registar</button>
         </form>
      </div>
   </main>
</body>

</html>