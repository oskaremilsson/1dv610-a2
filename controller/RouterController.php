<?php
namespace controller;

require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('model/DatabaseModel.php');
require_once('controller/LoginController.php');
require_once('settings.php');

class RouterController {
  //private $cookiePassword;
  private $isLoggedIn = false;
  private $message = "";
  private $loginView;
  private $dateTimeView;
  private $layoutView;
  private $LoginController;

  function __construct($database) {
    $this->loginView = new \view\LoginView();
    $this->dateTimeView = new \view\DateTimeView();
    $this->layoutView = new \view\LayoutView();
    $this->loginController = new \controller\LoginController($this->loginView, $database);

    //setcookie("LoginView::CookiePassword", $cookiePassword, $cookieLifeTime, "/");
    $this->loginView->setCookiePasswordCookie();
  }

  public function route() {
    $this->checkLoggedInStatus();
    $this->checkCookiePassword();

    if ($this->loginView->isUserLoggingIn()) {
      //route to login
      $this->isLoggedIn = $this->loginController->login();
      $this->message = $this->loginController->getMessage();
    }

    $response = $this->loginView->response($this->isLoggedIn, $this->message);
    $this->layoutView->render($this->isLoggedIn, $this->loginView, $this->dateTimeView, $this->message, $response);
  }

  private function checkCookiePassword() {
    if($this->loginView->cookiePassWordCookieExist()) {
       if ($this->loginView->getCookiePasswordCookie() != md5("test")) {
         $this->loginController->logout();
         $this->isLoggedIn = false;
         $this->message = "Wrong information in cookies";
       }
    }
  }

  private function checkLoggedInStatus() {
    if ($this->loginView->isLoggedInCookieExist()) {
      //cookie with isLoggedIn exist
      $this->isLoggedIn = $this->loginView->getIsLoggedInCookie();
      $this->loginController->handleFlashMessage();

      if ($this->loginView->isUserLoggingOut()) {
        //logout form sent, handle it
        $this->loginController->logout();
        $this->isLoggedIn = false;
      }

      $this->message = $this->loginController->getMessage();
    }
    else if ($this->loginView->isLoggedInSessionExist()) {
      $this->isLoggedIn = $this->loginView->getIsLoggedInSession();

      if ($this->loginView->isUserLoggingOut()) {
        //logout form sent, handle it
        $this->loginController->logout();
        $this->isLoggedIn = false;
        $this->message = $this->loginController->getMessage();
      }
    }
    else {
      /*if ($this->loginController->isRequest($this->loginView->getLoginID())) {
        //login form sent, handle it
        $this->isLoggedIn = $this->loginController->login();
        $this->message = $this->loginController->getMessage();
      }*/
    }
  }
}
