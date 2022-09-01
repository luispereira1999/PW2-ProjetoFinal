<!-- DEFINIÇÃO: modelo dos comentários que aparecem na página individual do post -->

<?php
require_once("src/configs/database-config.php");
require_once("entities/comment.php");

class CommentModel extends Database
{
   public $errors;

   public function __construct()
   {
      parent::__construct();
      $this->errors = array();
   }

   public function getAll($postId, $userLoggedId)
   {
      // validar inputs
      if (empty($postId)) {
         $error = new Exception("Identificador do post é inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($userLoggedId)) {
         $error = new Exception("Identificador do utilizador logado é inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }

      $query = "
      SELECT c.id AS comment_id, c.description AS description, c.votes_amount AS votes_amount, c.user_id AS comment_user_id, c.post_id AS comment_post_id, u.name AS comment_user_name, vc.user_id AS vote_user_id, vc.vote_type_id AS vote_type_id
      FROM comments c
      INNER JOIN users u ON c.user_id = u.id
      LEFT JOIN comments_votes vc ON c.id = vc.comment_id AND :userLoggedId = vc.user_id
      WHERE c.post_id = :postId
      ORDER BY votes_amount
      DESC";

      // selecionar comentários
      try {
         $result = $this->connection->prepare($query);

         // executar query
         $result->bindParam(":userLoggedId", $userLoggedId, PDO::PARAM_INT);
         $result->bindParam(":postId", $postId, PDO::PARAM_INT);
         $result->execute();

         // se existir comentários
         if ($result->rowCount() > 0) {
            $comments = array();

            // obter dados dos comentários
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
               $comment = new Comment();
               $comment->comment_id = $row["comment_id"];
               $comment->description = $row["description"];
               $comment->votes_amount = $row["votes_amount"];
               $comment->comment_user_id = $row["comment_user_id"];
               $comment->comment_post_id = $row["comment_post_id"];
               $comment->comment_user_name = $row["comment_user_name"];
               $comment->vote_user_id = $row["vote_user_id"];
               $comment->vote_type_id = $row["vote_type_id"];

               array_push($comments, $comment);
            }

            return $comments; // retorna os comentários
         }
      } catch (PDOException $exception) {
         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
   }
}
?>