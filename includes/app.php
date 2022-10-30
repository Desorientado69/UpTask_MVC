<?php
    require __DIR__ . '/../vendor/autoload.php'; // Incluye autoload.php que está en /vendor
    //Este autoload se crea en el Terminal con: composer update

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv ->safeLoad();

    require 'functions.php';
    require 'database.php';

    // Conectarnos a la base de datos
    use Model\ActiveRecord;
    ActiveRecord::setDB($db); //Se crea la DB con una clase estática static
?>