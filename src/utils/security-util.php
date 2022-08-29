<!-- DEFINIÇÃO: ficheiro com funções auxiliares relacionadas a segurança -->

<?php
function protectOutputToHtml($data)
{
   if (is_array($data)) {
      foreach ($data as $key => $value) {
         $data[htmlspecialchars($key)] = htmlspecialchars($value);
      }
   } else if (is_object($data)) {
      foreach ($data as $key => $value) {
         $data->{htmlspecialchars($key)} = htmlspecialchars($value);
      }
   } else {
      $data = htmlspecialchars($data);
   }

   return $data;
}
?>