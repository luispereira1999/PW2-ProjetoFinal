<?php
// DEFINIÇÃO: classe para conexão à base de dados

class Database
{
   protected $connection;

   public function __construct()
   {
      $this->connect();
   }

   private function connect()
   {
      try {
         $this->connection = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE_NAME . ";charset=" . CHARSET, USER, PASSWORD);
      } catch (PDOException $exception) {
         $_SESSION["errors"] =  ["Não ao comunicar com o servidor."];
         header("location: /error");
         die();
      }
   }
}
?>