 <!-- DEFINAÇÃO: Modal de Adicionar um Novo Comentário -->

 <div class="modal fade" id="newComment" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">Adicionar Novo Comentário</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form id="formNewComment" method="post" action="../server/commentController.php" class="modalForm">
                     <input type="hidden" name="new" value="comment">
                     <textarea name="description" cols="40" rows="5" id="commentTA" placeholder="Adicionar Comentário ..." require></textarea>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="submit" form="formNewComment" class="button buttonPrimary">Submeter</button>
             </div>
         </div>
     </div>
 </div>