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

if (isset($_POST['addadmin'])) {
		  
		  echo "<tr><td colspan='2' class='text'>";
         	$username1 = $_POST['username'];
			$password = $_POST['password'];
			$password1 = $_POST['password2'];
			$domain = $_POST['domain'];
			$active = $_POST['active'];
			$superadmin = $_POST['superadmin'];
          	
			if ($password == "" or $password1 == "") {
				$error = "<img src='/images/no.png' /></td><td>Please type in a password and confirm the password.";
			} elseif ($password != $password1) {
				$error = "<img src='/images/no.png' /></td><td>Passwords did not match, please try again.";
			} elseif ($username = "") {
				$error = "<img src='/images/no.png' /></td><td>Please enter in a username!";
			} else {
				$addadmin1 = addadmin($username1,$password,$domain,$active,$superadmin);
			}	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin</title>
<link href="../style2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
-->
</style>
</head>

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
          <td class='text'>Domain Admins </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <?php include('adminmenu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">
<?
 
if (isset($error)) {
	echo "<table class='sample' width='100%'><tr class='text'><td class='text' width='22'>$error</td></tr></table>";
}

if (isset($addadmin1) and $addadmin1 == 1) {
	echo "<table width='100%' class='sample'><tr><td width='22'><img src='/images/ok.png' /></td><td class='text'>Admin successfully added</td></tr></table>";
} elseif (isset($addadmin1)) {
	echo "<table width='100%' class='sample'><tr><td width='22'><img src='/images/no.png' /></td><td class='text'>" .$addadmin1 . " Something went wrong</td></tr></table>";
}
?>
      <form id="form1" name="form1" method="post" action="">
        <table width="50%" border="0" align="center" cellpadding="0" class="main">
          <tr>
            <td colspan="2" bgcolor="#003366"><div align="center" class="boldwhitetext style1"><strong>Create Admin </strong></div></td>
          </tr>
          <tr>
            <td width="48%" background="../images/butonbackground.jpg"  class="text">Username: </td>
            <td width="52%"><label>
              <input name="username" type="text" class="style5" id="username" value="<? echo $_POST['username']; ?>"/>
            </label></td>
          </tr>
          <tr>
            <td background="../images/butonbackground.jpg" class="text">Password:</td>
            <td><label>
              <input name="password" type="password" class="style5" id="password" />
            </label></td>
          </tr>
          <tr>
            <td background="../images/butonbackground.jpg" class="text">Confirm Password:  </td>
            <td><input name="password2" type="password" class="style5" id="password2" /></td>
          </tr>
          <tr>
            <td height="24" background="../images/butonbackground.jpg" class="text">Domain:</td>
            <td><label>
              <select name="domain" class="style5" id="domain">
<?php 
$query = "SELECT domain FROM domain";
if ($dbconfig == "mysqli") { 

	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
		}
	$result= $mysqli->query($query);
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		echo "<option value='$row[0]'>$row[0]</option>'";
	}
	$result->close();
	$mysqli->close();
} else { 
	$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
	mysql_select_db($postfixdatabase) or die('Could not select database');
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result, MYSQL_NUM)){
		echo "<option value='$row[0]'>$row[0]</option>'";
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
              <input name="active" type="checkbox" class="style5" id="active" value="1" />
            </label></td>
          </tr>
			<tr> 
			<td background="../images/butonbackground.jpg" class="text">SuperAdmin:</td>				
<td>
					<input name="superadmin" type="checkbox" class="style5" id="active" value="1" />			  </td>			
			</tr>          
          
          <tr>
            <td height="26" colspan="2" bgcolor="#003366" class="text"><div align="center">
              <label>
              <input name="addadmin" type="hidden" id="addadmin" value="yes" />
              <input name="Submit" type="submit" class="footertext" value="Submit" />
              </label>
            </div></td>
            </tr>
        </table>
        </form>
      </div>
<table width="100%" border="0" align="center" cellpadding="0" class="main" style="main">
 <tr><td colspan="4" bgcolor="#003366"><div align="center" class="boldwhitetext"><strong>Admin List</strong></div></td>
</tr>
 <tr>
   <td background="../images/butonbackground.jpg" class="text"><div align="center">Username</div></td>
   <td background="../images/butonbackground.jpg" class="text"><div align="center">Domain</div></td>
   <td background="../images/butonbackground.jpg" class="text"><div align="center">Active</div></td>
   <td background="../images/butonbackground.jpg" class="text"><div align="center">Delete/Edit</div></td>
 </tr>
<?php
$query = "SELECT * FROM admin";
if ($dbconfig == "mysqli") { 
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if (mysqli_connect_errno()) {
					printf("Connect failed: %s\n", mysqli_connect_error());
					exit();
		}

	$result = $mysqli->query($query);
	$i = 0;
	while ($row=$result->fetch_array(MYSQLI_NUM)) {
		if ($i == 1){
			$background = "bgcolor='#F2F2F2'";
			$i=0;
		} else {
			$background = "bgcolor = '#FFFFFF'";
			$i=1;
		}		
		echo "<tr class='style5' $background><td>$row[0]</td><td>$row[2]</td>";
		if ($row[6] == 1) {
			echo "<td><center>Yes</center></td>";
		} else {	
			echo "<td><center>No</center></td>";
		}
		echo "<td><a href='editadmin.php?user=$row[0]&domain=$row[2]'>Edit</a> / <a href='deladmin.php?user=$row[0]'>Delete</a></td></tr>";
	}
} else { 
	$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
	mysql_select_db($postfixdatabase) or die('Could not select database');
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) { 
		if ($i == 1){
			$background = "bgcolor='#F2F2F2'";
			$i=0;
		} else {
			$background = "bgcolor = '#FFFFFF'";
			$i=1;
		}		
		echo "<tr class='style5' $background><td>$row[0]</td><td>$row[2]</td>";
		if ($row[6] == 1) {
			echo "<td>Yes</td>";
		} else {	
			echo "<td>No</td>";
		}
		echo "<td><a href='editadmin.php?user=$row[0]&domain=$row[2]'>Edit</a> / <a href='deladmin.php?user=$row[0]'>Delete</a></td></tr>";
	}
}

?>
</table>	</td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
