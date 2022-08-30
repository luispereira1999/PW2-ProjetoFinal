<!-- DEFINIÇÃO: modelo dos posts que aparecem na página principal -->

<?php
require_once("src/configs/database-config.php");
require_once("entities/post.php");

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
      // validar inputs
      if (empty($userLoggedId)) {
         $error = new Exception("Identificador do utilizador logado é inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }

      $query = "
      SELECT p.id AS post_id, p.title AS title, p.description AS description, p.date AS date, p.votes_amount AS votes_amount, p.comments_amount AS comments_amount, p.user_id AS post_user_id, u.name AS post_user_name, v.user_id AS vote_user_id, v.vote_type_id AS vote_type_id
      FROM posts p
      LEFT JOIN users u ON p.user_id = u.id
      LEFT JOIN posts_votes v ON p.id = v.post_id AND :userLoggedId = v.user_id
      ORDER BY votes_amount
      DESC";

      // selecionar posts
      try {
         $result = $this->connection->prepare($query);

         // executar query
         $result->bindParam(":userLoggedId", $userLoggedId, PDO::PARAM_INT);
         $result->execute();

         // se existir posts
         if ($result->rowCount() > 0) {
            $posts = array();

            // obter dados dos posts
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
               $post = new Post();
               $post->post_id = $row["post_id"];
               $post->title = $row["title"];
               $post->description = $row["description"];
               $post->date = $row["date"];
               $post->votes_amount = $row["votes_amount"];
               $post->comments_amount = $row["comments_amount"];
               $post->post_user_id = $row["post_user_id"];
               $post->post_user_name = $row["post_user_name"];
               $post->vote_user_id = $row["vote_user_id"];
               $post->vote_type_id = $row["vote_type_id"];

               array_push($posts, $post);
            }

            return $posts; // retorna os posts
         } else {
            $error = new Exception("Posts não encontrados.", 1);
            array_push($this->errors, $error);
            return null;
         }
      } catch (PDOException $exception) {
         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
   }
}
?>