<!-- DEFINIÇÃO: popup de criar um novo post -->

<div class="modal fade" id="newPost" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Novo Post</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>

         <div class="modal-body">
            <form class="popup__form" id="formNewPost" method="post" action="/posts/create">
               <input class="popup__text" type="text" name="title" placeholder="Título" require>
               <textarea class="popup__textarea" name="description" cols="40" rows="5" placeholder="Texto do Post ..." require></textarea>
            </form>
         </div>

         <div class="modal-footer">
            <button class="button button-primary" type="submit" form="formNewPost" name="isCreate">Criar</button>
         </div>
      </div>
   </div>
</div>
