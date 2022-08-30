<!-- DEFINIÇÃO: template dos comentários do post que aparecem na página do post -->

<li data-comment="<?= $comments[$current]->comment_id; ?>" class="comment">
   <div class="row">
      <!-- VOTOS DO COMENTÁRIO -->
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
            <form class="editDeleteComment" method="post" action="../server/comment-controller.php">
               <span class="comment__edit" data-action="edit" data-toggle="tooltip" data-placement="bottom" title="Editar"><a class="comment__link" data-toggle="modal" data-target="#editComment"><i class="fas fa-edit col-0 comment__icon"></i></a></span>
               <input type="hidden" name="action" value="edit">
               <input type="hidden" name="commentId" value="<?= $comments[$current]->comment_id; ?>">
               <a class="comment__link" data-action="delete" data-toggle="tooltip" data-placement="bottom" title="Eliminar"><i class="fas fa-trash-alt col-0"></i></a>
            </form>
         </div>
      <?php endif; ?>
   </div>
</li>