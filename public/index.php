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
} catch (Exception $e) {
    echo "<p>Connection error : " . $e->getMessage() . "</p>";
}

require CONTROLLER_DIR . '/ExerciseController.php';
require CONTROLLER_DIR . '/HomeController.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>ExerciseLooper</title>
</head>

<body>
    <?php
    $request_uri = $_SERVER['REQUEST_URI'];

    $exploded_uri = explode('/', $request_uri);
    $base_uri = '/' . $exploded_uri[1];
    if ($exploded_uri[2] !== null) {
        $redirect = '/' . $exploded_uri[2];
    } else {
        $redirect = '';
    }


    switch ($base_uri) {
        case '/':
            (new HomeController())->show();
            exit();
        case '/exercises':
            (new ExerciseController())->renderer($base_uri, $redirect);
            exit();
        default:
            header("HTTP/1.0 404 Not Found");
            echo "Page not found";
            exit();
    }
    ?>
</body>

</html>