<?php

require 'config/config.php';
require 'check_login.php';

if ($loggedin == 0) {
	die('You are not logged in so you cannot log out.');
}


unset($_SESSION['username']);
unset($_SESSION['password']);

$_SESSION = array(); 
session_destroy();  
header('Location: login.php');

?>