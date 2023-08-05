// executar este código quando a página é carregada
$(document).ready(function () {
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
