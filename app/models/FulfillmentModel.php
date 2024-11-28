<?php
require_once APP_DIR . '/core/Model.php';

/**
 * FieldModel class
 */
class FulfillmentModel extends Model
{
    public function create($create, $idexercise)
    {
        $query = "INSERT INTO fulfillments (created_at, id_exercises) VALUES (:created_at,:id_exercises)";

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
        $query = "UPDATE fulfillments SET updated_at = :updated_at WHERE id_fulfillments = :id;";

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
        $query = "SELECT * FROM fulfillments ORDER BY id_fulfillments DESC LIMIT 1";

        $req = $this->db->querySimpleExecute($query);

        return $this->db->fetchAll($req);
    }
}
