<?php
namespace controller;

require_once('view/RegisterView.php');
require_once('model/DatabaseModel.php');

class RegisterController {
  private $v;
  private $message = "";
  private $database;

  function __construct(\view\RegisterView $v, \model\dataBaseModel $database) {
    $this->v = $v;
    $this->database = $database;
    $this->database->connectToDatabase();
  }

  public function registerNewUser($username, $password) {
    if($this->checkInput()) {
      if(!$this->database->registerNewUser($username, $password)) {
        $this->message = "User exists, pick another username.";
        return false;
      }
      else {
        return true;
      }
    }
  }

  public function checkInput() {
    if ($this->v->userNameExist() && $this->v->passwordExist()) {
        if (strlen($this->v->getUserName()) < 3) {
          $this->message = "Username has too few characters, at least 3 characters.";
          return false;
        }

        else if (strlen($this->v->getPassword()) < 6) {
          $this->message = "Password has too few characters, at least 6 characters.";
          return false;
        }
    }
    return true;
  }

  public function getMessage() {
    return $this->message;
  }
}
