<!-- DEFINIÇÃO: conexão à base de dados -->

<?php
// iniciar conexão
$connection = new mysqli("localhost", "root", "", "kll");
$connection->set_charset("utf8");

// verificar conexão
if ($connection->connect_error) {
   header("location: not-found.php");
   die();
}
?>