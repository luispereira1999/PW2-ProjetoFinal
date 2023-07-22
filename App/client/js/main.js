// executar este código quando a página é carregada
$(document).ready(function () {
   // ao clicar na checkbox de lembrar login, verificar se está ativa ou desativa 
   $("[value='rememberLogin']").click(function () {
      if ($("[value='rememberLogin']").is(":checked")) {
         $("[value='rememberLogin']").attr("name", "remember");
      } else {
         $("[value='rememberLogin']").attr("name", "dismember");
      }
   });
   // clicar na label de "relembrar login" para ativar a checkbox
   $("#rememberText").click(function () {
      $("[value='rememberLogin']").click()
   });


   // alterar layout dos posts para full width
   $("[data-grid='fullWidth']").click(function () {
      $(".posts").children(".sectionPost").removeClass("sectionPost");
      $(".posts").children().children(".width3").addClass("fullWidth");
      $(".posts").children().children(".width3").removeClass("width3");
   });
   // alterar layouts dos posts para width 3
   $("[data-grid='width3']").click(function () {
      $(".posts").children().addClass("sectionPost");
      $(".posts").children().children(".fullWidth").addClass("width3");
      $(".posts").children().children(".fullWidth").removeClass("fullWidth");
   });
});