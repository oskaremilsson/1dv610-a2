<?php
namespace controller;

require_once('view/LoginView.php');

class Logincontroller {
  private $username;
  private $password;
  private $v;

  function __construct($username, $password) {
    $this->username = $username;
    $this->password = $password;

    $this->v = new \view\LoginView();
  }

  public function getView() {
    return $this->v->response("test");
  }

}
