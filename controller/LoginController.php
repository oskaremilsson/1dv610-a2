<?php
namespace controller;

require_once('view/LoginView.php');
require_once('model/DatabaseModel.php');

class Logincontroller {
  //private $_PASSWORD = "Password";
  //private $_USERNAME = "Admin";

  private $v;
  private $message = "";
  //private $usernameID;
  //private $passwordID;
  //private $keepID;
  private $database;

  function __construct(\view\LoginView $v, \model\dataBaseModel $database) {
    $this->v = $v;
    $this->database = $database;
    $this->database->connectToDatabase();

    //$this->usernameID = $this->v->getUserNameID();
    //$this->passwordID = $this->v->getUserPasswordID();
    //$this->keepID = $this->v->getKeepID();
  }

  /*public function isRequest($name) {
    return isset($_POST[$name]);
  }*/

  public function login() {
    $correct = false;

    $this->checkInput();

    if ($this->v->userNameExist() && $this->v->passwordExist()) {
      $username = $this->v->getUserName();
      $password = $this->v->getPassword();

      if ($this->database->checkCredentials($username, $password)) {
        //$_SESSION["isLoggedIn"] = true;
        //$session = $this->v->getIsLoggedInSession();
        //$session = true;
        $this->v->setIsLoggedInSession(true);
        $correct = true;
        $this->message = "Welcome";
        $this->handleKeep();
      }
    }

    return $correct;
  }

  private function handleKeep() {
    if ($this->v->keepStatusExist()) {
      if ($this->v->keepIsActivated()) {
        //keep user loggedin
        $this->v->setIsLoggedInCookie(true);
        $this->message .= " and you will be remembered";
      }
    }
  }

  public function logout() {
    //$session = $this->v->getIsLoggedInSession();
    //unset($session);
    $this->v->unsetIsLoggedInSession();

    //setcookie("isLoggedIn", false , time()-1);
    $this->v->setIsLoggedInCookie(false);
    $this->message = "Bye bye!";
  }

  public function checkInput() {
		if ($this->v->userNameExist() && $this->v->passwordExist()) {
				if ($this->v->getUserName() == "") {
					$this->message = "Username is missing";
				}

				else if ($this->v->getPassword() == "") {
					$this->message = "Password is missing";
				}

				else {
					//LOGIN FAILED
					$this->message = "Wrong name or password";
				}
		}
	}

  public function getMessage() {
    return $this->message;
  }

  public function handleFlashMessage() {
    //show and unset flashmessage cookie
    if ($this->v->flashMessageCookieExist() && !$this->v->isLoggedInSessionExist()) {
      $this->message = $this->v->getFlashMessageCookie();
      $this->v->clearFlashMessageCookie();
    }
  }

}
