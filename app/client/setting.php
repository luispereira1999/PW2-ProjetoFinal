<?php
// verificar se a sessão está vazia para redirecionar para a página principal
session_start();
if (empty($_COOKIE["login"]) && empty($_SESSION["login"])) {
   header("location: index.php");
}
?>

<body>
   <?php require("error.php"); ?>
   <?php require("success.php"); ?>
   <?php require("../server/message.php"); ?>
</body>

</html>