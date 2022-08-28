<!-- DEFINIÇÃO: ficheiro com funções auxiliares relacionadas a sessões -->

<?php
function startOrContinueSession()
{
   session_start();
}

function setLoginSession($isActive, $id, $name, $email, $firstName, $lastName)
{
   $_SESSION["login"] = $isActive;
   $_SESSION["id"] = $id;
   $_SESSION["name"] = $name;
   $_SESSION["email"] = $email;
   $_SESSION["firstName"] = $firstName;
   $_SESSION["lastName"] = $lastName;
}

function setLoginCookies($isActive, $id, $name, $email, $firstName, $lastName)
{
   $activeTime = time() + 7 * 24 * 3600; // 7 dias | 24 horas | 3600 minutos  

   setcookie("login",  $isActive, $activeTime, "/");
   setcookie("id", $id, $activeTime, "/");
   setcookie("name", $name, $activeTime, "/");
   setcookie("email", $email, $activeTime, "/");
   setcookie("firstName",  $firstName, $activeTime, "/");
   setcookie("lastName",  $lastName, $activeTime, "/");
}

function destroySession()
{
   session_destroy();
}

function removeCookies()
{
   // remover cookies
   unset($_COOKIE["login"]);
   unset($_COOKIE["id"]);
   unset($_COOKIE["name"]);
   unset($_COOKIE["email"]);
   unset($_COOKIE["firstName"]);
   unset($_COOKIE["lastName"]);

   setcookie("login", "", time() - 3600, "/");
   setcookie("id", "", time() - 3600, "/");
   setcookie("name", "", time() - 3600, "/");
   setcookie("email", "", time() - 3600, "/");
   setcookie("firstName", "", time() - 3600, "/");
   setcookie("lastName", "", time() - 3600, "/");
}
?>