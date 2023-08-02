<!-- DEFINIÇÃO: modal de remover um comentário -->

<div class="modal fade" id="deleteComment{{ $comment->comment_id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remover Comentário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="modal__paragraph">Deseja mesmo remover este post?</p>

                <form id="formDeleteComment{{ $comment->comment_id }}" method="post" action="{{ route('comments.delete', ['commentId' => $comment->comment_id]) }}">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="postId" value="{{ $post->id }}">
                </form>
            </div>

            <div class="modal-footer">
                <button class="button button-primary" type="submit" form="formDeleteComment{{ $comment->comment_id }}">Remover</button>
                <button class="button button-cancel" type="button" data-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>