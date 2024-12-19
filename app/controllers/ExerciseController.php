<?php

/**
 * @file ExerciseController.php
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 19.12.2024
 * @description Controller logic for managing exercises, fields, fulfillments and answers in the application.
 * 
 * @details This file handles the CRUD operations for exercise, fields, fullfillments and answers entities,
 *          including validation, data manipulation, and response formatting.
 */

require_once APP_DIR . '/core/Controller.php';
require_once MODEL_DIR . '/ExerciseModel.php';
require_once MODEL_DIR . '/FieldModel.php';
require_once MODEL_DIR . '/AnswerModel.php';
require_once MODEL_DIR . '/FulfillmentModel.php';

const SINGLE_LINE_TYPE = 1;
const EXERCISE_ID = 1;
const FIELD_ID = 2;
const FULFILLMENT_ID = 2;
const STATUS_EXERCISE = 2;

/**
 * Class ExerciseController
 *
 * ---------------------------------------------------------------------------
 * This class handles all backend operations related to exercises, including
 * creation, updating, deletion, as well as managing answers and associated
 * fulfillments. It is responsible for interacting with the data model to perform
 * these actions and redirecting to the appropriate views to display the results.
 *
 * The methods in this class cover a wide range of functionalities:
 * - Creation and management of exercises, fields, answers, and fulfillments.
 * - Updating exercises, fields, and answers based on incoming requests.
 * - Retrieving necessary information to display pages with relevant data
 *   (for example, answers related to an exercise).
 * - Handling HTTP requests, including rendering the page via the `renderer`
 *   function, which analyzes the URL and performs the associated action.
 *
 * In summary, this class acts as a link between the business logic (managing exercises,
 * answers, etc.) and the view, enabling dynamic page rendering based on the actions
 * performed by the user.
 */
class ExerciseController extends Controller
{
    /******************************************************************************
     * Page Rendering Function (renderer)
     * ---------------------------------------------------------------------------
     * This function handles the rendering of pages based on HTTP requests (GET/POST)
     * and the various URLs requested. It is responsible for processing form data,
     * updating or creating records in the database, and rendering the appropriate
     * views for each action.
     *
     * The logic of this function is based on analyzing the URL (request_uri) and
     * executing corresponding actions such as creating, updating, or deleting exercises,
     * fields, answers, and fulfillments. Based on the URL, it routes to the correct controller
     * or view.
     *
     * @param string $request_uri The URL of the request used to determine
     *                            the action to perform and the view to display.
     * 
     * This function handles the following cases:
     * - Creation, update, deletion, and display of exercises, fields, and fulfillments.
     * - Rendering pages to display exercises, their answers, and results.
     * - Redirection and error 404 handling for invalid URLs.
     *****************************************************************************/
    public function renderer($request_uri)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($request_uri) {
                case '/exercises':
                    $this->createExercise();
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/(\d+)\/edit/', $request_uri, $matches) ? true : false):
                    $this->updateFulfillments($matches[FULFILLMENT_ID]);
                    foreach ($_POST as $answer) {
                        if ($answer != $_POST['updated_at']) {
                            $this->updateAnswer($answer['0'], $answer['1'], $matches[FULFILLMENT_ID]);
                        }
                    }
                    $exercise = $this->getOneExercise($matches[EXERCISE_ID]);
                    $fields = $this->getFields($exercise['id_exercises']);
                    $answers = $this->getAnswersFromIdFulfillment($matches[FULFILLMENT_ID]);
                    $exerciseAnswer = $this->getFulfillmentById($matches[FULFILLMENT_ID]);

                    require_once VIEW_DIR . '/home/fulfill-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments/', $request_uri, $matches) ? true : false):
                    $this->createFulfillments($matches[EXERCISE_ID]);
                    $exerciseAnswer = $this->getLastFulfillment();
                    foreach ($_POST as $answer) {
                        if ($answer != $_POST['created_at']) {
                            $this->createAnswer($answer['0'], $answer['1'], $exerciseAnswer['id_fulfillments']);
                        }
                    }
                    $_SESSION['state'] = 'edit';
                    $exercise = $this->getOneExercise($matches[EXERCISE_ID]);
                    $fields = $this->getFields($exercise['id_exercises']);
                    $answers = $this->getAnswersFromIdFulfillment($exerciseAnswer['id_fulfillments']);
                    $url = "/exercises/" . $matches[EXERCISE_ID] . "/fulfillments/" . $exerciseAnswer['id_fulfillments'] . "/edit";

                    header("Location: $url");
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fields\/(\d+)\/update/', $request_uri, $matches) ? true : false):
                    $this->updateFields($matches[FIELD_ID], 'label', $_POST['field_label']);
                    $this->updateFields($matches[FIELD_ID], 'id_fields_type', $_POST['field_type']);
                    $exercise = $this->getOneExercise($matches[EXERCISE_ID]);
                    $fields = $this->getFields($matches[EXERCISE_ID]);

                    require_once VIEW_DIR . '/home/create-field.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fields/', $request_uri, $matches) ? true : false):
                    $this->createField($matches[EXERCISE_ID]);
                    $exercise = $this->getOneExercise($matches[EXERCISE_ID]);
                    $fields = $this->getFields($matches[EXERCISE_ID]);

                    require_once VIEW_DIR . '/home/create-field.php';
                    exit();

                default:
                    header("HTTP/1.0 404 Not Found");
                    echo "Page not found";
                    exit();
            }
        } else {
            if (preg_match('/^\/exercises\/(\d+)\/fields/', $request_uri, $matches)) {
                if (preg_match('/\/exercises\/(\d+)\/fields\/(\d+)/', $request_uri)) {
                    if (preg_match('/\/exercises\/(\d+)\/fields\/(\d+)\/edit/', $request_uri, $matches)) {
                        $exercise = $this->getOneExercise($matches[EXERCISE_ID]);
                        $field = $this->getOneField($matches[FIELD_ID]);

                        require_once VIEW_DIR . '/home/edit-field-page.php';
                        exit();
                    } elseif (preg_match('/\/exercises\/(\d+)\/fields\/(\d+)\/destroy/', $request_uri, $matches)) {
                        $this->deleteField($matches[FIELD_ID]);
                        $exercise = $this->getOneExercise($matches[EXERCISE_ID]);
                        $fields = $this->getFields($matches[EXERCISE_ID]);

                        require_once VIEW_DIR . '/home/create-field.php';
                        exit();
                    }
                }
                $request_uri = '/exercises/fields';
            } elseif (
                preg_match("/^\/exercises\/(\d+)\/(delete|update\/answering|update\/closed)$/", $request_uri, $matches)
            ) {
                $request_uri = '/exercises';

                if ($matches[STATUS_EXERCISE] === 'delete') {
                    if (!$this->deleteExercise($matches[EXERCISE_ID])) {
                        header("HTTP/1.0 404 Not Found");
                    }
                } elseif ($matches[STATUS_EXERCISE] === 'update/answering') {
                    $fields = $this->getFields($matches[EXERCISE_ID]);
                    if ($this->getFields($matches[EXERCISE_ID]) != null) {
                        $this->updateExercise($matches[EXERCISE_ID], 2);
                    } else {
                        $url = "/exercises/{$matches[EXERCISE_ID]}/fields/";
                        header("Location: $url");
                        exit();
                    }
                } elseif ($matches[STATUS_EXERCISE] === 'update/closed') {
                    $this->updateExercise($matches[EXERCISE_ID], 3);
                }
            }

            switch ($request_uri) {
                case '/exercises':
                    $exercises = $this->getExercises();
                    require_once VIEW_DIR . '/home/manage-exercise.php';
                    exit();

                case '/exercises/new':
                    require_once VIEW_DIR . '/home/create-exercise.php';
                    exit();

                case '/exercises/fields':
                    $exercise = $this->getOneExercise($matches[EXERCISE_ID]);
                    $fields = $this->getFields($matches[EXERCISE_ID]);

                    require_once VIEW_DIR . '/home/create-field.php';
                    exit();

                case '/exercises/answering':
                    $exercises = $this->getExercises();

                    require_once VIEW_DIR . '/home/take-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/results\/(\d+)/', $request_uri, $matches) ? true : false):
                    $exercise = $this->getOneExercise($matches[EXERCISE_ID]);
                    $field = $this->getOneField($matches[FIELD_ID]);
                    $fulfillments = $this->getFulfillmentsByExerciseId($matches[EXERCISE_ID]);
                    $answers = $this->getAnswersFromFulfillment($fulfillments, $field);

                    require_once VIEW_DIR . '/home/result-field.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/results/', $request_uri, $matches) ? true : false):

                    $exercise = $this->getOneExercise(id: $matches[EXERCISE_ID]);
                    $fields = $this->getFields($matches[EXERCISE_ID]);
                    $fulfillments = $this->getFulfillmentsByExerciseId($matches[EXERCISE_ID]);
                    $answers = $this->getIconAnswersFromFulfillment($fulfillments, $fields);
                    $createdAtWhidId = $this->getCreatedAtWithIdFulfillments($fulfillments);

                    require_once VIEW_DIR . '/home/result-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/new/', $request_uri, $matches) ? true : false):
                    $_SESSION['state'] = 'new';
                    $exercise = $this->getOneExercise($matches[EXERCISE_ID]);
                    $fields = $this->getFields($matches[EXERCISE_ID]);

                    require_once VIEW_DIR . '/home/fulfill-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/(\d+)\/destroy/', $request_uri, $matches) ? true : false):
                    $this->deleteFulfillment($matches[FULFILLMENT_ID]);

                    header("Location: /");
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/(\d+)\/edit/', $request_uri, $matches) ? true : false):
                    $_SESSION['state'] = 'edit';
                    $exercise = $this->getOneExercise($matches[EXERCISE_ID]);
                    $fields = $this->getFields($exercise['id_exercises']);
                    $answers = $this->getAnswersFromIdFulfillment($matches[FULFILLMENT_ID]);
                    $exerciseAnswer = $this->getFulfillmentById($matches[FULFILLMENT_ID]);

                    require_once VIEW_DIR . '/home/fulfill-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/(\d+)/', $request_uri, $matches) ? true : false):
                    $exercise = $this->getOneExercise($matches[EXERCISE_ID]);
                    $fields = $this->getFields($matches[EXERCISE_ID]);
                    $fulfillment = array("0" => $this->getFulfillmentById($matches[FULFILLMENT_ID]));
                    $answers = $this->getAnswersFromIdFulfillment($matches[FULFILLMENT_ID]);

                    require_once VIEW_DIR . '/home/response-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments/', $request_uri, $matches) ? true : false):
                    $exercise = $this->getOneExercise(id: $matches[EXERCISE_ID]);
                    $fields = $this->getFields($matches[EXERCISE_ID]);
                    $fulfillments = $this->getFulfillmentsByExerciseId($matches[EXERCISE_ID]);
                    $answers = $this->getIconAnswersFromFulfillment($fulfillments, $fields);
                    $createdAtWhidId = $this->getCreatedAtWithIdFulfillments($fulfillments);

                    if (!$exercise) {
                        header("Location: /failtofind");
                    }

                    require_once VIEW_DIR . '/home/all-fulfill.php';
                    exit();
                default:
                    header("HTTP/1.0 404 Not Found");
                    echo "Page not found";
                    exit();
            }
        }
    }

    /******************************************************************************
     * Exercise Management Functions
     * ---------------------------------------------------------------------------
     * This section contains all the functions and logic related to handling
     * exercises, including CRUD operations, validations, and more.
     *****************************************************************************/

    /**
     * Handles the creation of a new exercise.
     *
     * This method retrieves the exercise title from the POST request,
     * creates a new exercise record using the ExerciseModel,
     * and redirects the user based on the success or failure of the operation.
     *
     * @return void
     */
    public function createExercise()
    {
        $title = $_POST['exercises_title'];
        $exercise = new ExerciseModel();
        $response = $exercise->create($title);

        if (!$response) {
            header('Location: /exercises/new');
            return;
        }

        header("Location: /exercises/$exercise->id/fields");
    }

    /**
     * Updates the status of an existing exercise.
     *
     * This method updates the `id_status` field of an exercise identified
     * by its ID with a new status value. If the update operation fails,
     * the user is redirected to the homepage. On success, the user is 
     * redirected to the exercises list page.
     *
     * @param int $id The ID of the exercise to update.
     * @param mixed $newStatus The new status value to assign to the exercise.
     * @return void
     */
    public function updateExercise($id, $newStatus)
    {
        $exercise = new ExerciseModel();
        $response = $exercise->update($id, 'id_status', $newStatus);

        if (!$response) {
            header('Location: /');
            return;
        }

        header('Location: /exercises');
    }

    /**
     * Deletes an exercise by its ID.
     *
     * This method iterates through all exercises to find a match with the given ID.
     * If a match is found, the exercise is deleted, and the user is redirected to 
     * the exercises list page. If no matching exercise is found, the method returns false.
     *
     * @param int $id The ID of the exercise to delete.
     * @return bool Returns true if the exercise was successfully deleted, false otherwise.
     */
    public function deleteExercise($id)
    {
        $exerciseModel = new ExerciseModel();

        $exercises = $exerciseModel->getAll();

        foreach ($exercises as $exercise) {
            if ($exercise['id_exercises'] == $id) {
                $response = $exerciseModel->delete($id);
                header('Location: /exercises');
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieves a single exercise by its ID.
     *
     * This method fetches the details of an exercise identified by the given ID.
     * It is typically used to display or manipulate the details of a specific exercise.
     *
     * @param int $id The ID of the exercise to retrieve.
     * @return array|null Returns an associative array of the exercise data if found,
     *                    or null if no exercise matches the given ID.
     */
    public function getOneExercise($id)
    {
        $exerciseModel = new ExerciseModel();
        $exercise = $exerciseModel->getOne($id);

        return $exercise;
    }

    /**
     * Retrieves all exercises.
     *
     * This method fetches all exercises from the database using the ExerciseModel.
     * It is useful for displaying a list of all available exercises.
     *
     * @return array An array of associative arrays, where each array represents an exercise.
     */
    public function getExercises()
    {
        $exerciseModel = new ExerciseModel();
        $exercise = $exerciseModel->getAll();

        return $exercise;
    }

    /******************************************************************************
     * Field Management Functions
     * ---------------------------------------------------------------------------
     * This section contains all the functions and logic related to handling
     * exercises, including CRUD operations, validations, and more.
     *****************************************************************************/

    /**
     * Creates a new field for a specific exercise.
     *
     * This method retrieves the field label and type from the POST request,
     * creates a new field associated with the given exercise ID using the FieldModel,
     * and redirects the user to the fields management page for the exercise.
     *
     * @param int $exerciseId The ID of the exercise to associate the field with.
     * @return void
     */
    public function createField($exerciseId)
    {
        $label = $_POST['field_label'];
        $type = $_POST['field_type'];
        $field = new FieldModel();
        $field->create($label, $exerciseId, $type);

        header("Location: /exercises/$exerciseId/fields");
    }

    /**
     * Updates a field with new data.
     *
     * This method updates a specific field identified by its ID with new data.
     * The update is handled by the FieldModel. If the operation fails, the user
     * is redirected to the homepage.
     *
     * @param int $idField The ID of the field to update.
     * @param string $field The name of the field to update (e.g., 'label', 'type').
     * @param mixed $newData The new data to assign to the field.
     * @return void
     */
    public function updateFields($idField, $field, $newData)
    {
        $fieldModel = new FieldModel();
        $response = $fieldModel->update($idField, $field, $newData);

        if (!$response) {
            header('Location: /');
            return;
        }
    }

    /**
     * Deletes a field by its ID.
     *
     * This method removes a field identified by its ID using the FieldModel.
     * If the deletion operation is successful, the method returns true. Otherwise,
     * it returns false.
     *
     * @param int $id The ID of the field to delete.
     * @return bool True if the field was successfully deleted, false otherwise.
     */
    public function deleteField($id)
    {
        $fieldModel = new FieldModel();
        $fieldModel->getOne($id);
        $response = $fieldModel->delete($id);

        return $response;
    }

    /**
     * Retrieves all fields for a specific exercise.
     *
     * This static method fetches all fields associated with the given exercise ID.
     * It is useful for displaying or manipulating fields tied to a specific exercise.
     *
     * @param int $exerciseId The ID of the exercise to retrieve fields for.
     * @return array An array of associative arrays, where each array represents a field.
     */
    public static function getFields($exerciseId)
    {
        $fieldModel = new FieldModel();
        $field = $fieldModel->getFieldsFromExercise($exerciseId);

        return $field;
    }

    /**
     * Retrieves a single field by its ID.
     *
     * This method fetches the details of a specific field identified by its ID
     * using the FieldModel.
     *
     * @param int $fieldId The ID of the field to retrieve.
     * @return array|null Returns an associative array of the field data if found,
     *                    or null if no field matches the given ID.
     */
    public function getOneField($fieldId)
    {
        $fieldModel = new FieldModel();

        return $fieldModel->getOne($fieldId);;
    }

    /******************************************************************************
     * Answer Management Functions
     * ---------------------------------------------------------------------------
     * This section contains all the functions related to the management of answers,
     * including creation, retrieval, updating, and deletion of answers associated 
     * with exercises or fields.
     *****************************************************************************/

    /**
     * Creates a new answer for a specific field and exercise.
     *
     * This method uses the AnswerModel to create an answer associated with 
     * a field and an exercise response (fulfillment). The created answer 
     * is returned as an AnswerModel instance.
     *
     * @param int $idfield The ID of the field associated with the answer.
     * @param mixed $value The value of the answer.
     * @param int $idexerciseAnswer The ID of the exercise response associated with the answer.
     * @return AnswerModel The created AnswerModel instance.
     */
    public function createAnswer($idfield, $value, $idexerciseAnswer)
    {
        $answer = new AnswerModel();
        $answer->create($value, $idfield, $idexerciseAnswer);

        return $answer;
    }

    /**
     * Updates an existing answer.
     *
     * This method uses the AnswerModel to update the value of an answer
     * associated with a specific field and exercise response (fulfillment).
     *
     * @param int $idfield The ID of the field associated with the answer.
     * @param mixed $value The new value to update the answer with.
     * @param int $idFulfillments The ID of the exercise response associated with the answer.
     * @return AnswerModel The updated AnswerModel instance.
     */
    public function updateAnswer($idfield, $value, $idFulfillments)
    {
        $answer = new AnswerModel();
        $answer->update($value, $idfield, $idFulfillments);

        return $answer;
    }

    /**
     * Retrieves all answers.
     *
     * This method fetches all answers from the database using the AnswerModel.
     *
     * @return array An array of all answers as associative arrays.
     */
    public function getAnswers()
    {
        $answerModel = new AnswerModel();
        $answers = $answerModel->getAllAnswers();

        return $answers;
    }

    /**
     * Retrieves answers based on a fulfillment ID.
     *
     * This method fetches answers linked to a specific fulfillment ID 
     * using the AnswerModel.
     *
     * @param int $idfulfillment The ID of the fulfillment to retrieve answers for.
     * @return array An array of answers associated with the given fulfillment ID.
     */
    public function getAnswersFromIdFulfillment($idfulfillment)
    {
        $answerModel = new AnswerModel();
        $answers = $answerModel->getAnswersFromIdFulfillment($idfulfillment);

        return $answers;
    }

    /**
     * Retrieves answers organized by fulfillment timestamps.
     *
     * This method associates answers with their respective `created_at` timestamps 
     * for a list of fulfillments and fields. It initializes a table structure where 
     * each timestamp is a row, and fields are columns.
     *
     * @param array $fulfillments An array of fulfillments containing `created_at` timestamps.
     * @param array $field The field data to associate with answers.
     * @return array A data table where keys are timestamps, and values are answers for fields.
     */
    public function getAnswersFromFulfillment($fulfillments, $field)
    {
        $answers = $this->getAnswers();
        $data = [];

        //Init all column with same lenght
        foreach ($fulfillments as $fulfillment) {
            $data[$fulfillment['created_at']] = '';
        }
        //Link answers to correct created_at
        foreach ($fulfillments as $fulfillment) {
            foreach ($answers as $answer) {
                if ($answer['id_fulfillments'] == $fulfillment['id_fulfillments']) {
                    if ($field['id_fields'] == $answer['id_fields']) {
                        $data[$fulfillment['created_at']] = $answer['value'];
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Generates a table of answer icons based on field types and responses.
     *
     * This method creates a table where each cell contains an icon that 
     * represents the state of an answer. Icons include crosses, single checks, 
     * or double checks based on the response and field type.
     *
     * @param array $fulfillments An array of fulfillments containing `created_at` timestamps.
     * @param array $fields An array of fields to associate with answers.
     * @return string[][] A two-dimensional array where rows represent timestamps, 
     *                    columns represent fields, and values are icon class strings.
     */
    public function getIconAnswersFromFulfillment($fulfillments, $fields)
    {
        $data = [];

        foreach ($fulfillments as $fulfillment) {
            //Init all column with same lenght
            foreach ($fields as $field) {
                $data[$fulfillment['created_at']][$field['id_fields']] = "fa fa-x XIcon";
            }
        }

        $answers = $this->getAnswers();

        //Adding data to the table of icons corresponding to the type of field and response
        foreach ($fulfillments as $fulfillment) {
            foreach ($fields as $field) {
                foreach ($answers as $answer) {
                    if ($field['id_fields'] == $answer['id_fields']) {
                        if ($fulfillment['id_fulfillments'] == $answer['id_fulfillments']) {
                            if ($answer['value'] != null) {
                                //Simple line field type
                                if ($field['id_fields_type'] === 'SINGLE_LINE_TYPE') {

                                    $data[$fulfillment['created_at']][$field['id_fields']] = "fa-solid fa-check VIcon";
                                } else {
                                    //Differentiate simple or double line answer.
                                    if (preg_match("/.+\n.+/", $answer['value'])) {

                                        $data[$fulfillment['created_at']][$field['id_fields']] =  'fa-solid fa-check-double VIcon';
                                    } else {
                                        $data[$fulfillment['created_at']][$field['id_fields']] = 'fa-solid fa-check VIcon';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    /******************************************************************************
     * Fulfillment Management Functions
     * ---------------------------------------------------------------------------
     * This section contains all functions related to managing fulfillments, 
     * including creation, updating, retrieval, and deletion of fulfillments 
     * associated with exercises. These functions manage the complete responses 
     * for each exercise.
     *****************************************************************************/

    /**
     * Creates a new fulfillment for a specific exercise.
     *
     * This method generates a new fulfillment entry in the database using the current timestamp 
     * and the provided exercise ID.
     *
     * @param int $idExercise The ID of the exercise associated with the fulfillment.
     * @return void
     */
    public function createFulfillments($idExercise)
    {
        $fulfillmentsModel = new FulfillmentModel();
        $date = date("Y-m-d H:i:s");
        $fulfillmentsModel->create($date, $idExercise);
    }

    /**
     * Updates the timestamp of an existing fulfillment.
     *
     * This method updates the `updated_at` timestamp of a fulfillment to the current date and time.
     *
     * @param int $idFulfillments The ID of the fulfillment to update.
     * @return void
     */
    public function updateFulfillments($idFulfillments)
    {
        $fulfillmentsModel = new FulfillmentModel();
        $date = date("Y-m-d H:i:s");
        $fulfillmentsModel->update($date, $idFulfillments);
    }

    /**
     * Retrieves the most recently created fulfillment.
     *
     * This method fetches the last fulfillment entry from the database.
     *
     * @return array The most recently created fulfillment as an associative array.
     */
    public function getLastFulfillment()
    {
        $fulfillmentModel = new FulfillmentModel();
        $fulffilment = $fulfillmentModel->getLast();

        return $fulffilment['0'];
    }

    /**
     * Retrieves all fulfillments for a specific exercise.
     *
     * This method fetches all fulfillments associated with the given exercise ID.
     *
     * @param int $exerciseId The ID of the exercise to retrieve fulfillments for.
     * @return array An array of fulfillments associated with the specified exercise.
     */
    public function getFulfillmentsByExerciseId($exerciseId)
    {
        $fulfillmentModel = new FulfillmentModel();
        $fulfillments = $fulfillmentModel->getFulfillmentsByExerciseId($exerciseId);

        return $fulfillments;
    }

    /**
     * Maps fulfillment timestamps to their corresponding IDs.
     *
     * This method creates an associative array where the keys are the `created_at` timestamps 
     * and the values are the corresponding fulfillment IDs.
     *
     * @param array $fulfillments An array of fulfillments with `created_at` and `id_fulfillments` fields.
     * @return array An associative array mapping timestamps to fulfillment IDs.
     */
    public function getCreatedAtWithIdFulfillments($fulfillments)
    {
        $createdAtWithId = [];

        foreach ($fulfillments as $fulfillment) {

            $createdAtWithId[$fulfillment['created_at']] = $fulfillment['id_fulfillments'];
        }

        return $createdAtWithId;
    }

    /**
     * Retrieves a specific fulfillment by its ID.
     *
     * This method fetches a single fulfillment entry from the database using the given ID.
     *
     * @param int $idFulfillments The ID of the fulfillment to retrieve.
     * @return array The fulfillment data as an associative array.
     */
    public function getFulfillmentById($idFulfillments)
    {
        $fulfillmentModel = new FulfillmentModel();
        $fulffilment = $fulfillmentModel->getOne($idFulfillments);

        return $fulffilment['0'];
    }

    /**
     * Deletes a fulfillment by its ID.
     *
     * This method removes a fulfillment from the database using the given ID.
     *
     * @param int $idFulfillment The ID of the fulfillment to delete.
     * @return void
     */
    public function deleteFulfillment($idFulfillment)
    {
        $fulfillmentModel = new FulfillmentModel();
        $fulfillmentModel->delete($idFulfillment);
    }
}
