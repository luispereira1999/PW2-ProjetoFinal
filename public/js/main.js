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


    // pesquisar posts
    $("#linkSearchPosts").click(function () {
        const textSearched = $("#inputSearchText").val();

        // verifica se o texto está vazio ou contém apenas espaços em branco
        if (!textSearched.trim()) {
            return;
        }

        // obter endpoint
        const encodedText = encodeURIComponent(textSearched);
        const route = "/search/" + encodedText;

        // atualizar URL
        $(this).attr("href", route);
    });


    // votar no post em destaque na página principal - up vote
    $(".featured [data-vote='upvote']").click(function () {
        let elements = {
            current: $(this),
            // voto contrário ao deste clique
            opposite: $(this).parent().children("[data-vote='downvote']"),
            currentVoteIcon: $(this).children(".featured__interactions__icon"),
            oppositeVoteIcon: $(this).parent().children("[data-vote='downvote']").children(".featured__interactions__icon"),
            votesAmount: $(this).parent().children(".featured__votes-amount")
        }

        let voteTypeId = 1;
        let postId = $(this).parent().parent().parent().parent().attr("data-post");

        votePost(elements, voteTypeId, postId);
    });

    // votar no post em destaque na página principal - down vote
    $(".featured [data-vote='downvote']").click(function () {
        let elements = {
            current: $(this),
            // voto contrário ao deste clique
            opposite: $(this).parent().children("[data-vote='downvote']"),
            currentVoteIcon: $(this).children(".featured__interactions__icon"),
            oppositeVoteIcon: $(this).parent().children("[data-vote='upvote']").children(".featured__interactions__icon"),
            votesAmount: $(this).parent().children(".featured__votes-amount")
        }

        let voteTypeId = 2;
        let postId = $(this).parent().parent().parent().parent().attr("data-post");

        votePost(elements, voteTypeId, postId);
    });


    // votar num post na página principal - up vote
    $(".brief-posts__interactions [data-vote='upvote']").click(function () {
        let elements = {
            current: $(this),
            // voto contrário ao deste clique
            opposite: $(this).parent().children("[data-vote='downvote']"),
            currentVoteIcon: $(this).children(".brief-posts__interactions__icon"),
            oppositeVoteIcon: $(this).parent().children("[data-vote='downvote']").children(".brief-posts__interactions__icon"),
            votesAmount: $(this).parent().children(".brief-posts__votes-amount")
        }

        let voteTypeId = 1;
        let postId = $(this).parent().parent().parent().attr("data-post");

        votePost(elements, voteTypeId, postId);
    });

    // votar num post na página principal - down vote
    $(".brief-posts__interactions [data-vote='downvote']").click(function () {
        let elements = {
            current: $(this),
            // voto contrário ao deste clique
            opposite: $(this).parent().children("[data-vote='downvote']"),
            currentVoteIcon: $(this).children(".brief-posts__interactions__icon"),
            oppositeVoteIcon: $(this).parent().children("[data-vote='upvote']").children(".brief-posts__interactions__icon"),
            votesAmount: $(this).parent().children(".brief-posts__votes-amount")
        }

        let voteTypeId = 2;
        let postId = $(this).parent().parent().parent().attr("data-post");

        votePost(elements, voteTypeId, postId);
    });


    // votar num post na página do post - up vote
    $(".full-post__interactions [data-vote='upvote']").click(function () {
        let elements = {
            current: $(this),
            // voto contrário ao deste clique
            opposite: $(this).parent().children("[data-vote='downvote']"),
            currentVoteIcon: $(this).children(".full-post__icon"),
            oppositeVoteIcon: $(this).parent().children("[data-vote='downvote']").children(".full-post__icon"),
            votesAmount: $(this).parent().children(".full-post__votes-amount")
        }

        let voteTypeId = 1;
        let postId = $(this).parent().parent().parent().parent().attr("data-post");

        votePost(elements, voteTypeId, postId);
    });

    // votar num post na página do post - down vote
    $(".full-post__interactions [data-vote='downvote']").click(function () {
        let elements = {
            current: $(this),
            // voto contrário ao deste clique
            opposite: $(this).parent().children("[data-vote='downvote']"),
            currentVoteIcon: $(this).children(".full-post__icon"),
            oppositeVoteIcon: $(this).parent().children("[data-vote='upvote']").children(".full-post__icon"),
            votesAmount: $(this).parent().children(".full-post__votes-amount")
        }

        let voteTypeId = 2;
        let postId = $(this).parent().parent().parent().parent().attr("data-post");

        votePost(elements, voteTypeId, postId);
    });


    // votar num comentário - up vote
    $(".comments [data-vote='upvote']").click(function () {
        let elements = {
            current: $(this),
            // voto contrário ao deste clique
            opposite: $(this).parent().children("[data-vote='upvote']"),
            currentVoteIcon: $(this).children(".comment__vote__icon"),
            oppositeVoteIcon: $(this).parent().children("[data-vote='downvote']").children(".comment__vote__icon"),
            votesAmount: $(this).parent().children(".comment__votes-amount")
        }

        let voteTypeId = 1;
        let commentId = $(this).parent().parent().parent().attr("data-comment");

        voteComment(elements, voteTypeId, commentId);
    });

    // votar num comentário - down vote
    $(".comments [data-vote='downvote']").click(function () {
        let elements = {
            current: $(this),
            // voto contrário ao deste clique
            opposite: $(this).parent().children("[data-vote='upvote']"),
            currentVoteIcon: $(this).children(".comment__vote__icon"),
            oppositeVoteIcon: $(this).parent().children("[data-vote='upvote']").children(".comment__vote__icon"),
            votesAmount: $(this).parent().children(".comment__votes-amount")
        }

        let voteTypeId = 2;
        let commentId = $(this).parent().parent().parent().attr("data-comment");

        voteComment(elements, voteTypeId, commentId);
    });


    // mostrar tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
