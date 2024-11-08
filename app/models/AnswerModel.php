<?php
require_once APP_DIR . '/core/Model.php';

/**
 * AnswerModel class
 */
class AnswerModel extends Model
{
    public function getAllAnswers($fieldId) {
        $query = "SELECT * FROM answers WHERE id_fields = :id_fields";
        
        $binds = [
            'id_fields' => ['value' => $fieldId, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $answers = $this->db->fetchAll($req);

        return $answers;
    }
}