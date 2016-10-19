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
        if (strlen($this->v->getUserName()) < 3) {
          $this->message = "Username has too few characters, at least 3 characters.";
        }

        else if (strlen($this->v->getPassword()) < 6) {
          $this->message = "Password has too few characters, at least 6 characters.";
        }
    }
  }

  public function getMessage() {
    return $this->message;
  }
}
