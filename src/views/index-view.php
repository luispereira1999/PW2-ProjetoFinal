<!-- DEFINIÇÃO: página principal do site -->

<!DOCTYPE html>
<html lang="pt-PT">

<head>
   <!-- TÍTULO DA PÁGINA -->
   <title>KLL</title>

   <!-- METADADOS -->
   <meta charset="utf-8">
   <meta name="description" content="Uma rede social nova e alternativa!">
   <meta name="keywords" content="IPCA, Programação Web 2, Projeto Final, Rede Social">
   <meta name="author" content="Lara Ribeiro, Luís Pereira, Maria Costa">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!-- FAVICON -->
   <link rel="shortcut icon" type="image/x-icon" href="../public/assets/images/favicon.ico">

   <!-- CSS -->
   <link rel="stylesheet" href="../public/css/global.css">
   <link rel="stylesheet" href="../public/css/index.css">
   <link rel="stylesheet" href="../public/css/nav.css">
   <link rel="stylesheet" href="../public/css/footer.css">

   <!-- JQUERY -->
   <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

   <!-- JS -->
   <script type="text/javascript" src="../public/js/main.js"></script>
   <script type="text/javascript" src="../public/js/function.js"></script>

   <!-- BOOTSTRAP -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

   <!-- FONT AWESOME -->
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
   <script src="https://kit.fontawesome.com/ed5c768cb2.js" crossorigin="anonymous"></script>

   <!-- FONT FAMILY -->
   <link href="https://fonts.googleapis.com/css2?family=Nova+Round&family=Nunito:wght@300;400&display=swap" rel="stylesheet">
</head>

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
               <h3 class="featured__title"><a class="featured__link__title" href="/post/<?= $featuredPost->post_id; ?>"><?= $featuredPost->title; ?></a></h3>
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

   <!-- RODAPÉ:  -->
   <footer class="footer">
      <?php require_once("components/footer-component.php"); ?>
   </footer>
</body>

</html>