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
    public function getAnswersFromIdFulfillment($idFulfillment) {
        
        $query = "SELECT * FROM answers WHERE id_fulfillments = :id_fulfillments ORDER BY id_fulfillments";

        $binds = [
            'id_fulfillments' => ['value' => $idFulfillment, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);

        return $this->db->fetchAll($req);
    }
}