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

   public function validate($name, $password)
   {
      if (empty($name)) {
         $error = new Exception("Insira um nome de utilizador/email.", 1);
         array_push($this->errors, $error);
      }
      if (empty($password)) {
         $error = new Exception("Insira uma senha.", 1);
         array_push($this->errors, $error);
         return null;
      }

      // encriptar senha para que seja igual à que está armazenada na base de dados, caso esta exista
      $password = md5($password);

      $query = "
      SELECT id, name, email, first_name, last_name
      FROM users
      WHERE name = :name AND password = :password
      LIMIT 1";

      // selecionar utilizador na base de dados
      if ($result = $this->connection->prepare($query)) {
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
      } else {
         $error = new Exception("Erro ao comunicar com o servidor.", 1);
         array_push($this->errors, $error);
         return null;
      }
   }
}
?>