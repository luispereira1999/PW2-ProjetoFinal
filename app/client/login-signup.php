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

<body class="ls">
   <?php require("error.php"); ?>
   <?php require("../server/message.php"); ?>
</body>