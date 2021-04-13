<?php
require_once("config/config.php");
require 'check_login.php';

if ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
}
require_once 'Mail/mimeDecode.php';
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
    <td colspan="2" class="boldtext"><div align="center">
      <div align="left"><img src="images/postvisadmin.png" alt="" width="288" height="41" /></div>
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
          <td class='text'>Viewing Quarantined Message Details </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
<?php include('menu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">
	<table width="100%"  class="main">
<?php 
$mail_id = $_GET['mail_id'];
$query = "SELECT * FROM quarantine WHERE mail_id = '$mail_id'";
if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
	$results = $mysqli->query($query);
	$row = $results->fetch_array(MYSQLI_ASSOC);
	$string = $row["mail_text"];
	$mysqli->close();
} else { 
	$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
	mysql_select_db($postfixdatabase) or die('Could not select database');
	$results = mysql_query($query);
	$row = mysql_fetch_array($results, MYSQL_ASSOC);
	$string = $row["mail_text"];
	mysql_close($link);
}
$params['include_bodies'] = false;
$params['decode_bodies']  = true;
$params['decode_headers'] = true;
$params['input']          = $string;
$params['crlf']           = "\r\n";
$structure = Mail_mimeDecode::decode($params);
$headers = $structure->headers;
$received = $structure->headers["received"];
$mail_id = $_GET['mail_id'];
$mail_id = urlencode($mail_id);
echo "<tr><td colspan='2' bgcolor='#003366'><div align='center' class='boldwhitetext'>Message ID : " .$_GET["mail_id"] . "</div></td></tr>";
echo "<tr><td colspan='2'><form><table width='100%' class='text' border='0'><tr background='images/butonbackground.jpg'><td><center><input value='View HTML' class='footertext' onclick='myRef = window.open(\"messageviewall.php?mail_id=$mail_id&format=html\",\"mywin\",\"left=20,top=20,width=925,height=600,toolbar=1,resizable=0,scrollbars=1\");myRef.focus()' type='button'></center>
</td><td><center><input value='View Plain Text' class='footertext' onclick='myRef = window.open(\"messageviewall.php?mail_id=$mail_id&format=plain\",\"mywin\",\"left=20,top=20,width=925,height=600,toolbar=1,resizable=0,scrollbars=1\");myRef.focus()' type='button'></center></td><td><center><input value='View Full Headers' class='footertext' onclick='myRef = window.open(\"viewheaders.php?mail_id=$mail_id\",\"mywin\",\"left=20,top=20,width=925,height=600,toolbar=1,resizable=0,scrollbars=1\");myRef.focus()' type='button'></center></td></tr></table></form></td></tr>";
foreach ($headers as $key => $value) {
	if ($key == "received") {
		if (is_array($received)) {
			echo "<tr class='text'><td background='..images/butonbackground.jpg' width='100'>Headers:</td><td  class='footertext'>";
			foreach ($received as $key => $value) {
				$value = str_replace("<", "&lt;", $value);
				echo "$value<br /><br />";
			}
			echo "</td></tr>";
		} else {
			$value = str_replace("<", "&lt;", $value);
			echo "<tr class='text'><td background='..images/butonbackground.jpg'>$key:</td><td class='footertext'>$value</td></tr>";
		}
	} elseif ($key=="reply-to" or $key=="from" or $key=="to" or $key == "subject" or $key=="date") {
		$value = str_replace("<", "&lt;", $value);
		echo "<tr class='text'><td background='..images/butonbackground.jpg'>$key:</td><td class='footertext'>$value</td></tr>";
	} elseif ($key =="x-spam-flag"){
		$value = str_replace("<", "&lt;", $value);
		echo "<tr class='text'><td background='..images/butonbackground.jpg'>Spam</td><td class='footertext'>$value</td></tr>";
	} elseif ($key == "x-spam-status") {
		//Grab Spam Report Header for parsing later
		$sa_tests = substr(strrchr($value, 'tests'), 4);  
		$value = str_replace("<", "&lt;", $value);
	} elseif ($key == "x-spam-score") {
		$value = str_replace("<", "&lt;", $value);
		echo "<tr class='text'><td background='..images/butonbackground.jpg'>Spam Score</td><td class='footertext'>$value</td></tr>";
	} elseif ($key=="x-amavis-alert") {
		if (is_array($structure->headers["x-amavis-alert"])) {
			echo "<tr class='text'><td background='..images/butonbackground.jpg'>Amavis Alert</td><td class='footertext'>";
			$amavisheaders = $structure->headers["x-amavis-alert"];
			foreach ($amavisheaders as $key => $value) {
				$value = str_replace("<", "&lt;", $value);
				echo "$value<br /><br />";
			}
			echo "</td></tr>";
		} else {
		$value = str_replace("<", "&lt;", $value);
		echo "<tr class='text'><td background='..images/butonbackground.jpg'>Amavis Alert</td><td class='footertext'>$value</td></tr>";
		}
	} elseif ($key=="x-spam-level") {
		$value = str_replace("<", "&lt;", $value);
		echo "<tr class='text'><td background='..images/butonbackground.jpg'>Spam Level</td><td class='footertext'>$value</td></tr>";
	}
}
echo "<tr class='boldwhitetext'><td colspan='2' bgcolor='#003366'><center>Spamassassin Report<center></td></tr>";
echo "<tr><table width='100%' class=main><tr class='boldtext' background='..images/butonbackground.jpg'><td>Rule</td><td>Score</td><td>Description</td></tr>";
if ($dbconfig == "mysqli") { 
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		
		if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
		}
} else { 
		$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
		mysql_select_db($postfixdatabase) or die('Could not select database');	
}	
$sa_tests = str_replace("]","",$sa_tests);
$sa_tests = str_replace(" ","",$sa_tests);
$sa_rules = explode(",",$sa_tests);
$sa_count = count($sa_rules);
for ($i=0;$i<$sa_count;$i++) {
	$sa_rule = explode("=",$sa_rules[$i]);
	$query = "SELECT * FROM sa_rules WHERE rule = '$sa_rule[0]'";
		
		if ($dbconfig == "mysqli") { 
			$result = $mysqli->query($query);
			$row = $result->fetch_array(MYSQLI_NUM);
		} else {
			$results = mysql_query($query);
			$row = mysql_fetch_array($results, MYSQL_NUM);
		}
	echo "<tr class='footertext'><td>$sa_rule[0]</td><td>$sa_rule[1]</td><td>$row[1]</td></tr>";
	
}
echo "";
	?>
</table>
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>
</html>
