<!-- DEFINIÇÃO: controlador dos posts na página principal (posts resumidos) -->

<?php
require_once("src/views/view.php");

class FullPostController
{
   private $fullPostModel;
   private $commentModel;

   // obter o post, os comentários associados e ir para a página individual do post
   public function index($params)
   {
      require_once("src/models/full-post-model.php");
      require_once("src/models/comment-model.php");
      $this->fullPostModel = new FullPostModel();
      $this->commentModel = new CommentModel();

      // obter utilizador logado
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // obter post e comentários desse post
      $id = $params["id"];
      $post = $this->fullPostModel->getById($id, $userLoggedId);
      $comments = $this->commentModel->getAll($id, $userLoggedId);

      // proteger dados 
      require_once("src/utils/security-util.php");
      $post = protectOutputToHtml($post);

      $commentsCleaned = array();
      foreach ($comments as $comment) {
         $comment = protectOutputToHtml($comment);
         array_push($commentsCleaned, $comment);
      }

      $comments = $commentsCleaned; // obter os comentários protegidos para a variável original

      // se houve erros na requisição
      if (!isset($post) || !isset($comments) || count($this->fullPostModel->errors) > 0 || count($this->commentModel->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->fullPostModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }
         foreach ($this->commentModel->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         header("location: /not-found");
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
}
?>