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

  function __construct(\view\LoginView $v) {
    $this->v = $v;

    $this->usernameID = $this->v->getUserNameID();
    $this->passwordID = $this->v->getUserPasswordID();
  }

  public function isRequest($name) {
    return isset($_POST[$name]);
  }

  public function login() {
    if (isset($_POST[$this->usernameID]) && isset($_POST[$this->passwordID])) {
      $username = $_POST[$this->usernameID];
      $password = $_POST[$this->passwordID];
		  if ($username == $this->_USERNAME && $password == $this->_PASSWORD) {
			   $_SESSION["isLoggedIn"] = true;
		  }
    }

    $this->checkInput();
  }

  public function logout() {
    unset($_SESSION['isLoggedIn']);
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
					if($_SESSION["isLoggedIn"]) {
						//LOGIN SUCCESSFUL
						$this->message = "Welcome";
					}
					else {
						//LOGIN FAILED
						$this->message = "Wrong name or password";
					}
				}
		}
	}

  public function getView() {
    return $this->v->response("test");
  }

  public function getMessage() {
    return $this->message;
  }

}
