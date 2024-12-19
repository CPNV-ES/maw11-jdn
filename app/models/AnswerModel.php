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
     * @return array The result of the insertion query as an associative array.
     */
    public function create($value, $idField, $idFulfillments)
    {
        $query = "INSERT INTO answers (value, id_fields, id_fulfillments) VALUES (:value,:id_fields, :id_fulfillments)";

        $binds = [
            'value' => ['value' => $value, 'type' => PDO::PARAM_STR],
            'id_fields' => ['value' => $idField, 'type' => PDO::PARAM_INT],
            'id_fulfillments' => ['value' => $idFulfillments, 'type' => PDO::PARAM_INT],
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $fields = $this->db->fetchAll($req);

        return $fields;
    }

    /**
     * Updates an existing record in the `answers` table.
     * 
     * @param string $value The new value of the answer.
     * @param int $idField The ID of the associated field.
     * @param int $idFulfillments The ID of the associated fulfillment.
     * 
     * @return array The result of the update query as an associative array.
     */
    public function update($value, $idField, $idFulfillments)
    {
        $query = "UPDATE answers SET value = :value WHERE id_fields = :id_fields AND id_fulfillments = :id_fulfillments ;";

        $binds = [
            'value' => ['value' => $value, 'type' => PDO::PARAM_STR],
            'id_fields' => ['value' => $idField, 'type' => PDO::PARAM_INT],
            'id_fulfillments' => ['value' => $idFulfillments, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $fields = $this->db->fetchAll($req);

        return $fields;
    }

    /**
     * Retrieves all records from the `answers` table.
     * 
     * @return array An array of associative arrays representing all rows in the `answers` table.
     */
    public function getAllAnswers()
    {

        $query = "SELECT * FROM answers";

        $req = $this->db->querySimpleExecute($query);

        return $this->db->fetchAll($req);
    }

    /**
     * Retrieves all answers associated with a specific fulfillment ID.
     * 
     * @param int $idFulfillment The ID of the fulfillment.
     * 
     * @return array An array of associative arrays representing the filtered rows.
     */
    public function getAnswersFromIdFulfillment($idFulfillment)
    {

        $query = "SELECT * FROM answers WHERE id_fulfillments = :id_fulfillments ORDER BY id_fulfillments";

        $binds = [
            'id_fulfillments' => ['value' => $idFulfillment, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);

        return $this->db->fetchAll($req);
    }
}
