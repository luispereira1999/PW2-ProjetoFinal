<h1>404 ERROR</h1>

<?php
$errors = array();

if (isset($_SESSION["errors"])) {
   $errors = $_SESSION["errors"];
   unset($_SESSION["errors"]);
}

foreach ($errors as $error) : ?>
   <p>Erro: <?= $error ?></p>
<?php endforeach; ?>