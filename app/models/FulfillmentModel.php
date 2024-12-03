<?php
require_once APP_DIR . '/core/Model.php';

/**
 * AnswerModel class
 */
class FulfillmentModel extends Model
{
    public function getFulfillmentsByExerciseId($exerciseId) {
        
        $query = "SELECT * FROM fulfillments WHERE id_exercises = :id_exercises ORDER BY created_at";

        $binds = [
            'id_exercises' => ['value' => $exerciseId, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);

        return $this->db->fetchAll($req);
    }

    public function getOneFulfillment ($id) {
        $query = "SELECT * FROM fulfillments WHERE id_fulfillments = :id_fulfillments";

        $binds = [
            'id_fulfillments' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);

        return $this->db->fetch($req);
    }
}