<!-- DEFINIÇÃO: classe para conexão à base de dados -->

<?php
define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "");
define("DATABASE_NAME", "kll");

class Database
{
   protected $connection;

   public function __construct()
   {
      $this->connectDatabase();
   }

   private function connectDatabase()
   {
      try {
         $this->connection = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE_NAME, USER, PASSWORD);
      } catch (PDOException $exception) {
         header("location: not-found.php");
         die();
      }
   }
}
?>
