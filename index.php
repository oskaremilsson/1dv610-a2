<?php
session_start();
//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$v = new \view\LoginView();
$dtv = new \view\DateTimeView();
$lv = new \view\LayoutView();

$isLoggedIn = false;


$message = $v->checkInput();

if(isset($_SESSION["isLoggedIn"])) {
  $isLoggedIn = $_SESSION["isLoggedIn"];
}

$lv->render($isLoggedIn, $v, $dtv, $message);
