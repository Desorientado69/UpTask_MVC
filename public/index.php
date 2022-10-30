<?php 
    // Incluye las app.php que está en /includes
    //En app.php incluye autoload.php que está en vendor, iniciando la aplicación web
    require_once __DIR__ . '/../includes/app.php';

    use Controllers\LoginController;
    use Controllers\DashboardController;
    use Controllers\TaskController;
    use MVC\Router;

    $router = new Router();

    //Rutas públicas ________________________________________________________________
    //Login_______________________________________________
    $router->get('/', [LoginController::class, 'login']);
    $router->post('/', [LoginController::class, 'login']);
    //Logout____________________________________________________
    $router->get('/logout', [LoginController::class, 'logout']);
    //Crear Cuenta_______________________________________________________
    $router->get('/create_account', [LoginController::class, 'create']);
    $router->post('/create_account', [LoginController::class, 'create']);
    //Confirmar la cuenta creada.Seguir el enlace del email y queda confirmada con el token.______
    $router->get('/confirmed', [LoginController::class, 'confirmed']);
    //Password olvidado, enviar mensaje de renovación__________________
    $router->get('/forgotten', [LoginController::class, 'forgotten']);
    $router->post('/forgotten', [LoginController::class, 'forgotten']);
    //Password olvidado, introducir uno nuevo________________________
    $router->get('/restore', [LoginController::class, 'restore']);
    $router->post('/restore', [LoginController::class, 'restore']);
    //Rutas privadas ________________________________________________________________
    //Projects_______________________________________________
    $router->get('/dashboard', [DashboardController::class, 'index']);
    //Projects_______________________________________________
    $router->get('/dashboard/create_project', [DashboardController::class, 'createProject']);
    $router->post('/dashboard/create_project', [DashboardController::class, 'createProject']);
    //Project_______________________________________________
    $router->get('/dashboard/project', [DashboardController::class, 'project']);
    $router->post('/dashboard/project', [DashboardController::class, 'project']);
    //Profile_______________________________________________
    $router->get('/dashboard/profile', [DashboardController::class, 'profile']);
    $router->post('/dashboard/profile', [DashboardController::class, 'profile']);
    $router->get('/dashboard/change-password', [DashboardController::class, 'changePassword']);
    $router->post('/dashboard/change-password', [DashboardController::class, 'changePassword']);
    //API para las tasks
    $router->get('/api/tasks', [TaskController::class, 'tasks']);
    $router->post('/api/task', [TaskController::class, 'create']);
    $router->post('/api/task/update', [TaskController::class, 'update']);
    $router->post('/api/task/delete', [TaskController::class, 'delete']);

    // Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
    $router->checkRoutes();
?>