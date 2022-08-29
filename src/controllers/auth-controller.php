<!-- DEFINIÇÃO: controlador relativo a autenticação no site (login, signup, logout) -->

<?php
class AuthController
{
   private $model;

   // redirecionar para a página de login ou signup
   public function index()
   {
      require_once("src/views/auth-view.php");
   }

   // fazer signup e redirecionar para a página principal
   public function signup()
   {
      require_once("src/models/user-model.php");
      $this->model = new userModel();

      // obter os dados do formulário
      $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      // verificar se o utilizador clicou no botão de signup
      if (!isset($data["isSignup"])) {
         $_SESSION["errors"] = "Não é possível efetuar esta operação.";
         header("location: /auth");
         die();
      }

      // registar utilizador na base de dados
      $userId = $this->model->create($data["name"], $data["email"], $data["password"], $data["confirmPassword"]);
      $user = $this->model->getById($userId);

      // se houve erros na requisição
      if (!isset($user) || count($this->model->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->model->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         header("location: /auth");
         die();
      }

      require_once("src/utils/security-util.php");
      $user = protectOutputToHtml($user);

      // definir sessão de login no site
      require_once("src/utils/session-util.php");
      setLoginSession(true, $user->id, $user->name, $user->email, $user->first_name, $user->last_name);

      // redirecionar para a página de login ou signup
      header("location: /");
   }

   // fazer login e redirecionar para a página principal
   public function login()
   {
      require_once("src/models/user-model.php");
      $this->model = new userModel();

      // obter os dados do formulário
      $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      // verificar se o utilizador clicou no botão de login
      if (!isset($data["isLogin"])) {
         $_SESSION["errors"] = "Não é possível efetuar esta operação.";
         header("location: /auth");
         die();
      }

      // obter o utilizador que pretende fazer login
      $user = $this->model->validate($data["name"], $data["password"]);

      // se houve erros na requisição
      if (!isset($user) || count($this->model->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->model->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         header("location: /auth");
         die();
      }

      require_once("src/utils/security-util.php");
      $user = protectOutputToHtml($user);

      // definir sessão de login no site
      require_once("src/utils/session-util.php");
      setLoginSession(true, $user->id, $user->name, $user->email, $user->first_name, $user->last_name);

      // definir cookies para lembrar login quando o browser é fechado
      if (isset($data["rememberLogin"]) == true) {
         setLoginCookies(true, $user->id, $user->name, $user->email, $user->first_name, $user->last_name);
      }

      // redirecionar para a página principal
      header("location: /");
   }

   // fazer logout e redirecionar para a página principal
   public function logout()
   {
      require_once("src/utils/session-util.php");

      // efetuar logout
      destroySession();
      if (isset($_COOKIE["id"])) {
         removeCookies();
      }

      // redirecionar para a página principal
      header("location: /");
   }
}
?>