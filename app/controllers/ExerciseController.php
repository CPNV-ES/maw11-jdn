<?php

require_once '../core/Controller.php';

class ExerciseController extends Controller
{
    public function manage()
    {
        require_once '../views/home/manage-exercise.php';
    }
}
