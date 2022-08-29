<!-- DEFINIÇÃO: representação de um post
(constituído por campos das tabelas "posts", "users" e "posts_votes" da base de dados) -->

<?php
class Post
{
   public $post_id;
   public $title;
   public $description;
   public $date;
   public $votes_amount;
   public $comments_amount;
   public $post_user_id;
   public $post_user_name;
   public $vote_user_id;
   public $vote_type_id;
}
?>