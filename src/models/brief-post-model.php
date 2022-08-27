<!-- DEFINIÇÃO: modelo de um post que aparece na página principal -->

<?php
require_once("src/configs/database-config.php");

// representação de um post resumido
// (constituído por campos das tabelas "posts", "users" e "posts_votes" na base de dados)
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
   public $errors;

   public function __construct()
   {
      parent::__construct();
      $this->errors = array();
   }

   public function getAll($userLoggedId)
   {
      $query = "
      SELECT p.id AS post_id, p.title AS title, p.description AS description, p.date AS date, p.votes_amount AS votes_amount, p.comments_amount AS comments_amount, p.user_id AS post_user_id, u.name AS name, v.user_id AS user_logged_id, v.vote_type_id AS vote_type_id
      FROM posts p
      INNER JOIN users u ON p.user_id = u.id
      LEFT JOIN posts_votes v ON p.id = v.post_id AND :userLoggedId = v.user_id
      ORDER BY votes_amount
      DESC";

      // selecionar posts
      if ($result = $this->connection->prepare($query)) {
         // executar query
         $result->bindParam(":userLoggedId", $userLoggedId, PDO::PARAM_INT);
         $result->execute();

         // se existir dados
         if ($result->rowCount() > 0) {
            $briefPosts = array();

            // obter dados dos posts
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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

            return $briefPosts; // retorna os posts
         } else {
            $error = new Exception("Posts não encontrados.", 1);
            array_push($this->errors, $error);
            return null;
         }
      } else {
         $error = new Exception("Posts não encontrados.", 1);
         array_push($this->errors, $error);
         return null;
      }
   }
}
?>