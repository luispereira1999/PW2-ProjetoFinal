<!-- DEFINIÇÃO: controlador dos posts na página principal (posts resumidos) -->

<?php
require_once("../src/models/brief-post-model.php");

class BriefPostController
{
   private $model;

   public function __construct()
   {
      $this->model = new briefPostModel;
   }

   public function getBriefPosts($userLoggedId)
   {
      $briefPosts = $this->model->getAll($userLoggedId);
      require_once("../src/views/index.php");
   }
}
?>