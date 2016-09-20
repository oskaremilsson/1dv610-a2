<?php
namespace controller;

require_once('view/LoginView.php');

class Logincontroller {
  private $_PASSWORD = "Password";
  private $_USERNAME = "Admin";

  private $v;
  private $message = "";
  private $usernameID;
  private $passwordID;
  private $keepID;

  function __construct(\view\LoginView $v) {
    $this->v = $v;

    $this->usernameID = $this->v->getUserNameID();
    $this->passwordID = $this->v->getUserPasswordID();
    $this->keepID = $this->v->getKeepID();
  }

  public function isRequest($name) {
    return isset($_POST[$name]);
  }

  public function login() {
    $correct = false;

    $this->checkInput();

    if (isset($_POST[$this->usernameID]) && isset($_POST[$this->passwordID])) {
      $username = $_POST[$this->usernameID];
      $password = $_POST[$this->passwordID];
		  if ($username == $this->_USERNAME && $password == $this->_PASSWORD) {
        //TODO:change this to db-check
			   $_SESSION["isLoggedIn"] = true;
         $correct = true;
         $this->message = "Welcome";
         $this->handleKeep();
		  }
    }

    return $correct;
  }

  private function handleKeep() {
    if (isset($_POST[$this->keepID])) {
      if ($_POST[$this->keepID]) {
        //keep user loggedin
        setcookie("isLoggedIn", true, time() + (86400 * 30), "/");
        setcookie("flashMessage", "Welcome back with cookie" , time() + (86400 * 30), "/");
        $this->message .= " and you will be remembered";
      }
    }
  }

  public function logout() {
    unset($_SESSION['isLoggedIn']);
    setcookie("isLoggedIn", false , time()-1);
    $this->message = "Bye bye!";
  }

  public function checkInput() {
		if (isset($_POST[$this->usernameID]) && isset($_POST[$this->passwordID])) {
				if ($_POST[$this->usernameID] == "") {
					$this->message = "Username is missing";
				}

				else if ($_POST[$this->passwordID] == "") {
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
    if (isset($_COOKIE['flashMessage']) && !isset($_SESSION['isLoggedIn'])) {
      $this->message = $_COOKIE['flashMessage'];
      setcookie("flashMessage", false , time()-1);
    }
  }

}
