<!-- DEFINIÇÃO: controlador dos posts na página principal (posts resumidos) -->

<?php
require_once("src/views/view.php");

class BriefPostController
{
   private $postModel;

   // obter os posts e ir para a página inicial
   public function index()
   {
      require_once("src/models/post-model.php");
      $this->postModel = new PostModel();

      // obter utilizador logado
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // obter posts para mostrar na página principal
      $posts = $this->postModel->getAll($userLoggedId);

      require_once("src/utils/security-util.php");
      $userLoggedId = protectOutputToHtml($userLoggedId);

      $postsCleaned = array();
      foreach ($posts as $post) {
         $post = protectOutputToHtml($post);
         array_push($postsCleaned, $post);
      }

      $posts = $postsCleaned; // obter os posts protegidos para a variável original

      // se houve erros na requisição
      if (!isset($posts) || count($this->postModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->postModel->errors as $error) {
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

   // apagar post, registos associados e redirecionar para a página principal
   public function delete($params)
   {
      require_once("src/models/post-model.php");
      $this->postModel = new PostModel();

      // obter os dados do formulário
      $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      // verificar se o utilizador clicou no botão de novo post
      if (!isset($data["isDelete"])) {
         $_SESSION["errors"] = "Não é possível efetuar esta operação.";
         header("location: /not-found");
         die();
      }

      // obter utilizador logado
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // apagar post na base de dados
      $postId = $params["id"];
      $isDeleted = $this->postModel->delete($postId, $userLoggedId);

      // se houve erros na requisição
      if (!isset($isDeleted) || count($this->postModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->postModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         header("location: /not-found");
         die();
      }

      // redirecionar para a página principal
      header("location: /");
   }
}
?>