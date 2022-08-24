<?php
function safeHtmlOutput($data)
{
   if (is_array($data)) {
      foreach ($data as $key => $value) {
         $data[htmlspecialchars($key)] = safeHtmlOutput($value);
      }
   } else if (is_object($data)) {
      foreach ($data as $key => $value) {
         $data->{htmlspecialchars($key)} = safeHtmlOutput($value);
      }
   } else {
      $data = htmlspecialchars($data);
   }

   return $data;
}
