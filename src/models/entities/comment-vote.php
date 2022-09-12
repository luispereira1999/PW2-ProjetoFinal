<?php
// DEFINIÇÃO: representação de um voto de um comentário
// (constituído por campos da tabelas "comments_votes" da base de dados)

class CommentVote
{
   public $comment_id;
   public $user_id;
   public $vote_type_id;
}
?>