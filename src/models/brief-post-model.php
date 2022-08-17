<!-- DEFINIÇÃO: modelo de um post que aparece na página principal -->

<?php
require_once("../src/configurations/database.php");

class BriefPost
{
   public $post_id;
   public $title;
   public $description;
   public $date;
   public $votes_amount;
   public $comments_amount;
   public $post_user_id;
   public $name;
   public $user_logged_id;
   public $vote_type_id;
}

class BriefPostModel extends Database
{
   public function __construct()
   {
      parent::__construct();
   }

   public function getAll($userLoggedId)
   {
      $briefPosts = array();

      // selecionar posts
      if ($query = $this->connection->prepare("SELECT p.id AS post_id, p.title AS title, p.description AS description, p.date AS date, p.votes_amount AS votes_amount, p.comments_amount AS comments_amount, p.user_id AS post_user_id, u.name AS name, v.user_id AS user_logged_id, v.vote_type_id AS vote_type_id FROM posts p INNER JOIN users u ON p.user_id = u.id LEFT JOIN posts_votes v ON p.id = v.post_id AND :userLoggedId = v.user_id ORDER BY votes_amount DESC")) {
         // executar query
         $query->bindParam(":userLoggedId", $userLoggedId, PDO::PARAM_INT);
         $query->execute();

         // existem dados
         if ($query->rowCount() > 0) {
            // obter dados dos posts
            while ($row = $query->fetch()) {
               $briefPost = new BriefPost();
               $briefPost->post_id = $row["post_id"];
               $briefPost->title = $row["title"];
               $briefPost->description = $row["description"];
               $briefPost->date = $row["date"];
               $briefPost->votes_amount = $row["votes_amount"];
               $briefPost->comments_amount = $row["comments_amount"];
               $briefPost->post_user_id = $row["post_user_id"];
               $briefPost->name = $row["name"];
               $briefPost->user_logged_id = $row["user_logged_id"];
               $briefPost->vote_type_id = $row["vote_type_id"];

               array_push($briefPosts, $briefPost);
            }

            return $briefPosts; // retorna os dados dos posts
         } else {
            $_SESSION["messageError"] = "Sem resultados.";
            $_SESSION["error"] = true;
         }
      } else {
         $_SESSION["messageError"] = "Sem resultados.";
         $_SESSION["error"] = true;
      }

      return $briefPosts; // retorna array sem dados
   }
}
?>
