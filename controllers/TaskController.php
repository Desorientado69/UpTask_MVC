<?php
    namespace Controllers;

    use Model\Project;
    use Model\User;
    use Model\Task;
    use MVC\Router;

class TaskController {
    public function __construct() 
    {
        
    }
    //Página inicial de tasks
    public static function tasks() {
        //Abrir sesion
        session_start();
        //Si la url no está presente o no existe
        if (!isset($_GET['url']) || !$_GET['url'] ) header ('Location: /dashboard');
        //Tomamos la url
        $url = $_GET['url'];
        //Consultamos por el project con la url
        $project = Project::where('url', $url);
        //Comprobamos que existe y el ownerid del proyecto es el mismo que el id de la sesión
        if(!$project || $project->ownerid !== $_SESSION['id']) header ('Location: /404');
        //Listamos las tareas de un project
        $tasks = Task::belongsTo('projectid',$project->id);
        echo json_encode(['tasks'=> $tasks]);
    }
    //Crear nueva task
    public static function create() {
        //En el caso de ser POST...
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Iniciar sesión
            session_start();
            //Id del project. Es igual a parte de la url
            $url = $_POST['url'];
            //Consultamos por el project con la url
            $project = Project::where('url', $url);
            //Comprobar que el project existe y corresponde al usuario identificado
            if(!$project || ($project->ownerid !== $_SESSION['id'])) {
                $response = [
                    'type' => 'error',
                    'message' =>'Error Adding Task'
                ];
                //Recogemos la respuesta y la enviamos
                echo json_encode($response);
                return;
            }
            //Todo ha ido bien
            //Crear e instanciar la task
            $task = new Task($_POST);
            //Incluir projectid
            $task->projectid = $project->id;
            //Guardar en la DB
            $result = $task->save();
            if ($result){
                //Mensaje de tarea añadida correctamente
                $response = [
                    'type' => 'success',
                    'message' => 'Task: '.$task->name.', Added Successfully' ,
                    'id' =>$result['id'],
                    'projectid' => $project->id
                ];
                //Recogemos la respuesta y la enviamos
                echo json_encode($response);
                return;
            }
            //Si falla al meterla en la DB mandar mensaje de error
            $response = [
                'type' => 'error',
                'message' =>'Error Adding Task'
            ];
            //Envío de la respuesta
            echo json_encode($response);
        }
    }
    //Actualizar una task
    public static function update() {

        //En el caso de ser POST...
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Iniciar sesión
            session_start();
            //Id del project. Es igual a parte de la url
            $url = $_POST['url'];
            //Consultamos por el project con la url
            $project = Project::where('url', $url);
            //Comprobar que el project existe y corresponde al usuario identificado
            if(!$project || ($project->ownerid !== $_SESSION['id'])) {            
                $response = [
                    'type' => 'error',
                    'message' =>'Error Updating Task'
                ];
                //Recogemos la respuesta y la enviamos
                echo json_encode($response);
                return;
            }
            //Todo ha ido bien
            //Crear e instanciar la task
            $task = new Task($_POST);
            //Incluir projectid
            $task->projectid = $project->id;
            //Guardar en la DB
            $result = $task->save();
            if ($result){
                //Mensaje de tarea añadida correctamente
                $response = [
                    'type' => 'success',
                    'id' =>$task->id,
                    'projectid' => $project->id,
                    'message' =>'Task: '.$task->name.', Updated Successfully'
                ];
                //Recogemos la respuesta y la enviamos como array
                echo json_encode(['response' => $response]);
                return;
            }
            //Si falla al meterla en la DB mandar mensaje de error
            $response = [
                'type' => 'error',
                'message' =>'Error Updating Task'
            ];
            //Envío de la respuesta
            echo json_encode($response);
        }
    }

    //Borrar una task
    public static function delete() {
        //En el caso de ser POST...
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Iniciar sesión
            session_start();
            //Id del project. Es igual a parte de la url
            $url = $_POST['url'];
            //Consultamos por el project con la url
            $project = Project::where('url', $url);
            //Comprobar que el project existe y corresponde al usuario identificado
            if(!$project || ($project->ownerid !== $_SESSION['id'])) {            
                $response = [
                    'type' => 'error',
                    'message' =>'Error Deleting Task'
                ];
                //Recogemos la respuesta y la enviamos
                echo json_encode($response);
                return;
            }
            //Todo ha ido bien
            //Crear e instanciar la task
            $task = new Task($_POST);
            //Borrar de la DB
            $result = $task->delete();
            if ($result){
                //Mensaje de tarea borrada correctamente
                $response = [
                    'result' => $result,
                    'type' => 'success',
                    'id' => $task->id,
                    'message' =>'Task: '.$task->name.', Deleted Successfully'
                ];
                //Recogemos la respuesta y la enviamos como array
                echo json_encode($response);
                return;
            }
            //Si falla al borrarla en la DB mandar mensaje de error
            $response = [
                'type' => 'error',
                'message' =>'Error Deleting Task'
            ];
            //Envío de la respuesta
            echo json_encode($response);
                    }
                }
}
?>