<!-- DEFINIÇÃO: template dos posts que aparecem na página principal e na página do perfil -->

<div data-post="<?= $posts[$current]->post_id; ?>" class="width-3">
   <div class="brief-posts__options">
      <?php if ($posts[$current]->post_user_id == $userLoggedId) : ?>
         <span data-toggle="tooltip" data-placement="bottom" title="Editar Post">
            <a class="brief-posts__link__edit" data-toggle="modal" data-target="#editPost<?= $posts[$current]->post_id; ?>"><i class="brief-posts__icon fas fa-edit col-0"></i></a>
         </span>

         <span data-toggle="tooltip" data-placement="bottom" title="Eliminar Post">
            <a class="brief-posts__link__delete" data-toggle="modal" data-target="#deletePost<?= $posts[$current]->post_id; ?>"><i class="brief-posts__icon fas fa-trash-alt col-0"></i></a>
         </span>

         <?php require("edit-post-component.php"); ?>
         <?php require("delete-post-component.php"); ?>
      <?php endif; ?>
   </div>

   <div class="brief-posts__title-wrapper">
      <h3 class="brief-posts__title"><a class="brief-posts__link" href="/post/<?= $posts[$current]->post_id; ?>"><?= $posts[$current]->title; ?></a></h3>
   </div>

   <div>
      <h5 class="brief-posts__name"><a class="brief-posts__link" href="/profile/<?= $posts[$current]->post_user_id; ?>"><?= $posts[$current]->post_user_name; ?></a></h5>
      <h5 class="brief-posts__date"><?= $posts[$current]->date; ?></h5>
   </div>

   <p class="brief-posts__description"><?= $posts[$current]->description; ?></p>

   <div class="brief-posts__interactions">
      <!-- votos do post -->
      <div class="brief-posts__votes">
         <span class="brief-posts__vote" data-vote="upvote">
            <i data-markedvote="<?php if ($posts[$current]->vote_user_id == $userLoggedId && $posts[$current]->vote_type_id == 1) : echo "marked";
                                 else : echo "none";
                                 endif; ?>" data-toggle=" tooltip" data-placement="bottom" title="Up Vote" class="brief-posts__interactions__icon fas fa-heart"></i>
         </span>
         <label class="brief-posts__votes-amount"><?= $posts[$current]->votes_amount; ?></label>
         <span class="brief-posts__vote" data-vote="downvote">
            <i data-markedvote="<?php if ($posts[$current]->vote_user_id == $userLoggedId && $posts[$current]->vote_type_id == 2) : echo "marked";
                                 else : echo "none";
                                 endif; ?>" data-toggle="tooltip" data-placement="bottom" title="Down Vote" class="brief-posts__interactions__icon fas fa-heart-broken"></i>
         </span>
      </div>

      <span class="brief-posts__votes-amount__tooltip" data-toggle="tooltip" data-placement="bottom" title="Comentários"><i class="fas fa-comment brief-posts__interactions__icon"></i></span>
      <label class="brief-posts__votes-amount"><?= $posts[$current]->comments_amount ?></label>
   </div>
</div>