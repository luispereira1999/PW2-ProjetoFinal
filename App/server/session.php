<?php
// fazer login, signup e logout

require("../server/connect-db.php");
session_start();

// registar utilizador
if (isset($_POST["signup"])) {
   // obter de forma segura os dados que vem do formulário
   $username = mysqli_real_escape_string($connection, $_POST["username"]);
   $email = mysqli_real_escape_string($connection, $_POST["email"]);
   $password = mysqli_real_escape_string($connection, $_POST["password"]);
   $confirmPassword = mysqli_real_escape_string($connection, $_POST["confirmPassword"]);

   // validar campos
   if (empty($username)) {
      $_SESSION["messageError"] = "O utilizador é obrigatório.";
      header("location: ../client/login-signup.php");
      die();
   }
   if (empty($email)) {
      $_SESSION["messageError"] = "O email é obrigatório.";
      header("location: ../client/login-signup.php");
      die();
   }
   if (empty($password)) {
      $_SESSION["messageError"] = "A senha é obrigatória.";
      header("location: ../client/login-signup.php");
      die();
   }
   if (empty($confirmPassword)) {
      $_SESSION["messageError"] = "A senha é obrigatória.";
      header("location: ../client/login-signup.php");
      die();
   }
   if ($password != $confirmPassword) {
      $_SESSION["messageError"] = "As senhas não correspondem.";
      header("location: ../client/login-signup.php");
      die();
   }

   // Selecionar nome de utilizador ou email
   $query = "SELECT id, nomeUtilizador, email, primeiroNome, ultimoNome, cidade, pais FROM utilizadores WHERE (nomeUtilizador = '$username' OR email = '$email');";
   $result = mysqli_query($connection, $query);
   $user = mysqli_fetch_assoc($result);

   // verificar se utilizador já existe
   if ($user) {
      if ($user["nomeUtilizador"] === $username) {
         $_SESSION["messageError"] = "O utilizador já existe.";
         header("Location: ../client/login-signup.php");
         die();
      }
      if ($user["email"] === $email) {
         $_SESSION["messageError"] = "O email já existe.";
         header("Location: ../client/login-signup.php");
         die();
      }
   }

   // verificar se não existe erros para registar utilizador
   if (count($errors) == 0) {
      // encriptar a password para não mostrar a verdadeira password na BD
      $password = md5($password);

      // executar query
      $query = "INSERT INTO utilizadores (nomeUtilizador, senha, email) 
        VALUES ('$username', '$password', '$email');";
      mysqli_query($connection, $query);

      // definir sessão de login
      setSession(true, $connection->insert_id,  $username, $user["email"], $user["primeiroNome"], $user["ultimoNome"], $user["cidade"], $user["pais"]);

      // fechar conexão
      $connection->close();

      // redirecionar utilizador para a página principal
      header("location: ../client/index.php");
   }
}


// fazer login
if (isset($_POST["login"])) {
   // obter de forma segura os dados que vem do formulário
   $username = mysqli_real_escape_string($connection, $_POST["username"]);
   $password = mysqli_real_escape_string($connection, $_POST["password"]);

   if (empty($username)) {
      $_SESSION["messageError"] = "O nome de utilizador ou email é obrigatório.";
      header("location: ../client/login-signup.php");
      die();
   }
   if (empty($password)) {
      $_SESSION["messageError"] = "A senha é obrigatória.";
      header("location: ../client/login-signup.php");
      die();
   }

   // encriptar password para depois verificar se coincide com o nome de utilizador
   $password = md5($password);

   // executar query para verificar se o utilizador já está registado na base de dados
   $query = "SELECT id, nomeUtilizador, email, primeiroNome, ultimoNome, cidade, pais FROM utilizadores WHERE nomeUtilizador = '$username' AND senha = '$password';";
   $result = mysqli_query($connection, $query);

   // obter resultado da query
   $user = mysqli_fetch_array($result);

   if (mysqli_num_rows($result) == 1) {
      // definir sessão de login
      setSession(true, $user["id"], $user["nomeUtilizador"], $user["email"], $user["primeiroNome"], $user["ultimoNome"], $user["cidade"], $user["pais"]);

      // definir cookies para lembrar login quando o browser é fechado
      if (isset($_POST["remember"])) {
         rememberLogin(true, $user["id"], $user["nomeUtilizador"], $user["email"], $user["primeiroNome"], $user["ultimoNome"], $user["cidade"], $user["pais"]);
      }

      // fechar conexão
      $connection->close();

      // redirecionar utilizador para a página principal
      header("location: ../client/index.php");
   } else {
      $_SESSION["messageError"] = "O utilizador e senha não combinam.";
      header("location: ../client/login-signup.php");
      die();
   }
}


// fazer logout
if (isset($_GET["logout"])) {
   // destruir sessão atual
   session_destroy();

   // remover cookies
   unset($_COOKIE["login"]);
   unset($_COOKIE["id"]);
   unset($_COOKIE["username"]);
   unset($_COOKIE["email"]);
   setcookie("login", null, -1, "/");
   setcookie("id", null, -1, "/");
   setcookie("username", null, -1, "/");
   setcookie("email", null, -1, "/");

   // redirecionar utilizador para a página principal
   header("location: ../client/index.php");
}


function setSession($isActive, $userId, $username, $email, $firstName, $lastName, $city, $country)
{
   $_SESSION["login"] = $isActive;
   $_SESSION["id"] = $userId;
   $_SESSION["username"] = $username;
   $_SESSION["email"] = $email;
}


function rememberLogin($isActive, $userId, $username, $email, $firstName, $lastName, $city, $country)
{
   // 7 dias | 24 horas | 3600 minutos  
   $activeTime = time() + 7 * 24 * 3600;
   setcookie("login",  $isActive, $activeTime, "/");
   setcookie("id", $userId, $activeTime, "/");
   setcookie("username", $username, $activeTime, "/");
   setcookie("email",  $email, $activeTime, "/");
}
