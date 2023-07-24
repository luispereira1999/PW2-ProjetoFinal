<body>
   <!-- CABEÇALHO: menu de navegação (logótipo, links) -->
   <header>
      <?php require_once("components/nav-component.php"); ?>
      <?php require_once("components/new-post-component.php"); ?>
      <?php require_once("components/about-component.php"); ?>
   </header>

   <!-- PRINCIPAL: post em destaque, pesquisa, posts (informações, ações) -->
   <main class="brief-posts">
      <!-- post em destaque -->
      <section data-post="<?= $featuredPost->post_id; ?>" class="featured">
         <div class="featured-wrapper">
            <div class="featured__title-wrapper">
               <h3 class="featured__title"><a class="featured__link__title" href="/posts/<?= $featuredPost->post_id; ?>"><?= $featuredPost->title; ?></a></h3>
            </div>

            <div>
               <h5 class="featured__name"><a class="featured__link__name" href="/profile/<?= $featuredPost->post_user_id; ?>"><?= $featuredPost->post_user_name; ?></a></h5>
               <h5 class="featured__date"><?= $featuredPost->date; ?></h5>
            </div>

            <p class="featured__description"><?= $featuredPost->description; ?></p>

            <div class="featured__interactions">
               <!-- votos do post -->
               <div class="featured__votes">
                  <span class="featured__vote" data-vote="upvote">
                     <i data-markedvote="<?php if ($featuredPost->vote_user_id == $userLoggedId && $featuredPost->vote_type_id == 1) : echo "marked";
                                          else : echo "none";
                                          endif; ?>" data-toggle="tooltip" data-placement="bottom" title="Up Vote" class="featured__interactions__icon fas fa-heart"></i>
                  </span>
                  <label class="featured__votes-amount"><?= $featuredPost->votes_amount; ?></label>
                  <span class="featured__vote" data-vote="downvote">
                     <i data-markedvote="<?php if ($featuredPost->vote_user_id == $userLoggedId && $featuredPost->vote_type_id == 2) : echo "marked";
                                          else : echo "none";
                                          endif; ?>" data-toggle="tooltip" data-placement="bottom" title="Down Vote" class="featured__interactions__icon fas fa-heart-broken"></i>
                  </span>
               </div>

               <span data-toggle="tooltip" data-placement="bottom" title="Comentários"><i class="fas fa-comment featured__interactions__icon"></i></span>
               <label class="featured__comments-amount"><?= $featuredPost->comments_amount ?></label>
            </div>

            <div class="featured__buttons">
               <button class="button button-primary">Ver Mais ...</button>

               <div class="featured__options">
                  <?php if ($featuredPost->post_user_id == $userLoggedId) : ?>
                     <span data-toggle="tooltip" data-placement="bottom" title="Editar Post">
                        <a class="featured__link__edit" data-toggle="modal" data-target="#editPost<?= $featuredPost->post_id; ?>"><i class="featured__icon fas fa-edit col-0"></i></a>
                     </span>

                     <span data-toggle="tooltip" data-placement="bottom" title="Eliminar Post">
                        <a class="featured__link__delete" data-toggle="modal" data-target="#deletePost<?= $featuredPost->post_id; ?>"><i class="featured__icon fas fa-trash-alt col-0"></i></a>
                     </span>

                     <?php
                     $post = $featuredPost;
                     require_once("components/edit-post-component.php");
                     require_once("components/delete-post-component.php");
                     ?>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </section>

      <!-- pesquisa -->
      <section class="search">
         <input id="inputSearchText" class="search__text" type="text" name="title" placeholder="Título a Pesquisar ..." require>

         <a id="linkSearchPosts" href=""><i class="search__icon fas fa-search" data-toggle="tooltip" data-placement="bottom" title="Pesquisar"></i></a>
         <a href="/"><i class="search__icon fas fa-backspace" data-toggle="tooltip" data-placement="bottom" title="Limpar Pesquisa"></i></a>
      </section>

      <?php
      // contador auxiliar para saber quando já foram criados 3 posts
      $counter = 0;

      $postsWithoutFeatured = [];

      // remover o post em destaque do array de posts, caso exista, para não mostra-lo novamente
      foreach ($posts as $key => $value) {
         if ($value->post_id != $featuredPost->post_id) {
            $postsWithoutFeatured[] = $value;
            unset($posts[$key]);
         }
      }

      // obter os posts sem o post em destaque
      $posts = $postsWithoutFeatured;

      // mostrar posts (3 em 3 por padrão)
      for ($current = 0; $current < count($posts); $current++) : ?>

         <?php
         $counter++;

         if ($current % 3 == 0) : ?>
            <section class="brief-posts__post">
            <?php endif; ?>

            <!-- post -->
            <?php
            $post = $posts[$current];
            require("components/brief-post-component.php");
            ?>

            <?php if ($counter == 3) :  ?>
            </section>
         <?php
               $counter = 0;
            endif; ?>

      <?php endfor; ?>
   </main>
</body>
