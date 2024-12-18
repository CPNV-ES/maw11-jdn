<?php

require_once APP_DIR . '/core/Controller.php';
require_once MODEL_DIR . '/ExerciseModel.php';
require_once MODEL_DIR . '/FieldModel.php';
require_once MODEL_DIR . '/AnswerModel.php';
require_once MODEL_DIR . '/FulfillmentModel.php';
const SINGLE_LINE_TYPE = 1;

class ExerciseController extends Controller
{
    /**
     * Summary of renderer
     * @param mixed $request_uri
     * @return never
     */
    public function renderer($request_uri)
    {
        // TODO Refactor this code
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($request_uri) {
                case '/exercises':
                    $this->createExercise();
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/(\d+)\/edit*/', $request_uri, $matches) ? true : false):
                    $this->updateFulfillments($matches[2]);
                    foreach ($_POST as $answer) {
                        if ($answer != $_POST['updated_at']) {
                            $this->updateAnswer($answer['0'], $answer['1'], $matches[2]);
                        }
                    }
                    $exercise = $this->getOneExercise($matches[1]);
                    $fields = $this->getFields($exercise['id_exercises']);
                    $answers = $this->getAnswers($matches[2]);
                    $exerciseAnswer = $this->getFulfillmentById($matches[2]);

                    require_once VIEW_DIR . '/home/fulfill-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments.*/', $request_uri, $matches) ? true : false):
                    $this->createFulfillments($matches[1]);
                    $exerciseAnswer = $this->getLastFulfillment();
                    foreach ($_POST as $answer) {
                        if ($answer != $_POST['created_at']) {
                            $this->createAnswer($answer['0'], $answer['1'], $exerciseAnswer['id_fulfillments']);
                        }
                    }
                    $_SESSION['state'] = 'edit';
                    $exercise = $this->getOneExercise($matches[1]);
                    $fields = $this->getFields($exercise['id_exercises']);
                    $answers = $this->getAnswers($exerciseAnswer['id_fulfillments']);
                    $url = "/exercises/" . $matches[1] . "/fulfillments/" . $exerciseAnswer['id_fulfillments'] . "/edit";

                    header("Location: $url");
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fields\/(\d+)\/update/', $request_uri, $matches) ? true : false):
                    $this->updateFields($matches[2], 'label', $_POST['field_label']);
                    $this->updateFields($matches[2], 'id_fields_type', $_POST['field_type']);

                    $exercise = $this->getOneExercise($matches[1]);
                    $fields = $this->getFields($matches[1]);

                    require_once VIEW_DIR . '/home/create-field.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fields/', $request_uri, $matches) ? true : false):
                    $this->createField($matches[1]);
                    $exercise = $this->getOneExercise($matches[1]);
                    $fields = $this->getFields($matches[1]);

                    require_once VIEW_DIR . '/home/create-field.php';
                    exit();

                default:
                    header("HTTP/1.0 404 Not Found");
                    echo "Page not found";
                    exit();
            }
        } else {
            if (preg_match('/^\/exercises\/(\d+)\/fields/', $request_uri, $matches)) {
                // TODO : find a way to avoid the resetting the first $matches 
                if (preg_match('/\/exercises\/(\d+)\/fields\/(\d+)/', $request_uri)) {
                    if (preg_match('/\/exercises\/(\d+)\/fields\/(\d+)\/edit/', $request_uri, $matches)) {
                        $exercise = $this->getOneExercise($matches[1]);
                        $field = $this->getOneField($matches[2]);
                        require_once VIEW_DIR . '/home/edit-field-page.php';
                        exit();
                    } elseif (preg_match('/\/exercises\/(\d+)\/fields\/(\d+)\/destroy/', $request_uri, $matches)) {
                        $this->deleteField($matches[2]);
                        $exercise = $this->getOneExercise($matches[1]);
                        $fields = $this->getFields($matches[1]);
                        require_once VIEW_DIR . '/home/create-field.php';
                        exit();
                    }
                }
                $request_uri = '/exercises/fields';
            } elseif (
                preg_match("/^\/exercises\/(\d+)\/(delete|update\/answering|update\/closed)$/", $request_uri, $matches)
            ) {
                $request_uri = '/exercises';

                if ($matches[2] === 'delete') {
                    if (!$this->deleteExercise($matches[1])) {
                        header("HTTP/1.0 404 Not Found");
                    }
                } elseif ($matches[2] === 'update/answering') {
                    //check if field empty
                    $fields = $this->getFields($matches[1]);
                    if ($this->getFields($matches[1]) != null) {
                        $this->updateExercise($matches[1], 2);
                    } else {
                        $url = "/exercises/{$matches[1]}/fields/";
                        header("Location: $url");
                        exit();
                    }
                } elseif ($matches[2] === 'update/closed') {
                    $this->updateExercise($matches[1], 3);
                }
            }

            switch ($request_uri) {
                case '/exercises':
                    $exercises = $this->getAllExercises();
                    require_once VIEW_DIR . '/home/manage-exercise.php';
                    exit();

                case '/exercises/new':
                    require_once VIEW_DIR . '/home/create-exercise.php';
                    exit();

                case '/exercises/fields':
                    $exercise = $this->getOneExercise($matches[1]);
                    $fields = $this->getFields($matches[1]);
                    require_once VIEW_DIR . '/home/create-field.php';
                    exit();

                case '/exercises/answering':
                    $exercises = $this->getAllExercises();
                    require_once VIEW_DIR . '/home/take-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/results\/(\d+)/', $request_uri, $matches) ? true : false):
                    $exercise = $this->getOneExercise($matches[1]);
                    $field = $this->getOneField($matches[2]);
                    $fulfillments = $this->getFulfillmentsByExerciseId($matches[1]);
                    $answers = $this->getAnswersFromFulfillment($fulfillments, $field);
                    require_once VIEW_DIR . '/home/result-field.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/results*/', $request_uri, $matches) ? true : false):
                    $exercise = $this->getOneExercise(id: $matches[1]);
                    $fields = $this->getFields($matches[1]);
                    $fulfillments = $this->getFulfillmentsByExerciseId($matches[1]);
                    $answers = $this->getIconAnswersFromFulfillment($fulfillments, $fields);
                    $createdAtWhidId = $this->getCreatedAtWithIdFulfillments($fulfillments);
                    require_once VIEW_DIR . '/home/result-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/new*/', $request_uri, $matches) ? true : false):
                    $_SESSION['state'] = 'new';
                    $exercise = $this->getOneExercise($matches[1]);
                    $fields = $this->getFields($matches[1]);
                    require_once VIEW_DIR . '/home/fulfill-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/(\d+)\/edit*/', $request_uri, $matches) ? true : false):
                    $_SESSION['state'] = 'edit';
                    $exercise = $this->getOneExercise($matches[1]);
                    $fields = $this->getFields($exercise['id_exercises']);
                    $answers = $this->getAnswers($matches[2]);
                    $exerciseAnswer = $this->getFulfillmentById($matches[2]);
                    require_once VIEW_DIR . '/home/fulfill-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/(\d+)/', $request_uri, $matches) ? true : false):
                    $exercise = $this->getOneExercise($matches[1]);
                    $fields = $this->getFields($matches[1]);
                    $fulfillment = $this->getFulfillmentsByExerciseId($matches[2]);
                    $answers = $this->getAnswersFromIdFulfillment($matches[2]);
                    require_once VIEW_DIR . '/home/response-exercise.php';
                    exit();

                default:
                    header("HTTP/1.0 404 Not Found");
                    echo "Page not found";
                    exit();
            }
        }
    }

    /******************************************************************************
     * Function Exercise Zone
     *****************************************************************************/

    /**
     * Summary of createExercise
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
     * Summary of updateExercise
     * @param mixed $id
     * @param mixed $newStatus
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
     * Summary of deleteExercise
     * @param mixed $id
     * @return bool
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
     * Summary of getOneExercise
     * @param mixed $id
     * @return mixed
     */
    public function getOneExercise($id)
    {
        $exerciseModel = new ExerciseModel();
        $exercise = $exerciseModel->getOne($id);

        return $exercise;
    }

    /**
     * Summary of getAllExercises
     * @return array
     */
    public function getAllExercises()
    {
        $exerciseModel = new ExerciseModel();
        $exercise = $exerciseModel->getAll();

        return $exercise;
    }

    /******************************************************************************
     * Function Fields Zone
     *****************************************************************************/

    /**
     * Summary of createField
     * @param mixed $exerciseId
     * @return void
     */
    public function createField($exerciseId)
    {
        $label = $_POST['field_label'];
        $type = $_POST['field_type'];
        $field = new FieldModel();
        $response = $field->create($label, $exerciseId, $type);

        header("Location: /exercises/$exerciseId/fields");
    }

    /**
     * Summary of updateFiels
     * @param mixed $idField
     * @param mixed $newLabel
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
     * Summary of deleteField
     * @param mixed $id
     * @return bool
     */

    public function deleteField($id)
    {
        $fieldModel = new FieldModel();

        $fieldModel->getOne($id);
        $response = $fieldModel->delete($id);
        return true;
    }

    /**
     * Summary of getFields
     * @param mixed $exerciseId
     * @return array
     */
    public static function getFields($exerciseId)
    {
        $fieldModel = new FieldModel();
        $field = $fieldModel->getFieldsFromExercise($exerciseId);

        return $field;
    }

    /**
     * Summary of getOneField
     * @param mixed $fieldId
     * @return mixed
     */
    public function getOneField($fieldId)
    {
        $fieldModel = new FieldModel();

        return $fieldModel->getOne($fieldId);;
    }

    /******************************************************************************
     * Function Answers Zone
     *****************************************************************************/

    /**
     * Summary of createAnswer
     * @param mixed $idfield
     * @param mixed $value
     * @param mixed $idexerciseAnswer
     * @return AnswerModel
     */
    public function createAnswer($idfield, $value, $idexerciseAnswer)
    {
        $answer = new AnswerModel();
        $answer->create($value, $idfield, $idexerciseAnswer);

        return $answer;
    }

    /**
     * Summary of updateAnswer
     * @param mixed $idfield
     * @param mixed $value
     * @param mixed $idFulfillments
     * @return AnswerModel
     */
    public function updateAnswer($idfield, $value, $idFulfillments)
    {
        $answer = new AnswerModel();
        $answer->update($value, $idfield, $idFulfillments);

        return $answer;
    }

    /**
     * Summary of getAllAnswers
     * @return array
     */
    public function getAllAnswers()
    {
        $answerModel = new AnswerModel();
        $answers = $answerModel->getAllAnswers();

        return $answers;
    }

    /**
     * Summary of getAnswers
     * @param mixed $idFulfillments
     * @return array
     */
    public function getAnswers($idFulfillments)
    {
        $answersModel = new AnswerModel();
        $answers = $answersModel->getAnswersFromIdFulfillment($idFulfillments);

        return $answers;
    }

    /**
     * Summary of getAnswersFromIdFulfillment
     * @param mixed $idfulfillment
     * @return array
     */
    public function getAnswersFromIdFulfillment($idfulfillment)
    {
        $answerModel = new AnswerModel();
        $answers = $answerModel->getAnswersFromIdFulfillment($idfulfillment);

        return $answers;
    }

    /**
     * Summary of getAnswersFromFulfillment
     * @param mixed $fulfillments
     * @param mixed $field
     * @return array
     * Description :
     *  Recovery of response linked to created_at
     */
    public function getAnswersFromFulfillment($fulfillments, $field)
    {
        $answers = $this->getAllAnswers();
        $data = [];

        $maxAnswers = count($fulfillments);
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
     * Summary of getIconAnswersFromFulfillment
     * @param mixed $fulfillments
     * @param mixed $fields
     * @return string[][]
     * Descritpion :
     * Retrieve a table of symbols, crosses, checks and double checks depending on the response and the field
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

        $answers = $this->getAllAnswers();

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
     * Function Fulfillments Zone
     *****************************************************************************/

    /**
     * Summary of createFulfillments
     * @param mixed $idExercise
     * @return void
     */
    public function createFulfillments($idExercise)
    {
        $fulfillmentsModel = new FulfillmentModel();
        $date = date("Y-m-d H:i:s");
        $fulfillmentsModel->create($date, $idExercise);
    }

    /**
     * Summary of updateFulfillments
     * @param mixed $idFulfillments
     * @return void
     */
    public function updateFulfillments($idFulfillments)
    {
        $fulfillmentsModel = new FulfillmentModel();
        $date = date("Y-m-d H:i:s");
        $fulfillmentsModel->update($date, $idFulfillments);
    }

    /**
     * Summary of getLastFulfillment
     * @return mixed
     */
    public function getLastFulfillment()
    {
        $fulfillmentModel = new FulfillmentModel();
        $fulffilment = $fulfillmentModel->getLast();

        return $fulffilment['0'];
    }

    /**
     * Summary of getFulfillmentsByExerciseId
     * @param mixed $exerciseId
     * @return array
     */
    public function getFulfillmentsByExerciseId($exerciseId)
    {
        $fulfillmentModel = new FulfillmentModel();
        $fulfillments = $fulfillmentModel->getFulfillmentsByExerciseId($exerciseId);

        return $fulfillments;
    }

    /**
     * Summary of getCreatedAtWithIdFulfillments
     * @param mixed $fulfillments
     * @return array
     * Description :
     *  Get id fulfilment and created_at on same table.
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
     * Summary of getFulfillmentById
     * @param mixed $idFulfillments
     * @return mixed
     */
    public function getFulfillmentById($idFulfillments)
    {
        $fulfillmentModel = new FulfillmentModel();
        $fulffilment = $fulfillmentModel->getOne($idFulfillments);

        return $fulffilment['0'];
    }
}
