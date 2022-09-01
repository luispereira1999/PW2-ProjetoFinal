<!-- DEFINIÇÃO: popup de eliminar um post -->

<div class="modal fade" id="deletePost" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Eliminar Post</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>

         <div class="modal-body">
            <p class="popup__paragraph">Deseja mesmo eliminar este post?</p>
         </div>

         <div class="modal-footer">
            <form id="formDeletePost" method="post" action="/post/delete">
               <button type="submit" form="formDeletePost" name="id" value="<?php $posts[$current]->post_id; ?>" class="button button-primary">Sim</button>
               <button type="button" form="formDeletePost" value="cancel" onClick="" name="" class="button button-cancel">Não</button>
            </form>
         </div>
      </div>
   </div>
</div>