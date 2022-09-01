<!-- DEFINIÇÃO: modelo de um post que aparece na página individual do post -->

<?php
require_once("src/configs/database-config.php");
require_once("entities/post.php");

class FullPostModel extends Database
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
         $result->bindParam(":userId", $userId, PDO::PARAM_STR);
         $result->execute();

         $postId = $this->connection->lastInsertId();
         return $postId; // retorna o id do post inserido
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
}
?>