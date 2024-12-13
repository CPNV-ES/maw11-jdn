<?php

session_start();

define('BASE_DIR', dirname(__FILE__) . '/..');
define('APP_DIR', BASE_DIR . '/app');
define('MODEL_DIR', APP_DIR . '/models');
define('VIEW_DIR', APP_DIR . '/views');
define('CONTROLLER_DIR', APP_DIR . '/controllers');
define('CONFIG_DIR', BASE_DIR . '/config');

require_once APP_DIR . '/core/Model.php';

try {
    $model = new Model();
} catch (Exception $e) {
    echo "<p>Connection error : " . $e->getMessage() . "</p>";
}

require CONTROLLER_DIR . '/ExerciseController.php';
require CONTROLLER_DIR . '/HomeController.php';

?>
<?php
$request_uri = $_SERVER['REQUEST_URI'];

$exploded_uri = explode('/', $request_uri);
$base_uri = '/' . $exploded_uri[1];


switch ($base_uri) {
    case '/':
        (new HomeController())->show();
        exit();
    case '/exercises':
        (new ExerciseController())->renderer($request_uri);
        exit();
    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page not found";
        exit();
}
?>