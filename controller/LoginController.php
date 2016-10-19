<?php
namespace controller;

require_once('view/LoginView.php');
require_once('model/DatabaseModel.php');

class Logincontroller {

  private $v;
  private $message = "";
  private $database;

  function __construct(\view\LoginView $v, \model\dataBaseModel $database) {
    $this->v = $v;
    $this->database = $database;
    $this->database->connectToDatabase();
  }

  public function login() {
    $correct = false;

    $this->checkInput();

    if ($this->v->userNameExist() && $this->v->passwordExist()) {
      $username = $this->v->getUserName();
      $password = $this->v->getPassword();

      if ($this->database->checkCredentials($username, $password)) {
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
    $this->v->unsetIsLoggedInSession();
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
