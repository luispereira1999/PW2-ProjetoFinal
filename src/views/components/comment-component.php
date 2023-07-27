<!-- DEFINIÇÃO: template dos comentários do post que aparecem na página do post -->

<li data-comment="<?= $comment->comment_id; ?>" class="comment">
    <div class="width-100">
        <!-- votos do comentário -->
        <div class="comment__votes">
            <span class="comment__vote" data-vote="upvote">
                <i data-markedvote="<?php if ($comment->vote_user_id == $loggedUserId && $comment->vote_type_id == 1) : echo "marked";
                                    else : echo "none";
                                    endif; ?>" data-toggle="tooltip" data-placement="bottom" title="Up Vote" class="fas fa-heart comment__vote__icon"></i>
            </span>
            <label class="comment__votes-amount" data-toggle="tooltip" data-placement="bottom" title="Número de Comentários"><?= $comment->votes_amount ?></label>
            <span class="comment__vote" data-vote="downvote">
                <i data-markedvote="<?php if ($comment->vote_user_id == $loggedUserId && $comment->vote_type_id == 2) : echo "marked";
                                    else : echo "none";
                                    endif; ?>" data-toggle="tooltip" data-placement="bottom" title="Down Vote" class="fas fa-heart comment__vote__icon"></i>
            </span>
        </div>

        <div class="comment__name-wrapper">
            <h2 class="comment__name"><?= $comment->comment_user_name ?></h2>
        </div>

        <div class="<?php if ($comment->comment_user_id == $loggedUserId) : echo "width-70";
                    else : echo "width-80";
                    endif; ?>">
            <p class="comment__description"><?= $comment->description ?></p>
        </div>

        <?php if ($comment->comment_user_id == $loggedUserId) : ?>
            <div class="comment__actions">
                <span class="comment__action" data-toggle="tooltip" data-placement="bottom" title="Editar Comentário">
                    <a class="comment__link" data-toggle="modal" data-target="#editComment<?= $comment->comment_id; ?>"><i class="fas fa-edit col-0 comment__action__icon"></i></a>
                </span>
                <span class="comment__action" data-toggle="tooltip" data-placement="bottom" title="Eliminar Comentário">
                    <a class="comment__link" data-toggle="modal" data-target="#deleteComment<?= $comment->comment_id; ?>"><i class="fas fa-trash-alt col-0 comment__action__icon"></i></a>
                </span>

                <?php require("edit-comment-component.php"); ?>
                <?php require("delete-comment-component.php"); ?>
            </div>
        <?php endif; ?>
    </div>
</li>