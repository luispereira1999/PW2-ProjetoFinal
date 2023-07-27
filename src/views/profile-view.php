
   <!-- PRINCIPAL: utilizador, posts (informações, ações) -->
   <main>
      <!-- posts -->
      <section class="posts">
         <?php
         // contador auxiliar para saber quando já foram criados 3 posts
         $counter = 0;

         // mostrar posts (3 em 3 por padrão)
         for ($current = 0; $current < count($posts); $current++) : ?>

            <?php
            $counter++;

            if ($current % 3 == 0) : ?>
               <section class="brief-posts__post">
               <?php endif; ?>

               <!-- POST -->
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

      </section>
   </main>
