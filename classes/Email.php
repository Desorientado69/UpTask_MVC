<?php
    namespace Classes;
    //Sitio web mailtrap.io
    use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $email;
    public $name;
    public $token;

    public function __construct($email, $name, $token) {
        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }
    //Envía email de confirmación de creación de usuario o modificación de password
    public function sendConfirmation (): void {
        //Crear el objeto del email (Copiado de mi cuenta de Mailtrap)
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'cc4ce816a82bdc';
        $mail->Password = 'e859c4c6d03b55';
        //Email que envía el email
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'UpTask.com');
        //Resto de campos del email
        //Asunto
        $mail->Subject = 'Confirma tu cuenta UpTask';
        //Va a tener contenido html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
        //Cuerpo del email
            $contents = "<html>";
            $contents .= "<p>    Hola <strong>". $this->name."</strong>.-</br>    Has creado tu cuenta en UpTask, sólo debes confirmarla presionando el siguiente enlace:</p>";
            $contents .= "<p>    Presiona aquí: <a href='http://localhost:3000/confirmed?token=".
                $this->token . "'>Confirmar Cuenta</a></p>";
            $contents .= "<p>    Si tú no solicitaste esta cuenta, puedes ignorar el mensaje.</p>";
            $contents .= "<html>";
        $mail->Body = $contents;
        //Enviar email
        $mail->send();
    }
    //Envía email con instrucciones para la modificación de password
    public function sendInstructions(): void {
        //Crear el objeto del email (Copiado de mi cuenta de Mailtrap)
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'cc4ce816a82bdc';
        $mail->Password = 'e859c4c6d03b55';
        //Email que envía el email
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'UpTask.com');
        //Resto de campos del email
        //Asunto
        $mail->Subject = 'Restablece tu password de tu cuenta UpTask';
        //Va a tener contenido html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
        //Cuerpo del email
            $contents = "<html>";
            $contents .= "<p><strong>Hola ". $this->name. "</strong> Has solicitado cambiar el password de tu cuenta en UpTask.
                            Elige un nuevo password presionando el siguiente enlace:</p>";
            $contents .= "<p>Presiona aquí: <a href='http://localhost:3000/restore?token=".
                $this->token . "'>Restablecer Password</a></p>";
            $contents .= "<p>Si tú no solicitaste este cambio, puedes ignorar el mensaje.</p>";
            $contents .= "<html>";
        $mail->Body = $contents;
        //Enviar email
        $mail->send();
    }
}
?>