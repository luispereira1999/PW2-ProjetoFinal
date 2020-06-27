<?php  // atualizar dados do utilizador

require("connectDB.php");


// atualizar dados do utilizador
if (isset($_POST["save"]) == "save") {
    session_start();

    $_SESSION["messageError"] = "Erro: Nova senha  é obrigatória.";
    header("location: ../client/settings.php");
    die();

    // obter de forma segura os dados que vem do formulário
    $email = mysqli_real_escape_string($connection, $_POST["email"]);
    $firstName = mysqli_real_escape_string($connection, $_POST["firstName"]);
    $lastName = mysqli_real_escape_string($connection, $_POST["lastName"]);
    $city = mysqli_real_escape_string($connection, $_POST["city"]);
    $country = mysqli_real_escape_string($connection, $_POST["country"]);

    // validar campos
    if (empty($email)) {
        array_push($errors, "Erro: Email é obrigatório.");
    }

    // verificar se não existe erros para atualizar detalhes básicos
    if (count($errors) == 0) {
        // atualizar detalhes básicos
        $query = "UPDATE utilizadores SET email = '$email', primeiroNome = '$firstName', ultimoNome = '$lastName' , cidade = '$city' , pais = '$country' WHERE id = " . $_SESSION["id"] . ";";
        $result = mysqli_query($connection, $query);

        header("location: ../client/settings.php");
    } else {
        header("location: ../client/settings.php");
    }

    // atualizar senha
    if (isset($_POST["currentPassword"]) && isset($_POST["newPassword"]) && isset($_POST["confirmNewPassword"])) {
        $currentPassword = mysqli_real_escape_string($connection, $_POST["currentPassword"]);
        $newPassword = mysqli_real_escape_string($connection, $_POST["newPassword"]);
        $confirmNewPassword = mysqli_real_escape_string($connection, $_POST["confirmNewPassword"]);
        echo $currentPassword;
        echo $newPassword;
        echo $confirmNewPassword;
        die();
        // validar senhas
        if (empty($currentPassword)) {
            $_SESSION["messageError"] = "Erro: Senha atual é obrigatória.";
            header("location: ../client/settings.php");
        }
        if (empty($newPassword)) {
            $_SESSION["messageError"] = "Erro: Nova senha  é obrigatória.";
            header("location: ../client/settings.php");
        }
        if (empty($confirmNewPassword)) {
            $_SESSION["messageError"] = "Erro: Nova senha  é obrigatória.";
            header("location: ../client/settings.php");
        }
        if ($newPassword != $confirmNewPassword) {
            $_SESSION["messageError"] = "Erro: As novas senhas não correspondem.";
            header("location: ../client/settings.php");
        }

        // Selecionar password atual do utilizador
        $query = "SELECT senha FROM utilizadores WHERE id = " . $_SESSION["id"] . ";";
        $result = mysqli_query($connection, $query);
        $user = mysqli_fetch_assoc($result);

        // se o utilizador existe
        if ($user) {
            // se atual password não corresponde à password inserida
            if ($user["senha"] != $currentPassword) {
                $_SESSION["messageError"] = "Erro: Senha atual está incorreta.";
                header("location: ../client/settings.php");
            }

            // encriptar a password para não mostrar a verdadeira password na BD
            $newPassword = md5($newPassword);

            // executar query
            $query = "UPDATE utilizadores SET senha = " . $newPassword . " WHERE id = " . $_SESSION["id"] . ";";
            mysqli_query($connection, $query);

            updateSession($username, $email);
            if (isset($_COOKIE["login"])) {
                updateCookies($username, $email);
            }

            header("location: ../client/settings.php");
        }
    }
}


// cancelar atualização dos dados do utilizador
if (isset($_POST["cancel"]) == "cancel") {
    session_start();
    header("location: ../client/index.php");
}


function updateSession($username, $email)
{
    $_SESSION["username"] = $username;
    $_SESSION["email"] = $email;
}


function updateCookies($username, $email)
{
    $_COOKIE["username"] = $username;
    $_COOKIE["email"] = $email;
}
