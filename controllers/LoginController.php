<?php
    namespace Controllers;

    use Classes\Email;
    use Model\User;
    use MVC\Router;

class LoginController {
    public function __construct() 
    {
        
    }
    //Login con usuario y password
    public static function login(Router $router) {
        //En el caso de ser POST...
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Tomamos los valores del formulario
            $user= new User($_POST);
            //Validar entrada
            $user->validateUser();
            //Sacamos las alertas
            $alerts = User::getAlerts(); 
            //Si $alerts está vacio, no hay errores de validación...
            if(empty($alerts)){
                //Comprobar si ya tiene cuenta y extraerla
                $user = User::where('email', $user->email);
                //Mensaje de error si no existe o no está confirmado
                if (!$user || !$user->confirmed) {
                    User::setAlert('error', 'Username Does Not Exist or is Not Confirmed');
                } else {
                    //Si tiene cuenta comprobar password...
                    if(password_verify($_POST['password'], $user->password)) {
                        //Iniciar sesión
                        session_start();
                        //Introducir los datos de la sesión
                        $_SESSION['id'] = $user->id;
                        $_SESSION['name'] = $user->name;
                        $_SESSION['surnames'] = $user->surnames;
                        $_SESSION['email'] = $user->email;
                        $_SESSION['login'] = true;
                        //redireccionar
                        header('Location: /dashboard');
                    } else {
                        //Mensaje de error
                        User::setAlert('error', 'Wrong Password');
                    };
                }
            }
        }
        //Sacamos las alertas
        $alerts = User::getAlerts();      
        //Render de la vista auth/login
        $router->render('auth/login', [
            'title' => 'Log In UpTask',        //Variable con el título de la página. Se incluye en el Layout
            'alerts' => $alerts
        ]);
    }
    //Cerrar la sesión de usuario
    public static function logout(Router $router) {
        session_start();
        //Si la sesión no existe enviar a página principal
        if (!isset($_SESSION['name'])) header ('Location: /');
        //Terminar sesión vaciando variables
        $_SESSION = [];
        //Mensaje de fin de sesión
        User::setAlert('success', 'UpTask Session Finished');
        //Sacamos las alertas
        $alerts = User::getAlerts();
        //Render de la vista auth/login con el mensaje de sesión cerrada
        $router->render('auth/login', [
            'title' => 'Log In',        //Variable con el título de la página. Se incluye en el Layout
            'alerts' => $alerts            
        ]);
    }
    //Crear cuenta de usuario. Introducir datos.
    public static function create(Router $router) {
        //Valiable para mostrar o no el formulario
        $show = true; 
        //Crear el usuario vacío
        $user = new User;
        //En el caso de ser POST...
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Sincronizar el user con el formulario
            $user->syncUp($_POST);
            //Validamos los datos del formulario y recogemos las alertas
            $alerts = $user->validateNewAccount();
            //Sacamos las alertas
            $alerts = User::getAlerts();
            //Si $alerts está vacio, no hay errores de validación...
            if(empty($alerts)){
                //Comprobar si el $user ya tiene cuenta
                $userExists = User::where('email', $user->email);
                //Mensaje de error si existe
                if ($userExists) {
                    User::setAlert('error', 'Already Registered User');
                } else {
                    //Si no tiene cuenta...
                    //Hashear el password y borrar el repetido
                    $user->hashPassword();
                    //Eliminar password_repeated
                    unset($user->password_repeated);
                    //Generar token único
                    $user->createToken();
                    //Enviar email de confirmación de cuenta
                    $email = new Email($user->email, ($user->name. ' ' .$user->surnames) , $user->token);
                    $email->sendConfirmation();
                    //Guardar en la DB y mensaje: enviado email con instrucciones  
                    $result = $user->save();
                    if($result) {
                        //Mensaje de exito
                        User::setAlert('success', 'We have Sent Instructions to your Email');
                    } else {
                        //Mensaje de error al guardar en la DB
                        User::setAlert('error', 'Error Saving to DataBase');
                    }
                    //Dejamos de mostrar el formulario
                    $show = false;
                }
            }
        }
        //Sacamos las alertas
        $alerts = User::getAlerts();
        //Render de la vista auth/create
        $router->render('auth/create', [
            'title' => 'Create New Account', //Variable con el título de la página. Se incluye en el Layout
            'user' => $user,
            'alerts' => $alerts,
            'show' => $show
        ]);
    }
    //Al seguir el enlace del email, se confirma la cuenta.
    public static function confirmed(Router $router) {
        //Tomamos el token de la dirección web sanitizado
        $token = s($_GET['token']);
        //Si no hay token it a la página principal
        if(!$token) header ('Location: /');
        //Consultamos en la DB el usuario con este token
        $user = User::where('token', $token);
        //Si existe actualizar el usuario confirmed =1 y token=''
        if(!$user) {
            //Error, no se encuentra ningún usuario con el token
            User::setAlert('error', 'There is no User with this Token');
        } else {
            //Modificamos el usuario confirmed =1 y token=null
            $user -> confirmed = 1;
            $user -> token = null;
            //Eliminar password_repeated
            unset($user->password_repeated);
            //Actualizamos la DB
            $user -> save();
            //Exito, cuenta confirmada
            User::setAlert('success', 'Confirmed Account');       
        };
        //Sacamos las alertas
        $alerts = User::getAlerts();
        //Render de la vista auth/confirmed
        $router->render('auth/confirmed', [
            'title' => 'Confirme your Account',        //Variable con el título de la página. Se incluye en el Layout
            'alerts' => $alerts
        ]);
    }
    //Password olvidado. Mensaje de renovación.
    public static function forgotten(Router $router) {
        //Valiable para mostrar o no el formulario
        $show = true;        
        //En el caso de ser POST...
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Recogemos el email en un user nuevo
            $user = new User($_POST);
            //Validamos los datos del formulario y recogemos las alertas
            $alerts = $user->validateEmail();
            //Sacamos las alertas
            $alerts = User::getAlerts();           
            //Si $alerts está vacio, no hay errores de validación...
            if(empty($alerts)){
                //Comprobar si el $user ya tiene cuenta
                $user = User::where('email', $user->email);
                //Mensaje de error si no existe o no está confirmado($user->confirmed === "1" es lo mismo que $user->confirmed)
                if (!$user || !$user->confirmed) {
                    User::setAlert('error', 'Username Does Not Exist or is Not Confirmed');
                } else {
                    //Si tiene cuenta...
                    //Generar token único
                    $user->createToken();                    
                    //Enviar email de cambio de password
                    $email = new Email($user->email, ($user->name. ' ' .$user->surnames) , $user->token);
                    $email-> sendInstructions();
                    //Eliminar password_repeated
                    unset($user->password_repeated);    
                    //Guardar en la DB el y redirigir a página con mensaje: enviado email con instrucciones  
                    $result = $user->save();
                    if($result) {
                        //Mensaje de exito
                        User::setAlert('success', 'We have Sent Instructions to your Email');
                    } else {
                        //Mensaje de error al guardar en la DB
                        User::setAlert('error', 'Error Saving to DataBase');
                    }
                    //Dejamos de mostrar el formulario
                    $show = false;
                }
            }
        }
        //Sacamos las alertas
        $alerts = User::getAlerts();
        //Render de la vista auth/forgotten
        $router->render('auth/forgotten', [
            'title' => 'Send Restore Email',        //Variable con el título de la página. Se incluye en el Layout
            'alerts' => $alerts,
            'show' => $show
        ]);        
    }
    //Introducir nuevo password restablecido.
    public static function restore(Router $router) {
        //Valiable para mostrar o no el formulario
        $show = true;
        //Inicializar $alerts vacío para que no de error
        $alerts = [];
        //Si se ha pasado token lo tomamos de la dirección web sanitizado
        //Si no se ha pasado token, aunque sea vacio, vamos a la página principal (Al final)
        if (isset($_GET['token'])) {
            $token = s($_GET['token']);
            //Si no hay token mensaje de token no válido
            if(!$token) { //Si el token no existe error
                User::setAlert('error', 'Invalid Token');
                $show = false;
            } else {
                //Consultamos en la DB el usuario con este token
                $user = User::where('token', $token);
                //Mensaje de error si no existe o no está confirmado
                if (!($user && $user->confirmed)) {
                    User::setAlert('error', 'Username Does Not Exist or is Not Confirmed');
                } else {
                    //Si existe y está confirmado...
                    //En el caso de ser POST...
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        //Sincronizar el user con la DB
                        $user->syncUp($_POST);
                        //Validamos los datos del formulario y recogemos las alertas
                        $alerts = $user->validateNewPassword();
                        //Si $alerts está vacio, no hay errores de validación...
                        if(empty($alerts)){
                            //Hashear el password y borrar el repetido
                            $user->hashPassword();
                            //Eliminar password_repeated y $token
                            unset($user->password_repeated);
                            $user -> token = null;
                            //Guardar en la DB y redirigir a página con mensaje  
                            $result = $user->save();
                            if($result) {
                                //Exito, cuenta confirmada
                                User::setAlert('success', 'Password Restored');
                            } else {
                                //Mensaje de error al guardar en la DB
                                User::setAlert('error', 'Error Saving to DataBase');                     
                            }
                            //Dejamos de mostrar el formulario
                            $show = false;
                        }
                    }
                }
            }
        } else {
            //Si está vacío ir a la página principal
            header ('Location: /');
        };
        //Sacamos las alertas
        $alerts = User::getAlerts();
        //Render de la vista auth/restore
        $router->render('auth/restore', [
            'title' => 'Restore Password',        //Variable con el título de la página. Se incluye en el Layout
            'alerts' => $alerts,
            'show' => $show
        ]);        
    }
}
?>