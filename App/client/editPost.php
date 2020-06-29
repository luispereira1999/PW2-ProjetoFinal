<!-- DEFINAÇÃO: Modal de Adicionar um Novo Post -->

<div class="modal fade" id="editPost" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditPost" method="post" action="../server/postController.php" class="modalForm">
                    <input type="text" name="title" value="" placeholder="Título" require>
                    <input type="hidden" name="edit" value="post">
                    <input type="hidden" name="postId" value="">
                    <textarea name="description" value="" cols="40" rows="5" id="commentTA" placeholder="Texto da Publicação ..." require></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="formEditPost" class="button buttonPrimary">Submeter</button>
            </div>
        </div>
    </div>
</div>