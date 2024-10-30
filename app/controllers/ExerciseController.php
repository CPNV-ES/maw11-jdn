<?php

require_once APP_DIR . '/core/Controller.php';

class ExerciseController extends Controller
{

    public function renderer($request_uri)
    {
        switch ($request_uri) {
            case '/exercises':
                require_once VIEW_DIR . '/home/manage-exercise.php';
                exit();
            case '/exercises/new':
                require_once VIEW_DIR . '/home/create-exercise.php';
                exit();
            case '/exercises/answering':
                require_once VIEW_DIR . '/home/take-exercise.php';
                exit();
            case (preg_match('/\/exercises\/(\d+)\/results.*/', $request_uri) ? true : false):
                require_once VIEW_DIR . '/home/result-exercise.php';
                exit();
            case (preg_match('/\/exercises\/(\d+)\/fulfillments\/new.*/', $request_uri) ? true : false):
                $_SESSION['state'] = 'new';
                require_once VIEW_DIR . '/home/fulfill-exercise.php';
                exit();
            case (preg_match('/\/exercises\/(\d+)\/fulfillments\/edit.*/', $request_uri) ? true : false):
                $_SESSION['state'] = 'edit';
                require_once VIEW_DIR . '/home/fulfill-exercise.php';
                exit();
            default:
                header("HTTP/1.0 404 Not Found");
                echo "Page not found";
                exit();
        }
    }
}
