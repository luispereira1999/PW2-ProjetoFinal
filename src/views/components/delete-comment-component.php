<!-- DEFINIÇÃO: popup de eliminar um comentário -->

<div class="modal fade" id="deleteComment<?= $comment->comment_id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Eliminar Comentário</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>

         <div class="modal-body">
            <p class="popup__paragraph">Deseja mesmo eliminar este post?</p>
         </div>

         <div class="modal-footer">
            <form id="formDeleteComment<?= $comment->comment_id; ?>" method="post" action="/comment/delete/<?= $comment->comment_id; ?>">
               <input type="hidden" name="postId" value="<?= $post->post_id ?>">
               <button class="button button-primary" type="submit" form="formDeleteComment<?= $comment->comment_id; ?>" name="isDelete">Eliminar</button>
               <button class="button button-cancel" type="button">Não</button>
            </form>
         </div>
      </div>
   </div>
</div>