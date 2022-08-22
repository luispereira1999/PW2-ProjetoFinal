<?php
require_once("src/route.php");

require_once("src/controllers/brief-post-controller.php");
require_once("src/controllers/user-controller.php");

$route = new Route();
$briefPostController = new BriefPostController();
$userController = new UserController();

// rotas
$route->add("/", "src/controllers/brief-post-controller.php", array($briefPostController, "index"));
$route->add("/login-signup", "src/controllers/user-controller.php", array($userController, "loginSignup"));

// quando nenhuma rota foi encontrada
$route->notFound("src/views/404.php");
