<?php
require_once '../core/Model.php';

/**
 * ExerciseModel class
 */
class ExerciseModel extends Model
{
    /**
     * Method to get all exercise
     * @return array - array of exercises 
     */
    public function getAllExercise()
    {
        $query = "SELECT * FROM exercises";

        $req = $this->querySimpleExecute($query);

        return $this->fetchAll($req);
    }

    /**
     * Method to get all exercise
     * @return array - array of exercises 
     */
    public function getOneExercise($id)
    {
        $query = "SELECT * FROM exercises where id = :id";

        $binds = [
            'idExercise' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->queryPrepareExecute($query, $binds);
        $exercise = $this->fetch($req);

        return $exercise;
    }

    /**
     * Method to create exercise
     * @param mixed $id - id of exercise
     * @param mixed $title - title of exercise
     * @param mixed $description - description of exercise
     * @return void
     */
    public function createExercise($title)
    {
        $query = "INSERT INTO exercise (title) VALUES (:title)";

        $binds = [
            'title' => ['value' => $title, 'type' => PDO::PARAM_STR],
        ];

        $this->queryPrepareExecute($query, $binds);
    }
}
