<?php
require_once '../core/Model.php';

class ExerciseModel extends Model
{
    /**
     * Method to get all exercise
     * @return array - array of exercises 
     */
    public function getExercises()
    {
        $query = "SELECT * FROM exercises";
        return $this->fetchAll($query);
    }
}
