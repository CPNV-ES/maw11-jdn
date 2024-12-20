<?php

/**
 * @file FieldModel.php
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 18.12.2024
 * @description Model class for managing operations related to the `fields` table in the database.
 *
 * @details Provides methods to create, retrieve, update, and delete fields for exercises.
 */

require_once APP_DIR . '/core/Model.php';

class FieldModel extends Model
{
    /**
     * ID of the field
     * @var int|null
     */
    public $id = null;

    /**
     * Retrieves all fields for a specific exercise.
     * 
     * @param int $exerciseId The ID of the exercise to retrieve fields for.
     * 
     * @return array|false An array of associative arrays representing the fields or false if the operation fails.
     */
    public function getFieldsFromExercise($exerciseId)
    {
        $query = "SELECT * FROM fields WHERE id_exercises = :id_exercises ORDER BY id_fields";

        $binds = [
            'id_exercises' => ['value' => $exerciseId, 'type' => PDO::PARAM_INT]
        ];

        try {
            $req = $this->db->queryPrepareExecute($query, $binds);
            return $this->db->fetchAll($req);
        } catch (PDOException $e) {
            error_log("Error fetching fields: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves a specific field by its ID.
     * 
     * @param int $fieldId The ID of the field to retrieve.
     * 
     * @return array|false An associative array representing the field or false if not found.
     */
    public function getOne($fieldId)
    {
        $query = "SELECT * FROM fields WHERE id_fields = :id_fields";

        $binds = [
            'id_fields' => ['value' => $fieldId, 'type' => PDO::PARAM_INT]
        ];

        try {
            $req = $this->db->queryPrepareExecute($query, $binds);
            return $this->db->fetch($req);
        } catch (PDOException $e) {
            error_log("Error fetching field: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes a field by its ID.
     * 
     * @param int $fieldId The ID of the field to delete.
     * 
     * @return bool False if the deletion fails.
     */
    public function delete($fieldId)
    {
        $query = "DELETE FROM fields WHERE id_fields = :id_fields;";

        $binds = ['id_fields' => ['value' => $fieldId, 'type' => PDO::PARAM_INT]];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting field: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Creates a new field for a specific exercise.
     * 
     * @param string $label The label of the field.
     * @param int $exerciseId The ID of the exercise to associate the field with.
     * @param int $typeFieldID The ID of the field's type.
     * 
     * @return int|false The ID of the newly created field or false if the creation fails.
     */
    public function create($label, $exerciseId, $typeFieldID)
    {
        $query = "INSERT INTO fields (label, id_exercises,id_fields_type) VALUES (:label,:id_exercises,:id_fields_type)";

        $binds = [
            'label' => ['value' => $label, 'type' => PDO::PARAM_STR],
            'id_exercises' => ['value' => $exerciseId, 'type' => PDO::PARAM_INT],
            'id_fields_type' => ['value' => $typeFieldID, 'type' => PDO::PARAM_INT]
        ];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            $this->id = $this->db->lastInsertId();
            return $this->id ?? false;
        } catch (PDOException $e) {
            error_log("Error creating field: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Updates a specific field's value in the `fields` table.
     * 
     * @param int $idField The ID of the field to update.
     * @param string $field The name of the field to update.
     * @param mixed $newData The new value for the specified field.
     * 
     * @return bool False if the update fails.
     */
    public function update($idField, $field, $newData)
    {
        $query = "UPDATE fields SET $field = :newData WHERE id_fields = :id_field;";

        $binds = [
            'newData' => ['value' => $newData, 'type' => PDO::PARAM_STR],
            'id_field' => ['value' => $idField, 'type' => PDO::PARAM_INT]
        ];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            return true;
        } catch (PDOException $e) {
            error_log("Error updating field: " . $e->getMessage());
            return false;
        }
    }
}
