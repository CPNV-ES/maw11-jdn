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
}