<!-- DEFINIÇÃO: controlador dos posts na página principal (posts resumidos) -->

<?php
require_once("src/views/view.php");

class BriefPostController
{
   private $model;

   // obter os posts e ir para a página inicial
   public function index()
   {
      require_once("src/models/post-model.php");
      $this->model = new PostModel();

      // obter utilizador logado
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // obter posts para mostrar na página principal
      $posts = $this->model->getAll($userLoggedId);

      require_once("src/utils/security-util.php");
      $userLoggedId = protectOutputToHtml($userLoggedId);

      $postsCleaned = array();
      foreach ($posts as $post) {
         $post = protectOutputToHtml($post);
         array_push($postsCleaned, $post);
      }

      $posts = $postsCleaned; // obter os posts protegidos para a variável original

      // se houve erros na requisição
      if (!isset($posts) || count($this->model->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->model->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         header("location: /not-found");
         die();
      }

      new View(
         "src/views/index-view.php",
         [
            "posts" => $posts,
            "userLoggedId" => $userLoggedId
         ]
      );
   }
}
?>