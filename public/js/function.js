function votePost(currentElement, oppositeElement, voteTypeId, postId) {
   // dados que vão para o servidor (corpo da requisição)
   let body = {
      "voteTypeId": voteTypeId
   };

   // para enviar dados ao servidor é preciso primeiro converter para JSON
   let serverJson = JSON.stringify(body);

   // fazer pedido ao servidor
   ajaxRequest = $.ajax({
      cache: false,
      contentType: "application/json; charset=utf-8",
      data: serverJson,
      dateType: "json",
      type: "post",
      url: "/post/vote/" + postId
   });

   // executar esta função quando o pedido é concluído com sucesso
   ajaxRequest.done(function (response) {
      // converter JSON que veio do servidor para JS
      clientJs = JSON.parse(response);

      // atualizar cor dos ícones de up e down vote
      let currentIsActive = currentElement.children(".brief-posts__interactions__icon").attr("data-markedvote") == "marked";
      let oppositeIsActive = oppositeElement.children(".brief-posts__interactions__icon").attr("data-markedvote") == "marked";

      if (currentIsActive) {
         currentElement.children(".brief-posts__interactions__icon").attr("data-markedvote", "none"); // desativa atual
      }
      else {
         currentElement.children(".brief-posts__interactions__icon").attr("data-markedvote", "marked"); // ativa atual

         if (oppositeIsActive) {
            oppositeElement.children(".brief-posts__interactions__icon").attr("data-markedvote", "none"); // desativa oposto
         }
      }

      // atualizar número de votos do post na página
      currentElement.parent().children(".brief-posts__votes-amount").text(clientJs.votesAmount);
   });

   // executar esta função quando existe algum erro ao fazer o pedido
   ajaxRequest.fail(function (error) {
      // showErrorAlert("Erro ao comunicar com o servidor.");
   });
}

function voteComment(currentElement, oppositeElement, voteTypeId, commentId) {
   // dados que vão para o servidor (corpo da requisição)
   let body = {
      "voteTypeId": voteTypeId
   };

   // para enviar dados ao servidor é preciso primeiro converter para JSON
   let serverJson = JSON.stringify(body);

   // fazer pedido ao servidor
   ajaxRequest = $.ajax({
      cache: false,
      contentType: "application/json; charset=utf-8",
      data: serverJson,
      dateType: "json",
      type: "post",
      url: "/comment/vote/" + commentId
   });

   // executar esta função quando o pedido é concluído com sucesso
   ajaxRequest.done(function (response) {
      // converter JSON que veio do servidor para JS
      clientJs = JSON.parse(response);

      // atualizar cor dos ícones de up e down vote
      let currentIsActive = currentElement.children(".comment__icon").attr("data-markedvote") == "marked";
      let oppositeIsActive = oppositeElement.children(".comment__icon").attr("data-markedvote") == "marked";

      if (currentIsActive) {
         currentElement.children(".comment__icon").attr("data-markedvote", "none"); // desativa atual
      }
      else {
         currentElement.children(".comment__icon").attr("data-markedvote", "marked"); // ativa atual

         if (oppositeIsActive) {
            oppositeElement.children(".comment__icon").attr("data-markedvote", "none"); // desativa oposto
         }
      }

      // atualizar número de votos do post na página
      currentElement.parent().children(".comment__votes-amount").text(clientJs.votesAmount);
   });

   // executar esta função quando existe algum erro ao fazer o pedido
   ajaxRequest.fail(function (error) {
      // showErrorAlert("Erro ao comunicar com o servidor.");
   });
}