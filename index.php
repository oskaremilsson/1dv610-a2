<?php
session_start();
//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');

$CookiePassword = $_SESSION["PHPSESSID"];

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');


//CREATE OBJECTS OF THE VIEWS
$v = new \view\LoginView();
$dtv = new \view\DateTimeView();
$lv = new \view\LayoutView();
$lc = new \controller\LoginController($v);

$isLoggedIn = false;
$message = "";

setcookie("LoginView::CookiePassword", $CookiePassword, time() + (86400 * 30), "/");

if (isset($_COOKIE['isLoggedIn'])) {
  //cookie with isLoggedIn exist
  $isLoggedIn = $_COOKIE['isLoggedIn'];
  $lc->handleFlashMessage();

  if ($lc->isRequest($v->getLogoutID())) {
    //logout form sent, handle it
    $lc->logout();
    $isLoggedIn = false;
  }

  $message = $lc->getMessage();
}
else if (isset($_SESSION["isLoggedIn"])) {
  $isLoggedIn = $_SESSION["isLoggedIn"];

  if ($lc->isRequest($v->getLogoutID())) {
    //logout form sent, handle it
    $lc->logout();
    $isLoggedIn = false;
    $message = $lc->getMessage();
  }
}
else {
  if ($lc->isRequest($v->getLoginID())) {
    //login form sent, handle it
    $isLoggedIn = $lc->login();
    $message = $lc->getMessage();
  }
}

//if cookie password wrong logout
{
  if(isset($_COOKIE['LoginView::CookiePassword'])) {
     if ($_COOKIE['LoginView::CookiePassword'] != $CookiePassword) {
       $lc->logout();
       $isLoggedIn = false;
       $message = "Wrong information in cookies";
     }
  }
}


$response = $v->response($isLoggedIn, $message);
$lv->render($isLoggedIn, $v, $dtv, $message, $response);
