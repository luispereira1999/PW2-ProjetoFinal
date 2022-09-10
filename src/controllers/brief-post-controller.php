<?php
// DEFINIÇÃO: controlador dos posts na página principal (posts resumidos)

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

   // editar post e ir para a página do post
   public function edit($params)
   {
      require_once("src/models/post-model.php");
      $this->postModel = new PostModel();

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

      // editar post na base de dados
      $postId = $params["id"];
      $isUpdated = $this->postModel->updateData($postId, $data["title"], $data["description"], $userLoggedId);

      // se houve erros na requisição
      if (!isset($isUpdated) || count($this->postModel->errors) > 0) {
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

      require_once("src/utils/security-util.php");
      $postId = protectOutputToHtml($postId);

      // redirecionar para a página do post
      header("location: /post/" . $postId);
   }

   // votar num post e ir para a página do post
   public function vote($params)
   {
      // converter JSON do cliente para PHP
      $json = file_get_contents('php://input');

      // obter dados do cliente
      $data = json_decode($json, true);

      echo $json;
      die();
   }

   // apagar post, registos associados e ir para a página principal
   public function delete($params)
   {
      require_once("src/models/post-model.php");
      $this->postModel = new PostModel();

      // obter os dados do formulário
      $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      // verificar se o utilizador clicou no botão de novo post
      if (!isset($data["isDelete"])) {
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
         header("location: /error");
         die();
      }

      // redirecionar para a página principal
      header("location: /");
   }
}
