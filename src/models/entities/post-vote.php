<?php
// DEFINIÇÃO: representação de um voto de um post
// (constituído por campos da tabelas "posts_votes" da base de dados)

class PostVote
{
   public $post_id;
   public $user_id;
   public $vote_type_id;
}
?>