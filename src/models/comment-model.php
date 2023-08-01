<?php
// DEFINIÇÃO: modelo dos comentários que aparecem na página individual do post

require_once("src/configs/database-config.php");
require_once("entities/comment.php");
require_once("entities/comment-vote.php");

class CommentModel extends Database
{
   public $errors;

   public function __construct()
   {
      parent::__construct();
      $this->errors = array();
   }

   public function insert($description, $postId, $userId)
   {
      // validar inputs
      if (empty($description)) {
         $error = new Exception("Insira uma descrição.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($postId)) {
         $error = new Exception("Insira um identificador do post.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($userId)) {
         $error = new Exception("Insira um identificador do utilizador (autor) do post.", 1);
         array_push($this->errors, $error);
         return null;
      }

      $query1 = "
      INSERT INTO comments (description, votes_amount, user_id, post_id)
      VALUES (:description, 0, :userId, :postId)";

      $query2 = "
      UPDATE posts
      SET comments_amount = comments_amount + 1
      WHERE id = :postId";

      // inserir comentário na base de dados
      try {
         $this->connection->beginTransaction();

         // inserir comentário pelo id do post e id do utilizador
         $result1 = $this->connection->prepare($query1);
         $result1->bindParam(":description", $description, PDO::PARAM_STR);
         $result1->bindParam(":userId", $userId, PDO::PARAM_INT);
         $result1->bindParam(":postId", $postId, PDO::PARAM_INT);
         $result1->execute();

         $commentId = $this->connection->lastInsertId();

         // atualizar quantidade de comentários do post
         $result2 = $this->connection->prepare($query2);
         $result2->bindParam(":postId", $postId, PDO::PARAM_INT);
         $result2->execute();

         $this->connection->commit();

         return $commentId; // retorna o id do comentário inserido
      } catch (PDOException $exception) {
         $this->connection->rollback();

         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
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

   public function getVotesAmount($commentId)
   {
      // validar inputs
      if (empty($commentId)) {
         $error = new Exception("Identificador do comentário é inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }

      $query = "
      SELECT votes_amount
      FROM comments
      WHERE id = :commentId
      LIMIT 1";

      // selecionar comentários
      try {
         $result = $this->connection->prepare($query);

         // executar query
         $result->bindParam(":commentId", $commentId, PDO::PARAM_INT);
         $result->execute();

         // se o comentário existir
         if ($result->rowCount() > 0) {
            $comment = new Comment();

            // obter a quantidade de votos do comentário
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
               $comment->votes_amount = $row["votes_amount"];
            }

            return $comment->votes_amount; // retorna a quantidade de votos do comentário
         } else {
            $error = new Exception("Comentário não encontrado.", 1);
            array_push($this->errors, $error);
            return null;
         }
      } catch (PDOException $exception) {
         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
   }

   public function updateData($commentId, $description, $userId)
   {
      // validar inputs
      if (empty($commentId)) {
         $error = new Exception("Identificador do comentário inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($description)) {
         $error = new Exception("Insira uma descrição.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($userId)) {
         $error = new Exception("Identificador do utilizador logado inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }

      $query = "
      UPDATE comments
      SET description = :description
      WHERE id = :commentId AND user_id = :userId";

      // atualizar comentário na base de dados
      try {
         $result = $this->connection->prepare($query);

         $result->bindParam(":description", $description, PDO::PARAM_STR);
         $result->bindParam(":commentId", $commentId, PDO::PARAM_INT);
         $result->bindParam(":userId", $userId, PDO::PARAM_INT);
         $result->execute();

         return true; // retorna o estado da operação
      } catch (PDOException $exception) {
         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
   }

   public function updateVote($commentId, $voteTypeId, $userId)
   {
      // validar inputs
      if (empty($commentId)) {
         $error = new Exception("Identificador do comentário inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($voteTypeId)) {
         $error = new Exception("Identificador do tipo de voto inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($userId)) {
         $error = new Exception("Identificador do utilizador logado inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if ($voteTypeId != 1 && $voteTypeId != 2) {
         $error = new Exception("Identificador do tipo de voto inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }

      $query1 = "
      SELECT comment_id, user_id, vote_type_id
      FROM comments_votes
      WHERE comment_id = :commentId AND user_id = :userId
      LIMIT 1";

      $query2 = "
      DELETE FROM comments_votes
      WHERE comment_id = :commentId AND user_id = :userId";

      $query3 = "
      INSERT INTO comments_votes (comment_id, user_id, vote_type_id)
      VALUES (:commentId, :userId, :voteTypeId)
      ON DUPLICATE KEY
      UPDATE vote_type_id = :voteTypeId";

      $query4 = "
      UPDATE comments
      SET votes_amount = votes_amount + (:operation)
      WHERE id = :commentId";

      // inserir, atualizar ou remover voto do comentário na base de dados
      try {
         $this->connection->beginTransaction();

         // selecionar voto
         $result1 = $this->connection->prepare($query1);
         $result1->bindParam(":commentId", $commentId, PDO::PARAM_INT);
         $result1->bindParam(":userId", $userId, PDO::PARAM_INT);
         $result1->execute();

         // se o voto já existe
         if ($result1->rowCount() > 0) {
            $commentVote = new CommentVote();

            // obter dados do voto do comentário
            while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
               $commentVote->comment_id = $row["comment_id"];
               $commentVote->user_id = $row["user_id"];
               $commentVote->vote_type_id = $row["vote_type_id"];
            }

            // se o voto tem o mesmo tipo
            if ($voteTypeId == $commentVote->vote_type_id) {
               // remover voto
               $result2 = $this->connection->prepare($query2);
               $result2->bindParam(":commentId", $commentId, PDO::PARAM_INT);
               $result2->bindParam(":userId", $userId, PDO::PARAM_INT);
               $result2->execute();

               if ($voteTypeId == 1) {
                  $operation = -1;
               } else if ($voteTypeId == 2) {
                  $operation = 1;
               }
            } else {
               // atualizar voto
               $result3 = $this->connection->prepare($query3);
               $result3->bindParam(":commentId", $commentId, PDO::PARAM_INT);
               $result3->bindParam(":userId", $userId, PDO::PARAM_INT);
               $result3->bindParam(":voteTypeId", $voteTypeId, PDO::PARAM_INT);
               $result3->execute();

               if ($voteTypeId == 1) {
                  $operation = 2;
               } else if ($voteTypeId == 2) {
                  $operation = -2;
               }
            }
         } else {
            // inserir voto
            $result3 = $this->connection->prepare($query3);
            $result3->bindParam(":commentId", $commentId, PDO::PARAM_INT);
            $result3->bindParam(":userId", $userId, PDO::PARAM_INT);
            $result3->bindParam(":voteTypeId", $voteTypeId, PDO::PARAM_INT);
            $result3->execute();

            if ($voteTypeId == 1) {
               $operation = 1;
            } else if ($voteTypeId == 2) {
               $operation = -1;
            }
         }

         // atualizar quantidade de votos
         $result4 = $this->connection->prepare($query4);
         $result4->bindParam(":operation", $operation, PDO::PARAM_INT);
         $result4->bindParam(":commentId", $commentId, PDO::PARAM_INT);
         $result4->execute();

         $this->connection->commit();

         return true; // retorna o estado da operação
      } catch (PDOException $exception) {
         $this->connection->rollback();

         $error = new Exception($exception->getMessage(), 1);
         array_push($this->errors, $error);
         return null;
      }
   }

   public function delete($commentId, $postId, $userId)
   {
      // validar inputs
      if (empty($commentId)) {
         $error = new Exception("Identificador do comentário inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($postId)) {
         $error = new Exception("Identificador do post inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($userId)) {
         $error = new Exception("Identificador do utilizador logado inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }

      $query1 = "
      DELETE FROM comments
      WHERE id = :commentId AND user_id = :userId AND post_id = :postId";

      $query2 = "
      DELETE FROM comments_votes
      WHERE comment_id = :commentId";

      $query3 = "
      UPDATE posts
      SET comments_amount = comments_amount - 1
      WHERE id = :postId";

      // eliminar comentário e registos associados na base de dados
      try {
         $this->connection->beginTransaction();

         // eliminar comentário pelo id do comentário
         $result1 = $this->connection->prepare($query1);
         $result1->bindParam(":commentId", $commentId, PDO::PARAM_INT);
         $result1->bindParam(":userId", $userId, PDO::PARAM_INT);
         $result1->bindParam(":postId", $postId, PDO::PARAM_INT);
         $result1->execute();

         // eliminar votos do comentário pelo id do comentário
         $result2 = $this->connection->prepare($query2);
         $result2->bindParam(":commentId", $postId, PDO::PARAM_INT);
         $result2->execute();

         // atualizar quantidade de comentários do post pelo id do post
         $result3 = $this->connection->prepare($query3);
         $result3->bindParam(":postId", $postId, PDO::PARAM_INT);
         $result3->execute();

         $this->connection->commit();

         return true; // retorna o estado da operação
      } catch (PDOException $exception) {
         $this->connection->rollback();

         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
   }
}
