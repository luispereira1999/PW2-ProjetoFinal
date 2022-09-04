<!-- DEFINIÇÃO: popup de editar um post -->

<div class="modal fade" id="editPost<?= $posts[$current]->post_id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Editar Post</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>

         <div class="modal-body">
            <form class="popup__form" id="formEditPost<?= $posts[$current]->post_id; ?>" method="post" action="/post/edit/<?= $posts[$current]->post_id; ?>">
               <input class="popup__text" type="text" name="title" value="<?= $posts[$current]->title; ?>" placeholder="Título" require>
               <textarea class="popup__textarea" name="description" cols="40" rows="5" placeholder="Texto da Publicação ..." require><?= $posts[$current]->description; ?></textarea>
            </form>
         </div>

         <div class="modal-footer">
            <button class="button button-primary" type="submit" form="formEditPost<?= $posts[$current]->post_id; ?>" name="isEdit">Editar</button>
            <button class="button button-cancel" type="button">Cancelar</button>
         </div>
      </div>
   </div>
</div>