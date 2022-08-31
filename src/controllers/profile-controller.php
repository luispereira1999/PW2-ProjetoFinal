<!-- DEFINIÇÃO: controlador do perfil de um utilizador na página do perfil -->

<?php
require_once("src/views/view.php");

class ProfileController
{
   private $userModel;
   private $briefPostModel;

   // obter o utilizador, os posts associados e ir para a página do perfil
   public function index($params)
   {
      require_once("src/models/user-model.php");
      require_once("src/models/brief-post-model.php");
      $this->userModel = new UserModel();
      $this->briefPostModel = new BriefPostModel();

      // obter utilizador logado
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // obter utilizador
      $id = $params["id"];
      $user = $this->userModel->getById($id);
      $posts = $this->briefPostModel->getByUserId($user->id, $userLoggedId);

      // proteger dados
      require_once("src/utils/security-util.php");
      $user = protectOutputToHtml($user);

      $postsCleaned = array();
      foreach ($posts as $post) {
         $post = protectOutputToHtml($post);
         array_push($postsCleaned, $post);
      }

      $posts = $postsCleaned; // obter os posts protegidos para a variável original

      // se houve erros na requisição
      if (!isset($user) || !isset($posts) || count($this->userModel->errors) > 0 || count($this->briefPostModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->userModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }
         foreach ($this->briefPostModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         header("location: /not-found");
         die();
      }

      new View(
         "src/views/profile-view.php",
         [
            "user" => $user,
            "posts" => $posts,
            "userLoggedId" => $userLoggedId
         ]
      );
   }
}
?>