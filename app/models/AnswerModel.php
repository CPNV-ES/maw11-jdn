<?php

/**
 * @file AnswerModel.php
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 19.12.2024
 * @description Model class for managing operations related to the `answers` table in the database.
 *
 * @details Provides methods to create, update, and retrieve data from the `answers` table.
 */

require_once APP_DIR . '/core/Model.php';

class AnswerModel extends Model
{
    /**
     * Inserts a new record into the `answers` table.
     * 
     * @param string $value The value of the answer.
     * @param int $idField The ID of the associated field.
     * @param int $idFulfillments The ID of the associated fulfillment.
     * 
     * @return bool True on success, or false if creation fails.
     */
    public function create($value, $idField, $idFulfillments)
    {
        $query = "INSERT INTO answers (value, id_fields, id_fulfillments) VALUES (:value,:id_fields, :id_fulfillments)";

        $binds = [
            'value' => ['value' => $value, 'type' => PDO::PARAM_STR],
            'id_fields' => ['value' => $idField, 'type' => PDO::PARAM_INT],
            'id_fulfillments' => ['value' => $idFulfillments, 'type' => PDO::PARAM_INT],
        ];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            return true;
        } catch (PDOException $e) {
            error_log('Create failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Updates an existing record in the `answers` table.
     * 
     * @param string $value The new value of the answer.
     * @param int $idField The ID of the associated field.
     * @param int $idFulfillments The ID of the associated fulfillment.
     * 
     * @return bool True on success, or false if update fails.
     */
    public function update($value, $idField, $idFulfillments)
    {
        $query = "UPDATE answers SET value = :value WHERE id_fields = :id_fields AND id_fulfillments = :id_fulfillments ;";

        $binds = [
            'value' => ['value' => $value, 'type' => PDO::PARAM_STR],
            'id_fields' => ['value' => $idField, 'type' => PDO::PARAM_INT],
            'id_fulfillments' => ['value' => $idFulfillments, 'type' => PDO::PARAM_INT]
        ];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            return true;
        } catch (PDOException $e) {
            error_log('Update failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all records from the `answers` table.
     * 
     * @return array|false An array of associative arrays representing all rows in the `answers`
     *                     table, or false if getting answers fails.
     */
    public function getAll()
    {
        $query = "SELECT * FROM answers";

        try {
            $req = $this->db->querySimpleExecute($query);
            return $this->db->fetchAll($req);
        } catch (PDOException $e) {
            error_log('Get all answers failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all answers associated with a specific fulfillment ID.
     * 
     * @param int $idFulfillment The ID of the fulfillment.
     * 
     * @return array|false An array of associative arrays representing the filtered rows, or false if getting answers fails.
     */
    public function getAnswersFromIdFulfillment($idFulfillment)
    {
        $query = "SELECT * FROM answers WHERE id_fulfillments = :id_fulfillments ORDER BY id_fulfillments";

        $binds = [
            'id_fulfillments' => ['value' => $idFulfillment, 'type' => PDO::PARAM_INT]
        ];

        try {
            $req = $this->db->queryPrepareExecute($query, $binds);
            return $this->db->fetchAll($req);
        } catch (PDOException $e) {
            error_log('Get answers from fulfillment id failed: ' . $e->getMessage());
            return false;
        }
    }
}
