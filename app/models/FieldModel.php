<?php
require_once APP_DIR . '/core/Model.php';

/**
 * FieldModel class
 */
class FieldModel extends Model
{
    /**
     * Id of the field
     * @var 
     */
    public $id = null;

    public function getFieldsFromExercise($exerciseId)
    {
        $query = "SELECT * FROM fields WHERE id_exercises = :id_exercises";

        $binds = [
            'id_exercises' => ['value' => $exerciseId, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $fields = $this->db->fetchAll($req);

        return $fields;
    }
  
    public function getOne($fieldId)
    {
        $query = "SELECT * FROM fields WHERE id_fields = :id_fields";

        $binds = [
            'id_fields' => ['value' => $fieldId, 'type' => PDO::PARAM_INT]
        ];

        $req = $this->db->queryPrepareExecute($query, $binds);
        $field = $this->db->fetchAll($req);

        return $field;
    }

    public function delete($fieldId)
    {

        $query = "DELETE FROM fields WHERE id_fields = :id_fields;";

        $binds = ['id_fields' => ['value' => $fieldId, 'type' => PDO::PARAM_INT]];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            return true;
        } catch (PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }

    public function create($label, $exerciseId, $typeFieldID)
    {
        $query = "INSERT INTO fields (label, id_exercises,id_fields_type) VALUES (:label,:id_exercises,:id_fields_type)";

        $binds = [
            'label' => ['value' => $label, 'type' => PDO::PARAM_STR],
            'id_exercises' => ['value' => $exerciseId, 'type' => PDO::PARAM_INT],
            'id_fields_type' => ['value' => $typeFieldID, 'type' => PDO::PARAM_INT]
        ];

        $response = null;

        try {
            $this->db->queryPrepareExecute($query, $binds);
            $response = $this->db->lastInsertId();
        } catch (PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }

        $this->id = is_string($response) ? $response : null;

        return $this->id ?? false;
    }
}
