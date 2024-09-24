<?php

require_once APP_DIR . '/core/Controller.php';

class ExerciseController extends Controller
{
    public function manage()
    {
        require_once VIEW_DIR . '/home/manage-exercise.php';
    }
}
