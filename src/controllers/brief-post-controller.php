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
      if (isset($_SESSION["id"])) {
         $userLoggedId = $_SESSION["id"];
      } else {
         $userLoggedId = -1;
      }

      // obter posts para mostrar na página principal
      $briefPosts = $this->model->getAll($userLoggedId);

      require_once("src/utils/security-util.php");
      $briefPostsCleaned = array();

      foreach ($briefPosts as $briefPost) {
         $briefPost = protectOutputToHtml($briefPost);
         array_push($briefPostsCleaned, $briefPost);
      }

      // se houve erros na requisição
      if (!isset($briefPostsCleaned) || count($this->model->errors) > 0) {
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