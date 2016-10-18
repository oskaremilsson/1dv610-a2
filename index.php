<?php
session_start();
//INCLUDE THE FILES NEEDED...
require_once('settings.php');
require_once('model/DatabaseModel.php');
require_once('controller/RouterController.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$database = new \model\DatabaseModel($dbHost, $dbName, $dbUsername, $dbPassword);

$routerController = new \controller\RouterController($database);
$routerController->route();
