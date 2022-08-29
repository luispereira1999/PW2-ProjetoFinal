<!-- DEFINIÇÃO: controlador dos posts na página principal (posts resumidos) -->

<?php
class FullPostController
{
   private $model;

   // obter o post e ir para a página individual do post
   public function index($params)
   {
      require_once("src/models/full-post-model.php");
      $this->model = new FullPostModel();

      // obter utilizador logado
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // obter post
      $postId = $params["id"];
      $post = $this->model->getById($postId, $userLoggedId);

      require_once("src/utils/security-util.php");
      $post = protectOutputToHtml($post);

      // se houve erros na requisição
      if (!isset($post) || count($this->model->errors) > 0) {
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

      require_once("src/views/post-view.php");
   }
}
?>