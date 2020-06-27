<?php  // DEFINIÇÃO: parte principal da apresentação dos posts


function getComments($postId)
{
    global $connection;
    $comments = array();

    // selecionar posts
    if ($query = $connection->prepare("SELECT c.id AS id, c.descricao AS descricao, c.quantidadeVotos AS quantidadeVotos, c.idUtilizador AS idUtilizador, c.idPost AS idPost, u.nomeUtilizador AS nomeUtilizador, vc.idUtilizador AS vcIdUtilizador, vc.idTipoVoto AS idTipoVoto FROM comentarios c INNER JOIN utilizadores u ON c.idUtilizador = u.id LEFT JOIN votoscomentarios vc ON c.id = vc.idComentario AND ? = vc.idUtilizador WHERE c.idPost = ? ORDER BY quantidadeVotos DESC")) {
        // executar query
        $query->bind_param("ii", $userLoggedId, $postId);
        $query->execute();

        // obter resultado da query
        $result = $query->get_result();

        // verificar se existem posts
        if ($result->num_rows > 0) {
            // obter dados dos posts
            while ($comment = $result->fetch_assoc()) {
                array_push($comments, $comment);
            }

            // fechar conexão
            $query->close();
            $connection->close();

            return $comments;
        } else {
            $_SESSION["error"] = true;
        }
    } else {
        die("Erro: Algo deu errado com o servidor.");
    }
}


function showComments($comments, $userLoggedId)
{
    // índices do ciclo for
    $i = 0;
    // índice do post atual
    $current = 0;

    for ($i; $i < count($comments); $i++) : ?>

        <li data-comment="<?= $comments[$current]["id"]; ?>" class="comment">
            <div class="row commentsCenter">
                <div>
                    <div class="commentVotes">
                        <!-- mostrar votos do comentario -->
                        <?php showCommentsVotes($comments, $current, $userLoggedId); ?>
                    </div>
                </div>
                <div class="col-1">
                    <h2><?= $comments[$current]["nomeUtilizador"] ?></h2>
                </div>
                <div class="<?php if ($comments[$current]["idUtilizador"] == $userLoggedId) : echo "col-9";
                            else : echo "col-10";
                            endif; ?>">
                    <p><?= $comments[$current]["descricao"] ?></p>
                </div>
                <?php if ($comments[$current]["idUtilizador"] == $userLoggedId) : ?>
                    <div class="col-1">
                        <form class="editDeleteComment" method="post" action="../server/commentController.php">
                            <span data-action="edit" data-toggle="tooltip" data-placement="bottom" title="Editar"><a data-toggle="modal" data-target="#editComment"><i class="fas fa-edit col-0"></i></a></span>
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="commentId" value="<?= $comments[$current]["id"]; ?>">
                            <a data-action="delete" data-toggle="tooltip" data-placement="bottom" title="Eliminar"><i class="fas fa-trash-alt col-0"></i></a>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </li>

    <?php $current++;
    endfor; ?>

<?php }


function showCommentsVotes($comments, $current, $userLoggedId)
{ ?>
    <span <?php if ($comments[$current]["idUtilizador"] == $userLoggedId && $comments[$current]["idTipoVoto"] == 1) : ?> data-markedvote="marked" <?php endif; ?> data-vote="upvote" data-toggle="tooltip" data-placement="bottom" title="Up Vote"><i class="fas fa-heart interactionsBarIcons"></i></span>
    <label data-toggle="tooltip" data-placement="bottom" title="Número de Comentários"><?= $comments[$current]["quantidadeVotos"] ?></label>
    <span <?php if ($comments[$current]["vcIdUtilizador"] == $userLoggedId && $comments[$current]["idTipoVoto"] == 2) : ?> data-markedvote="marked" <?php endif; ?> data-vote="downvote" data-toggle="tooltip" data-placement="bottom" title="Down Vote"><i class="fas fa-heart-broken interactionsBarIcons"></i></span>
<?php }


// adicionar novo comentário
if (isset($_POST["new"]) && $_POST["new"] == "comment" && isset($_POST["description"])) {
    require("../server/connectDB.php");
    session_start();

    // obter de forma segura os dados que vem do formulário
    $description = mysqli_real_escape_string($connection, $_POST["description"]);
    $userId = mysqli_real_escape_string($connection, $_SESSION["id"]);
    $postId = mysqli_real_escape_string($connection, $_SESSION["post"]["idPost"]);

    // inserir na base de dados
    mysqli_query($connection, "INSERT INTO comentarios (descricao, quantidadeVotos, idUtilizador, idPost) VALUES ('$description', 0, '$userId', '$postId')");
    mysqli_query($connection, "UPDATE posts SET quantidadeComentarios = quantidadeComentarios + 1 WHERE id = $postId");

    // fechar conexão
    $connection->close();

    $_SESSION["messageSuccess"] = "Comentário criado com sucesso!";
    header("location: ../client/post.php?postId=" . $postId . "");

    unset($_POST["new"]);
    unset($_POST["description"]);
}


// atualizar comentário
if (isset($_POST["edit"]) && $_POST["edit"] == "comment" && isset($_POST["commentId"]) && isset($_POST["description"])) {
    require("../server/connectDB.php");
    session_start();

    // obter de forma segura os dados que vem do formulário
    $description = mysqli_real_escape_string($connection, $_POST["description"]);
    $commentId = mysqli_real_escape_string($connection, $_POST["commentId"]);

    // atualizar na base de dados
    mysqli_query($connection, "UPDATE comentarios SET descricao = '$description' WHERE id = $commentId;");

    // fechar conexão
    $connection->close();

    $_SESSION["messageSuccess"] = "Comentário atualizado com sucesso!";
    header("location: ../client/post.php?postId=" . $_SESSION["post"]["idPost"] . "");

    unset($_POST["edit"]);
    unset($_POST["commentId"]);
    unset($_POST["description"]);
}


// eliminar comentário
if (isset($_POST["action"]) && $_POST["action"] == "delete") {
    require("../server/connectDB.php");
    session_start();

    // obter de forma segura os dados que vem do formulário
    $commentId = mysqli_real_escape_string($connection, $_POST["commentId"]);

    // selecionar voto
    if ($query = $connection->prepare("SELECT idTipoVoto FROM votoscomentarios WHERE idPost = ? AND idUtilizador = ?")) {
        // executar query
        $query->bind_param("ii", $_SESSION["post"]["idPost"], $_SESSION["id"]);
        $query->execute();

        // obter dados do voto selecionado
        $result = $query->get_result();
        $selectedVote = $result->fetch_assoc();

        // se voto clicado é up
        if ($selectedVote["idTipoVoto"] == 1) {
            mysqli_query($connection, "UPDATE comentarios SET quantidadeVotos = quantidadeVotos - 1 WHERE id = $commentId;");
        }
        // se voto clicado é down
        if ($selectedVote["idTipoVoto"] == 2) {
            mysqli_query($connection, "UPDATE comentarios SET quantidadeVotos = quantidadeVotos + 1 WHERE id = $commentId;");
        }
    } else {
        $_SESSION["messageError"] = "Algo deu errado com a base de dados.";
        header("location: ../client/post.php?postId=" . $_SESSION["post"]["idPost"] . "");
    }

    // eliminar na base de dados
    mysqli_query($connection, "DELETE FROM comentarios WHERE id = $commentId;");
    mysqli_query($connection, "UPDATE posts SET quantidadeComentarios = quantidadeComentarios - 1 WHERE id = " . $_SESSION["post"]["idPost"] . ";");

    // fechar conexão
    $connection->close();

    $_SESSION["messageSuccess"] = "Comentário eliminado com sucesso!";
    header("location: ../client/post.php?postId=" . $_SESSION["post"]["idPost"] . "");

    unset($_POST["action"]);
}
?>