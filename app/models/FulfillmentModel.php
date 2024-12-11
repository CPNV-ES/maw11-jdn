<?php
require_once APP_DIR . '/core/Model.php';

/**
 * FulfillmentModel class
 */
class FulfillmentModel extends Model
{
    public function create($create, $idExercise)
    {
        $query = "INSERT INTO fulfillments (created_at, id_exercises) VALUES (:created_at,:id_exercises)";

        $binds = [
            'created_at' => ['value' => $create, 'type' => PDO::PARAM_STR],
            'id_exercises' => ['value' => $idExercise, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $this->db->fetchAll($req);
    }

    public function update($update, $id)
    {
        $query = "UPDATE fulfillments SET updated_at = :updated_at WHERE id_fulfillments = :id;";

        $binds = [
            'updated_at' => ['value' => $update, 'type' => PDO::PARAM_STR],
            'id' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $this->db->fetchAll($req);
    }

    public function getLast()
    {
        $query = "SELECT * FROM fulfillments ORDER BY id_fulfillments DESC LIMIT 1";

        $req = $this->db->querySimpleExecute($query);
        return $this->db->fetchAll($req);
    }

    public function getFulfillmentsByExerciseId($exerciseId)
    {

        $query = "SELECT * FROM fulfillments WHERE id_exercises = :id_exercises ORDER BY created_at";

        $binds = [
            'id_exercises' => ['value' => $exerciseId, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);


        return $this->db->fetchAll($req);
    }

    public function getOne($id)
    {
        $query = "SELECT * FROM fulfillments WHERE id_fulfillments = :id";


        $binds = [
            'id' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $fulfillment = $this->db->fetchAll($req);

        return $fulfillment;
    }

    public function delete($idFulfillment)
    {
        $query = "DELETE FROM fulfillments WHERE id_fulfillments = :id";

        $binds = [
            'id' => ['value' => $idFulfillment, 'type' => PDO::PARAM_INT]
        ];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            return true;
        } catch (PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }
}
