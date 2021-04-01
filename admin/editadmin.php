<?php

 require_once("../config/config.php");
require '../check_login.php';

if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "/index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
}

require_once("../functions.inc.php");

if (isset($_POST['editadmin'])) {
	$username1 = $_POST['username'];
	
	$password = $_POST['password'];
	$password1 = $_POST['password1'];
	$domain = $_POST['domain'];
	$active = $_POST['active'];
	$superadmin1 = $_POST['superadmin1'];
	
	if ($password != $password1) {
		$error = "Passwords do not match! Please try again";
	}
	
	if ($active != 1) {
		$active = 0;
	}
	
	if ($superadmin1 != 1) {
		$SuperAdmin1 = 0;
	}
	
	if ($password == "" and $password1 == "") {
		$query = "UPDATE admin set domain = '$domain', modified = NOW(), active = '$active', superadmin = '$superadmin1' WHERE username = '$username1'";
		
		if ($dbconfig == "mysqli") { 
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
				if (mysqli_connect_errno()) {
					printf("Connect failed: %s\n", mysqli_connect_error());
					exit();
				}
			$results = $mysqli->query($query);
			$rows_affected = $mysqli->affected_rows;
			$mysqli->close();
		} else { 
			$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
			mysql_select_db($postfixdatabase) or die('Could not select database');
			$result = mysql_query($query);
			$rows_affected = mysql_affected_rows($link);
			
		}
		
		if ($rows_affected == 1) {
		$error = "<img src='/images/ok.png' /></td><td>Settings Updated.";
		} else {
			$error = "There was an error, nothing updated<br><br> " . $mysqli->error . "<br><br>$query";
		}
				
	} elseif ($password == $password1 and ($password != "" or $password !="")) {
		$query = "UPDATE admin set domain = '$domain', modified = NOW(), active = '$active', password = SHA1('$password'), superadmin = '$superadmin1' WHERE username = '$username1'";

		if ($dbconfig == "mysqli") { 
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
				if (mysqli_connect_errno()) {
   					printf("Connect failed: %s\n", mysqli_connect_error());
					exit();
				}
			$results = $mysqli->query($query);
			$rows_affected = $mysqli->affected_rows;
			$mysqli->close();
		} else { 
			$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
			mysql_select_db($postfixdatabase) or die('Could not select database');
			$result = mysql_query($query);
			$rows_affected = mysql_affected_rows($link);
			mysql_free_result($result);
		}		
		if ($rows_affected == 1) {
			$error = "<img src='/images/ok.png' /></td><td>Settings and Password Updated.";
		} else {
			$error = "There was an error, nothing updated<br><br> " . $mysqli->error . "<br><br>$query";
		}
		
		
	} else {
		$error = "<img src='/images/no.png' /></td><td>There was nothing done";
	}
}

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
<link href="../style2.css" rel="stylesheet" type="text/css" /></head>

<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="2" class="boldtext"><div align="center">
      <div align="left"><img src="../images/postvisadmin.png" width="288" height="41" /></div>
    </div></td>
  </tr>
  <tr>
    <td width="160" class="text"><table width="100%" class="sample">
      <tr>
        <td  class="text">&nbsp;</td>
      </tr>
    </table></td>
    <td width="728"><div id="title">
      <table class='sample' width='100%'>
        <tr>
          <td class='text'>Edit Domain Admin </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <? include('adminmenu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">
<? if (isset($error)) {
	echo "<table class='sample' width='100%'><tr class='text'><td class='text' width='22'>$error</td></tr></table>";
}
?>
    <br /><form id="form1" name="form1" method="post" action="">
        <table width="50%" border="0" align="center" class="main">
          <tr>
            <td colspan="2" bgcolor="#003366" class="boldwhitetext"><div align="center"><strong>Edit Admin </strong></div>
			<? 
if (isset($_POST['username'])) {
	$username = $_POST['username'];
	$domain = $_POST['domain'];
} else {
	$username = $_GET['user'];
	$domain = $_GET['domain'];
}
$query = "SELECT * FROM admin WHERE username ='$username' and domain = '$domain'";

if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if (mysqli_connect_errno()) {
   			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$adminresult= $mysqli->query($query);
	$row1 = $adminresult->fetch_array(MYSQLI_NUM);
} else {
	$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
	mysql_select_db($postfixdatabase) or die('Could not select database');
	$adminresult = mysql_query($query);
	$row1 = mysql_fetch_array($adminresult, MYSQL_NUM);
}






			
?>			</td>
          </tr>
          <tr>
            <td width="36%" background="../images/butonbackground.jpg" class="text">Username: </td>
            <td width="64%"><label>
              <input name="username" type="text" class="text" id="username" value="<? echo $row1[0]; ?>"/>
            </label></td>
          </tr>
          <tr>
            <td background="../images/butonbackground.jpg" class="text">Password:</td>
            <td><label>
              <input name="password" type="password" class="text" id="password" />
            </label></td>
          </tr>
          <tr>
            <td background="../images/butonbackground.jpg" class="text">Confirm Password:  </td>
            <td><input name="password1" type="password" class="text" id="password1" /></td>
          </tr>
          <tr>
            <td background="../images/butonbackground.jpg" class="text">Domain:</td>
            <td><label>
              <select name="domain" class="text" id="domain">
<?php 

$query = "SELECT domain FROM domain";
if ($dbconfig == "mysqli") {
	$result= $mysqli->query($query);
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		if ($row[0] == $_GET['domain']) {
			echo "<option value='$row[0]' selected>$row[0]</option>'";
		} else {
			echo "<option value='$row[0]'>$row[0]</option>'";
		}
	
	}
	$result->close();
	$mysqli->close();
} else { 
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		if ($row[0] == $_GET['domain']) {
			echo "<option value='$row[0]' selected>$row[0]</option>'";
		} else {
			echo "<option value='$row[0]'>$row[0]</option>'";
		}
	}
	mysql_free_result($result);
}
?>
			  </select>
            </label></td>
          </tr>
          <tr>
            <td background="../images/butonbackground.jpg" class="text">Active:</td>
            <td><label>
              <input name="active" type="checkbox" class="style5" id="active" value="1" <? if ($row1[6] == 1) { echo "checked='checked'"; } ?> />
            </label></td>
          </tr>
			<tr> 
			<td background="../images/butonbackground.jpg" class="text">SuperAdmin:</td>				
<td>
					<input name="superadmin1" type="checkbox" class="style5" id="active" value="1" <? if ($row1[7] == 1) { echo "checked='checked'"; } ?>  />			  </td>			
			</tr>          
          
          <tr>
            <td height="26" colspan="2" bgcolor="#003366" class="text"><div align="center">
              <label>
              <input name="editadmin" type="hidden" id="addadmin" value="yes" />
              <input name="Submit" type="submit" class="style5" value="Submit" />
              </label>
            </div></td>
            </tr>
        </table>
        <p align="center"><a href="domainadmins.php" class="text">Back to Domain Admins</a></p>
    </form>
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
