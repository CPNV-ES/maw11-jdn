<?php
require_once APP_DIR . '/core/Model.php';

/**
 * ExerciseModel class
 */
class ExerciseModel extends Model
{
    /**
     * Id of the exercise
     * @var 
     */
    public $id = null;

    /**
     * Method to get all exercise
     * @return array - array of exercises 
     */
    public function getAll()
    {
        $query = "SELECT * FROM exercises";

        $req = $this->db->querySimpleExecute($query);

        return $this->db->fetchAll($req);
    }

    /**
     * Method to get one exercise
     * @param mixed $id
     * @return mixed
     */
    public function getOne($id)
    {
        $query = "SELECT title FROM exercises where id_exercises = :id_exercises";

        $binds = [
            'id_exercises' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $exercise = $this->db->fetch($req);

        return $exercise;
    }

    /**
     * Method to create an exercise
     * @param mixed $title
     * @return bool|string
     */
    public function create($title)
    {
        $query = "INSERT INTO exercises (title, id_status) VALUES (:title,:id_status)";

        $binds = [
            'title' => ['value' => $title, 'type' => PDO::PARAM_STR],
            'id_status' => ['value'=> 1, 'type'=> PDO::PARAM_INT]
        ];

        $response = null;

        try {
            $this->db->queryPrepareExecute($query, $binds);
            $response = $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }

        $this->id = is_string($response) ? $response : null;

        return $this->id ?? false;
    }
}