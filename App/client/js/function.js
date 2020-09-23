// votar num post
function votePost(thisEvent, voteTypeId, postId) {
   // dados que vão para o servidor
   let values = {
      "postId": postId,
      "voteTypeId": voteTypeId
   };

   // para enviar dados ao servidor é preciso primeiro converter para JSON
   let serverJSON = JSON.stringify(values);

   // fazer pedido ao servidor
   ajaxRequest = $.ajax({
      cache: false,
      contentType: "application/json; charset=utf-8",
      data: { vote: serverJSON, action: "vote" },
      dateType: "json",
      type: "get",
      url: "../server/vote-post.php?action=vote"
   });

   // executar esta função quando o pedido é concluído com sucesso
   ajaxRequest.done(function(data) {
      // converter JSON que veio do servidor para JS
      clientJS = JSON.parse(data);

      // verificar se houve erros
      if (clientJS.error != null) {
         showErrorAlert(clientJS.error);
         return;
      }

      // atualizar atributos
      if (voteTypeId == 1 && clientJS.activeVote == true) {
         thisEvent.children().attr("data-markedvote", "marked");
      } else if (voteTypeId == 2 && clientJS.activeVote == true) {
         thisEvent.children().attr("data-markedvote", "marked");
      } else if (voteTypeId == 1 && clientJS.activeVote == false) {
         thisEvent.parent().children("[data-vote='upvote']").children("[data-markedvote]").attr("data-markedvote", "none");
      } else if (voteTypeId == 2 && clientJS.activeVote == false) {
         thisEvent.parent().children("[data-vote='downvote']").children("[data-markedvote]").attr("data-markedvote", "none");
      }

      if (voteTypeId == 1 && thisEvent.parent().children("[data-vote='downvote']").children("[data-markedvote]").data("markedvote") == "marked") {
         thisEvent.parent().children("[data-vote='downvote']").children("[data-markedvote]").attr("data-markedvote", "none");
      } else if (voteTypeId == 2 && thisEvent.parent().children("[data-vote='upvote']").children("[data-markedvote]").data("markedvote") == "marked") {
         thisEvent.parent().children("[data-vote='upvote']").children("[data-markedvote]").attr("data-markedvote", "none");
      }

      // atualizar número de votos na página
      thisEvent.parent().children("label").text(clientJS.numberOfVotes);
   });

   // executar esta função quando existe algum erro ao fazer o pedido
   ajaxRequest.fail(function() {
      showErrorAlert("Erro ao comunicar com o servidor.");
   });
}


// votar num comentário
function voteComment(thisEvent, voteTypeId, commentId) {
   // dados que vão para o servidor
   let values = {
      "commentId": commentId,
      "voteTypeId": voteTypeId
   };

   // para enviar dados ao servidor é preciso primeiro converter para JSON
   let serverJSON = JSON.stringify(values);

   // fazer pedido ao servidor
   ajaxRequest = $.ajax({
      cache: false,
      contentType: "application/json; charset=utf-8",
      data: { vote: serverJSON, action: "vote" },
      dateType: "json",
      type: "get",
      url: "../server/vote-comment.php?action=vote"
   });

   // executar esta função quando o pedido é concluído com sucesso
   ajaxRequest.done(function(data) {
      // converter JSON que veio do servidor para JS
      clientJS = JSON.parse(data);

      // verificar se houve erros
      if (clientJS.error != null) {
         showErrorAlert(clientJS.error);
         return;
      }

      // atualizar atributos
      if (voteTypeId == 1 && clientJS.activeVote == true) {
         thisEvent.children().attr("data-markedvote", "marked");
      } else if (voteTypeId == 2 && clientJS.activeVote == true) {
         thisEvent.children().attr("data-markedvote", "marked");
      } else if (voteTypeId == 1 && clientJS.activeVote == false) {
         thisEvent.parent().children("[data-vote='upvote']").children("[data-markedvote]").attr("data-markedvote", "none");
      } else if (voteTypeId == 2 && clientJS.activeVote == false) {
         thisEvent.parent().children("[data-vote='downvote']").children("[data-markedvote]").attr("data-markedvote", "none");
      }

      if (voteTypeId == 1 && thisEvent.parent().children("[data-vote='downvote']").children("[data-markedvote]").data("markedvote") == "marked") {
         thisEvent.parent().children("[data-vote='downvote']").children("[data-markedvote]").attr("data-markedvote", "none");
      } else if (voteTypeId == 2 && thisEvent.parent().children("[data-vote='upvote']").children("[data-markedvote]").data("markedvote") == "marked") {
         thisEvent.parent().children("[data-vote='upvote']").children("[data-markedvote]").attr("data-markedvote", "none");
      }

      // atualizar número de votos na página
      thisEvent.parent().children("label").text(clientJS.numberOfVotes);
   });

   // executar esta função quando existe algum erro ao fazer o pedido
   ajaxRequest.fail(function() {
      showErrorAlert("Erro ao comunicar com o servidor.");
   });
}


function showErrorAlert(message) {
   $("#error").modal("show");
   $("#errorText").text(message);
}


function showSuccessAlert(message) {
   $("#success").modal("show");
   $("#successText").text(message);
}


function showEditPost(data) {
   let post = $.parseJSON(data);
   $("#editPost").modal("show");
   $("#editPost [name='postId']").val(post.id);
   $("#editPost [name='title']").val(post.title);
   $("#editPost [name='description']").val(post.description);
}