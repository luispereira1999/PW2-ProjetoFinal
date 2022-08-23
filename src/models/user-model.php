<!-- DEFINIÇÃO: modelo de um utilizador -->

<?php
require_once("src/configurations/database.php");

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

class UserModel extends Database
{
   public function __construct()
   {
      parent::__construct();
   }

   public function login()
   {
      session_start();

      // $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      if (isset($_POST["login"])) {
         // obter os dados que vem do formulário
         $name = $_POST["name"];
         $password = $_POST["password"];

         if (empty($name)) {
            $_SESSION["error"] = "O nome de utilizador ou email é obrigatório.";
            header("location: /authentication");
            die();
         }
         if (empty($password)) {
            $_SESSION["error"] = "A senha é obrigatória.";
            header("location: /authentication");
            die();
         }
      }

      // encriptar senha para que seja igual à que está armazenada na base de dados, caso esta exista
      $password = md5($password);

      $query = "
      SELECT id, name, email, first_name, last_name
      FROM users
      WHERE name = :name AND password = :password
      LIMIT 1";

      // selecionar posts
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
            require_once("src/utils/session.php");
            setLoginSession(true, $user->id, $user->name, $user->email, $user->first_name, $user->last_name);

            // definir cookies para lembrar login quando o browser é fechado
            if (isset($_POST["remember"])) {
               setLoginCookies(true, $user->id, $user->name, $user->email, $user->first_name, $user->last_name);
            }

            return $user; // retorna o utilizador logado
         } else {
            $_SESSION["error"] = "Senha inválida.";
         }
      } else {
         $_SESSION["error"] = "Sem resultados.";
      }

      return null;
   }
}
?>