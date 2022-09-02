<!-- DEFINIÇÃO: classe para conexão à base de dados -->

<?php
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
         $_SESSION["errors"] =  ["Não é possível efetuar esta operação."];
         header("location: /not-found");
         die();
      }
   }
}
?>