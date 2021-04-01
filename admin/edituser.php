<?php
require_once("../functions.inc.php");
require '../check_login.php';

if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "/index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
}  
 require_once("../config/config.php");



if (isset($_POST['edituser'])) {

	
	
	$name = $_POST['name'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
	$quota = $_POST['quota'];
	$active = $_POST['active'];
	$username = $_POST['username'];
	$domain = $_POST['domain'];
	$amavis = $_POST['amavis'];
	$priority = $_POST['priority'];
	$quarantine_notify = $_POST['quarantine_notify'];
	
	if ($password1=="" and $password2=="") {
	
		$postfixquery = "UPDATE mailbox set name = '$name', quota = '$quota', modified = NOW(), active = '$active', quarantine_notify = '$quarantine_notify' WHERE username = '$username'";
		
		$amavisquery = "UPDATE users set priority = '$priority', policy_id = '$amavis' WHERE email = '$username'";
		
		if ($dbconfig == "mysqli") {
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
			$result = $mysqli->query($postfixquery);
			$result = $mysqli->query($amavisquery);
			$error = $error . "<img src='/images/ok.png' /></td><td>Filtering Options Adjusted";
			$mysqli->close();
		} else { 
			$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
			mysql_select_db($postfixdatabase) or die('Could not select database');	
			$result = mysql_query($postfixquery);
			$result = mysql_query($amavisquery);
			$error = $error . "<img src='/images/ok.png' /></td><td>Filtering Options Adjusted";
		}
		
	} else {
			
	
		if ($password1 == $password2) {
		
			$password = cryptpassword($password1);
			$query = "UPDATE mailbox set name = '$name', password = '$password', quota = '$quota', modified = NOW(), active = '$active', quarantine_notify = '$quarantine_notify' WHERE username = '$username'";
			$amavisquery = "UPDATE users set priority = '$priority', policy_id = '$amavis' WHERE email = '$username'";
			
			if ($dbconfig == "mysqli") {
			
				$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
				$result = $mysqli->query($query);
				$result = $mysqli->query($amavisquery);
				$error = "<img src='/images/ok.png' /></td><td>Password and Information Updated.";
				$mysqli->close();
			} else  {
				$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
				mysql_select_db($postfixdatabase) or die('Could not select database');	
				$result = mysql_query($query);
				$result = mysql_query($amavisquery);
				$error = "<img src='/images/ok.png' /></td><td>Password and Information Updated.";
			}
		} else { 
		
			$error = "<img src='/images/no.png' /></td><td>Passwords did not match, please try again";

		}
	}

}

$userinfo = new UserInfo;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin</title>
<link href="../style2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
</head>

<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="2" class="boldtext"><div align="left"><img src="../images/postvisadmin.png" width="288" height="41" /></div></td>
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
          <td class='text'>Edit User</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top"><? include('adminmenu.php'); ?><br />    </td>
    <td valign="top" class="main">
	
<?
if (isset($error)) {
	echo "<table class='sample' width='100%'><tr class='text'><td class='text' width='22'>$error</td></tr></table>";
}
?>
	
	<form id="form1" name="form1" method="post" action="">
      <table width="50%" border="0" align="center" class="main">
        <tr>
          <td colspan="2" bgcolor="#003366"><div align="center" class="text style1"><strong>Edit User </strong></div></td>
        </tr>
        <tr>
          <td width="50%" background="/images/butonbackground.jpg" class="text">Email Address</td>
          <td width="50%" class="text"><? echo $userinfo->username; ?></td>
        </tr>
        <tr>
          <td background="/images/butonbackground.jpg" class="text">Full Name </td>
          <td class="text"><input name="name" type="text" class="text" id="name" value="<? echo $userinfo->name ?>"/></td>
        </tr>
        <tr>
          <td background="/images/butonbackground.jpg" class="text">Password</td>
          <td class="text"><input name="password1" type="password" class="text" id="password1" /></td>
        </tr>
        <tr>
          <td background="/images/butonbackground.jpg" class="text">Retype Password </td>
          <td class="text"><input name="password2" type="password" class="text" id="password2" /></td>
        </tr>
        <tr>
          <td background="/images/butonbackground.jpg" class="text">Quota</td>
          <td class="text"><input name="quota" type="text" class="text" id="quota" value="<? echo $userinfo->quota ?>"/></td>
        </tr>
        <tr>
          <td background="/images/butonbackground.jpg" class="text">Active</td>
          <td class="text"><input name="active" type="checkbox" class="text" id="active" value="1" 
		  
		  
<? 
	if ($userinfo->active == 1) {
		echo "checked='checked'";
	}
		  
		  ?>/></td>
        </tr>
        <tr>
          <td background="/images/butonbackground.jpg" class="text">Quarantine Notification</td>
          <td class="text"><input name="quarantine_notify" type="checkbox" class="text" id="quarantine_notify" value="1" 
		  
		  
<? 
	if ($userinfo->quarantine_notify == 1) {
		echo "checked='checked'";
	}
		  
		  ?>/></td>
        </tr>
        <tr>
          <td background="/images/butonbackground.jpg" class="text">Filter Settings </td>
          <td class="text"><label>
            <select name="amavis" class="text" id="amavis">
<? 
$policyquery = "SELECT * FROM policy";
if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
	$result = $mysqli->query($policyquery);
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		if ($userinfo->filterpolicy == $row[1]) {
			echo '<option value="' . $row[0] . '" selected >' . $row[1] . '</option>';
		} else {
			echo "<option value='$row[0]'>$row[1]</option>";
		}
	}	
	$result->close();
	$mysqli->close();
} else {
	
	mysql_select_db($postfixdatabase) or die('Could not select database');
	$result = mysql_query($policyquery);
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		if ($userinfo->filterpolicy == $row[1]) {
			echo '<option value="' . $row[0] . '" selected >' . $row[1] . '</option>';
		} else {
			echo "<option value='$row[0]'>$row[1]</option>";
		}
	}
}
			?>
			</select>
          </label></td>
        </tr>
        <tr>
          <td background="/images/butonbackground.jpg" class="text">Priority</td>
          <td class="text"><label>
            <select name="priority" class="text" id="priority">
              <option value="0" selected="selected" <? if ($userinfo->priority == 0) { echo "selected";} ?>>0</option>
              <option value="1" <? if ($userinfo->priority == 1) { echo "selected";} ?>>1</option>
              <option value="2" <? if ($userinfo->priority == 2) { echo "selected";} ?>>2</option>
              <option value="3" <? if ($userinfo->priority == 3) { echo "selected";} ?>>3</option>
              <option value="4" <? if ($userinfo->priority == 4) { echo "selected";} ?>>4</option>
              <option value="5" <? if ($userinfo->priority == 5) { echo "selected";} ?>>5</option>
              <option value="6" <? if ($userinfo->priority == 6) { echo "selected";} ?>>6</option>
              <option value="7" <? if ($userinfo->priority == 7) { echo "selected";} ?>>7</option>
              <option value="8" <? if ($userinfo->priority == 8) { echo "selected";} ?>>8</option>
              <option value="9" <? if ($userinfo->priority == 9) { echo "selected";} ?>>9</option>
            </select>
            <span class="footertext">          Default: 7 </span></label></td>
        </tr>
        <tr>
          <td height="30" colspan="2" bgcolor="#003366" class="text"><div align="center">
            <input name="Submit" type="submit" class="text" value="Submit" />
            <input name="edituser" type="hidden" id="edituser" value="submitedit" />
            <input type="hidden" name="domain" value="<? echo $_GET['domain']; ?>" />
            <input name="username" type="hidden" id="username" value="<? echo $userinfo->username ?>" />
          </div></td>
          </tr>
      </table>
      <div align="center"><br />
        <a href="users.php?domain=<? echo $_GET['domain']; ?>" class="text">Back to Domain Details </a></div>
	</form>    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
