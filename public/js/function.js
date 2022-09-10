function votePost(currentElement, voteTypeId, postId) {
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
   });

   // executar esta função quando existe algum erro ao fazer o pedido
   ajaxRequest.fail(function (error) {
   });
}