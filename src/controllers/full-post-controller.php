<!-- DEFINIÇÃO: controlador dos posts na página individual do post -->

<?php
require_once("src/views/view.php");

class FullPostController
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

   // criar post e redirecionar para a página do post
   public function create()
   {
      require_once("src/models/post-model.php");
      $this->postModel = new PostModel();

      // obter os dados do formulário
      $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      // verificar se o utilizador clicou no botão de novo post
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