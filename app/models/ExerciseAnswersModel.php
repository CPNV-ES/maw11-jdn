<?php
require_once APP_DIR . '/core/Model.php';

/**
 * FieldModel class
 */
class ExerciseAnswersModel extends Model
{
    public function create($create, $idexercise)
    {
        $query = "INSERT INTO exercise_answer (created_at, id_exercises) VALUES (:created_at,:id_exercises)";

        $binds = [
            'created_at' => ['value' => $create, 'type' => PDO::PARAM_INT],
            'id_exercises' => ['value' => $idexercise, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $exerciseAnswered = $this->db->fetchAll($req);

        return $exerciseAnswered;
    }

    public function update($update, $id)
    {
        $query = "UPDATE exercise_answer SET updated_at = :updated_at WHERE id_exercise_answer = :id;";

        $binds = [
            'updated_at' => ['value' => $update, 'type' => PDO::PARAM_INT],
            'id' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $exerciseAnswered = $this->db->fetchAll($req);

        return $exerciseAnswered;
    }

    public function getAnswer()
    {



        return;
    }

    public function getLast()
    {
        $query = "SELECT id_exercise_answer FROM exercise_answer ORDER BY id_exercise_answer DESC LIMIT 1";

        $req = $this->db->querySimpleExecute($query);

        return $this->db->fetchAll($req);
    }
}
