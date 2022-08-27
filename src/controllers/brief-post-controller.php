<!-- DEFINIÇÃO: controlador dos posts na página principal (posts resumidos) -->

<?php
class BriefPostController
{
   private $model;

   // obter todos os posts ao carregar a página principal
   public function index()
   {
      require_once("src/models/brief-post-model.php");
      $this->model = new briefPostModel();

      // obter utilizador logado
      $userLoggedId = isset($_SESSION["id"]);

      // obter posts para mostrar na página principal
      $briefPosts = $this->model->getAll($userLoggedId);

      // se houve erros na requisição
      if (!isset($briefPosts) || count($this->model->errors) > 0) {
         $messages = array();

         // obter mensagens de erros
         foreach ($this->model->errors as $error) {
            array_push($messages, $error->getMessage());
         }

         // aceder aos erros na página de autenticação
         $_SESSION["errors"] = $messages;
         require_once("src/views/index-view.php");
         die();
      }

      require_once("src/views/index-view.php");
   }
}
?>