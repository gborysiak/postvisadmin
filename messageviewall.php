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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/style2.css" rel="stylesheet" type="text/css" />
</head>

<body>
<? 


$mail_id = $_GET['mail_id'];
$mail_id = urldecode($mail_id);
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
$params['include_bodies'] = true;
$params['decode_bodies']  = true;
$params['decode_headers'] = true;
$params['input']          = $string;
$params['crlf']           = "\r\n";
$structure = Mail_mimeDecode::decode($params);
$headers = $structure->headers;
$received = $structure->headers["received"];


?>

<table width="899" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td width="895" colspan="2" bgcolor="#FFFFFF"><table width="100%" border="1" cellpadding="0" cellspacing="0" class="main">
      <tr>
        <td colspan="2" bgcolor="#003366"><div align="center" class="boldwhitetext">
          <table width="100%" border="0" cellpadding="0" cellspacing="0" class="main">
            <tr>
              <td width="96%" bgcolor="#003366"><div align="center">
                <? if ($_GET['format'] == "html") {
        		echo "Viewing HTML Message: ". $mail_id; 
			} elseif ($_GET['format'] == "plain") {
				echo "Viewing Plain Text Message: ". $mail_id;
			}
				?>
              </div></td>
              <td width="4%" bgcolor="#003366"><div align="right">
             <? 
			 	$mail_id = $_GET['mail_id'];
				$mail_id = urlencode($mail_id);
			 	
				if ($_GET['format'] == 'html') {
			 		echo "<a href='messageviewall.php?mail_id=$mail_id&format=plain'><img src='images/text.png' width='73' height='14' border=0/></a>";
				} elseif ($_GET['format'] == 'plain') {
					echo "<a href='messageviewall.php?mail_id=$mail_id&format=html'><img src='images/html.png' width='73' height='14' border=0/></a>";
				}
				?>
              
              </div></td>
            </tr>
          </table>
        </div></td>
        </tr>
      <tr>
        <td width="8%" bgcolor="#003366" class="boldwhitetext">From: </td>
        <td width="92%" class="text">
		<? 
		$from = $headers['from'];
		$from = str_replace("<", "&lt;", $from);
		echo $from;
		 ?></td>
      </tr>
      <tr>
        <td bgcolor="#003366" class="boldwhitetext">To: </td>
        <td class="text">
		<? 
		$to = $headers['to'];
		$to = str_replace("<", "&lt;", $to);
		echo $from; 
		?></td>
      </tr>
      <tr>
        <td bgcolor="#003366" class="boldwhitetext">Date:</td>
        <td class="text">
		<? 
		$date = $headers['date'];
		$date = str_replace("<", "&lt;", $date);
		echo $date;
		 ?></td>
      </tr>
      <tr>
        <td bgcolor="#003366" class="boldwhitetext">Subject:</td>
        <td class="text"><? echo $headers['subject']; ?></td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="#FFFFFF" class="text">
		<? 
		
		if (isset($structure->parts)) {
			foreach ($structure->parts as $part) {
				if ($_GET['format'] == "plain"){
					if ($part->ctype_primary=="text" and $part->ctype_secondary=="plain") {
						$bodytext = str_replace("\n", "<br />",$part->body);
						echo $bodytext; 
					} 
						
				} elseif ($_GET['format'] == "html"){
					if ($part->ctype_primary=="text" and $part->ctype_secondary=="html") {
						$bodytext = str_replace("\n", "<br />",$part->body);
						echo $bodytext; 
					}
				}
			}
		} else {
			if ($_GET['format'] == "plain"){
				$bodytext=$structure->body;
				$bodytext = wordwrap($bodytext, 90, "\n");
				echo "<xmp>$bodytext</xmp>";
			} elseif ($_GET['format'] == "html") {
				echo $structure->body;
			}
		}
			
			
			?>        </td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
