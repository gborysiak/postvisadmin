<?php
 require_once("functions.inc.php");
 require 'check_login.php';

if ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
} 
require_once("config/config.php");


if (isset($_POST['editalias'])) {
	$address = $_POST['address'];
	$gotoaddress = $_POST['gotoaddress'];
	$domain = $_POST['domain'];
	$query1 = "UPDATE alias set goto = '$gotoaddress', modified = NOW() WHERE address = '$address' LIMIT 1";
	
	if ($dbconfig == "mysqli") {
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);

		if (mysqli_connect_errno()) {
   			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		$result = $mysqli->query($query1);
		$rowsaffected = $mysqli->affected_rows;
		$mysqli->close();
	} else { 
		$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
		mysql_select_db($postfixdatabase) or die('Could not select database');
		$result = mysql_query($query1);
		$rowsaffected = mysql_affected_rows($link);
		mysql_close($link);
	}		
		
		$error = "Alias Updated: $rowsaffected Modified";
}

$aliasinfo = new AliasInfo;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin - Edit Alias</title>
<link href="style2.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="2" class="boldtext"><img src="images/postvisadmin.png" alt="" width="288" height="41" /></td>
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
    <td valign="top"><? include('menu.php'); ?><br />    </td>
    <td valign="top" class="main">
	
<?
if (isset($error)) {	

	echo "<table class='sample' width='100%'><tr><td class='text' width='22'><img src='images/ok.png'></td><td class='text'>$error</td></tr></table>";
	
}
	
?>
	
	<form id="form1" name="form1" method="post" action="">
      <table width="50%" border="0" align="center" class="main">
        <tr>
          <td colspan="2" bgcolor="#003366"><div align="center" class="boldwhitetext"><strong>Edit Alias </strong></div></td>
        </tr>
        <tr>
          <td width="30%" background="images/butonbackground.jpg" class="text">Email Address:</td>
          <td width="70%" class="text"><? echo $aliasinfo->address; ?></td>
        </tr>
        <tr>
          <td background="images/butonbackground.jpg" class="text">Forward To: </td>
          <td class="text"><input name="gotoaddress" type="text" class="text" id="gotoaddress" value="<? echo $aliasinfo->gotoaddress ?>" size="35"/></td>
        </tr>
        <tr>
          <td height="30" colspan="2" bgcolor="#003366" class="text"><div align="center">
            <input name="Submit" type="submit" class="style5" value="Submit" />
            <input name="editalias" type="hidden" id="editalias" value="submitedit" />
            <input type="hidden" name="domain" value="<? echo $_GET['domain']; ?>" />
            <input name="address" type="hidden" id="address" value="<? echo $aliasinfo->address ?>" />
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
