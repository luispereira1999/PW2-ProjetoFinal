<?php
if (!isset($_GET["userId"])) {
   header("location:../client/404-error.php");
}

require("../client/error.php");
require("../server/message.php");
require("../server/user-controller.php");

session_start();

// verificar se algum utilizador está logado, para obter o id
if (isset($_SESSION["login"])) {
   $userLoggedId = $_SESSION["id"];
} else {
   $userLoggedId = -1;
}
?>

<!-- DEFINIÇÃO: página de cada utilizador -->

<html>

<body>
   <main>
      <?php if (!isset($_SESSION["error"])) { ?>
         <!-- opções de layouts dos posts -->
         <section class="gridOptions">
            <i class="fas fa-th-list" data-grid="fullWidth" data-toggle="tooltip" data-placement="bottom" title="Vista de Lista"></i>
            <i class="fas fa-th" data-grid="width3" data-toggle="tooltip" data-placement="bottom" title="Vista de Grelha"></i>
         </section>

         <!-- posts -->
         <section class="posts">
         </section>
      <?php } else {
         unset($_SESSION["error"]);
      } ?>
   </main>
</body>

</html>