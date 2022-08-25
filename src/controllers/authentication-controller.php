<!-- DEFINIÇÃO: controlador relativo a autenticação no site (login, signup, logout) -->

<?php
require_once("src/models/user-model.php");
require_once("src/utils/security-util.php");

class AuthenticationController
{
   private $model;

   public function __construct()
   {
      $this->model = new userModel();
   }

   // obter todos os posts ao carregar a página principal
   public function authentication()
   {
      require_once("src/views/authentication-view.php");
   }

   // fazer login e redirecionar utilizador para a página principal
   public function login()
   {
      // obter os dados do formulário
      $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      // verificar se o utilizador clicou no botão de login
      if (!isset($data["isLogin"])) {
         $_SESSION["errors"] = "Não é possível efetuar esta operação.";
         die();
      }

      $user = $this->model->login($data["name"], $data["password"]);

      // se houve erros na requisição
      if (!isset($user) || count($this->model->errors) > 0) {
         $errors = array();

         // obter mensagens de erros
         foreach ($this->model->errors as $error) {
            array_push($errors, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $errors;
         header("location: /authentication");
         die();
      }

      // requisição com sucesso
      $user = protectOutputToHtml($user);
      header("location: /");
   }
}
?>