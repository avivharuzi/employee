<?php

session_start();

date_default_timezone_set("Israel");

require_once("connection/db.php");
require_once("models/employee.php");
require_once("handlers/message-handler.php");
require_once("handlers/validation-handler.php");
require_once("handlers/employee-handler.php");

?>
