<?php
require_once APP_DIR . '/core/Model.php';

/**
 * FieldModel class
 */
class FieldModel extends Model
{
    public function getFieldsFromExercise($exerciseId) {
        $query = "SELECT * FROM fields WHERE id_exercises = :id_exercises";
        
        $binds = [
            'id_exercises' => ['value' => $exerciseId, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $fields = $this->db->fetchAll($req);

        return $fields;
    }
}