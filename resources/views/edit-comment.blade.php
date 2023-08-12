<!-- DEFINIÇÃO: modal de editar um comentário -->

<div class="modal fade" id="editComment{{ $comment->id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Comentário</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- mostrar erros -->
                <div class="errors errors--edit-comment-{{ $comment->id }} alert alert-danger">
                    <ul class="errors__list"></ul>
                </div>

                <form class="modal__form" id="formEditComment{{ $comment->id }}" method="post" action="{{ route('comments.edit', ['commentId' => $comment->id]) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="postId" value="{{ $post->id }}">
                    <textarea class="modal__textarea" name="description" cols="40" rows="5" placeholder="Texto do Comentário ...">{{ $comment->description }}</textarea>
                </form>
            </div>

            <div class="modal-footer">
                <button class="button button-primary" type="submit" form="formEditComment{{ $comment->id }}">Editar</button>
                <button class="button button-cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
