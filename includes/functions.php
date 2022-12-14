<?php
    function debugger($variable) : string {
        echo "<pre>";
        var_dump($variable);
        echo "</pre>";
        exit;
    }

    // Escapa / Sanitizar el HTML
    function s($html) : string {
        $s = htmlspecialchars($html);
        return $s;
    }

    // Función que revisa que el usuario esté autenticado
    function isAuth() : void {
        if(!isset($_SESSION['login'])) {
            header('Location: /');
        }
    }
?>