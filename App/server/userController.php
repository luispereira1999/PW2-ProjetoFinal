<?php  // DEFINIÇÃO: posts de cada utilizador


function getPostUser()
{
    global $connection;

    // selecionar posts de um utilizador
    if ($query = $connection->prepare("SELECT nomeUtilizador FROM utilizadores WHERE id = ?")) {
        // executar query
        $query->bind_param("i", $_GET["userId"]);
        $query->execute();

        $result = $query->get_result();
        if ($result->num_rows) {
            $user = $result->fetch_assoc();

            // fechar conexão
            $query->close();

            return $user;
        } else {
            // $_SESSION["messageError"] = "Erro: Algo deu errado com o servidor.";
            // header("location: ../client/404.php");
        }
    } else {
        // $_SESSION["messageError"] = "Erro: Algo deu errado com o servidor.";
        // header("location: ../client/404.php");
    }
}


function getUserPosts($userLoggedId)
{
    global $connection;
    $posts = array();

    // selecionar posts de um utilizador
    if ($query = $connection->prepare("SELECT p.id AS idPost, p.titulo AS titulo, p.descricao AS descricao, p.data AS data, p.quantidadeVotos AS quantidadeVotos, p.quantidadeComentarios AS quantidadeComentarios, p.idUtilizador AS pIdUtilizador, u.nomeUtilizador AS nomeUtilizador, v.idUtilizador AS vIdUtilizador, v.idTipoVoto AS idTipoVoto FROM posts p INNER JOIN utilizadores u ON p.idUtilizador = u.id AND p.idUtilizador = ? LEFT JOIN votosposts v ON p.id = v.idPost AND ? = v.idUtilizador ORDER BY quantidadeVotos DESC")) {
        // executar query
        $query->bind_param("ii", $_GET["userId"], $userLoggedId);
        $query->execute();

        $result = $query->get_result();
        if ($result->num_rows) {
            while ($post = $result->fetch_assoc()) {
                array_push($posts, $post);
            }

            // fechar conexão
            $query->close();
            $connection->close();

            return $posts;
        } else {
            $_SESSION["error"] = true;
        }
    } else {
        header("location:../client/404.php");
    }
}


function showUserPosts($posts, $userLoggedId)
{
    // índices do ciclo for e do clico while
    $i = 0;
    $j = 0;
    // índice do post atual
    $current = 0;

    for ($i; $i < count($posts); $i++) : ?>
        <section class="sectionPost">

            <!-- ciclo para mostrar 3 em 3 posts -->
            <?php while ($j < 3) :

                // se já não existem posts para mostrar sai desta função "showPosts"
                if ($current == count($posts)) :
                    return;
                endif; ?>

                <!-- mostrar post -->
                <div data-post="<?= $posts[$current]["idPost"]; ?>" class="width3">
                    <div class="postOptions">
                        <?php if ($posts[$current]["pIdUtilizador"] == $userLoggedId) : ?>
                            <form class="editDeletePost" method="post" action="../server/postController.php">
                                <a data-action="edit"><span data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fas fa-edit col-0"></i></span></a>
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="postId" value="<?= $posts[$current]["idPost"]; ?>">
                                <a data-action="delete" data-toggle="tooltip" data-placement="bottom" title="Eliminar"><i class="fas fa-trash-alt col-0"></i></a>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="postOptionDiv">
                        <h3><a href="post.php?postId=<?= $posts[$current]["idPost"]; ?>"><?= $posts[$current]["titulo"]; ?></a></h3>
                    </div>
                    <div>
                        <h5><a href="user.php?userId=<?= $posts[$current]["pIdUtilizador"]; ?>"><?= $posts[$current]["nomeUtilizador"]; ?></a></h5>
                        <h5><?= $posts[$current]["data"]; ?></h5>
                    </div>
                    <p><?= $posts[$current]["descricao"]; ?></p>
                    <div class="interactionsBar">
                        <div class="votes">
                            <!-- mostrar votos do post -->
                            <?php showVotes($posts, $current, $userLoggedId); ?>
                        </div>
                        <span data-toggle="tooltip" data-placement="bottom" title="Comentários"><i class="fas fa-comment interactionsBarIcons"></i></span>
                        <label><?= $posts[$current]["quantidadeComentarios"] ?></label>
                    </div>
                </div>

            <?php $j++ . $current++;
            endwhile;
            $j = 0; ?>

        </section>
    <?php endfor;
}


function showVotes($posts, $current, $userLoggedId)
{ ?>
    <span data-vote="upvote"><i <?php if ($posts[$current]["vIdUtilizador"] == $userLoggedId && $posts[$current]["idTipoVoto"] == 1) : ?> data-markedvote="marked" <?php endif; ?> data-toggle="tooltip" data-placement="bottom" title="Up Vote" class="fas fa-heart interactionsBarIcons"></i></span>
    <label><?= $posts[$current]["quantidadeVotos"]; ?></label>
    <span data-vote="downvote"><i <?php if ($posts[$current]["vIdUtilizador"] == $userLoggedId && $posts[$current]["idTipoVoto"] == 2) : ?> data-markedvote="marked" <?php endif; ?> data-toggle="tooltip" data-placement="bottom" title="Down Vote" class="fas fa-heart-broken interactionsBarIcons"></i></span>
<?php } ?>