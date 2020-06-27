<?php  // conectar à BD

// iniciar conexão
$connection = new mysqli("localhost", "root", "", "kll");
$connection->set_charset("utf8");

// verificar conexão
if ($connection->connect_error) {
	session_start();
	$_SESSION["messageError"] = "Não foi possível ligar à base de dados.";
	header("location: 404.php");
	die();
}