<!-- DEFINIÇÃO: ficheiro com funções auxiliares relacionadas a sessões -->

<?php
function setLoginSession($isActive, $id, $name, $email, $firstName, $lastName)
{
   $_SESSION["login"] = $isActive;
   $_SESSION["id"] = $id;
   $_SESSION["name"] = $name;
   $_SESSION["email"] = $email;
   $_SESSION["firstName"] = $firstName;
   $_SESSION["lastName"] = $lastName;
}
