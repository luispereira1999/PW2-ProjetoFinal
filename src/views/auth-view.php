<!-- DEFINIÇÃO: página de login ou signup do site -->

<?php
$errors = "";

if (!empty($_SESSION["errors"])) {
   $errors = $_SESSION["errors"];
   unset($_SESSION["errors"]);
}
?>

<html lang="pt-PT">
</html>
