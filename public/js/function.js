function votePost(elements, voteTypeId, postId) {
    // dados que vão para o servidor (corpo da requisição)
    let body = {
        "voteTypeId": voteTypeId
    };

    // para enviar dados ao servidor é preciso primeiro converter para JSON
    let serverJson = JSON.stringify(body);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        contentType: "application/json; charset=utf-8",
        data: serverJson,
        dateType: "json",
        type: "post",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        url: "/posts/vote/" + postId
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        let votesAmount = response.votesAmount;

        // atualizar cor dos ícones de up e down vote
        let currentIsActive = elements.currentVoteIcon.attr("data-markedvote") == "marked";
        let oppositeIsActive = elements.oppositeVoteIcon.attr("data-markedvote") == "marked";

        if (currentIsActive) {
            elements.currentVoteIcon.attr("data-markedvote", "none"); // desativa atual
        }
        else {
            elements.currentVoteIcon.attr("data-markedvote", "marked"); // ativa atual

            if (oppositeIsActive) {
                elements.oppositeVoteIcon.attr("data-markedvote", "none"); // desativa oposto
            }
        }

        // atualizar número de votos do post na página
        elements.votesAmount.text(votesAmount);
    });

    // executar esta função quando existe algum erro ao fazer o pedido
    ajaxRequest.fail(function (error) {
        // utilizador não autenticado
        if (error.status === 401) {
            window.location.href = '/auth';
        }
        // showErrorAlert("Erro ao comunicar com o servidor.");
    });
}


function voteComment(elements, voteTypeId, commentId) {
    // dados que vão para o servidor (corpo da requisição)
    let body = {
        "voteTypeId": voteTypeId
    };

    // para enviar dados ao servidor é preciso primeiro converter para JSON
    let serverJson = JSON.stringify(body);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        contentType: "application/json; charset=utf-8",
        data: serverJson,
        dateType: "json",
        type: "post",
        headers: {
            'X-CSRF-TOKEN': csrfToken // Adiciona o token CSRF ao cabeçalho
        },
        url: "/comments/vote/" + commentId
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        let votesAmount = response.votesAmount;

        // atualizar cor dos ícones de up e down vote
        let currentIsActive = elements.currentVoteIcon.attr("data-markedvote") == "marked";
        let oppositeIsActive = elements.oppositeVoteIcon.attr("data-markedvote") == "marked";

        if (currentIsActive) {
            elements.currentVoteIcon.attr("data-markedvote", "none"); // desativa atual
        }
        else {
            elements.currentVoteIcon.attr("data-markedvote", "marked"); // ativa atual

            if (oppositeIsActive) {
                elements.oppositeVoteIcon.attr("data-markedvote", "none"); // desativa oposto
            }
        }

        // atualizar número de votos do post na página
        elements.votesAmount.text(votesAmount);
    });

    // executar esta função quando existe algum erro ao fazer o pedido
    ajaxRequest.fail(function (error) {
        // utilizador não autenticado
        if (error.status === 401) {
            window.location.href = '/auth';
        }
        // showErrorAlert("Erro ao comunicar com o servidor.");
    });
}
