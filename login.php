<?php

require_once("config/config.php");
require 'check_login.php';

if ($loggedin == 1 and $superadmin == 1) {
	$url = $siteurl . "admin/index.php";
	header('Location:'. $url);
} elseif($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "index.php";
	header('Location:'. $url);
} else {


if (isset($_POST['login'])) {
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	
	
	if ($username == "" or $password == "") {
		
		$error = "Please enter in a username and password and try again";

	} else {

		$query = "SELECT * FROM admin WHERE username = '$username' AND password = SHA1('$password')";
		
		if ($dbconfig == "mysqli") { 
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
	
			if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
					exit();
			}
		
			$result = $mysqli->query($query);
			$row = $result->fetch_array(MYSQLI_NUM);
			$numrows = $result->num_rows;
		} else { 
			$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
			mysql_select_db($postfixdatabase) or die('Could not select database');
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$row = mysql_fetch_array($result, MYSQL_NUM);
			$numrows = mysql_num_rows($result);
		}

	
		
		
		if ($row[7] == 1 and $numrows == 1 and $row[6] == 1) {
			
			$updatequery = "UPDATE admin set lastlogin = NOW() WHERE username = '$username'";
			
			if ($dbconfig == "mysqli") {
				$loginupdate = $mysqli->query($updatequery);
			} else { 
				$loginupdate = mysql_query($updatequery);
			}
			$_SESSION['username'] = $username;
			$_SESSION['password'] = SHA1($password);
			$_SESSION['domain'] = $row[2];
			$_SESSION['superadmin'] = $row[7];
						
			
			$url = $siteurl . "admin/index.php";
			header('Location:'. $url);
			
		} elseif ($numrows == 0){
			$error =  "Username and Password Invalid, Please try again.";

		} elseif ($row[7] == 0 and $numrows == 1 and $row[6] == 1) {
	
			$updatequery = "UPDATE admin set lastlogin = NOW() WHERE username = '$username'";
			if ($dbconfig == "mysqli") {
				$loginupdate = $mysqli->query($updatequery);
			} else { 
				$loginupdate = mysql_query($updatequery);
			}
	
			$_SESSION['username'] = $username;
			$_SESSION['password'] = SHA1($password);
			$_SESSION['domain'] = $row[2];
			$_SESSION['superadmin'] = $row[7];
	
			$url = $siteurl . "index.php";
			header('Location:'. $url);
		} elseif ($numrows == 1 and $row [6] == 0) {
			$error =  "Account Disabled, Contact Administrator";
		}
	if ($dbconfig == "mysqli") {
		$result->close();
		$mysqli->close();
	} else {
		mysql_free_result($result);
	}
}
}
 require_once("functions.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin</title>
<style type="text/css">
<!--
.style5 {font-family: Verdana; font-size: 9px; }
-->
</style>
<link href="style2.css" rel="stylesheet" type="text/css" /></head>

<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td class="boldtext"><div align="left"><img src="images/postvisadmin.png" width="288" height="41" /></div></td>
  </tr>
  <tr>
    <td class="text"><div id="title">
      <table class='sample' width='100%'>
        <tr>
          <td class='text'>Login</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">      <form id="form1" name="form1" method="post" action="">
<? 
if (isset($error)) {
	echo "<table class='sample' width=47% align='center'><td width='22'><img src='/images/no.png' /></td><td class='text'>$error</td></tr></table>";
}

?>
        <table width="47%" border="0" align="center" class="main">
          <tr>
            <td colspan="2" background="images/butonbackground.jpg"><div align="center" class="text">Please Login </div></td>
          </tr>
          <tr>
            <td width="49%" class="text">Username</td>
            <td width="51%"><label>
              <input name="username" type="text" class="text" id="username" />
            </label></td>
          </tr>
          <tr>
            <td class="text">Password</td>
            <td><label>
              <input name="password" type="password" class="text" id="password" />
            </label></td>
          </tr>
          <tr>
            <td colspan="2" class="text"><div align="center">
              <label>
              <input name="Submit" type="submit" class="text" value="Submit" />
              </label>
              <input name="login" type="hidden" id="login" />
            </div></td>
          </tr>
        </table>
        <div align="center"></div>
      </form></td>
  </tr>
  <tr>
    <td><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
<?php  } ?>
