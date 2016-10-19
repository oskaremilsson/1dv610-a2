<?php

namespace model;

class DatabaseModel {

  private $username;
  private $password;
  private $host;
  private $databaseName;
  private $connection;

  function __construct($host, $databaseName, $username, $password) {
    $this->host = $host;
    $this->databaseName = $databaseName;
    $this->username = $username;
    $this->password = $password;
    $this->connection = false;
  }

  public function connectToDatabase() {
    try {
      $this->connection = new \PDO('mysql:host='.$this->host.';dbname='.$this->databaseName, $this->username, $this->password);
      $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
      $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch(\PDOException $e) {
      return false;
    }
    return true;
  }

  public function checkCredentials($username, $password) {
    try {
      //$sql = 'SELECT * FROM users WHERE $username ';
      $stmt = $this->connection->prepare('SELECT * FROM users WHERE name = :name AND password = :password');
      $stmt->execute(array('name' => $username, 'password' => $password));

      //var_dump($stmt->rowCount());

      if ($stmt->rowCount() > 0) {
        return true;
      }
    } catch(\PDOException $e) {
      //var_dump($e);
      return false;
    }

    return false;
  }

}
