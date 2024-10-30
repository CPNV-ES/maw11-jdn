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
                    $this->createExercise();
                    exit();
                default:
                    header("HTTP/1.0 404 Not Found");
                    echo "Page not found";
                exit();
            }
        } else {
            
            if (preg_match("/^\/exercises\/(\d+)\/fields$/", $request_uri, $id)) {
                $request_uri = '/exercises/fields';
            } elseif (preg_match("/^\/exercises\/(\d+)\/delete$/",$request_uri,$id)) {
                $this->deleteExercice($id[1]);
                $request_uri = '/exercises';
            } elseif (preg_match("/^\/exercises\/(\d+)\/update\/answering$/",$request_uri,$id)) {
                $newStatus = 2;
                $this->updateExerciceStatus($id[1],$newStatus);
                $request_uri = '/exercises';
            } elseif (preg_match("/^\/exercises\/(\d+)\/update\/closed$/",$request_uri,$id)) {
                $newStatus = 3;
                $this->updateExerciceStatus($id[1],$newStatus);
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
    public function createExercise() {
        $title = $_POST['exercises_title'];
        $exercise = new ExerciseModel();
        $response = $exercise->create($title);

        if (!$response) {
            header('Location: /exercises/new');
            return;
        }
        
        header('Location: /exercises/' . $exercise->id . '/fields');
    }

    public function deleteExercice ($id) {
        $exercise = new ExerciseModel();
        $response = $exercise->delete($id);

        if (!$response) {
            header('Location: /');
            return;
        }

        header('Location: /exercises');
    }

    public function updateExerciceStatus ($id,$newStatus) {
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