<?php

namespace model;

class DatabaseModel {

  private $username;
  private $password;
  private $host;
  private $database;

  function __construct($host, $database, $username, $password) {
    $this->host = $host;
    $this->database = $database;
    $this->username = $username;
    $this->password = $password;
  }

  public function connectToDatabase() {
    try {
      $db = new \PDO('mysql:host='.$this->host.';dbname='.$this->database, $this->username, $this->password);
    } catch(\PDOException $e) {
      return false;
    }
    return true;
  }

}
