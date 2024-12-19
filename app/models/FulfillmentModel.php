<?php

/**
 * @file FulfillmentModel.php
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 19.12.2024
 * @description Model class for managing operations related to the `fulfillments` table in the database.
 *
 * @details Provides methods to create, retrieve, update, and delete fulfillments for exercises.
 */

require_once APP_DIR . '/core/Model.php';

class FulfillmentModel extends Model
{
    /**
     * Creates a new fulfillment record in the database.
     * 
     * @param string $create The creation date of the fulfillment.
     * @param int $idExercise The ID of the exercise associated with the fulfillment.
     * 
     * @return void
     */
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

    /**
     * Updates the `updated_at` field for a specific fulfillment.
     * 
     * @param string $update The new update date for the fulfillment.
     * @param int $id The ID of the fulfillment to update.
     * 
     * @return void
     */
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

    /**
     * Retrieves the last fulfillment record in the database.
     * 
     * @return array The last fulfillment record.
     */
    public function getLast()
    {
        $query = "SELECT * FROM fulfillments ORDER BY id_fulfillments DESC LIMIT 1";

        $req = $this->db->querySimpleExecute($query);
        return $this->db->fetchAll($req);
    }

    /**
     * Retrieves all fulfillments for a specific exercise.
     * 
     * @param int $exerciseId The ID of the exercise.
     * 
     * @return array An array of fulfillments associated with the given exercise.
     */
    public function getFulfillmentsByExerciseId($exerciseId)
    {

        $query = "SELECT * FROM fulfillments WHERE id_exercises = :id_exercises ORDER BY created_at";

        $binds = [
            'id_exercises' => ['value' => $exerciseId, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);


        return $this->db->fetchAll($req);
    }

    /**
     * Retrieves a specific fulfillment by its ID.
     * 
     * @param int $id The ID of the fulfillment.
     * 
     * @return array The fulfillment record with the specified ID.
     */
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

    /**
     * Deletes a fulfillment record from the database by its ID.
     * 
     * @param int $idFulfillment The ID of the fulfillment to delete.
     * 
     * @return bool|string True on success, or an error message if the deletion fails.
     */
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
