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


// verificar se algum utilizador está logado, para obter o id
if (isset($_SESSION["login"])) {
   $userLoggedId = $_SESSION["id"];
} else {
   $userLoggedId = -1;
}
?>

<body>
   <!-- alertas -->
   <?php require("error.php"); ?>
   <?php require("success.php"); ?>
   <?php require("../server/message.php"); ?>
</body>

</html>