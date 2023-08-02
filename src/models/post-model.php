<?php

class PostModel extends Database
{
    public $errors;

    public function delete($postId, $userId)
    {
        // validar inputs
        if (empty($postId)) {
            $error = new Exception("Identificador do utilizador inválido.", 1);
            array_push($this->errors, $error);
            return null;
        }

        $query1 = "
      DELETE FROM posts
      WHERE id = :postId AND user_id = :userId";

        $query2 = "
      DELETE FROM posts_votes
      WHERE post_id = :postId";

        $query3 = "
      DELETE FROM comments
      WHERE post_id = :postId";

        $query4 = "
      SELECT id FROM comments
      WHERE post_id = :postId";

        $query5 = "
      DELETE FROM comments_votes
      WHERE comment_id = :commentId";

        // eliminar post e registos associados na base de dados
        try {
            $this->connection->beginTransaction();

            // eliminar post pelo id do post
            $result1 = $this->connection->prepare($query1);
            $result1->bindParam(":postId", $postId, PDO::PARAM_INT);
            $result1->bindParam(":userId", $userId, PDO::PARAM_INT);
            $result1->execute();

            // eliminar votos do post pelo id do post
            $result2 = $this->connection->prepare($query2);
            $result2->bindParam(":postId", $postId, PDO::PARAM_INT);
            $result2->execute();

            // eliminar comentários pelo id do post
            $result3 = $this->connection->prepare($query3);
            $result3->bindParam(":postId", $postId, PDO::PARAM_INT);
            $result3->execute();

            // selecionar comentários pelo id do post
            $result4 = $this->connection->prepare($query4);
            $result4->bindParam(":postId", $postId, PDO::PARAM_INT);
            $result4->execute();
            $commentId = $result4;

            // se existir comentários
            if ($result4->rowCount() > 0) {
                // obter id dos comentários
                while ($row = $result4->fetch(PDO::FETCH_ASSOC)) {
                    $commentId = $row["id"];

                    // eliminar votos do comentário pelo id do comentário
                    $result5 = $this->connection->prepare($query5);
                    $result5->bindParam(":commentId", $commentId, PDO::PARAM_INT);
                    $result5->execute();
                }
            }

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
