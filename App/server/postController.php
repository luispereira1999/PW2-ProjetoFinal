<?php  // DEFINIÇÃO: parte principal da apresentação dos posts


// obter post da página de cada na base de dados 
function getPost($postId, $userLoggedId)
{
    global $connection;
    $post = array();

    // selecionar posts
    if ($query = $connection->prepare("SELECT p.id AS idPost, p.titulo AS titulo, p.descricao AS descricao, p.data AS data, p.quantidadeVotos AS quantidadeVotos, p.quantidadeComentarios AS quantidadeComentarios, p.idUtilizador AS pIdUtilizador, u.nomeUtilizador AS nomeUtilizador, v.idUtilizador AS vIdUtilizador, v.idTipoVoto AS idTipoVoto FROM posts p INNER JOIN utilizadores u ON p.idUtilizador = u.id LEFT JOIN votos v ON p.id = v.idPost AND ? = v.idUtilizador WHERE p.id = ?")) {
        // executar query
        $query->bind_param("ii", $userLoggedId, $postId);
        $query->execute();

        // obter resultado da query
        $result = $query->get_result();

        // verificar se existem posts
        if ($result->num_rows > 0) {
            // obter dados dos posts
            $post = $result->fetch_assoc();

            // fechar conexão
            $query->close();

            return $post;
        } else {
            header("location:../client/404.php");
        }
    } else {
        header("location:../client/404.php");
    }
}


// mostrar post da página de cada post
function showPost($post, $userLoggedId)
{ ?>
    <section data-post="<?= $post["idPost"]; ?>" class="postText">
        <div>
            <p><?= $post["descricao"]; ?></p>
        </div>
        <div class="interactionsBar">
            <div class="votes">
                <?php showPostVotes($post, $userLoggedId); ?>
            </div>
            <span data-toggle="tooltip" data-placement="bottom" title="Comentários"><i class="fas fa-comment interactionsBarIcons"></i></span>
            <label><?= $post["quantidadeComentarios"]; ?></label>
        </div>
    </section>
<?php }


// mostrar votos do post
function showPostVotes($post, $userLoggedId)
{ ?>
    <span data-vote="upvote"><i <?php if ($post["vIdUtilizador"] == $userLoggedId && $post["idTipoVoto"] == 1) : ?> data-markedvote="marked" <?php endif; ?> data-toggle="tooltip" data-placement="bottom" title="Up Vote" class="fas fa-heart interactionsBarIcons"></i></span>
    <label><?= $post["quantidadeVotos"]; ?></label>
    <span data-vote="downvote"><i <?php if ($post["vIdUtilizador"] == $userLoggedId && $post["idTipoVoto"] == 2) : ?> data-markedvote="marked" <?php endif; ?> data-toggle="tooltip" data-placement="bottom" title="Down Vote" class="fas fa-heart-broken interactionsBarIcons"></i></span>
<?php }


// criar novo post
if (isset($_POST["action"]) && $_POST["action"] == "new" && isset($_POST["title"]) && isset($_POST["description"])) {
    require("../server/connectDB.php");
    session_start();

    if (!empty($_POST["title"]) && !empty($_POST["description"])) {
        // obter de forma segura os dados que vem do formulário
        $title = mysqli_real_escape_string($connection, $_POST["title"]);
        $description = mysqli_real_escape_string($connection, $_POST["description"]);
        $userId = mysqli_real_escape_string($connection, $_SESSION["id"]);
        $date = date("Y/m/d h:i:s", time());

        // inserir na base de dados
        mysqli_query($connection, "INSERT INTO posts (titulo, descricao, data, quantidadeVotos, quantidadeComentarios, idUtilizador) VALUES ('$title', '$description' ,'$date', 0, 0, '$userId')");
        $postId = $connection->insert_id;

        // fechar conexão
        $connection->close();

        $_SESSION["messageSuccess"] = "Post criado com sucesso!";
        header("location: ../client/post.php?postId=" . $postId . "");
    } else {
        $_SESSION["messageError"] = "Erro: Os campos devem ser preenchidos.";
        header("location: ../client/index.php");
    }
}


// executar código quando o botão de editar post é clicado
if (isset($_POST["action"]) && $_POST["action"] === "edit") {
    require("../server/connectDB.php");
    session_start();

    // obter de forma segura os dados que vem da página
    $postId = mysqli_real_escape_string($connection, $_POST["postId"]);

    // selecionar post na base de dados
    $result = mysqli_query($connection, "SELECT titulo, descricao FROM posts WHERE id = $postId");
    $post = mysqli_fetch_assoc($result);

    // enviar JSON para conseguir ler estes dados em JS 
    $sendJSON = json_encode(array("id" => $postId, "title" => $post["titulo"], "description" => $post["descricao"]));

    // fechar conexão
    $connection->close();

    // ir para a página de user e mostrar popup de editar post
    $_SESSION["popupEditPost"] = $sendJSON;
    header("location: ../client/post.php?postId=" . $postId . "");
}


// atualizar post
if (isset($_POST["edit"]) && $_POST["edit"] === "post") {
    require("../server/connectDB.php");
    session_start();

    // obter de forma segura os dados que vem do formulário
    $title = mysqli_real_escape_string($connection, $_POST["title"]);
    $description = mysqli_real_escape_string($connection, $_POST["description"]);
    $postId = mysqli_real_escape_string($connection, $_POST["postId"]);

    // atualizar na base de dados
    mysqli_query($connection, "UPDATE posts SET titulo = '$title', descricao = '$description' WHERE id = $postId;");

    // fechar conexão
    $connection->close();

    $_SESSION["messageSuccess"] = "Post atualizado com sucesso!";
    header("location: ../client/post.php?postId=" . $postId . "");
}


// eliminar post
if (isset($_POST["action"]) && $_POST["action"] == "delete") {
    require("../server/connectDB.php");
    session_start();

    // obter de forma segura os dados que vem do formulário
    $postId = mysqli_real_escape_string($connection, $_POST["postId"]);

    // eliminar na base de dados
    mysqli_query($connection, "DELETE FROM posts WHERE id = $postId;");
    mysqli_query($connection, "DELETE FROM votos WHERE id = $postId;");
    mysqli_query($connection, "DELETE FROM comentarios WHERE id = $postId;");
    mysqli_query($connection, "DELETE FROM votoscomentarios WHERE id = $postId;");

    // fechar conexão
    $connection->close();

    $_SESSION["messageSuccess"] = "Post eliminado com sucesso!";
    header("location: ../client/index.php");
}
?>