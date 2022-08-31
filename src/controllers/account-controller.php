<!-- DEFINIÇÃO: controlador das definições da conta do utilizador na página de definições -->

<?php
require_once("src/views/view.php");

class AccountController
{
   private $model;

   // obter o utilizador e ir para a página de definições da conta
   public function index()
   {
      require_once("src/models/user-model.php");
      $this->model = new UserModel();

      // obter ID do utilizador logado
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // obter utilizador
      $user = $this->model->getById($userLoggedId);

      // proteger dados
      require_once("src/utils/security-util.php");
      $user = protectOutputToHtml($user);

      // se houve erros na requisição
      if (!isset($user) || count($this->model->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->fullPostModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }
         foreach ($this->commentModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         header("location: /not-found");
         die();
      }

      new View(
         "src/views/account-view.php",
         [
            "user" => $user
         ]
      );
   }
}
?>