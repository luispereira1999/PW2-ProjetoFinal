<?php
require_once("src/route.php");

require_once("src/controllers/brief-post-controller.php");
require_once("src/controllers/authentication-controller.php");

$route = new Route();
$briefPostController = new BriefPostController();
$authenticationController = new AuthenticationController();

// rotas
$route->add("/", "src/controllers/brief-post-controller.php", array($briefPostController, "index"));
$route->add("/authentication", "src/controllers/authentication-controller.php", array($authenticationController, "authentication"));
$route->add("/authentication/login", "src/controllers/authentication-controller.php", array($authenticationController, "login"));

// quando nenhuma rota foi encontrada
$route->notFound("src/views/not-found-view.php");
