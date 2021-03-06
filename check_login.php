<?php

require('config/config.php');

session_start();

if (!isset($_SESSION['username']) or !isset($_SESSION['password'])) {
	$loggedin = 0;
	return;
} else {

   // 210405 GRBOFR use vmail mailbox table for authentifications
	//$query = "SELECT password , superadmin, domain, active FROM admin WHERE username ='" . $_SESSION['username']. "'";
	$query = "SELECT password, isadmin, domain, active FROM mailbox WHERE username = ?";
   
	if ($dbconfig == "mysqli") { 
	
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $authentdatabase);
      #if (mysqli_connect_errno()) {
      #   printf("Connect failed: %s\n", mysqli_connect_error());
      #   exit();
      #}

      if( $stmt = $mysqli->prepare($query) ) {
         $stmt->bind_param("s", $_SESSION['username']);
         $stmt->execute();
         $result = $stmt->get_result();
         $row = $result->fetch_assoc();
      }  else {
         die( $mysqli->error);
      }
	} else {
      die("configuration error");
	}	
	$dbpass1 = $row["password"];
	$domain = $row["domain"];
	$active = $row["active"];
   if (($_SESSION['password'] == $dbpass1)) {
      $_SESSION['domain'] = $domain;
      if($row["isadmin"] == 1) {
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
   die("Configuration error");
}	
}

	
?>