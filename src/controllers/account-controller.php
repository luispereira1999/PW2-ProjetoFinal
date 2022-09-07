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
         foreach ($this->model->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         header("location: /error");
         die();
      }

      new View(
         "src/views/account-view.php",
         [
            "user" => $user
         ]
      );
   }

   // editar utilizador e ir para a página do perfil
   public function edit($params)
   {
      require_once("src/models/user-model.php");
      $this->userModel = new UserModel();

      // obter os dados do formulário
      $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      // verificar se o utilizador clicou no botão de novo post
      if (!isset($data["isEdit"])) {
         $_SESSION["errors"] =  ["Não é possível efetuar esta operação."];
         header("location: /error");
         die();
      }

      // obter utilizador logado
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // editar perfil na base de dados
      $userLoggedId = $params["id"];
      $isUpdated = $this->userModel->updateData($data["email"], $data["firstName"], $data["lastName"], $data["city"], $data["country"], $userLoggedId);

      // se houve erros na requisição
      if (!isset($isUpdated) || count($this->userModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->userModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         header("location: /error");
         die();
      }

      require_once("src/utils/security-util.php");
      $userId = protectOutputToHtml($userId);

      // redirecionar para a página do perfil
      header("location: /profile/" . $userLoggedId);
   }
}
?>