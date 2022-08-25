<!-- DEFINIÇÃO: modelo de um utilizador -->

<?php
// require_once("src/configs/database-config.php");
require_once("src/models/model.php");

// representação de um utilizador na base de dados
// (constituído pelos campos da tabela "users" na base de dados)
class User
{
   public $id;
   public $name;
   public $password;
   public $email;
   public $first_name;
   public $last_name;
   public $city;
   public $country;
}

class UserModel extends Model
{
   public $errors;

   public function __construct()
   {
      parent::__construct();
      $this->errors = array();
   }

   public function login($name, $password)
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

            // definir sessão de login no site
            require_once("src/utils/session-util.php");
            setLoginSession(true, $user->id, $user->name, $user->email, $user->first_name, $user->last_name);

            // definir cookies para lembrar login quando o browser é fechado
            if (isset($_POST["remember"])) {
               setLoginCookies(true, $user->id, $user->name, $user->email, $user->first_name, $user->last_name);
            }

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