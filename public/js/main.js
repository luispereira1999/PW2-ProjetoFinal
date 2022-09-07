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

   // submeter formulário de pesquisa de posts
   $("#submitForm").click(function () {
      // obter URL amigável
      let textSearched = $("#inputSearchText").val();
      let uri = "/search/" + textSearched;
      let link = location.protocol + '//' + location.host + uri;

      // submeter formulário
      let form = $("#formSearchPosts");
      form.prop("action", uri);
      form.submit();

      // alterar link
      location.href = link;
   });
});