function login(formElement) {
    const formData = formElement.serialize();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = formElement.attr('action');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        data: formData,
        dateType: "json",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        type: "post",
        url: url
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        if (response.success) {
            window.location.href = '/';
        }
    });

    // executar esta função quando existe algum erro ao fazer o pedido
    ajaxRequest.fail(function (response) {
        if (response.status == 401 || response.status == 422) {
            let errorsArray = Object.values(response.responseJSON.errors).flat();
            let errorsContainer = $('.errors');
            let errorsList = $('.errors__list');

            errorsContainer.show();
            errorsList.html('');

            if (errorsArray.length > 0) {
                errorsContainer.show();

                errorsArray.forEach((error) => {
                    const $errorItem = $('<li>').text(error);
                    errorsList.append($errorItem);
                });
            }
        }
        else {
            window.location.href = '/500';
        }
    });
}


function signup(formElement) {
    const formData = formElement.serialize();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = formElement.attr('action');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        data: formData,
        dateType: "json",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        type: "post",
        url: url
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        if (response.success) {
            window.location.href = '/';
        }
    });

    // executar esta função quando existe algum erro ao fazer o pedido
    ajaxRequest.fail(function (response) {
        if (response.status == 422) {
            let errorsArray = Object.values(response.responseJSON.errors).flat();
            let errorsContainer = $('.errors');
            let errorsList = $('.errors__list');

            errorsContainer.show();
            errorsList.html('');

            if (errorsArray.length > 0) {
                errorsContainer.show();

                errorsArray.forEach((error) => {
                    const $errorItem = $('<li>').text(error);
                    errorsList.append($errorItem);
                });
            }
        }
        else {
            window.location.href = '/500';
        }
    });
}


function newPost(formElement) {
    const formData = formElement.serialize();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = formElement.attr('action');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        data: formData,
        dateType: "json",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        type: "post",
        url: url
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        if (response.success) {
            window.location.href = '/';
        }
    });

    // executar esta função quando existe algum erro ao fazer o pedido
    ajaxRequest.fail(function (response) {
        if (response.status == 422) {
            let errorsArray = Object.values(response.responseJSON.errors).flat();

            let errorsContainer = $('.errors.errors--new-post');
            let errorsList = errorsContainer.find('.errors__list');

            if (errorsArray.length > 0) {
                errorsList.html('');
                errorsContainer.show();

                errorsArray.forEach((error) => {
                    const errorItem = $('<li>').text(error);
                    errorsList.append(errorItem);
                });
            }
        }
        else {
            window.location.href = '/500';
        }
    });
}


function editPost(formElement) {
    const formData = formElement.serialize();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = formElement.attr('action');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        data: formData,
        dateType: "json",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        type: "post",
        url: url
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        if (response.success) {
            window.location.href = '/';
        }
    });

    // executar esta função quando existe algum erro ao fazer o pedido
    ajaxRequest.fail(function (response) {
        if (response.status == 422) {
            let errorsArray = Object.values(response.responseJSON.errors).flat();

            let postId = formElement.attr('id').replace('formEditPost', '');
            let errorsContainer = $('.errors.errors--edit-post-' + postId);
            let errorsList = errorsContainer.find('.errors__list');

            if (errorsArray.length > 0) {
                errorsList.html('');
                errorsContainer.show();

                errorsArray.forEach((error) => {
                    const errorItem = $('<li>').text(error);
                    errorsList.append(errorItem);
                });
            }
        }
        else {
            window.location.href = '/500';
        }
    });
}


function newComment(formElement) {
    const formData = formElement.serialize();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = formElement.attr('action');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        data: formData,
        dateType: "json",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        type: "post",
        url: url
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        if (response.success) {
            location.reload();
        }
    });

    // executar esta função quando existe algum erro ao fazer o pedido
    ajaxRequest.fail(function (response) {
        if (response.status == 422) {
            let errorsArray = Object.values(response.responseJSON.errors).flat();

            let errorsContainer = $('.errors.errors--new-comment');
            let errorsList = errorsContainer.find('.errors__list');

            if (errorsArray.length > 0) {
                errorsList.html('');
                errorsContainer.show();

                errorsArray.forEach((error) => {
                    const errorItem = $('<li>').text(error);
                    errorsList.append(errorItem);
                });
            }
        }
        else {
            window.location.href = '/500';
        }
    });
}


function editComment(formElement) {
    const formData = formElement.serialize();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = formElement.attr('action');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        data: formData,
        dateType: "json",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        type: "post",
        url: url
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        if (response.success) {
            location.reload();
        }
    });

    // executar esta função quando existe algum erro ao fazer o pedido
    ajaxRequest.fail(function (response) {
        if (response.status == 422) {
            let errorsArray = Object.values(response.responseJSON.errors).flat();

            let commentId = formElement.attr('id').replace('formEditComment', '');
            let errorsContainer = $('.errors.errors--edit-comment-' + commentId);
            let errorsList = errorsContainer.find('.errors__list');

            if (errorsArray.length > 0) {
                errorsList.html('');
                errorsContainer.show();

                errorsArray.forEach((error) => {
                    const errorItem = $('<li>').text(error);
                    errorsList.append(errorItem);
                });
            }
        }
        else {
            window.location.href = '/500';
        }
    });
}


function editData(formElement) {
    const formData = formElement.serialize();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = formElement.attr('action');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        data: formData,
        dateType: "json",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        type: "post",
        url: url
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        if (response.success) {
            location.reload();
        }
    });

    // executar esta função quando existe algum erro ao fazer o pedido
    ajaxRequest.fail(function (response) {
        if (response.status == 422) {
            let errorsArray = Object.values(response.responseJSON.errors).flat();

            let errorsContainer = $('.errors.errors--edit-data');
            let errorsList = errorsContainer.find('.errors__list');

            if (errorsArray.length > 0) {
                errorsList.html('');
                errorsContainer.show();

                errorsArray.forEach((error) => {
                    const errorItem = $('<li>').text(error);
                    errorsList.append(errorItem);
                });
            }
        }
        else {
            window.location.href = '/500';
        }
    });
}


function editPassword(formElement) {
    const formData = formElement.serialize();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = formElement.attr('action');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        data: formData,
        dateType: "json",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        type: "post",
        url: url
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        if (response.success) {
            location.reload();
        }
    });

    // executar esta função quando existe algum erro ao fazer o pedido
    ajaxRequest.fail(function (response) {
        if (response.status == 422) {
            let errorsArray = Object.values(response.responseJSON.errors).flat();

            let errorsContainer = $('.errors.errors--edit-password');
            let errorsList = errorsContainer.find('.errors__list');

            if (errorsArray.length > 0) {
                errorsList.html('');
                errorsContainer.show();

                errorsArray.forEach((error) => {
                    const errorItem = $('<li>').text(error);
                    errorsList.append(errorItem);
                });
            }
        }
        else {
            window.location.href = '/500';
        }
    });
}


function votePost(elements, voteTypeId, postId) {
    // dados que vão para o servidor (corpo da requisição)
    let data = {
        "voteTypeId": voteTypeId
    };

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        data: data,
        dateType: "json",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        type: "post",
        url: "/posts/vote/" + postId
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        let votesAmount = response.data.votesAmount;

        // atualizar cor dos ícones de up e down vote
        let currentIsActive = elements.currentVoteIcon.attr("data-markedvote") == "marked";
        let oppositeIsActive = elements.oppositeVoteIcon.attr("data-markedvote") == "marked";

        if (currentIsActive) {
            elements.currentVoteIcon.attr("data-markedvote", "none");  // desativa atual
        }
        else {
            elements.currentVoteIcon.attr("data-markedvote", "marked");  // ativa atual

            if (oppositeIsActive) {
                elements.oppositeVoteIcon.attr("data-markedvote", "none");  // desativa oposto
            }
        }

        // atualizar número de votos do post na página
        elements.votesAmount.text(votesAmount);
    });

    // executar esta função quando existe algum erro ao fazer o pedido
    ajaxRequest.fail(function (response) {
        // utilizador não autenticado
        if (response.status == 401) {
            showErrorModal('É necessário iniciar sessão para realizar esta operação.')
        } else {
            window.location.href = '/500';
        }
    });
}


function voteComment(elements, voteTypeId, commentId) {
    // dados que vão para o servidor (corpo da requisição)
    let data = {
        "voteTypeId": voteTypeId
    };

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // fazer pedido ao servidor
    ajaxRequest = $.ajax({
        cache: false,
        data: data,
        dateType: "json",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        type: "post",
        url: "/comments/vote/" + commentId
    });

    // executar esta função quando o pedido é concluído com sucesso
    ajaxRequest.done(function (response) {
        let votesAmount = response.data.votesAmount;

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
        if (error.status == 401) {
            showErrorModal('É necessário iniciar sessão para realizar esta operação.')
        } else {
            window.location.href = '/500';
        }
    });
}


function showErrorModal(message) {
    $("#errorModal").modal("show");
    $("#errorMessage").text(message);
}
