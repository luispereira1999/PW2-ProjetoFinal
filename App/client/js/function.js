// votar num post
function votePost(thisEvent, voteTypeId, postId) {
   // executar esta função quando existe algum erro ao fazer o pedido
   ajaxRequest.fail(function() {
      showErrorAlert("Erro ao comunicar com o servidor.");
   });
}


// votar num comentário
function voteComment(thisEvent, voteTypeId, commentId) {
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