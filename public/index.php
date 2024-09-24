<?php

session_start();

define('BASE_DIR', dirname(__FILE__) . '/..');
define('APP_DIR', BASE_DIR . '/app');
define('MODEL_DIR', APP_DIR . '/models');
define('VIEW_DIR', APP_DIR . '/views');
define('CONTROLLER_DIR', APP_DIR . '/controllers');

require_once APP_DIR . '/core/Model.php';

try {
    $model = new Model();
    echo "<p>Connection successfull !</p>";
} catch (Exception $e) {
    // Si la connexion échoue, afficher un message d'erreur
    echo "<p>Connection error : " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <!-- Le message de connexion sera affiché ici -->
    <p>Test</p>
</body>

</html>