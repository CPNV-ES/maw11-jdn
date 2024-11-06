<?php

require_once APP_DIR . '/core/Controller.php';
require_once MODEL_DIR . '/ExerciseModel.php';
require_once MODEL_DIR . '/FieldModel.php';

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
                default:
                    header("HTTP/1.0 404 Not Found");
                    echo "Page not found";
                    exit();
            }
        } else {
            if (preg_match("/^\/exercises\/(\d+)\/fields$/", $request_uri, $id)) {
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
                    $exercise = $this->getOne($id[1]);
                    require_once VIEW_DIR . '/home/field-exercise.php';
                    exit();
                case '/exercises/answering':
                    $exercises = $this->getAll();
                    require_once VIEW_DIR . '/home/take-exercise.php';
                    exit();
                case (preg_match('/\/exercises\/(\d+)\/results.*/', $request_uri) ? true : false):
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
}
