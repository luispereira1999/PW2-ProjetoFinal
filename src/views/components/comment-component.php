<!-- DEFINIÇÃO: template dos comentários do post que aparecem na página do post -->

<li data-comment="<?= $comments[$current]->comment_id; ?>" class="comment">
   <div class="row">
      <!-- votos do comentário -->
      <div>
         <div class="comment__votes">
            <span class="comment__vote" data-vote="upvote"><i class="fas fa-heart interactionsBarIcons comment__icon" <?php if ($comments[$current]->vote_user_id == $userLoggedId && $comments[$current]->vote_type_id == 1) : ?> data-markedvote="marked" <?php endif; ?> data-toggle="tooltip" data-placement="bottom" title="Up Vote"></i></span>
            <label class="comment__votes-amount" data-toggle="tooltip" data-placement="bottom" title="Número de Comentários"><?= $comments[$current]->votes_amount ?></label>
            <span class="comment__vote" data-vote="downvote"><i class="fas fa-heart interactionsBarIcons comment__icon" <?php if ($comments[$current]->vote_user_id == $userLoggedId && $comments[$current]->vote_type_id == 2) : ?> data-markedvote="marked" <?php endif; ?> data-toggle="tooltip" data-placement="bottom" title="Down Vote"></i></span>
         </div>
      </div>

      <div class="col-1">
         <h2 class="comment__name"><?= $comments[$current]->comment_user_name ?></h2>
      </div>

      <div class="<?php if ($comments[$current]->comment_user_id == $userLoggedId) : echo "col-9";
                  else : echo "col-10";
                  endif; ?>">
         <p class="comment__description"><?= $comments[$current]->description ?></p>
      </div>

      <?php if ($comments[$current]->comment_user_id == $userLoggedId) : ?>
         <div class="col-1">
            <span class="comment__edit" data-toggle="tooltip" data-placement="bottom" title="Editar Comentário">
               <a class="comment__link" data-toggle="modal" data-target="#editComment<?= $comments[$current]->comment_id; ?>"><i class="fas fa-edit col-0 comment__icon"></i></a>
            </span>
            <span class="comment__delete" data-toggle="tooltip" data-placement="bottom" title="Eliminar Comentário">
               <a class="comment__link" data-toggle="modal" data-target="#deleteComment<?= $comments[$current]->comment_id; ?>"><i class="fas fa-trash-alt col-0 comment__icon"></i></a>
            </span>

            <?php require("edit-comment-component.php"); ?>
            <?php require("delete-comment-component.php"); ?>
         </div>
      <?php endif; ?>
   </div>
</li>