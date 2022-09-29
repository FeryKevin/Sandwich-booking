<?php

/* fonction connexion */

$connection = null;

function connect()
{

    if (file_exists('php/config.php' )){
        require 'php/config.php';
    }    
    else{
        require 'config.php';
    }

    try{
        $connection = new PDO("mysql:host=". $dbHost . ";dbname=" . $dbName, $dbUser, $dbUserPw);
    }
    catch(Exception $e){
        die('Erreur: '. $e->getMessage());
    }
    return $connection;
}

/* fonction qui vérifie que l'adresse mail soit bien une adresse mail */

function isEmail ($var)
{
    return filter_var($var, FILTER_VALIDATE_EMAIL);
}

/* fonction qui fait un contrôle de sécurité */

function verifyInput($var)
{
    $var = trim($var);
    $var = stripslashes($var);
    $var = htmlspecialchars($var);
    return $var;
}

?>