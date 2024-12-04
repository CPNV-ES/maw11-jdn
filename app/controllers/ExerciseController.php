<?php

require_once APP_DIR . '/core/Controller.php';
require_once MODEL_DIR . '/ExerciseModel.php';
require_once MODEL_DIR . '/FieldModel.php';
require_once MODEL_DIR . '/AnswerModel.php';
require_once MODEL_DIR . '/FulfillmentModel.php';
define('SINGLE_LINE_TYPE', 1);
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
                    $this->create();
                    exit();
                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/(\d+)\/edit*/', $request_uri, $matches) ? true : false):
                    $this->updateFulfillments($matches[2]);
                    foreach ($_POST as $answer) {
                        if ($answer != $_POST['updated_at']) {
                            $this->updateAnswer($answer['0'], $answer['1'], $matches[2]);
                        }
                    }
                    $exercise = $this->getOne($matches[1]);
                    $fields = $this->getFields($exercise['id_exercises']);
                    $answers = $this->getAnswers($matches[2]);
                    $exerciseAnswer = $this->getFulfillment($matches[2]);
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
                    $exercise = $this->getOne($matches[1]);
                    $fields = $this->getFields($exercise['id_exercises']);
                    $answers = $this->getAnswers($exerciseAnswer['id_fulfillments']);
                    $url = "/exercises/" . $matches[1] . "/fulfillments/" . $exerciseAnswer['id_fulfillments'] . "/edit";
                    header("Location: " . $url);
                    exit();
                case (preg_match('/\/exercises\/(\d+)\/fields/', $request_uri, $matches) ? true : false):
                    $this->createField($matches[1]);
                    $exercise = $this->getOne($matches[1]);
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
                    if (preg_match('/\/exercises\/(\d+)\/fields\/(\d+)\/edit/', $request_uri)) {
                        require_once VIEW_DIR . '/home/edit-field.php';
                        exit();
                    } elseif (preg_match('/\/exercises\/(\d+)\/fields\/(\d+)\/destroy/', $request_uri, $matches)) {
                        $this->deleteField($matches[2]);
                        $exercise = $this->getOne($matches[1]);
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

                // TODO : retrieve dynamically status from database for setting new status

                if ($matches[2] === 'delete') {
                    if (!$this->delete($matches[1])) {
                        header("HTTP/1.0 404 Not Found");
                    }
                } elseif ($matches[2] === 'update/answering') {
                    $this->update($matches[1], 2);
                } elseif ($matches[2] === 'update/closed') {
                    $this->update($matches[1], 3);
                }
            }

            switch ($request_uri) {
                case '/exercises':
                    $exercises = $this->getAll();
                    require_once VIEW_DIR . '/home/manage-exercise.php';
                    exit();
                case '/exercises/new':
                    require_once VIEW_DIR . '/home/create-exercise.php';
                    exit();
                case '/exercises/fields':
                    $exercise = $this->getOne($matches[1]);
                    $fields = $this->getFields($matches[1]);
                    require_once VIEW_DIR . '/home/create-field.php';
                    exit();
                case '/exercises/answering':
                    $exercises = $this->getAll();
                    require_once VIEW_DIR . '/home/take-exercise.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/results\/(\d+)/', $request_uri, $matches) ? true : false):

                    $exercise = $this->getOne($matches[1]);

                    $field = $this->getOneField($matches[2]);

                    $fulfillments = $this->getFulfillmentsByExerciseId($matches[1]);

                    $answers = $this->getAnswersFromFulfillment($fulfillments, $field);

                    require_once VIEW_DIR . '/home/result-field.php';
                    exit();

                case (preg_match('/\/exercises\/(\d+)\/results*/', $request_uri, $matches) ? true : false):
                    $exercise = $this->getOne(id: $matches[1]);
                    $fields = $this->getFields($matches[1]);
                    $fulfillments = $this->getFulfillmentsByExerciseId($matches[1]);

                    $answers = $this->getIconAnswersFromFulfillment($fulfillments, $fields);

                    $createdAtWhidId = $this->getCreatedAtWithIdFulfillments($fulfillments);

                    require_once VIEW_DIR . '/home/result-exercise.php';
                    exit();
                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/new*/', $request_uri, $matches) ? true : false):
                    $_SESSION['state'] = 'new';
                    $exercise = $this->getOne($matches[1]);
                    $fields = $this->getFields($matches[1]);
                    require_once VIEW_DIR . '/home/fulfill-exercise.php';
                    exit();
                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/(\d+)\/edit*/', $request_uri, $matches) ? true : false):
                    $_SESSION['state'] = 'edit';
                    $exercise = $this->getOne($matches[1]);
                    $fields = $this->getFields($exercise['id_exercises']);
                    $answers = $this->getAnswers($matches[2]);
                    $exerciseAnswer = $this->getFulfillment($matches[2]);
                    require_once VIEW_DIR . '/home/fulfill-exercise.php';
                    exit();
                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/(\d+)/', $request_uri, $matches) ? true : false):
                    $exercise = $this->getOne($matches[1]);
                    $fields = $this->getFields($matches[1]);
                    $fulfillment = $this->getOnefulfillment($matches[2]);
                    $answers = $this->getAnswersFromIdFulfillment($matches[2]);
                    require_once VIEW_DIR . '/home/response-exercise.php';
                    exit();
                default:
                    header("HTTP/1.0 404 Not Found");
                    echo "Page not found";
                    exit();
            }
        }

        header('Location: /exercises');
    }

    /**
     * Summary of update
     * @param mixed $id
     * @param mixed $newStatus
     * @return void
     */
    public function update($id, $newStatus)
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
     * Summary of getOne
     * @param mixed $id
     * @return mixed
     */
    public function getOne($id)
    {
        $exerciseModel = new ExerciseModel();
        $exercise = $exerciseModel->getOne($id);

        return $exercise;
    }

    /**
     * Summary of getAll
     * @return array
     */
    public function getAll()
    {
        $exerciseModel = new ExerciseModel();
        $exercise = $exerciseModel->getAll();

        return $exercise;
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
     * Summary of create
     * @return void
     */
    public function create()
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
     * Summary of delete
     * @param mixed $id
     * @return bool
     */
    public function delete($id)
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
     *  Retrieve a table of symbols, crosses, checks and double checks 
     *  depending on the response and the field
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
     * Summary of getOneField
     * @param mixed $fieldId
     * @return mixed
     */
    public function getOneField($fieldId)

    {
        $fieldModel = new FieldModel();

        $fieldModel->getOne($fieldId);
        return $fieldModel->getOne($fieldId);;
    }

    public function createAnswer($idfield, $value, $idexerciseAnswer)
    {
        $answer = new AnswerModel();
        $answer->create($value, $idfield, $idexerciseAnswer);

        return $answer;
    }

    public function updateAnswer($idfield, $value, $idFulfillments)
    {
        $answer = new AnswerModel();
        $answer->update($value, $idfield, $idFulfillments);

        return $answer;
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

    public function createFulfillments($idExercise)
    {
        $fulfillmentsModel = new FulfillmentModel();
        $date = date("Y-m-d H:i:s");
        $fulfillmentsModel->create($date, $idExercise);
    }

    public function updateFulfillments($idFulfillments)
    {
        $fulfillmentsModel = new FulfillmentModel();
        $date = date("Y-m-d H:i:s");
        $fulfillmentsModel->update($date, $idFulfillments);
    }

    public function getLastFulfillment()
    {
        $fulfillmentModel = new FulfillmentModel();
        $fulffilment = $fulfillmentModel->getLast();

        return $fulffilment['0'];
    }

    public function getAnswers($idFulfillments)
    {
        $answersModel = new AnswerModel();
        $answers = $answersModel->getAnswerFromId($idFulfillments);

        return $answers;
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
     * Summary of getOnefulfillment
     * @param mixed $id
     * @return array
     */
    public function getOneFulfillment($id)
    {
        $fulfillmentModel = new FulfillmentModel();
        $fulfillment = $fulfillmentModel->getOnefulfillment($id);

        return $fulfillment;
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

    public function getFulfillment($idFulfillments)
    {

        $fulfillmentModel = new FulfillmentModel();
        $fulffilment = $fulfillmentModel->getOne($idFulfillments);

        return $fulffilment['0'];
    }
}
