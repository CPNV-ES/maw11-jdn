<?php

require_once APP_DIR . '/core/Controller.php';
require_once MODEL_DIR . '/ExerciseModel.php';
require_once MODEL_DIR . '/FieldModel.php';
require_once MODEL_DIR . '/AnswerModel.php';

define('SINGLE_LINE_TYPE', 1);

class ExerciseController extends Controller
{
    public function renderer($request_uri)
    {
        // TODO Refactor this code

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($request_uri) {
                case '/exercises':
                    $this->create();
                    exit();
                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/edit.*/', $request_uri, $matches) ? true : false):
                    $_SESSION['state'] = 'edit';
                    $exercise = $this->getOne($matches[1]);
                    require_once VIEW_DIR . '/home/fulfill-exercise.php';
                    exit();
                case (preg_match('/\/exercises\/(\d+)\/fields/', $request_uri, $matches) ? true : false):
                    $this->createfield($matches[1]);
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
                case (preg_match('/\/exercises\/(\d+)\/results.*/', $request_uri,$matches) ? true : false):
                    $exercise = $this->getOne($matches[1]);
                    $fields = $this->getFields($matches[1]);

                    $filterAnswers = $this->getSymboleAnswerByFields($fields);

                    require_once VIEW_DIR . '/home/result-exercise.php';
                    exit();
                case (preg_match('/\/exercises\/(\d+)\/fulfillments\/new*/', $request_uri, $matches) ? true : false):
                    $_SESSION['state'] = 'new';
                    $exercise = $this->getOne($matches[1]);
                    require_once VIEW_DIR . '/home/fulfill-exercise.php';
                    exit();
                default:
                    header("HTTP/1.0 404 Not Found");
                    echo "Page not found";
                    exit();
            }
        }
    }

    public function createfield($exerciseId)
    {
        $label = $_POST['field_label'];
        $type = $_POST['field_type'];
        $field = new FieldModel();
        $response = $field->create($label, $exerciseId, $type);

        header("Location: /exercises/$exerciseId/fields");
    }

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

    public function getOne($id)
    {
        $exerciseModel = new ExerciseModel();
        $exercise = $exerciseModel->getOne($id);

        return $exercise;
    }


    public function getAll()
    {
        $exerciseModel = new ExerciseModel();
        $exercise = $exerciseModel->getAll();

        return $exercise;
    }

    public static function getFields($exerciseId)
    {
        $fieldModel = new FieldModel();
        $field = $fieldModel->getFieldsFromExercise($exerciseId);

        return $field;
    }

    public function getAllAnswers()
    {
        $answerModel = new AnswerModel();
        $answers = $answerModel->getAllAnswers();

        return $answers;
    }

    /**
     * Method to get table of symbole by answer.
     * @param mixed $fields
     * @return array
     */
    public function getSymboleAnswerByFields ($fields) {
        $answers = $this->getAllAnswers();

        //Count max fields column and max answer row.
        foreach ($fields as $field) {
            $i = 0;
            $maxAnswer = 0;

            foreach ($answers as $answer) {
                $i++;
            }
            if ($i > $maxAnswer) {
                $maxAnswer = $i;
            }
        }

        $maxField = count($fields);

        $groupedAnswers = [];
        //Init table with maxField and maxAnswer with cross icon
        foreach ($fields as $field) {
            foreach ($answers as $answer) {
                if ($field['id_fields'] === $answer['id_fields']) {
                    for($i = 1;$i <= $maxAnswer;$i++){
                        $groupedAnswers[$answer['create_at']][$i] = 'fa fa-x XIcon';
                    }
                }
            }
        }
        //Add a simple, double check if answer contain content.
        //Else cross stay on table.
        foreach ($fields as $field) {
            foreach ($answers as $answer) {  
                if ($field['id_fields'] === $answer['id_fields']) {
                    
                    if ($answer['value'] != '') {

                         if ($field['id_fields_type'] === 'SINGLE_LINE_TYPE') {

                            $groupedAnswers[$answer['create_at']][$answer['id_fields']] = 'fa-solid fa-check VIcon';
                        } else {
                            //Differentiate simple or double line answer.
                            if (preg_match("/.+\n.+/",$answer['value'])) {

                                $groupedAnswers[$answer['create_at']][$answer['id_fields']] = 'fa-solid fa-check-double VIcon';
                            } else {
                                $groupedAnswers[$answer['create_at']][$answer['id_fields']] = 'fa-solid fa-check VIcon';
                            }
                        }
                    }
                }
            }
        }
        return $groupedAnswers;
    }

    public function deleteField($id)
    {
        $fieldModel = new FieldModel();

        $fieldModel->getOne($id);
        $response = $fieldModel->delete($id);
        return true;
    }
}    

