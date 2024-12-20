<?php

/**
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 19.12.2024
 * @description Entry point for routing requests and initializing models and controllers.
 *
 * This script:
 * - Starts the session and defines constants for directory paths.
 * - Includes necessary model and controller files.
 * - Handles the routing based on the requested URI and directs to the corresponding controller.
 * - If no route matches, a 404 error is shown.
 */

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

require CONTROLLER_DIR . '/MainController.php';
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
        (new MainController())->renderer($request_uri);
        exit();
    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page not found";
        exit();
}
?>