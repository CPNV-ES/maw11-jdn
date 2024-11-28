<?php
require_once APP_DIR . '/core/Model.php';

/**
 * FieldModel class
 */
class AnswerModel extends Model
{
    public function create($value, $idfield, $idfulfill)
    {
        $query = "INSERT INTO answers (value, id_fields, id_fulfillments) VALUES (:value,:id_fields, :id_fulfillments)";

        $binds = [
            'value' => ['value' => $value, 'type' => PDO::PARAM_INT],
            'id_fields' => ['value' => $idfield, 'type' => PDO::PARAM_INT],
            'id_fulfillments' => ['value' => $idfulfill, 'type' => PDO::PARAM_INT],
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $fields = $this->db->fetchAll($req);

        return $fields;
    }

    public function update($value, $idfield)
    {
        $query = "UPDATE answers SET value = :value WHERE id_fields = :id_fields;";

        $binds = [
            'value' => ['value' => $value, 'type' => PDO::PARAM_INT],
            'id_fields' => ['value' => $idfield, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $fields = $this->db->fetchAll($req);

        return $fields;
    }

    public function getAnswerFrom($idfulfillments)
    {
        $query = "SELECT * FROM answers WHERE id_fulfillments = :id_fulfillments";

        $binds = [
            'id_fulfillments' => ['value' => $idfulfillments, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $answers = $this->db->fetchAll($req);

        return $answers;
    }
}
