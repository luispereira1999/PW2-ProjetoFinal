<?php
// verificar se existem cookies, para atribui-los a uma sessão quando o utilizador volta abrir a página depois de fechar o browser
if (isset($_COOKIE["login"])) {
   session_start();
   $_SESSION["login"] = $_COOKIE["login"];
   $_SESSION["id"] = $_COOKIE["id"];
   $_SESSION["username"] = $_COOKIE["username"];
   $_SESSION["email"] = $_COOKIE["email"];
} else {  // iniciar apenas a sessão
   session_start();
}

// verificar se algum utilizador está logado, para obter o id
if (isset($_SESSION["login"])) {
   $userLoggedId = $_SESSION["id"];
} else {
   $userLoggedId = -1;
}
?>

<!-- DEFINIÇÃO: página principal do site -->
<body>
   <main>
      <!-- opções de layouts dos posts -->
      <section class="gridOptions">
         <i class="fas fa-th-list" data-grid="fullWidth" data-toggle="tooltip" data-placement="bottom" title="Vista de Lista"></i>
         <i class="fas fa-th" data-grid="width3" data-toggle="tooltip" data-placement="bottom" title="Vista de Grellha"></i>
      </section>
   </main>

   <!-- alertas -->
   <?php require("error.php"); ?>
   <?php require("success.php"); ?>
   <?php require("../server/message.php"); ?>
</body>