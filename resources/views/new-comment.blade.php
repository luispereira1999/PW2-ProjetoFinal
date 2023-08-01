<!-- DEFINIÇÃO: popup de criar um novo comentário -->

<div class="modal fade" id="newComment" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Comentário</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- mostrar erros -->
                <div class="errors errors--new-comment alert alert-danger">
                    <ul class="errors__list"></ul>
                </div>

                <form class="modal__form" id="formNewComment" method="post" action="{{ route('comments.create') }}">
                    @csrf

                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <textarea class="modal__textarea" name="description" cols="40" rows="5" placeholder="Texto do Comentário ..." required></textarea>
                </form>
            </div>

            <div class="modal-footer">
                <button class="button button-primary" type="submit" form="formNewComment">Criar</button>
                <button class="button button-cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>