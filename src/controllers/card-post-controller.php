<!-- DEFINIÇÃO: controlador dos cards dos posts na página principal -->

<?php
// obter posts ao carregar a página principal
function getCardPosts($userLoggedId, $numberOfPosts)
{
   global $connection;
   $posts = array();

   // selecionar posts
   if ($query = $connection->prepare("SELECT p.id AS post_id, p.title AS title, p.description AS description, p.date AS date, p.votes_amout AS votes_amout, p.comments_amout AS comments_amout, p.user_id AS post_user_id, u.name AS name, v.user_id AS user_logged_id, v.vote_type_id AS vote_type_id FROM posts p INNER JOIN users u ON p.user_id = u.id LEFT JOIN posts_votes v ON p.id = v.post_id AND ? = v.user_id ORDER BY votes_amout DESC LIMIT ?")) {
      // executar query | "i" significa que é um número inteiro
      $query->bind_param("ii", $userLoggedId, $numberOfPosts);
      $query->execute();

      // resultado da query
      $result = $query->get_result();

      // verificar se existem posts
      if ($result->num_rows > 0) {
         // obter dados dos posts
         while ($row = $result->fetch_assoc()) {
            $cardPost = new CardPost();
            $cardPost->post_id = $row["post_id"];
            $cardPost->title = $row["title"];
            $cardPost->description = $row["description"];
            $cardPost->date = $row["date"];
            $cardPost->votes_amout = $row["votes_amout"];
            $cardPost->comments_amout = $row["comments_amout"];
            $cardPost->post_user_id = $row["post_user_id"];
            $cardPost->name = $row["name"];
            $cardPost->user_logged_id = $row["user_logged_id"];
            $cardPost->vote_type_id = $row["vote_type_id"];

            array_push($cardPosts, $cardPost);
         }

         $connection->close();

         return $posts;
      } else {
         $_SESSION["messageError"] = "Sem resultados.";
         $_SESSION["error"] = true;
      }
   } else {
      $_SESSION["messageError"] = "Sem resultados.";
      $_SESSION["error"] = true;
   }

   return $posts;
}
?>