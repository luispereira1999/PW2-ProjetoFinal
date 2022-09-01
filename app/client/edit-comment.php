<!-- DEFINAÇÃO: Modal de Adicionar um Novo Post -->

<div class="modal fade" id="editComment" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Editar Comentário</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="formEditComment" method="post" action="../server/comment-controller.php" class="modalForm">
               <input type="hidden" name="edit" value="comment">
               <input type="hidden" name="commentId" value="">
               <textarea name="description" cols="40" rows="5" placeholder="Texto da Publicação ..." require></textarea>
            </form>
         </div>
         <div class="modal-footer">
            <button type="submit" form="formEditComment" class="button buttonPrimary">Submeter</button>
         </div>
      </div>
   </div>
</div>