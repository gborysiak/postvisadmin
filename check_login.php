<?php

require('config/config.php');

session_start();

if (!isset($_SESSION['username']) or !isset($_SESSION['password'])) {
	$loggedin = 0;
	return;
} else {

	$query = "SELECT password , superadmin, domain, active FROM admin WHERE username ='" . $_SESSION['username']. "'";
	
	if ($dbconfig == "mysqli") { 
	
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		
			if (mysqli_connect_errno()) {
					printf("Connect failed: %s\n", mysqli_connect_error());
					exit();
			}
		$result= $mysqli->query($query);
		$row = $result->fetch_array(MYSQLI_NUM);	
	
	} else {
	
		$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
		mysql_select_db($postfixdatabase) or die('Could not select database');
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$row = mysql_fetch_array($result, MYSQL_NUM);
	}	
	
	
	$dbpass1 = $row[0];
	$domain = $row[2];
	$active = $row[3];
		if (($_SESSION['password'] == $dbpass1)) {
			$_SESSION['domain'] = $domain;
			if($row[1] == 1) {
				$superadmin = 1;
			} else {
				$superadmin = 0;
			}			
			if ($active = 0) {
				$error = "<table class='sample' width='100%'><tr class='text'><td class='text' width='22'><img src='/images/no.png' /></td><td>Account Disabled, Please Contact Administrator</td></tr></table>";
				$loggedin = 0;
			} else {			
				$loggedin = 1;
			}		
		} else {
			$loggedin = 0;
			unset($_SESSION['username']);
			unset($_SESSION['password']);
		}
	
if ($dbconfig == "mysqli") {
	$result->close();
	$mysqli->close();
} else {
	mysql_free_result($result);
}	
}

	
?>