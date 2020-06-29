// executar este código quando a página é carregada
$(document).ready(function() {
    // votar no post para cima na página de index e user
    $(".posts [data-vote='upvote']").click(function() {
        votePost($(this), 1, $(this).parent().parent().parent().attr("data-post"));
    });
    // votar no post para baixo na página de index e user
    $(".posts [data-vote='downvote']").click(function() {
        votePost($(this), 2, $(this).parent().parent().parent().attr("data-post"));
    });


    // votar no post para cima na página do post
    $(".postText [data-vote='upvote']").click(function() {
        votePost($(this), 1, $(this).parent().parent().parent().attr("data-post"));
    });
    // votar no post para baixo na página do post
    $(".postText [data-vote='downvote']").click(function() {
        votePost($(this), 2, $(this).parent().parent().parent().attr("data-post"));
    });


    // votar no comentário para cima na página do post
    $(".comments [data-vote='upvote']").click(function() {
        voteComment($(this), 1, $(this).parent().parent().parent().parent().attr("data-comment"));
    });
    // votar no comentário para baixo na página do post
    $(".comments [data-vote='downvote']").click(function() {
        voteComment($(this), 2, $(this).parent().parent().parent().parent().attr("data-comment"));
    });


    // mostrar formulário de signup
    $("[data-session='login']").click(function() {
        $("#div-signup").hide(500);
        $("#div-login").show(500);
    });
    // mostrar formulário de login
    $("[data-session='signup']").click(function() {
        $("#div-login").hide(500);
        $("#div-signup").show(500);
    });


    // ao clicar na checkbox de lembrar login, verificar se está ativa ou desativa 
    $("[value='rememberLogin']").click(function() {
        if ($("[value='rememberLogin']").is(":checked")) {
            $("[value='rememberLogin']").attr("name", "remember");
        } else {
            $("[value='rememberLogin']").attr("name", "dismember");
        }
    });
    // clicar na label de "relembrar login" para ativar a checkbox
    $("#rememberText").click(function() {
        $("[value='rememberLogin']").click()
    });


    // alterar layout dos posts para full width
    $("[data-grid='fullWidth']").click(function() {
        $(".posts").children(".sectionPost").removeClass("sectionPost");
        $(".posts").children().children(".width3").addClass("fullWidth");
        $(".posts").children().children(".width3").removeClass("width3");
    });
    // alterar layouts dos posts para width 3
    $("[data-grid='width3']").click(function() {
        $(".posts").children().addClass("sectionPost");
        $(".posts").children().children(".fullWidth").addClass("width3");
        $(".posts").children().children(".fullWidth").removeClass("fullWidth");
    });


    // ao clicar para ativar "Alterar Senha", verificar se está ativa ou desativa 
    $("#updatePassword").click(function() {
        if ($("#updatePassword").attr("src") === "../server/assets/images/switch-off.png") {
            $("#updatePassword").attr("src", "../server/assets/images/switch-on.png");
            $("#updatePassword").attr("name", "update");
            $("[name='currentPassword']").removeAttr("disabled");
            $("[name='newPassword']").removeAttr("disabled");
            $("[name='confirmNewPassword']").removeAttr("disabled");
        } else {
            $("#updatePassword").attr("src", "../server/assets/images/switch-off.png");
            $("#updatePassword").attr("name", "notUpdate");
            $("[name='currentPassword']").prop("disabled", true);
            $("[name='newPassword']").prop("disabled", true);
            $("[name='confirmNewPassword']").prop("disabled", true);
            $("[name='currentPassword']").val("");
            $("[name='newPassword']").val("");
            $("[name='confirmNewPassword']").val("");
        }
    });


    // pesquisar por posts
    $("#search").click(function() {
        $("form").submit();
    });


    // clicar no ícone de editar post
    $("[data-action='edit']").click(function() {
        $("[name='action']").attr("value", "edit");
        $(this).parent(".editDeletePost").submit();
    });
    // clicar no ícone de eliminar post
    $("[data-action='delete']").click(function() {
        $("[name='action']").attr("value", "delete");
        $(this).parent(".editDeletePost").submit();
    });


    // clicar no ícone de editar comentário
    $("[data-action='edit']").click(function() {
        $("[data-action='edit']").attr("value", "edit");
        $("#editComment [name='description']").val($(this).parent().parent().parent().children(".col-9").children("p").text());
        $("#editComment [name='commentId']").attr("value", $(this).parent().parent().parent().parent().data("comment"));
    });
    // clicar no ícone de eliminar comentário
    $("[data-action='delete']").click(function() {
        $("[name='action']").attr("value", "delete");
        $(this).parent(".editDeleteComment").submit();
    });


    // mostrar tooltips
    $('[data-toggle="tooltip"]').tooltip();
});