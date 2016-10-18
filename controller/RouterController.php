<?php
namespace controller;

require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('model/DatabaseModel.php');
require_once('controller/LoginController.php');

class RouterController {
  private $cookiePassword;
  private $isLoggedIn = false;
  private $message = "";
  private $loginView;
  private $dateTimeView;
  private $layoutView;
  private $LoginController;
  //private $database;

  function __construct($database) {
    $daysInSeconds = 86400;
    $daysInMonth = 30;
    $cookieLifeTime = time() + ($daysInSeconds * $daysInMonth);

    //CREATE OBJECTS OF THE VIEWS
    $this->loginView = new \view\LoginView();
    $this->dateTimeView = new \view\DateTimeView();
    $this->layoutView = new \view\LayoutView();
    $this->loginController = new \controller\LoginController($this->loginView, $database);
    //$this->database = $database;
    $cookiePassword = md5("test");

    //$this->database->connectToDatabase();

    setcookie("LoginView::CookiePassword", $cookiePassword, $cookieLifeTime, "/");
  }

  public function route() {
    $this->checkLoggedInStatus();

    $this->checkCookiePassword();

    $response = $this->loginView->response($this->isLoggedIn, $this->message);
    $this->layoutView->render($this->isLoggedIn, $this->loginView, $this->dateTimeView, $this->message, $response);
  }

  private function checkCookiePassword() {
    if(isset($_COOKIE['LoginView::CookiePassword'])) {
       if ($_COOKIE['LoginView::CookiePassword'] != md5("test")) {
         $this->Logincontroller->logout();
         $this->isLoggedIn = false;
         $this->message = "Wrong information in cookies";
       }
    }
  }

  private function checkLoggedInStatus() {
    if (isset($_COOKIE['isLoggedIn'])) {
      //cookie with isLoggedIn exist
      $this->isLoggedIn = $_COOKIE['isLoggedIn'];
      $this->loginController->handleFlashMessage();

      if ($this->loginController->isRequest($this->loginView->getLogoutID())) {
        //logout form sent, handle it
        $this->loginController->logout();
        $this->isLoggedIn = false;
      }

      $this->message = $this->loginController->getMessage();
    }
    else if (isset($_SESSION["isLoggedIn"])) {
      $this->isLoggedIn = $_SESSION["isLoggedIn"];

      if ($this->loginController->isRequest($this->loginView->getLogoutID())) {
        //logout form sent, handle it
        $this->loginController->logout();
        $this->isLoggedIn = false;
        $this->message = $this->loginController->getMessage();
      }
    }
    else {
      if ($this->loginController->isRequest($this->loginView->getLoginID())) {
        //login form sent, handle it
        $this->isLoggedIn = $this->loginController->login();
        $this->message = $this->loginController->getMessage();
      }
    }
  }
}
