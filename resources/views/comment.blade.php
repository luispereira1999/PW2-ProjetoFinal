<!-- DEFINIÇÃO: template dos comentários do post que aparecem na página do post -->

<li data-comment="{{ $comment->comment_id }}" class="comment">
    <div class="width-100">
        <!-- votos do comentário -->
        <div class="comment__votes">
            <span class="comment__vote" data-vote="upvote">
                <i data-markedvote="{{ ($comment->vote_user_id == $userLoggedId && $comment->vote_type_id == 1) ? 'marked' : 'none' }}" data-toggle="tooltip" data-placement="bottom" title="Up Vote" class="fas fa-heart comment__vote__icon"></i>
            </span>
            <label class="comment__votes-amount" data-toggle="tooltip" data-placement="bottom" title="Número de Comentários">{{ $comment->votes_amount }}</label>
            <span class="comment__vote" data-vote="downvote">
                <i data-markedvote="{{ ($comment->vote_user_id == $userLoggedId && $comment->vote_type_id == 2) ? 'marked' : 'none' }}" data-toggle="tooltip" data-placement="bottom" title="Down Vote" class="fas fa-heart comment__vote__icon"></i>
            </span>
        </div>

        <div class="{{ ($comment->comment_user_id == $userLoggedId) ? 'width-70' : 'width-80' }}">
            <div class="comment__name-wrapper">
                <h2 class="comment__name">{{ $comment->comment_user_name }}</h2>
            </div>
            <p class="comment__description">{{ $comment->description }}</p>

            @if ($comment->comment_user_id == $userLoggedId)
            <div class="comment__actions">
                <span class="comment__action" data-toggle="tooltip" data-placement="bottom" title="Editar Comentário">
                    <a class="comment__link" data-toggle="modal" data-target="#editComment{{ $comment->comment_id }}"><i class="fas fa-edit col-0 comment__action__icon"></i></a>
                </span>
                <span class="comment__action" data-toggle="tooltip" data-placement="bottom" title="Eliminar Comentário">
                    <a class="comment__link" data-toggle="modal" data-target="#deleteComment{{ $comment->comment_id }}"><i class="fas fa-trash-alt col-0 comment__action__icon"></i></a>
                </span>

                @include("edit-comment")
                @include("delete-comment")
            </div>
            @endif
        </div>
    </div>
</li>