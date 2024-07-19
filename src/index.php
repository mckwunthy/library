<?php
session_start();
require "include.php";
verifParams(); //check all informations send by website users

//requests manager
if (isset($_SERVER['PATH_INFO'])) {
    $url = trim($_SERVER['PATH_INFO'], '/');
    $url = explode('/', $url);
} else {
    $url = array("library");
}

//toutes les pages ou routes
$route = array(
    "library", "managebooks", "deletebooks", "updatbooks", "askborrow", "borrow", "authentification",
    "deconnexion", "profil", "updatUser", "createtUser", "availBorrowBook", "notAvailBorrowBook", "searchBookRequest"
);
// print_r($url);
// exit();;

$action = $url[0];

// controller
if (!in_array($action, $route)) { //si la route n'existe pas
    $title = "Page Error";
    $content = "<h1>URL introuvable !</h1>";
} else { //si la route ou page existe
    //echo 'Bienvenu sur la page '.$action;
    $function = "display" . ucwords($action);
    $title = "Page " . $action;
    $content = $function();
}
require VIEWS . SP . "templates" . SP . "default.php";