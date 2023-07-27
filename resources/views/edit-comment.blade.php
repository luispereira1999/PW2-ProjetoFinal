<!-- DEFINIÇÃO: popup de editar um comentário -->

<div class="modal fade" id="editComment{{ $comment->comment_id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Comentário</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="popup__form" id="formEditComment{{ $comment->comment_id }}" method="post" action="{{ route('comments.update', ['commentId' => $comment->comment_id]) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="postId" value="{{ $post->id }}">
                    <textarea class="popup__textarea" name="description" cols="40" rows="5" placeholder="Texto do Comentário ..." required>{{ $comment->description }}</textarea>
                </form>
            </div>

            <div class="modal-footer">
                <button class="button button-primary" type="submit" form="formEditComment{{ $comment->comment_id }}" name="isEdit">Editar</button>
            </div>
        </div>
    </div>
</div>