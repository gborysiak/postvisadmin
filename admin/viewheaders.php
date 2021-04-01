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
require_once 'Mail/mimeDecode.php';
require_once("../functions.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PostVis Admin - Viewing Headers for Mail ID: <? echo $_GET['mail_id']; ?></title>
<link href="/style2.css" rel="stylesheet" type="text/css" />
</head>

<body>
<? 


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
//print_r($headers);

//$bodytext = str_replace("<br />", "\n",$bodytext);
//$bodytext = str_replace ("<br>","\n",$bodytext);
//$bodytext = wordwrap($bodytext, 90, "\n");

?>

<table width="900" border="1" align="center" cellpadding="1" cellspacing="1" class="main">
  
    <?
	foreach ($headers as $key => $value) {
	if ($key == "received") {
		if (is_array($received)) {
			echo "<tr class='text'><td background='../images/butonbackground.jpg' width='100'>Headers:</td><td  class='footertext'>";
			foreach ($received as $key => $value) {
				$value = str_replace("<", "&lt;", $value);
				echo "$value<br /><br />";
			}
			echo "</td></tr>";
		} else {
			$value = str_replace("<", "&lt;", $value);
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>$key:</td><td class='footertext'>$value</td></tr>";
		}
	} elseif ($key=="x-amavis-alert") {
		if (is_array($structure->headers["x-amavis-alert"])) {
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Amavis Alert</td><td class='footertext'>";
			$amavisheaders = $structure->headers["x-amavis-alert"];
			foreach ($amavisheaders as $key => $value) {
				$value = str_replace("<", "&lt;", $value);
				echo "$value<br /><br />";
			}
			echo "</td></tr>";
		} else {
		$value = str_replace("<", "&lt;", $value);
		echo "<tr class='text'><td background='../images/butonbackground.jpg'>Amavis Alert</td><td class='footertext'>$value</td></tr>";
		}
	} elseif ($key=="x-spam-status") {
		if (is_array($structure->headers["x-spam-status"])) {
			$xspamstatus = $structure->headers["x-spam-status"];
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>$key:</td><td class='footertext'>" . $xspamstatus[0] . "</td></tr>";
		} else {
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>$key:</td><td class='footertext'>$value</td></tr>";
		}
	} else {
		
		$value = str_replace("<", "&lt;", $value);
		echo "<tr class='text'><td background='../images/butonbackground.jpg'>$key:</td><td class='footertext'>$value</td></tr>";
	
	}
	} 
    
    ?>
</table>
</body>
</html>
