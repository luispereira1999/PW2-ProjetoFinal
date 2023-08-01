<!-- DEFINIÇÃO: popup de criar um novo post -->

<div class="modal fade" id="newPost" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Post</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- mostrar erros -->
                <div class="errors errors--new-post alert alert-danger">
                    <ul class="errors__list"></ul>
                </div>

                <form class="modal__form" id="formNewPost" method="post" action="{{ route('posts.create') }}">
                    @csrf

                    <input class="modal__text" type="text" name="title" placeholder="Título">
                    <textarea class="modal__textarea" name="description" cols="40" rows="5" placeholder="Texto do Post ..."></textarea>
                </form>
            </div>

            <div class="modal-footer">
                <button class="button button-primary" type="submit" form="formNewPost">Criar</button>
                <button class="button button-cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>