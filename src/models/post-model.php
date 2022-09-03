<!-- DEFINIÇÃO: modelo dos posts que aparecem na página principal, na página do perfil e na página individual do post  -->

<?php
require_once("src/configs/database-config.php");
require_once("entities/post.php");

class PostModel extends Database
{
   public $errors;

   public function __construct()
   {
      parent::__construct();
      $this->errors = array();
   }

   public function insert($title, $description, $userId)
   {
      // validar inputs
      if (empty($title)) {
         $error = new Exception("Insira um título.", 1);
         array_push($this->errors, $error);
      }
      if (empty($description)) {
         $error = new Exception("Insira uma descrição.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($userId)) {
         $error = new Exception("Insira um identificador do utilizador (autor) do post.", 1);
         array_push($this->errors, $error);
         return null;
      }

      // obter data atual
      $date = date("Y/m/d h:i:s", time());

      $query = "
      INSERT INTO posts (title, description, date, votes_amount, comments_amount, user_id)
      VALUES (:title, :description, :date, 0, 0, :userId)";

      // inserir post na base de dados
      try {
         $result = $this->connection->prepare($query);

         $result->bindParam(":title", $title, PDO::PARAM_STR);
         $result->bindParam(":description", $description, PDO::PARAM_STR);
         $result->bindParam(":date", $date, PDO::PARAM_STR);
         $result->bindParam(":userId", $userId, PDO::PARAM_INT);
         $result->execute();

         $postId = $this->connection->lastInsertId();
         return $postId; // retorna o id do post inserido
      } catch (PDOException $exception) {
         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
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

   public function getById($id, $userLoggedId)
   {
      // validar inputs
      if (empty($id)) {
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
      SELECT p.id AS post_id, p.title AS title, p.description AS description, p.date AS date, p.votes_amount AS votes_amount, p.comments_amount AS comments_amount, p.user_id AS post_user_id, u.name AS post_user_name, v.user_id AS vote_user_id, v.vote_type_id AS vote_type_id
      FROM posts p
      INNER JOIN users u ON p.user_id = u.id
      LEFT JOIN posts_votes v ON p.id = v.post_id AND :userLoggedId = v.user_id
      WHERE p.id = :id
      LIMIT 1";

      // selecionar post
      try {
         $result = $this->connection->prepare($query);

         // executar query
         $result->bindParam(":userLoggedId", $userLoggedId, PDO::PARAM_INT);
         $result->bindParam(":id", $id, PDO::PARAM_INT);
         $result->execute();

         // se o post existir
         if ($result->rowCount() > 0) {
            // obter dados do post
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
            }

            return $post; // retorna o post
         } else {
            $error = new Exception("Post não encontrado.", 1);
            array_push($this->errors, $error);
            return null;
         }
      } catch (PDOException $exception) {
         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
   }

   public function getByUserId($userId, $userLoggedId)
   {
      // validar inputs
      if (empty($userId)) {
         $error = new Exception("Identificador do utilizador do perfil é inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($userLoggedId)) {
         $error = new Exception("Identificador do utilizador logado é inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }

      $query = "
      SELECT p.id AS post_id, p.title AS title, p.description AS description, p.date AS date, p.votes_amount AS votes_amount, p.comments_amount AS comments_amount, p.user_id AS post_user_id, u.name AS post_user_name, v.user_id AS vote_user_id, v.vote_type_id AS vote_type_id
      FROM posts p
      INNER JOIN users u ON p.user_id = u.id AND p.user_id = :userId
      LEFT JOIN posts_votes v ON p.id = v.post_id AND :userLoggedId = v.user_id
      ORDER BY votes_amount
      DESC";

      // selecionar posts
      try {
         $result = $this->connection->prepare($query);

         // executar query
         $result->bindParam(":userId", $userId, PDO::PARAM_INT);
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

   public function update($postId, $title, $description, $userId)
   {
      // validar inputs
      if (empty($postId)) {
         $error = new Exception("Identificador do post inválido.", 1);
         array_push($this->errors, $error);
      }
      if (empty($title)) {
         $error = new Exception("Insira um título.", 1);
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
      UPDATE posts
      SET title = :title, description = :description
      WHERE id = :postId AND user_id = :userId";

      // atualizar post na base de dados
      try {
         $result = $this->connection->prepare($query);

         $result->bindParam(":title", $title, PDO::PARAM_STR);
         $result->bindParam(":description", $description, PDO::PARAM_STR);
         $result->bindParam(":postId", $postId, PDO::PARAM_INT);
         $result->bindParam(":userId", $userId, PDO::PARAM_INT);
         $result->execute();

         return $postId; // retorna o id do post atualizado
      } catch (PDOException $exception) {
         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
   }

   public function delete($postId, $userId)
   {
      // validar inputs
      if (empty($postId)) {
         $error = new Exception("Identificador do utilizador inválido.", 1);
         array_push($this->errors, $error);
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

      // apagar post e registos associados na base de dados
      try {
         $this->connection->beginTransaction();

         // apagar post pelo id do post
         $result1 = $this->connection->prepare($query1);
         $result1->bindParam(":postId", $postId, PDO::PARAM_INT);
         $result1->bindParam(":userId", $userId, PDO::PARAM_INT);
         $result1->execute();

         // apagar votos do post pelo id do post
         $result2 = $this->connection->prepare($query2);
         $result2->bindParam(":postId", $postId, PDO::PARAM_INT);
         $result2->execute();

         // apagar comentários pelo id do post
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

               // apagar votos do comentário pelo id do comentário
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
?>