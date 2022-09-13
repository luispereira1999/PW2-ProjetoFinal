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

   // votar num post - up vote
   $(".brief-posts [data-vote='upvote']").click(function () {
      let currentElement = $(this);
      let downElement = $(this).parent().children("[data-vote='downvote']");
      let voteTypeId = 1;
      let postId = $(this).parent().parent().parent().attr("data-post");

      votePost(currentElement, downElement, voteTypeId, postId);
   });

   // votar num post - down vote
   $(".brief-posts [data-vote='downvote']").click(function () {
      let currentElement = $(this);
      let upElement = $(this).parent().children("[data-vote='upvote']");
      let voteTypeId = 2;
      let postId = $(this).parent().parent().parent().attr("data-post");

      votePost(currentElement, upElement, voteTypeId, postId);
   });

   // votar num comentário - up vote
   $(".comments [data-vote='upvote']").click(function () {
      let currentElement = $(this);
      let downElement = $(this).parent().children("[data-vote='downvote']");
      let voteTypeId = 1;
      let commentId = $(this).parent().parent().parent().attr("data-comment");

      voteComment(currentElement, downElement, voteTypeId, commentId);
   });

   // votar num comentário - down vote
   $(".comments [data-vote='downvote']").click(function () {
      let currentElement = $(this);
      let upElement = $(this).parent().children("[data-vote='upvote']");
      let voteTypeId = 2;
      let commentId = $(this).parent().parent().parent().attr("data-comment");

      voteComment(currentElement, upElement, voteTypeId, commentId);
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