<!-- DEFINIÇÃO: modelo de um utilizador -->

<?php
require_once("src/configs/database-config.php");
require_once("entities/user.php");

class UserModel extends Database
{
   public $errors;

   public function __construct()
   {
      parent::__construct();
      $this->errors = array();
   }

   public function create($name, $email, $password, $confirmPassword)
   {
      // validar inputs
      if (empty($name)) {
         $error = new Exception("Insira um nome de utilizador.", 1);
         array_push($this->errors, $error);
      }
      if (empty($email)) {
         $error = new Exception("Insira um email.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($password)) {
         $error = new Exception("Insira uma senha.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($confirmPassword)) {
         $error = new Exception("Confirma a sua senha.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if ($password != $confirmPassword) {
         $error = new Exception("As senhas não correspondem.", 1);
         array_push($this->errors, $error);
         return null;
      }

      $query = "
      SELECT name, email
      FROM users
      WHERE (name = :name OR email = :email)
      LIMIT 1";

      // selecionar utilizador na base de dados
      try {
         $result = $this->connection->prepare($query);

         // executar query
         $result->bindParam(":name", $name, PDO::PARAM_STR);
         $result->bindParam(":email", $email, PDO::PARAM_STR);
         $result->execute();

         // se o utilizador já existe
         if ($result->rowCount() > 0) {
            // obter dados do utilizador
            $row = $result->fetch(PDO::FETCH_ASSOC);

            $user = new User();
            $user->name = $row["name"];
            $user->email = $row["email"];

            if ($user->name == $name) {
               $error = new Exception("Este nome de utilizador já existe.", 1);
               array_push($this->errors, $error);
               return null;
            }
            if ($user->email == $email) {
               $error = new Exception("Este email já existe.", 1);
               array_push($this->errors, $error);
               return null;
            }
         }
      } catch (PDOException $exception) {
         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }

      // encriptar senha
      $password = md5($password);

      $query = "
      INSERT INTO users (name, password, email)
      VALUES (:name, :password, :email)";

      // inserir utilizador na base de dados
      try {
         $result = $this->connection->prepare($query);

         $result->bindParam(":name", $name, PDO::PARAM_STR);
         $result->bindParam(":password", $password, PDO::PARAM_STR);
         $result->bindParam(":email", $email, PDO::PARAM_STR);
         $result->execute();

         $id = $this->connection->lastInsertId();
         return $id; // retornar o id do utilizador inserido
      } catch (PDOException $exception) {
         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
   }

   public function validate($name, $password)
   {
      // validar inputs
      if (empty($name)) {
         $error = new Exception("Insira um nome de utilizador/email.", 1);
         array_push($this->errors, $error);
         return null;
      }
      if (empty($password)) {
         $error = new Exception("Insira uma senha.", 1);
         array_push($this->errors, $error);
         return null;
      }

      // encriptar senha
      $password = md5($password);

      $query = "
      SELECT id, name, email, first_name, last_name
      FROM users
      WHERE name = :name AND password = :password
      LIMIT 1";

      // selecionar utilizador na base de dados
      try {
         $result = $this->connection->prepare($query);

         // executar query
         $result->bindParam(":name", $name, PDO::PARAM_STR);
         $result->bindParam(":password", $password, PDO::PARAM_STR);
         $result->execute();

         // se o utilizador existir 
         if ($result->rowCount() > 0) {
            // obter dados do utilizador
            $row = $result->fetch(PDO::FETCH_ASSOC);

            $user = new User();
            $user->id = $row["id"];
            $user->name = $row["name"];
            $user->email = $row["email"];
            $user->first_name = $row["first_name"];
            $user->last_name = $row["last_name"];

            return $user; // retorna o utilizador logado
         } else {
            $error = new Exception("O nome de utilizador/email ou senha não coincidem.", 1);
            array_push($this->errors, $error);
            return null;
         }
      } catch (PDOException $exception) {
         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
   }

   public function getById($id)
   {
      // validar inputs
      if (empty($id)) {
         $error = new Exception("Identificador do utilizador inválido.", 1);
         array_push($this->errors, $error);
         return null;
      }

      $query = "
      SELECT id, name, email, first_name, last_name
      FROM users
      WHERE id = :id
      LIMIT 1";

      // selecionar utilizador na base de dados
      try {
         $result = $this->connection->prepare($query);

         // executar query
         $result->bindParam(":id", $id, PDO::PARAM_INT);
         $result->execute();

         // se o utilizador existir 
         if ($result->rowCount() > 0) {
            // obter dados do utilizador
            $row = $result->fetch(PDO::FETCH_ASSOC);

            $user = new User();
            $user->id = $row["id"];
            $user->name = $row["name"];
            $user->email = $row["email"];
            $user->first_name = $row["first_name"];
            $user->last_name = $row["last_name"];

            return $user; // retorna o utilizador logado
         } else {
            $error = new Exception("O nome de utilizador/email ou senha não coincidem.", 1);
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