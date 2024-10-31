<?php
require_once APP_DIR . '/core/Model.php';

/**
 * FieldModel class
 */
class FieldModel extends Model
{
    public function getAllFieldById($exericeId) {
        $query = "SELECT * FROM fields WHERE id_exercises = :id_exercises";
        
        $binds = [
            'id_exercises' => ['value' => $exericeId, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $fields = $this->db->fetchAll($req);

        return $fields;
    }
}