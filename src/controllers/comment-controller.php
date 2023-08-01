<?php
// DEFINIÇÃO: controlador dos posts na página principal (posts resumidos)

require_once("src/views/view.php");

class CommentController
{
   private $commentModel;

   // criar comentário e ir para a página do post
   public function create()
   {
      require_once("src/models/comment-model.php");
      $this->commentModel = new CommentModel();

      // obter os dados do formulário
      $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      // verificar se o utilizador clicou no botão de novo comentário
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
      $postId = $data["postId"];
      $commentId = $this->commentModel->insert($data["description"], $data["postId"], $userLoggedId);

      // se houve erros na requisição
      if (!isset($commentId) || count($this->commentModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->commentModel->errors as $error) {
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
      header("location: /posts/" . $postId);
   }

   // editar comentário e ir para a página do post
   public function edit($params)
   {
      require_once("src/models/comment-model.php");
      $this->commentModel = new CommentModel();

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

      // editar comentário na base de dados
      $commentId = $params["id"];
      $postId = $data["postId"];
      $isUpdated = $this->commentModel->updateData($commentId, $data["description"], $userLoggedId);

      // se houve erros na requisição
      if (!isset($isUpdated) || count($this->commentModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->commentModel->errors as $error) {
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
      header("location: /posts/" . $postId);
   }

   // votar num comentário e manter o utilizador nessa página
   public function vote($params)
   {
      require_once("src/models/comment-model.php");
      $this->commentModel = new CommentModel();

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

      // editar comentário na base de dados
      $commentId = $params["id"];
      $isUpdated = $this->commentModel->updateVote($commentId, $data["voteTypeId"], $userLoggedId);
      $votesAmount = $this->commentModel->getVotesAmount($commentId);

      // se houve erros na requisição
      if (!isset($isUpdated) || !isset($votesAmount) || count($this->commentModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->commentModel->errors as $error) {
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

   // eliminar comentário, registos associados e ir para a página principal
   public function delete($params)
   {
      require_once("src/models/comment-model.php");
      $this->commentModel = new CommentModel();

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

      // eliminar post na base de dados
      $commentId = $params["id"];
      $postId = $data["postId"];
      $isDeleted = $this->commentModel->delete($commentId, $postId, $userLoggedId);

      // se houve erros na requisição
      if (!isset($isDeleted) || count($this->commentModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->commentModel->errors as $error) {
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
      header("location: /posts/" . $postId);
   }
}
