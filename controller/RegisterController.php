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
    $this->database->registerNewUser($username, $password);
  }
}
