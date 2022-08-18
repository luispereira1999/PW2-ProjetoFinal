<?php
// if (!isset($_SESSION["error"])) {
// índices do ciclo for e do clico while
$i = 0;
$j = 0;

// índice do post atual
$current = 1;

for ($i; $i < count($briefPosts); $i++) : ?>
   <section class="brief-posts__post">

      <!-- ciclo para mostrar 3 em 3 posts -->
      <?php while ($j < 3) :

         // se já não existem posts para mostrar sai desta função "showPosts"
         if ($current == count($briefPosts)) :
            return;
         endif; ?>

         <!-- mostrar post -->
         <div data-post="<?= $briefPosts[$current]->post_id; ?>" class="width-3">
            <div class="brief-posts__options">
               <?php if ($briefPosts[$current]->post_user_id == $userLoggedId) : ?>
                  <form class="editDeletePost" method="post" action="../server/post-controller.php">
                     <a class="brief-posts__link" data-action="edit"><span data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="brief-posts__options__icon fas fa-edit col-0"></i></span></a>
                     <input type="hidden" name="action" value="edit">
                     <input type="hidden" name="postId" value="<?= $briefPosts[$current]->post_id; ?>">
                     <a class="brief-posts__link" data-action="delete" data-toggle="tooltip" data-placement="bottom" title="Eliminar"><i class="brief-posts__options__icon fas fa-trash-alt col-0"></i></a>
                  </form>
               <?php endif; ?>
            </div>

            <div class="brief-posts__title-wrapper">
               <h3><a href="post.php?postId=<?= $briefPosts[$current]->post_id; ?>"><?= $briefPosts[$current]->title; ?></a></h3>
            </div>

            <div>
               <h5><a href="user.php?userId=<?= $briefPosts[$current]->post_user_id; ?>"><?= $briefPosts[$current]->name; ?></a></h5>
               <h5><?= $briefPosts[$current]->date; ?></h5>
            </div>

            <p><?= $briefPosts[$current]->description; ?></p>

            <div class="brief-posts__interactions">
               <!-- mostrar votos do post -->
               <div class="brief-posts__votes">
                  <span data-vote="upvote" class="brief-posts__vote"><i <?php if ($briefPosts[$current]->user_logged_id == $userLoggedId && $briefPosts[$current]->vote_type_id == 1) : ?> data-markedvote="marked" <?php endif; ?> data-toggle="tooltip" data-placement="bottom" title="Up Vote" class="brief-posts__interactions__icon fas fa-heart"></i></span>
                  <label class="brief-posts__amount"><?= $briefPosts[$current]->votes_amount; ?></label>
                  <span data-vote="downvote" class="brief-posts__vote"><i <?php if ($briefPosts[$current]->user_logged_id == $userLoggedId && $briefPosts[$current]->vote_type_id == 2) : ?> data-markedvote="marked" <?php endif; ?> data-toggle="tooltip" data-placement="bottom" title="Down Vote" class="brief-posts__interactions__icon fas fa-heart-broken"></i></span>
               </div>

               <span data-toggle="tooltip" data-placement="bottom" title="Comentários"><i class="fas fa-comment brief-posts__interactions__icon"></i></span>
               <label><?= $briefPosts[$current]->comments_amount ?></label>
            </div>
         </div>

      <?php $j++ . $current++;
      endwhile;
      $j = 0; ?>

   </section>
<?php endfor;
// } else {
//    unset($_SESSION["error"]);
// }
?>