<?php
require_once("src/route.php");
require_once("src/controllers/brief-post-controller.php");

$route = new Route();
$briefPostController = new BriefPostController();

// rotas
$route->add("/", "src/controllers/brief-post-controller.php", array($briefPostController, "getBriefPosts"));

// quando nenhuma rota foi encontrada
$route->notFound("src/views/404.php");
