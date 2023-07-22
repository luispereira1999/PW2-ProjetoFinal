<!-- DEFINIÇÃO: popup de eliminar um post -->

<div class="modal fade" id="deletePost<?= $post->post_id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
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
            <form id="formDeletePost<?= $post->post_id; ?>" method="post" action="/posts/delete/<?= $post->post_id; ?>">
               <button class="button button-primary" type="submit" form="formDeletePost<?= $post->post_id; ?>" name="isDelete">Eliminar</button>
               <button class="button button-cancel" type="button">Não</button>
            </form>
         </div>
      </div>
   </div>
</div>
