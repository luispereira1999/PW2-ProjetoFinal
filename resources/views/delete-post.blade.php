<!-- DEFINIÇÃO: popup de eliminar um post -->

<div class="modal fade" id="deletePost{{ $post->post_id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="modal__paragraph">Deseja mesmo eliminar este post?</p>

                <form id="formDeletePost{{ $post->post_id }}" method="post" action="{{ route('posts.delete', ['postId' => $post->post_id]) }}">
                    @csrf
                    @method('DELETE')
                </form>
            </div>

            <div class="modal-footer">
                <button class="button button-primary" type="submit" form="formDeletePost{{ $post->post_id }}">Eliminar</button>
                <button class="button button-cancel" type="button" data-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>