<?php
require_once APP_DIR . '/core/Model.php';

/**
 * FieldModel class
 */
class AnswerModel extends Model
{
    public function create($value, $idfield, $idexerciseAnswer)
    {
        $query = "INSERT INTO answers (value, id_fields, id_exercise_answer) VALUES (:value,:id_fields, :id_exercise_answer)";

        $binds = [
            'value' => ['value' => $value, 'type' => PDO::PARAM_INT],
            'id_fields' => ['value' => $idfield, 'type' => PDO::PARAM_INT],
            'id_exercise_answer' => ['value' => $idexerciseAnswer, 'type' => PDO::PARAM_INT],
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $fields = $this->db->fetchAll($req);

        return $fields;
    }

    public function update($value, $idfield)
    {
        $query = "UPDATE answers SET value = :value WHERE id_fields = :id_fields;";

        $binds = [
            'value' => ['value' => $value, 'type' => PDO::PARAM_INT],
            'id_fields' => ['value' => $idfield, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $fields = $this->db->fetchAll($req);

        return $fields;
    }

    public function getAnswer()
    {



        return;
    }
}
