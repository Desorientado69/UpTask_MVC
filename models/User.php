<?php 
    namespace Model;

class User extends ActiveRecord {
    protected static $table = 'users';
    protected static $columnsDB = ['id','name','surnames','email','password','token','confirmed'];

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->surnames = $args['surnames'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->repeated_password = $args['repeated_password'] ?? '';
        $this->current_password = $args['current_password'] ?? '';
        $this->new_password = $args['new_password'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmed = $args['confirmed'] ?? 0;
    }
    //Validación de usuario en el login
    public function validateUser(): array {
        if (!$this->email) {
            self::$alerts['error'][] = "Email is required";
        }
        //Filtra el email por formato
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alerts['error'][] = "Invalid Email";
        }        
        if (!$this->password | strlen($this->password)<6) {
            self::$alerts['error'][] = "Password is required. Password must have at least six characters";
        }
        return self::$alerts;
    }
    //Validación de usuario en update Profile
    public function validateUpdateUser(): array {
        if (!$this->name) {
            self::$alerts['error'][] = "Username is required";
        }
        if (!$this->surnames) {
            self::$alerts['error'][] = "At least one last name of the user is required";
        }
        if (!$this->email) {
            self::$alerts['error'][] = "Email is required";
        }
        return self::$alerts;
    }

    //Validación para cuentas nuevas
    public function validateNewAccount(): array {
        if (!$this->name) {
            self::$alerts['error'][] = "Username is required";
        }
        if (!$this->surnames) {
            self::$alerts['error'][] = "At least one last name of the user is required";
        }
        if (!$this->email) {
            self::$alerts['error'][] = "Email is required";
        }
        //Filtra el email por formato
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alerts['error'][] = "Invalid Email";
        }        
        if (!$this->password | strlen($this->password)<6) {
            self::$alerts['error'][] = "Password is required. Password must have at least six characters";
        }
        if (!$this->repeated_password | strlen($this->repeated_password)<6) {
            self::$alerts['error'][] = "Repeating the password is required. The password must have at least six characters";
        }
        if ($this->password !== $this->repeated_password) {
            self::$alerts['error'][] = "Passwords are not the same";
        }
        return self::$alerts;
    }

    //Comprobar password
    public function checkPassword() : bool {
        return password_verify($this->current_password,$this->password);
    }
    //Hash del password
    public function hashPassword(): void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    //Generar token único. Función poco segura, pero para esta función es suficiente
    //Otra opción es md5(uniqid()) Crea un token de 32 caracteres
    public function createToken(): void {
        $this->token = uniqid();
    }
    //Validación para password olvidado
    public function validateNewPassword(): array {
        if (!$this->password | strlen($this->password)<6) {
            self::$alerts['error'][] = "Password is required. Password must have at least six characters";
        }
        if (!$this->repeated_password | strlen($this->repeated_password)<6) {
            self::$alerts['error'][] = "Repeating the password is required. The password must have at least six characters";
        }
        if ($this->password !== $this->repeated_password) {
            self::$alerts['error'][] = "Passwords are not the same";
        }
        return self::$alerts;
    }
    //Validación para password olvidado
    public function validateNewPasswordProfile(): array {
        if (!$this->current_password | strlen($this->current_password)<6) {
            self::$alerts['error'][] = "Current Password is required. Password must have at least six characters";
        }
        if (!$this->new_password | strlen($this->new_password)<6) {
            self::$alerts['error'][] = "New Password is required. Password must have at least six characters";
        }
        if (!$this->repeated_password | strlen($this->repeated_password)<6) {
            self::$alerts['error'][] = "Repeating the New Password is required. The password must have at least six characters";
        }
        if ($this->new_password !== $this->repeated_password) {
            self::$alerts['error'][] = "New Passwords are not the same";
        }
        return self::$alerts;
    }
    //Validación de Email para enviar email cunado se olvidan el password
    public function validateEmail(): array {
        if (!$this->email) {
            self::$alerts['error'][] = "Email is required";
        }
        //Filtra el email por formato
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alerts['error'][] = "Invalid Email";
        }
        return self::$alerts;
    }
}
?>