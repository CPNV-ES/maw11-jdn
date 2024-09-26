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
        }
    }
}
