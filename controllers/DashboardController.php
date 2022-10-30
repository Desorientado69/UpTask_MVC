<?php
    namespace Controllers;

    use Model\Project;
    use Model\User;
    use MVC\Router;
    use Classes\Email;

class DashboardController {
    public function __construct() 
    {
        
    }
    //Página inicial del dashboard de UpTask
    public static function index(Router $router) {
        //Iniciar sesión
        session_start();
        //Autorizado, sino a principal
        isAuth();
        //Guardar el id de la sesión
        $ownerid = $_SESSION['id'];
        //Traemos todos los proyectos del usuario
        $projects = Project::belongsTo('ownerid', $ownerid);
        //Sacamos las alertas
        $alerts = User::getAlerts();      
        //Render de la vista dashboard/index
        $router->render('dashboard/index', [
            'title' => 'Proyectos',        //Variable con el título de la página. Se incluye en el Layout
            'alerts' => $alerts,
            'projects' => $projects
        ]);
    }
    //Crear nuevo proyecto
    public static function createProject(Router $router) {
        //Iniciar sesión
        session_start();
        //Autorizado, sino a principal
        isAuth();
        $project =new Project;        //En el caso de ser POST...
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Sincronizar el project con el formulario
            $project = new Project($_POST);
            //Validamos los datos del formulario y recogemos las alertas
            $alerts = $project->validateProject();
            //Si $alerts está vacio, no hay errores de validación...
            if(empty($alerts)){
                //Guardar el propietario del proyecto
                $project->ownerid = $_SESSION['id'];
                //Comprobar si existe el mismo $ownerid un $project con el mismo nombre
                //Creamos la query
                $query = "SELECT * FROM projects WHERE ownerid='";
                $query .= $project->ownerid . "' && name='".$project->name . "'";
                //Realizamos la consulta a la DB
                $projectExists = Project::querySQL($query);
                //Ya existe un proyecto con ese nombre?
                if (!$projectExists) {
                    //Generar una url única
                    $project->url = md5(uniqid());
                    //Guardar en la DB y redirigir a página con mensaje: enviado email con instrucciones para confirmar cuenta  
                    $result = $project->save();
                    if($result) {
                        header('Location: /dashboard/project?url='.$project->url);
                    } else {
                        //Mensaje de error al guardar en la DB
                        Project::setAlert('error', 'Error Saving to DataBase');
                    }
                } else {
                    //Si existe error de que ya existe un proyecto con el mismo nombre
                    Project::setAlert('error', 'There is a Project with the Same Name');
                }
            }
        }        
        //Sacamos las alertas
        $alerts = Project::getAlerts();      
        //Render de la vista dashboard/index
        $router->render('dashboard/create-project', [
            'title' => 'Crear Proyecto',        //Variable con el título de la página. Se incluye en el Layout
            'alerts' => $alerts,
            'project' => $project
        ]);
    }
    //Cambio de nombre o email en el profile del usuario
    public static function profile(Router $router) {
        //Iniciar sesión
        session_start();
        //Autorizado, sino a principal
        isAuth();
        //Inicializamos alertas
        $alerts = [];
        //Buscamos el usuario con la sesión abierta
        $user = User::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Sincronizar el project con el formulario
            $user->syncUp($_POST);
            //Validamos los datos del formulario y recogemos las alertas
            $alerts = $user->validateUpdateUser();
            //Si $alerts está vacio, no hay errores de validación...
            if(empty($alerts)){
                //Verificamos que el nuevo email no existe en la DB
                //Y si existe que el email no sea del usuario que tiene abierta la sesión (no cambia email)
                $userExists =User::where('email', $user->email);
                if($userExists && ($userExists->id !== $user->id)) {
                    //Mensaje de error el email ya tiene otro usuario
                    User::setAlert('error', 'Email Already has Another Account');
                } else {
                    //Guardar en la DB y dar mensaje de error o éxito  
                    $result = $user->save();
                    if($result) {
                        //Actualizamos la sesion con el nuevo nombre del propietario del proyecto
                        $_SESSION['name'] = $user->name;
                        $_SESSION['surnames'] = $user->surnames;
                        $_SESSION['email'] = $user->email;
                        //Mensaje de éxito al guardar en la DB
                        User::setAlert('success', 'Updated Profile');
                    } else {
                        //Mensaje de error al guardar en la DB
                        User::setAlert('error', 'Error Saving to DataBase');
                    }       
                };
            }
        }     
        //Sacamos las alertas
        $alerts = User::getAlerts();      
        //Render de la vista dashboard/index
        $router->render('dashboard/profile', [
            'title' => 'Profile',        //Variable con el título de la página. Se incluye en el Layout
            'user' => $user,
            'alerts' => $alerts
        ]);
    }
    //Cambio de password en el profile del usuario
    public static function changePassword(Router $router) {
        //Iniciar sesión
        session_start();
        //Autorizado, sino a principal
        isAuth();
        //Inicializamos alertas
        $alerts = [];
        //Buscamos el usuario con la sesión abierta
        $user = User::find($_SESSION['id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Sincronizamos lo introducido en el formulario
            $user->syncUp($_POST);
            //Validamos los datos del formulario y recogemos las alertas
            $alerts = $user->validateNewPasswordProfile();
            //Sacamos las alertas
            $alerts = User::getAlerts();           
            //Si $alerts está vacio, no hay errores de validación...
            if(empty($alerts)){
                //Comprobamos si el password introducido es igual al password de la DB
                //$this->current_password hasheado ===$this->password
                if ($user->checkPassword()) {
                    //Asigna el nuevo password introducido
                    $user->password = $user->new_password;
                    //Borra el current_password y repeated_password
                    unset($user->current_password);
                    unset($user->repeated_password);
                    //Hashea el nuevo password introducido
                    $user->hashPassword();
                    //Guardar en la DB el y  mensaje de éxito o error
                    $result = $user->save();
                    if($result) {
                        //Mensaje de exito
                        User::setAlert('success', 'Password Changed');
                    } else {
                        //Mensaje de error al guardar en la DB
                        User::setAlert('error', 'Error Saving to DataBase');
                    }
                } else { //No coinciden los password
                    //Mensaje de error, no coincide el current password introducido con el actual
                    User::setAlert('error', 'Current Password does Not Match');
                }
            }
        }
       //Sacamos las alertas
        $alerts = User::getAlerts();      
        //Render de la vista dashboard/index
        $router->render('dashboard/change-password', [
            'title' => 'Change Password',        //Variable con el título de la página. Se incluye en el Layout
            'user' => $user,
            'alerts' => $alerts
        ]);
    }
    //Introducir datos en un nuevo proyecto
    public static function project(Router $router) {
        //Iniciar sesión
        session_start();
        //Autorizado, sino a principal
        isAuth();
        //Tomar url de la url de la página si existe
        if (isset($_GET['url'])) {
            $url = $_GET['url'];
        } else { //Si no existe redirigir
            header ('Location: /dashboard');
        }
        //Buscamos el proyecto con esa url en la DB
        $project = Project::where('url', $url);
        //Comprobamos que exista y que el ownerid del project sea igual al id de la sesión
        if(!$project || $project->ownerid !== $_SESSION['id']) {
            //Si no coincide redirigir
            header ('Location: /dashboard');
        }
        //Sacamos las alertas
        $alerts = User::getAlerts();      
        //Render de la vista dashboard/index
        $router->render('/dashboard/project', [
            'title' => $project->name,        //Variable con el título de la página. Se incluye en el Layout
            'alerts' => $alerts,
            'project' => $project
        ]);
    }
}
?>