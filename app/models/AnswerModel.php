<?php

/**
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 18.12.2024
 * @description This file is for the answer model
 */

require_once APP_DIR . '/core/Model.php';

/**
 * Class AnswerModel
 *
 * Manages all the operation made on the DB which affect the answers table
 */

class AnswerModel extends Model
{
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

    public function getAllAnswers()
    {

        $query = "SELECT * FROM answers";

        $req = $this->db->querySimpleExecute($query);

        return $this->db->fetchAll($req);
    }
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
