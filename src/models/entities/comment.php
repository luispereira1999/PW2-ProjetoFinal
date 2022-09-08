<?php
// DEFINIÇÃO: representação de um comentário
// (constituído por campos das tabelas "comments", "users" e "comments_votes" da base de dados)

class Comment
{
   public $comment_id;
   public $description;
   public $votes_amount;
   public $comment_user_id;
   public $comment_post_id;
   public $comment_user_name;
   public $vote_user_id;
   public $vote_type_id;
}
?>