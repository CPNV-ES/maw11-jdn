<?php

/**
 * @file ExerciseModel.php
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 19.12.2024
 * @description Model class for managing operations related to the `exercises` table in the database.
 *
 * @details Provides methods for creating, retrieving, updating, and deleting exercises in the database.
 */

require_once APP_DIR . '/core/Model.php';

class ExerciseModel extends Model
{
    /**
     * ID of the exercise
     * @var int|null
     */
    public $id = null;

    /**
     * Retrieves all exercises from the `exercises` table.
     * 
     * @return array|false An array of associative arrays representing all exercises
     *                     in the table, or false if the operation fails.
     */
    public function getAll()
    {
        $query = "SELECT * FROM exercises";

        try {
            $req = $this->db->querySimpleExecute($query);
            return $this->db->fetchAll($req);
        } catch (PDOException $e) {
            error_log('Get all exercises failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves a single exercise by its ID.
     * 
     * @param int $id The ID of the exercise to retrieve.
     * 
     * @return array|false An associative array representing the exercise, or false if not found or failed.
     */
    public function getOne($id)
    {
        $query = "SELECT * FROM exercises where id_exercises = :id_exercises";

        $binds = [
            'id_exercises' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];

        try {
            $req = $this->db->queryPrepareExecute($query, $binds);
            $exercise = $this->db->fetch($req);

            return $exercise;
        } catch (PDOException $e) {
            error_log('Get one exercise failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Creates a new exercise in the `exercises` table.
     * The exercise's status is set to 1, indicating that it is in editing.
     * 
     * @param string $title The title of the exercise.
     * 
     * @return int The ID of the newly created exercise or false if the operation fails.
     */
    public function create($title)
    {
        $query = "INSERT INTO exercises (title, id_status) VALUES (:title,:id_status)";

        $binds = [
            'title' => ['value' => $title, 'type' => PDO::PARAM_STR],
            'id_status' => ['value' => 1, 'type' => PDO::PARAM_INT]
        ];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            $this->id = $this->db->lastInsertId();
            return $this->id ?? false;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes an exercise from the `exercises` table by its ID.
     * 
     * @param int $id The ID of the exercise to delete.
     * 
     * @return bool True on success, or false if the operation fails.
     */
    public function delete($id)
    {
        $query = "DELETE FROM exercises WHERE id_exercises = :id;";

        $binds = ['id' => ['value' => $id, 'type' => PDO::PARAM_INT]];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            return true;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Updates a field of an existing exercise in the `exercises` table.
     * 
     * @param int $id The ID of the exercise to update.
     * @param string $field The name of the field to update.
     * @param mixed $newValue The new value to set for the specified field.
     * 
     * @return bool True on success, or false if the operation fails.
     */
    public function update($id, $field, $newValue)
    {
        $query = "UPDATE exercises SET $field = :value WHERE id_exercises = :id;";

        $binds = [
            'id' => ['value' => $id, 'type' => PDO::PARAM_INT],
            'value' => ['value' => $newValue, 'type' => PDO::PARAM_STR]
        ];

        try {
            $this->db->queryPrepareExecute($query, $binds);
            return true;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return false;
        }
    }
}
