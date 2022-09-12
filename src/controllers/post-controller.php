<?php
// DEFINIÇÃO: controlador dos posts na página individual do post

require_once("src/views/view.php");

class PostController
{
   private $postModel;
   private $commentModel;

   // obter o post, os comentários associados e ir para a página do post
   public function index($params)
   {
      require_once("src/models/post-model.php");
      require_once("src/models/comment-model.php");
      $this->postModel = new PostModel();
      $this->commentModel = new CommentModel();

      // obter utilizador logado
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // obter post e comentários desse post
      $id = $params["id"];
      $post = $this->postModel->getById($id, $userLoggedId);
      $comments = $this->commentModel->getAll($id, $userLoggedId);

      // proteger dados
      require_once("src/utils/security-util.php");
      $post = protectOutputToHtml($post);
      $userLoggedId = protectOutputToHtml($userLoggedId);

      if (isset($comments)) {
         $commentsCleaned = array();
         foreach ($comments as $comment) {
            $comment = protectOutputToHtml($comment);
            array_push($commentsCleaned, $comment);
         }

         $comments = $commentsCleaned; // obter os comentários protegidos para a variável original
      }

      // se houve erros na requisição
      if (!isset($post) || count($this->postModel->errors) > 0 || count($this->commentModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->postModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }
         foreach ($this->commentModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         header("location: /error");
         die();
      }

      new View(
         "src/views/post-view.php",
         [
            "post" => $post,
            "comments" => $comments,
            "userLoggedId" => $userLoggedId
         ]
      );
   }

   // criar post e ir para a página do post
   public function create()
   {
      require_once("src/models/post-model.php");
      $this->postModel = new PostModel();

      // obter os dados do formulário
      $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      // verificar se o utilizador clicou no botão de novo post
      if (!isset($data["isCreate"])) {
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

      // registar utilizador na base de dados
      $postId = $this->postModel->insert($data["title"], $data["description"], $userLoggedId);

      // se houve erros na requisição
      if (!isset($postId) || count($this->postModel->errors) > 0) {
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

   // votar num post e manter o utilizador nessa página
   public function vote($params)
   {
      require_once("src/models/post-model.php");
      $this->postModel = new PostModel();

      // converter JSON que vem do cliente para PHP
      $json = file_get_contents('php://input');

      // obter dados do cliente
      $data = json_decode($json, true);

      // obter utilizador logado
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // editar post na base de dados
      $postId = $params["id"];
      $isUpdated = $this->postModel->updateVote($postId, $data["voteTypeId"], $userLoggedId);
      $votesAmount = $this->postModel->getVotesAmount($postId);

      // se houve erros na requisição
      if (!isset($isUpdated) || !isset($votesAmount) || count($this->postModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->postModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página
         echo json_encode($messages);
         die(header("HTTP/1.0 500"));
      }

      // proteger dados de saída
      require_once("src/utils/security-util.php");
      $votesAmount = protectOutputToHtml($votesAmount);

      // retornar JSON ao cliente
      $returnJon = array("votesAmount" => $votesAmount);
      echo json_encode($returnJon);
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
