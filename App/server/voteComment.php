<?php
// votar nos comentarios

require("connectDB.php");
session_start();


// verificar se a ação na chamada ajax não está declarada e vazia
if (!isset($_GET["action"]) && empty($_GET["action"])) {
    die("Erro: Esta ação não é possível.");
} else {
    // verificar se a ação de votar no comentario está definida no ajax
    if ($_GET["action"] == "vote") {
        // verificar se o utilizador está logado para poder votar no comentario
        if (isset($_SESSION["login"])) {
            voteComment();
        } else {
            die(json_encode(["error" => "Só pode votar com login feito."]));
        }
    }
}


function voteComment()
{
    global $connection;

    // converter JSON que veio do cliente para PHP para obter os dados
    $vote = json_decode($_GET["vote"]);
    $userId = $_SESSION["id"];
    $commentId = $vote->commentId;
    $voteTypeId = $vote->voteTypeId;

    // selecionar voto
    if ($query = $connection->prepare("SELECT idTipoVoto FROM votoscomentarios WHERE idComentario = ? AND idUtilizador = ?")) {
        // executar query
        $query->bind_param("ii", $commentId, $userId);
        $query->execute();

        // obter dados do voto selecionado
        $result = $query->get_result();
        $selectedVote = $result->fetch_assoc();

        // se voto clicado é up
        if ($voteTypeId == 1) {
            // se o voto é up não está repetido - atualizar voto  
            if ($selectedVote && $voteTypeId != $selectedVote["idTipoVoto"]) {
                $query->close();
                updateVote($commentId, $userId, $voteTypeId);
            }
            // se o voto é up e está repetido - remover voto  
            else if ($selectedVote && $voteTypeId == $selectedVote["idTipoVoto"]) {
                $query->close();
                removeUpvote($commentId, $userId);
            }
            // senao existe - inserir novo voto
            else if (!$selectedVote) {
                $query->close();
                insertVote($commentId, $userId, 1);
            }
        }
        // se voto clicado é down
        if ($voteTypeId == 2) {
            // se o voto é down e não está repetido - atualizar voto
            if ($selectedVote && $voteTypeId != $selectedVote["idTipoVoto"]) {
                $query->close();
                updateVote($commentId, $userId, $voteTypeId);
            }
            // se o voto é down e está repetido - remover voto
            else if ($selectedVote && $voteTypeId == $selectedVote["idTipoVoto"]) {
                $query->close();
                removeDownvote($commentId, $userId);
            }
            // senao existe - inserir novo voto
            else if (!$selectedVote) {
                $query->close();
                insertVote($commentId, $userId, 2);
            }
        }

        // selecionar número de votos do post atualizado
        if ($query = $connection->prepare("SELECT quantidadeVotos FROM comentarios WHERE id = ?")) {
            $numberOfVotes = getUpdatedNumberOfVotes($query, $commentId);
        } else {
            die("Erro: Algo deu errado com a base de dados.");
        }

        // fechar conexão
        $connection->close();

        // retornar JSON ao cliente
        $returnData = array("voteTypeId" => $voteTypeId, "numberOfVotes" => $numberOfVotes, "selectedVote" => isset($selectedVote["idTipoVoto"]));
        echo json_encode($returnData);
    } else {
        die("Algo deu errado com a base de dados.");
    }
}


function removeUpvote($commentId, $userId)
{
    global $connection;

    if ($query = $connection->prepare("DELETE FROM votoscomentarios WHERE idComentario = ? AND idUtilizador = ?")) {
        $query->bind_param("ii", $commentId, $userId);
        $query->execute();
        $query->close();

        // atualizar número de votos
        if ($query = $connection->prepare("UPDATE comentarios SET quantidadeVotos = quantidadeVotos - 1 WHERE id = ?")) {
            $query->bind_param("i", $commentId);
            $query->execute();
            $query->close();
        } else {
            die("Erro: Algo deu errado com a base de dados.");
        }
    } else {
        die("Erro: Algo deu errado com a base de dados.");
    }
}


function removeDownvote($commentId, $userId)
{
    global $connection;

    if ($query = $connection->prepare("DELETE FROM votoscomentarios WHERE idComentario = ? AnD idUtilizador = ?")) {
        $query->bind_param("ii", $commentId, $userId);
        $query->execute();
        $query->close();

        // atualizar número de votos
        if ($query = $connection->prepare("UPDATE comentarios SET quantidadeVotos = quantidadeVotos + 1 WHERE id = ?")) {
            $query->bind_param("i", $commentId);
            $query->execute();
            $query->close();
        } else {
            die("Erro: Algo deu errado com a base de dados.");
        }
    } else {
        die("Erro: Algo deu errado com a base de dados.");
    }
}


function updateVote($commentId, $userId, $voteTypeId)
{
    global $connection;

    if ($voteTypeId == 1) {  // 1 = upvote
        if ($query = $connection->prepare("UPDATE votoscomentarios SET idTipoVoto = ? WHERE idComentario = ? AND idUtilizador = ?")) {
            $query->bind_param("iii", $voteTypeId, $commentId, $userId);
            $query->execute();
            $query->close();

            // atualizar número de votos
            if ($query = $connection->prepare("UPDATE comentarios SET quantidadeVotos = quantidadeVotos + 2 WHERE id = ?")) {
                $query->bind_param("i", $commentId);
                $query->execute();
                $query->close();
            } else {
                die("Erro: Algo deu errado com a base de dados.");
            }
        } else {
            die("Erro: Algo deu errado com a base de dados.");
        }
    }
    if ($voteTypeId == 2) {  // 2 = downvote
        if ($query = $connection->prepare("UPDATE votoscomentarios SET idTipoVoto = ? WHERE idComentario = ? AND idUtilizador = ?")) {
            $query->bind_param("iii", $voteTypeId, $commentId, $userId);
            $query->execute();
            $query->close();

            // atualizar número de votos
            if ($query = $connection->prepare("UPDATE comentarios SET quantidadeVotos = quantidadeVotos - 2 WHERE id = ?")) {
                $query->bind_param("i", $commentId);
                $query->execute();
                $query->close();
            } else {
                die("Erro: Algo deu errado com a base de dados.");
            }
        } else {
            die("Erro: Algo deu errado com a base de dados.");
        }
    }
}


function insertVote($commentId, $userId,  $voteTypeId)
{
    global $connection;

    if ($voteTypeId == 1) {  // 1 = upvote
        if ($query = $connection->prepare("INSERT INTO votoscomentarios (idComentario, idUtilizador, idTipoVoto) VALUES (?, ?, ?)")) {
            $query->bind_param("iii", $commentId, $userId, $voteTypeId);
            $query->execute();
            $query->close();

            // atualizar número de votos
            if ($query = $connection->prepare("UPDATE comentarios SET quantidadeVotos = quantidadeVotos + 1 WHERE id = ?")) {
                $query->bind_param("i", $commentId);
                $query->execute();
                $query->close();
            } else {
                die("Erro: Algo deu errado com a base de dados.");
            }
        } else {
            die("Erro: Algo deu errado com a base de dados.");
        }
    }
    if ($voteTypeId == 2) {  // 2 = downvote
        if ($query = $connection->prepare("INSERT INTO votoscomentarios (idComentario, idUtilizador, idTipoVoto) VALUES (?, ?, ?)")) {
            $query->bind_param("iii", $commentId, $userId, $voteTypeId);
            $query->execute();
            $query->close();

            // atualizar número de votos
            if ($query = $connection->prepare("UPDATE comentarios SET quantidadeVotos = quantidadeVotos - 1 WHERE id = ?")) {
                $query->bind_param("i", $commentId);
                $query->execute();
                $query->close();
            } else {
                die("Erro: Algo deu errado com a base de dados.");
            }
        } else {
            die("Erro: Algo deu errado com a base de dados.");
        }
    }
}


function getUpdatedNumberOfVotes($query, $commentId)
{
    $query->bind_param("i", $commentId);
    $query->execute();

    // obter dados do voto selecionado
    $result = $query->get_result();
    $comment = $result->fetch_assoc();

    return $comment["quantidadeVotos"];
}
