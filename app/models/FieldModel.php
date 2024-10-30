<?php
require_once APP_DIR . '/core/Model.php';

/**
 * FieldModel class
 */
class FieldModel extends Model
{
    public function getFieldById($exericeId) {
        $query = "SELECT * FROM fields WHERE id_exercises = :id_exercises";
        
        $binds = [
            'id_exercises' => ['value' => $exericeId, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $field = $this->db->fetch($req);

        return $field;
    }
}