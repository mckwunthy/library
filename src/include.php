<?php
//print_r($_SERVER);exit();
define("SRC", dirname(__FILE__));
define("ROOT", dirname(SRC));
define("SP", DIRECTORY_SEPARATOR);
define("CONFIG", ROOT . SP . "config");
define("VIEWS", ROOT . SP . "views");
define("MODEL", ROOT . SP . "model");
define("IMAGES", ROOT . SP . "src" . SP . "images");
define("BASE_URL", dirname($_SERVER['REQUEST_URI']));
// define("BASE_URL", dirname($_SERVER['SCRIPT_NAME']));
define("TVA", 20);

// import du model
require CONFIG . SP . "config.php";
require MODEL . SP . "DataLayer.class.php";

$model = new DataLayer();
$gender = $model->getGender();
$books = $model->getBooks();
$edition = $model->getEdition();
$book_borrow = $model->getBooksBorrow();
$book_stats = $model->getBooksBorrowStat();
$book_user_stats = $model->getBooksBorrowUserStat();


//$data = $model->getCustomer(1);
//print_r($data);exit();





// les fonctions appelée par le controller
require "functions.php";
