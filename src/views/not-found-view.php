<h1>404 ERROR</h1>

<?php
if (isset($_SESSION["errors"])) {
   $errors = $_SESSION["errors"];
   unset($_SESSION["errors"]);
} else {
   $errors = array();
}

foreach ($errors as $error) : ?>
   <p>Erro: <?= $error ?></p>
<?php endforeach; ?>