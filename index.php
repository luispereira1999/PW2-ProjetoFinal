<!-- DEFINIÇÃO: ponto de entrada do site (quaisquer requisições são encaminhadas para este ficheiro) -->

<?php
require_once("src/configs/route-config.php");
require_once("src/controllers/brief-post-controller.php");
require_once("src/controllers/auth-controller.php");
require_once("src/utils/session-util.php");

// variáveis de ambiente
define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "");
define("DATABASE_NAME", "kll");

startOrContinueSession(); // iniciar uma nova ou continuar a sessão atual 

// se só existem cookies definidos,
// significa que o utilizador já estava logado no site, antes de abrir o browser
if (isset($_COOKIE["id"]) && !isset($_SESSION["id"])) {
   // definir sessão de login
   setLoginSession($_COOKIE["login"], $_COOKIE["id"], $_COOKIE["name"], $_COOKIE["email"], $_COOKIE["firstName"], $_COOKIE["lastName"]);
}

$route = new Route();
$briefPostController = new BriefPostController();
$authController = new AuthController();

// rotas
$route->add("/", "src/controllers/brief-post-controller.php", array($briefPostController, "index"));
$route->add("/auth", "src/controllers/auth-controller.php", array($authController, "authenticate"));
$route->add("/auth/login", "src/controllers/auth-controller.php", array($authController, "login"));
$route->add("/auth/logout", "src/controllers/auth-controller.php", array($authController, "logout"));

// quando nenhuma rota foi encontrada
$route->notFound("src/views/not-found-view.php");
?>