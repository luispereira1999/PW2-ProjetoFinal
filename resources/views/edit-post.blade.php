<!-- DEFINIÇÃO: modal de editar um post -->

<div class="modal fade" id="editPost{{ $post->post_id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Post</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- mostrar erros -->
                <div class="errors errors--edit-post-{{ $post->post_id }} alert alert-danger">
                    <ul class="errors__list"></ul>
                </div>

                <form class="modal__form" id="formEditPost{{ $post->post_id }}" method="post" action="{{ route('posts.update', ['postId' => $post->post_id]) }}">
                    @csrf
                    @method('PATCH')

                    <input class="modal__text" type="text" name="title" value="{{ $post->title }}" placeholder="Título">
                    <textarea class="modal__textarea" name="description" cols="40" rows="5" placeholder="Texto da Publicação ...">{{ $post->description }}</textarea>
                </form>
            </div>

            <div class="modal-footer">
                <button class="button button-primary" type="submit" form="formEditPost{{ $post->post_id }}">Editar</button>
                <button class="button button-cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>