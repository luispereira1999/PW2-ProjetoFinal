<!-- DEFINIÇÃO: controlador dos posts na página principal (posts resumidos) -->

<?php
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
         header("location: /not-found");
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
         header("location: /not-found");
         die();
      }

      require_once("src/utils/security-util.php");
      $postId = protectOutputToHtml($postId);

      // redirecionar para a página do post
      header("location: /post/" . $postId);
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
         header("location: /not-found");
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
      $isUpdated = $this->commentModel->update($commentId, $data["description"], $userLoggedId);

      // se houve erros na requisição
      if (!isset($isUpdated) || count($this->commentModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->commentModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         header("location: /not-found");
         die();
      }

      require_once("src/utils/security-util.php");
      $postId = protectOutputToHtml($postId);

      // redirecionar para a página do post
      header("location: /post/" . $postId);
   }
}
?>