<?php  // mostrar popups
if (isset($_SESSION["messageError"])) {
   echo "<script> showErrorAlert('" . $_SESSION['messageError'] . "'); </script>";
   unset($_SESSION["messageError"]);
}

if (isset($_SESSION["messageSuccess"])) {
   echo "<script> showSuccessAlert('" . $_SESSION['messageSuccess'] . "'); </script>";
   unset($_SESSION["messageSuccess"]);
}

if (isset($_SESSION["popupEditPost"])) {
   echo "<script> showEditPost(" . json_encode($_SESSION['popupEditPost']) . "); </script>";
   unset($_SESSION["popupEditPost"]);
}
