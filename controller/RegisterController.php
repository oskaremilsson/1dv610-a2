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
    $this->checkInput();
    $this->database->registerNewUser($username, $password);
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
}
