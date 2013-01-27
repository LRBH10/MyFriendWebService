<?php
include_once 'classes/Controller.php';
include_once 'admin/Connection.php';
include_once 'admin/OwerUser.php';
include_once 'help/Alert.php';


define('API_SUCCESS', "success");
define('API_ERROR', "error");

Connection::getDbMapper("myfriends");
?>
