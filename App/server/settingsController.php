<?php  // atualizar dados do utilizador


// obter dados do utilizador
function getUserData()
{
    require("../server/connectDB.php");

    if ($query = $connection->prepare("SELECT primeiroNome, ultimoNome, cidade, pais FROM utilizadores WHERE id = ?")) {
        // executar query
        $query->bind_param("i", $_SESSION["id"]);
        $query->execute();

        // obter resultado da query
        $result = $query->get_result();

        // se o utilizador existe
        if ($result->num_rows > 0) {
            // obter dados dos posts
            return $result->fetch_assoc();
        } else {
            $_SESSION["messageError"] = "O utilizador não existe.";
            header("location: ../client/index.php");
        }

        // fechar ligações
        $query->close();
        $connection->close();
    } else {
        $_SESSION["messageError"] = "Erro: Algo deu errado com a base de dados.";
        header("location: ../client/404.php");
    }
}


// atualizar dados do utilizador
if (isset($_POST["action"]) && $_POST["action"] === "save") {
    require("connectDB.php");
    session_start();

    // $_SESSION["messageError"] = "Erro: Nova senha é obrigatória.";
    // header("location: ../client/settings.php");

    // obter de forma segura os dados que vem do formulário
    $email = mysqli_real_escape_string($connection, $_POST["email"]);
    $firstName = mysqli_real_escape_string($connection, $_POST["firstName"]);
    $lastName = mysqli_real_escape_string($connection, $_POST["lastName"]);
    $city = mysqli_real_escape_string($connection, $_POST["city"]);
    $country = mysqli_real_escape_string($connection, $_POST["country"]);

    // validar campos
    if (empty($email)) {
        $_SESSION["messageError"] = "O email é obrigatório.";
        header("location: ../client/settings.php");
        die();
    }

    // atualizar detalhes básicos
    $query = "UPDATE utilizadores SET email = '$email', primeiroNome = '$firstName', ultimoNome = '$lastName', cidade = '$city', pais = '$country' WHERE id = " . $_SESSION["id"] . ";";
    $result = mysqli_query($connection, $query);

    // atualizar senha
    if (isset($_POST["password"]) && $_POST["password"] === "update") {
        updatePassword($email);
    } else {
        header("location: ../client/settings.php");
    }
}


// atualizar senha
function updatePassword($email)
{
    global $connection;

    if (isset($_POST["currentPassword"]) && isset($_POST["newPassword"]) && isset($_POST["confirmNewPassword"])) {
        $currentPassword = mysqli_real_escape_string($connection, $_POST["currentPassword"]);
        $newPassword = mysqli_real_escape_string($connection, $_POST["newPassword"]);
        $confirmNewPassword = mysqli_real_escape_string($connection, $_POST["confirmNewPassword"]);

        // validar senhas
        if (empty($currentPassword)) {
            $_SESSION["messageError"] = "Erro: Senha atual é obrigatória.";
            header("location: ../client/settings.php");
            die();
        }
        if (empty($newPassword)) {
            $_SESSION["messageError"] = "Erro: Nova senha é obrigatória.";
            header("location: ../client/settings.php");
            die();
        }
        if (empty($confirmNewPassword)) {
            $_SESSION["messageError"] = "Erro: Nova senha é obrigatória.";
            header("location: ../client/settings.php");
            die();
        }
        if ($newPassword != $confirmNewPassword) {
            $_SESSION["messageError"] = "Erro: As novas senhas não combinam.";
            header("location: ../client/settings.php");
            die();
        }

        // Selecionar password atual do utilizador
        $query = "SELECT senha FROM utilizadores WHERE id = " . $_SESSION["id"] . ";";
        $result = mysqli_query($connection, $query);
        $user = mysqli_fetch_assoc($result);

        // se o utilizador existe
        if ($user) {
            // encriptar a senha atual que foi inserida para verificar se corresponde à senha guardada na BD
            $currentPassword = md5($currentPassword);
            if ($user["senha"] != $currentPassword) {
                $_SESSION["messageError"] = "Erro: Senha atual está incorreta.";
                header("location: ../client/settings.php");
            }

            // encriptar a password para não mostrar a verdadeira password na BD
            $newPassword = md5($newPassword);

            // executar query
            $query = "UPDATE utilizadores SET senha = '$newPassword' WHERE id = " . $_SESSION["id"] . ";";
            mysqli_query($connection, $query);

            updateSession($email);
            if (isset($_COOKIE["login"])) {
                updateCookies($email);
            }

            header("location: ../client/settings.php");
        }
    }
}


// cancelar atualização dos dados do utilizador
if (isset($_POST["action"]) && $_POST["action"] === "cancel") {
    header("location: ../client/index.php");
}


function updateSession($email)
{
    $_SESSION["email"] = $email;
}


function updateCookies($email)
{
    $_COOKIE["email"] = $email;
}
