<!-- DEFINIÇÃO: classe para renderizar as views e obter os dados de output para essas views -->

<?php
class View
{
   public function __construct($file, $data = [])
   {
      if (!empty($data)) {
         foreach ($data as $key => $value) {
            ${$key} = $value;
         }
      }

      require_once($file);
   }
}
?>