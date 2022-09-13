<?php
// DEFINIÇÃO: controlador dos posts na página principal (posts resumidos)

require_once("src/views/view.php");

class IndexController
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
      $featuredPost = $this->postModel->getByMaxVotesAmount($userLoggedId);

      require_once("src/utils/security-util.php");
      $userLoggedId = protectOutputToHtml($userLoggedId);
      $featuredPost = protectOutputToHtml($featuredPost);

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
         header("location: /error");
         die();
      }

      new View(
         "src/views/index-view.php",
         [
            "featuredPost" => $featuredPost,
            "posts" => $posts,
            "userLoggedId" => $userLoggedId
         ]
      );
   }

   // obter os posts com filtro e ir para a página inicial
   public function search($params)
   {
      require_once("src/models/post-model.php");
      $this->postModel = new PostModel();

      // obter utilizador logado
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // obter posts com filtro na página principal
      $title = $params["title"];
      $posts = $this->postModel->getByTitle($title, $userLoggedId);

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
         header("location: /error");
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
