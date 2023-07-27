<?php
$errors = "";

if (!empty($_SESSION["errors"])) {
   $errors = $_SESSION["errors"];
   unset($_SESSION["errors"]);
}
?>
