<?php
class Route
{
   public function add($route, $action)
   {
      // guarda todos os valores dos parâmetros da URI
      $params = array();

      // guarda todos os nomes dos parâmetros da URI
      $paramsKey = array();

      // contar o número de parâmetros e guardar os valores em $paramMatches
      preg_match_all("/(?<={).+?(?=})/", $route, $paramMatches);

      // se a rota não contém nenhum parâmetro
      if (empty($paramMatches[0])) {
         $this->addRouteWithoutParams($route, $action);
         return;
      }

      // definir nome dos parâmetros
      foreach ($paramMatches[0] as $key) {
         $paramsKey[] = $key;
      }

      // se a URI não está vazia
      if (!empty($_REQUEST['uri'])) {
         // obter rota
         $route = preg_replace("/(^\/)|(\/$)/", "", $route);
         $requestUri =  preg_replace("/(^\/)|(\/$)/", "", $_REQUEST['uri']);
      } else {
         $requestUri = "/";
      }

      // obter um array que contém as strings separadas de cada caminho da rota
      // separar cada caminho "/" da rota para várias posições de um array
      $uri = explode("/", $route);

      // armazenar os índices onde os parâmetros estão localizados na rota
      $paramIndex = array();
      foreach ($uri as $index => $param) {
         if (preg_match("/{.*}/", $param)) {
            $paramIndex[] = $index;
         }
      }

      // separar cada caminho "/" da URI, armazenando num array
      $requestUri = explode("/", $requestUri);

      // running for each loop to set the exact index number with reg expression
      // this will help in matching route
      foreach ($paramIndex as $key => $index) {

         // in case if req uri with param index is empty then return
         // because url is not valid for this route
         if (empty($requestUri[$index])) {
            return;
         }

         // setting params with params names
         $params[$paramsKey[$key]] = $requestUri[$index];

         // this is to create a regex for comparing route address
         $requestUri[$index] = "{.*}";
      }

      // converting array to sting
      $requestUri = implode("/", $requestUri);

      // replace all / with \/ for reg expression
      // regex to match route is ready !
      $requestUri = str_replace("/", '\\/', $requestUri);

      // now matching route with regex
      if (preg_match("/$requestUri/", $route)) {
         $action($params);
         exit();
      }
   }

   private function addRouteWithoutParams($route, $action)
   {
      if (!empty($_REQUEST['uri'])) {
         $route = preg_replace("/(^\/)|(\/$)/", "", $route);
         $requestUri =  preg_replace("/(^\/)|(\/$)/", "", $_REQUEST['uri']);
      } else {
         $requestUri = "/";
      }

      if ($requestUri == $route) {
         $params = [];
         $action();
         exit();
      }
   }

   public function go($action)
   {
      $action();
      exit();
   }
}
