<?php
// votar nos posts

require("connect-db.php");
session_start();


// verificar se a ação na chamada ajax não está declarada e vazia
if (!isset($_GET["action"]) && empty($_GET["action"])) {
   die("Erro: Esta ação não é possível.");
} else {
   // verificar se a ação de votar no post está definida no ajax
   if ($_GET["action"] == "vote") {
      // verificar se o utilizador está logado para poder votar no post
      if (isset($_SESSION["login"])) {
         votePost();
      } else {
         die(json_encode(["error" => "Só pode votar com login feito."]));
      }
   }
}


function votePost()
{
   global $connection;

   // converter JSON que veio do cliente para PHP para obter os dados
   $vote = json_decode($_GET["vote"]);
   $userId = $_SESSION["id"];
   $postId = $vote->postId;
   $voteTypeId = $vote->voteTypeId;
   $activeVote = false;

   // selecionar voto
   if ($query = $connection->prepare("SELECT idTipoVoto FROM votos WHERE idPost = ? AND idUtilizador = ?")) {
      // executar query
      $query->bind_param("ii", $postId, $userId);
      $query->execute();

      // obter dados do voto selecionado
      $result = $query->get_result();
      $selectedVote = $result->fetch_assoc();

      // se voto clicado é up
      if ($voteTypeId == 1) {
         // se o voto é up não está repetido - atualizar voto  
         if ($selectedVote && $voteTypeId != $selectedVote["idTipoVoto"]) {
            $query->close();
            $activeVote = updateVote($postId, $userId, $voteTypeId);
         }
         // se o voto é up e está repetido - remover voto  
         else if ($selectedVote && $voteTypeId == $selectedVote["idTipoVoto"]) {
            $query->close();
            removeUpvote($postId, $userId);
         }
         // senão existe - inserir novo voto
         else if (!$selectedVote) {
            $query->close();
            $activeVote = insertVote($postId, $userId, 1);
         }
      }
      // se voto clicado é down
      if ($voteTypeId == 2) {
         // se o voto é down e não está repetido - atualizar voto
         if ($selectedVote && $voteTypeId != $selectedVote["idTipoVoto"]) {
            $query->close();
            $activeVote = updateVote($postId, $userId, $voteTypeId);
         }
         // se o voto é down e está repetido - remover voto
         else if ($selectedVote && $voteTypeId == $selectedVote["idTipoVoto"]) {
            $query->close();
            removeDownvote($postId, $userId);
         }
         // senão existe - inserir novo voto
         else if (!$selectedVote) {
            $query->close();
            $activeVote =  insertVote($postId, $userId, 2);
         }
      }

      // selecionar número de votos do post atualizado
      if ($query = $connection->prepare("SELECT quantidadeVotos FROM posts WHERE id = ?")) {
         $numberOfVotes = getUpdatedNumberOfVotes($query, $postId);
      } else {
         die("Erro: Algo deu errado com a base de dados.");
      }

      // fechar conexão
      $connection->close();

      // retornar JSON ao cliente
      $returnData = array("voteTypeId" => $voteTypeId, "numberOfVotes" => $numberOfVotes, "selectedVote" => isset($selectedVote["idTipoVoto"]), "activeVote" => $activeVote);
      echo json_encode($returnData);
   } else {
      die("Erro: Algo deu errado com a base de dados.");
   }
}


function removeUpvote($postId, $userId)
{
   global $connection;

   if ($query = $connection->prepare("DELETE FROM votos WHERE idPost = ? AND idUtilizador = ?")) {
      $query->bind_param("ii", $postId, $userId);
      $query->execute();
      $query->close();

      // atualizar número de votos
      if ($query = $connection->prepare("UPDATE posts SET quantidadeVotos = quantidadeVotos - 1 WHERE id = ?")) {
         $query->bind_param("i", $postId);
         $query->execute();
         $query->close();
      } else {
         die("Erro: Algo deu errado com a base de dados.");
      }
   } else {
      die("Erro: Algo deu errado com a base de dados.");
   }
}


function removeDownvote($postId, $userId)
{
   global $connection;

   if ($query = $connection->prepare("DELETE FROM votos WHERE idPost = ? AnD idUtilizador = ?")) {
      $query->bind_param("ii", $postId, $userId);
      $query->execute();
      $query->close();

      // atualizar número de votos
      if ($query = $connection->prepare("UPDATE posts SET quantidadeVotos = quantidadeVotos + 1 WHERE id = ?")) {
         $query->bind_param("i", $postId);
         $query->execute();
         $query->close();
      } else {
         die("Erro: Algo deu errado com a base de dados.");
      }
   } else {
      die("Erro: Algo deu errado com a base de dados.");
   }
}


function updateVote($postId, $userId, $voteTypeId)
{
   global $connection;

   if ($voteTypeId == 1) {  // 1 = upvote
      if ($query = $connection->prepare("UPDATE votos SET idTipoVoto = ? WHERE idPost = ? AND idUtilizador = ?")) {
         $query->bind_param("iii", $voteTypeId, $postId, $userId);
         $query->execute();
         $query->close();

         // atualizar número de votos
         if ($query = $connection->prepare("UPDATE posts SET quantidadeVotos = quantidadeVotos + 2 WHERE id = ?")) {
            $query->bind_param("i", $postId);
            $query->execute();
            $query->close();

            return true;
         } else {
            die("Erro: Algo deu errado com a base de dados.");
         }
      } else {
         die("Erro: Algo deu errado com a base de dados.");
      }
   }
   if ($voteTypeId == 2) {  // 2 = downvote
      if ($query = $connection->prepare("UPDATE votos SET idTipoVoto = ? WHERE idPost = ? AND idUtilizador = ?")) {
         $query->bind_param("iii", $voteTypeId, $postId, $userId);
         $query->execute();
         $query->close();

         // atualizar número de votos
         if ($query = $connection->prepare("UPDATE posts SET quantidadeVotos = quantidadeVotos - 2 WHERE id = ?")) {
            $query->bind_param("i", $postId);
            $query->execute();
            $query->close();

            return true;
         } else {
            die("Erro: Algo deu errado com a base de dados.");
         }
      } else {
         die("Erro: Algo deu errado com a base de dados.");
      }
   }
}


function insertVote($postId, $userId, $voteTypeId)
{
   global $connection;

   if ($voteTypeId == 1) {  // 1 = upvote
      if ($query = $connection->prepare("INSERT INTO votos (idPost, idUtilizador, idTipoVoto) VALUES (?, ?, ?)")) {
         $query->bind_param("iii", $postId, $userId, $voteTypeId);
         $query->execute();
         $query->close();

         // atualizar número de votos
         if ($query = $connection->prepare("UPDATE posts SET quantidadeVotos = quantidadeVotos + 1 WHERE id = ?")) {
            $query->bind_param("i", $postId);
            $query->execute();
            $query->close();

            return true;
         } else {
            die("Erro: Algo deu errado com a base de dados.");
         }
      } else {
         die("Erro: Algo deu errado com a base de dados.");
      }
   }
   if ($voteTypeId == 2) {  // 2 = downvote
      if ($query = $connection->prepare("INSERT INTO votos (idPost, idUtilizador, idTipoVoto) VALUES (?, ?, ?)")) {
         $query->bind_param("iii", $postId, $userId, $voteTypeId);
         $query->execute();
         $query->close();

         // atualizar número de votos
         if ($query = $connection->prepare("UPDATE posts SET quantidadeVotos = quantidadeVotos - 1 WHERE id = ?")) {
            $query->bind_param("i", $postId);
            $query->execute();
            $query->close();

            return true;
         } else {
            die("Erro: Algo deu errado com a base de dados.");
         }
      } else {
         die("Erro: Algo deu errado com a base de dados.");
      }
   }
}


function getUpdatedNumberOfVotes($query, $postId)
{
   $query->bind_param("i", $postId);
   $query->execute();

   // obter dados do voto selecionado
   $result = $query->get_result();
   $post = $result->fetch_assoc();

   return $post["quantidadeVotos"];
}
