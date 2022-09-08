<?php
// DEFINIÇÃO: controlador relativo à página de erros

require_once("src/views/view.php");

class ErrorController
{
   // obter os erros e ir para a página de erros
   public function index()
   {
      $errors = array();

      if (isset($_SESSION["errors"])) {
         $errors = $_SESSION["errors"];
         unset($_SESSION["errors"]);
      }

      require_once("src/utils/security-util.php");
      $errors = protectOutputToHtml($errors);

      new View(
         "src/views/error-view.php",
         [
            "errors" => $errors
         ]
      );
   }
}
?>