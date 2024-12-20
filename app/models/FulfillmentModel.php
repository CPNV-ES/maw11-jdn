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
     * @return bool False on failure, true on success.
     */
    public function create($create, $idExercise)
    {
        $query = "INSERT INTO fulfillments (created_at, id_exercises) VALUES (:created_at,:id_exercises)";

        $binds = [
            'created_at' => ['value' => $create, 'type' => PDO::PARAM_STR],
            'id_exercises' => ['value' => $idExercise, 'type' => PDO::PARAM_INT]
        ];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            return true;
        } catch (PDOException $e) {
            error_log("Create failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Updates the `updated_at` field for a specific fulfillment.
     * 
     * @param string $update The new update date for the fulfillment.
     * @param int $id The ID of the fulfillment to update.
     * 
     * @return bool False on failure, true on success.
     */
    public function update($update, $id)
    {
        $query = "UPDATE fulfillments SET updated_at = :updated_at WHERE id_fulfillments = :id;";

        $binds = [
            'updated_at' => ['value' => $update, 'type' => PDO::PARAM_STR],
            'id' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            return true;
        } catch (PDOException $e) {
            error_log("Update failed: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Retrieves the last fulfillment record in the database.
     * 
     * @return array|false The last fulfillment record or false on failure.
     */
    public function getLast()
    {
        $query = "SELECT * FROM fulfillments ORDER BY id_fulfillments DESC LIMIT 1";

        try {
            $req = $this->db->querySimpleExecute($query);
            return $this->db->fetchAll($req);
        } catch (PDOException $e) {
            error_log("Get last fulfillment failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all fulfillments for a specific exercise.
     * 
     * @param int $exerciseId The ID of the exercise.
     * 
     * @return array|false An array of fulfillments or false on failure.
     */
    public function getFulfillmentsByExerciseId($exerciseId)
    {
        $query = "SELECT * FROM fulfillments WHERE id_exercises = :id_exercises ORDER BY created_at";

        $binds = [
            'id_exercises' => ['value' => $exerciseId, 'type' => PDO::PARAM_INT]
        ];

        try {
            $req = $this->db->queryPrepareExecute($query, $binds);
            return $this->db->fetchAll($req);
        } catch (PDOException $e) {
            error_log("Get fulfillments by exercise id failed: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Retrieves a specific fulfillment by its ID.
     * 
     * @param int $id The ID of the fulfillment.
     * 
     * @return array|false The fulfillment record or false on failure.
     */
    public function getOne($id)
    {
        $query = "SELECT * FROM fulfillments WHERE id_fulfillments = :id";

        $binds = [
            'id' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];

        try {
            $req = $this->db->queryPrepareExecute($query, $binds);
            return $this->db->fetchAll($req);
        } catch (PDOException $e) {
            error_log("Fulfillment not found: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes a fulfillment record from the database by its ID.
     * 
     * @param int $idFulfillment The ID of the fulfillment to delete.
     * 
     * @return bool False on failure, true on success.
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
            error_log("Delete failed: " . $e->getMessage());
            return false;
        }
    }
}
