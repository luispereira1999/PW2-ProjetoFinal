<!-- DEFINIÇÃO: popup de criar um novo comentário -->

<div class="modal fade" id="newComment" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Novo Comentário</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>

         <div class="modal-body">
            <form class="popup__form" id="formNewComment" method="post" action="/comment/create">
               <textarea class="popup__textarea" name="description" cols="40" rows="5" placeholder="Texto do Comentário ..." require></textarea>
            </form>
         </div>

         <div class="modal-footer">
            <button class="button button-primary" type="submit" form="formNewComment" name="isCreate">Criar</button>
         </div>
      </div>
   </div>
</div>