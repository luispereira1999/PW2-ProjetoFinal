<?php
class CommentModel extends Database
{
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
