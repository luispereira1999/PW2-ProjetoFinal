// executar este código quando a página é carregada
$(document).ready(function () {
   // mostrar formulário de signup
   $("[data-auth='login']").click(function () {
      $("#content__signup").hide(500);
      $("#content__login").show(500);
   });

   // mostrar formulário de login
   $("[data-auth='signup']").click(function () {
      $("#content__login").hide(500);
      $("#content__signup").show(500);
   });

   // votar num post - upvote
   $("[data-vote='upvote']").click(function () {
      let currentElement = $(this);
      let voteTypeId = 1;
      let postId = $(this).parent().parent().parent().attr("data-post");

      votePost(currentElement, voteTypeId, postId);
   });

   // pesquisar posts
   $("#linkSearchPosts").click(function () {
      // obter URL
      let textSearched = $("#inputSearchText").val();
      let uri = "/search/" + textSearched;

      // atualizar hyperlink
      $(this).attr("href", uri);
   });
});