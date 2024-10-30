<?php

require_once APP_DIR . '/core/Controller.php';
require_once MODEL_DIR . '/ExerciseModel.php';

class ExerciseController extends Controller
{ 
    public function renderer($request_uri)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            switch ($request_uri) {
                case '/exercises':
                    $this->create();
                    exit();
                default:
                    header("HTTP/1.0 404 Not Found");
                    echo "Page not found";
                exit();
            }
        } else {
            
            if (preg_match("/^\/exercises\/(\d+)\/fields$/", $request_uri, $id)) {
                $request_uri = '/exercises/fields';
            } elseif (preg_match("/^\/exercises\/(\d+)\/delete$/",$request_uri,$matches)) {
                $this->delete($matches[1]);
                $request_uri = '/exercises';
            } elseif (preg_match("/^\/exercises\/(\d+)\/update\/answering$/",$request_uri,$matches)) {
                $this->update($matches[1],2);
                $request_uri = '/exercises';
            } elseif (preg_match("/^\/exercises\/(\d+)\/update\/closed$/",$request_uri,$matches)) {
                $this->update($matches[1],3);
                $request_uri = '/exercises';
            }

            switch ($request_uri) {
                case '/exercises':
                    require_once VIEW_DIR . '/home/manage-exercise.php';
                    exit();
                case '/exercises/new':
                    require_once VIEW_DIR . '/home/create-exercise.php';
                    exit();
                case '/exercises/fields':
                    $exerciseName = $this->getNameExerciseById($id[1]);
                    require_once VIEW_DIR . '/home/field-exercise.php';
                    exit();
                case '/exercises/answering':
                    require_once VIEW_DIR . '/home/take-exercise.php';
                    exit();
                case (preg_match('/\/exercises\/(\d+)\/results.*/', $request_uri) ? true : false):
                  require_once VIEW_DIR . '/home/result-exercise.php';
                  exit();
                default:
                    header("HTTP/1.0 404 Not Found");
                    echo "Page not found";
                    exit();
            }
        }
    }
    public function create() {
        $title = $_POST['exercises_title'];
        $exercise = new ExerciseModel();
        $response = $exercise->create($title);

        if (!$response) {
            header('Location: /exercises/new');
            return;
        }
        
        header("Location: /exercises/$exercise->id/fields");
    }

    public function delete($id) {
        $exercise = new ExerciseModel();
        $response = $exercise->delete($id);

        if (!$response) {
            header('Location: /');
            return;
        }

        header('Location: /exercises');
    }

    public function update($id,$newStatus) {
        $exercise = new ExerciseModel();
        $response = $exercise->update($id,'id_status',$newStatus);

        if (!$response) {
            header('Location: /');
            return;
        }

        header('Location: /exercises');
    }

    public function getNameExerciseById($id) {
        $exerciseModel = new ExerciseModel();
        $exercise = $exerciseModel->getOne($id);
        
        return $exercise['title'];
    }
}