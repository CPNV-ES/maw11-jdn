<?php
require_once APP_DIR . '/core/Model.php';

/**
 * AnswerModel class
 */
class AnswerModel extends Model
{
    public function getAllAnswers() {
        
        $query = "SELECT * FROM answers";

        $req = $this->db->querySimpleExecute($query);

        return $this->db->fetchAll($req);
    }
    public function getAnswersById($idFields) {
        
        $query = "SELECT * FROM answers WHERE id_fields = :id_fields";

        $binds = [
            'id_fields' => ['value' => $idFields, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);

        return $this->db->fetch($req);
    }
}