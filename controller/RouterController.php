<?php
namespace controller;

require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('model/DatabaseModel.php');
require_once('controller/LoginController.php');
require_once('controller/RegisterController.php');
require_once('settings.php');

class RouterController {
  //private $cookiePassword;
  private $isLoggedIn = false;
  private $message = "";
  private $loginView;
  private $registerView;
  private $dateTimeView;
  private $layoutView;
  private $loginController;
  private $registerController;

  function __construct($database) {
    $this->loginView = new \view\LoginView();
    $this->registerView = new \view\RegisterView();
    $this->dateTimeView = new \view\DateTimeView();
    $this->layoutView = new \view\LayoutView();
    $this->loginController = new \controller\LoginController($this->loginView, $database);
    $this->registerController = new \controller\RegisterController($this->registerView, $database);

    $this->loginView->setCookiePasswordCookie();
  }

  public function route() {
    if ($this->registerView->isUserRequestingRegister()) {
      if ($this->registerView->isUserSendingNewRegister()) {
        //new user posted, get the user from view
        $username = $this->registerView->getUserName();
        $password = $this->registerView->getPassword();
        $this->registerController->registerNewUser($username, $password);
      }

      $this->message = $this->registerController->getMessage();
      $response = $this->registerView->response($this->message, $username);
    }
    else {
      $this->checkLoggedInStatus();
      $this->checkCookiePassword();

      if ($this->loginView->isUserLoggingIn()) {
        //route to login
        $this->isLoggedIn = $this->loginController->login();
        $this->message = $this->loginController->getMessage();
      }

      $response = $this->loginView->response($this->isLoggedIn, $this->message);
    }
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
  }
}
