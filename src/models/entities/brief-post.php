<!-- DEFINIÇÃO: representação de um post resumido
(constituído por campos das tabelas "posts", "users" e "posts_votes" da base de dados) -->

<?php
class BriefPost
{
   public $post_id;
   public $title;
   public $description;
   public $date;
   public $votes_amount;
   public $comments_amount;
   public $post_user_id;
   public $post_user_name;
   public $user_logged_id;
   public $vote_type_id;
}
?>