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
            case '/exercises/fields':
                require_once VIEW_DIR . '/home/field-exercice.php';
                exit();
            case '/exercises/new-fields':
                
                require_once VIEW_DIR . '/home/field-exercice.php';
                exit();
            case '/exercises/answering':
                require_once VIEW_DIR . '/home/take-exercise.php';
                exit();
            default:
                header("HTTP/1.0 404 Not Found");
                echo "Page not found";
                exit();
        }
    }


}
