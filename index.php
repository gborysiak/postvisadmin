<?php

 require_once("config/config.php");
require 'check_login.php';

if ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
}

require_once("functions.inc.php");
$domaininfo = new DomainInfo();
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
<link href="style2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style6 {font-weight: bold}
-->
</style>
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
          <td class='text'>Email Administration</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <? include('menu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">      
      <div align="center"><span class="text"><span class="style6">Welcome <? echo $_SESSION['username']; ?>!</span><br />
          <strong>Current Date/Time:</strong> <? echo date("n/j/y g:iA"); ?> </span><br />
      </div>
      <table width="50%" border="0" align="center" class="main">
        <tr>
          <td colspan="2" bgcolor="#003366" class="text"><div align="center" class="boldwhitetext">Account Status</div></td>
          </tr>
        <tr>
          <td width="48%" background="images/butonbackground.jpg" class="text">Domain: </td>
          <td width="52%" class="text"><? echo $domaininfo->domain; ?></td>
          </tr>
        <tr>
          <td background="images/butonbackground.jpg" class="text">MailBoxes            </td>
          <td class="text"><? echo $domaininfo->numberaccounts . "/"; 
		  if ($domaininfo->maxaccounts == 0) { echo "Unlimited"; } else { echo $domaininfo->maxaccounts;}?></td>
          </tr>
        <tr>
          <td background="images/butonbackground.jpg" class="text">Aliases</td>
          <td class="text"><? echo $domaininfo->aliascount . "/";
		   if ($domaininfo->maxalias == 0) { echo "Unlimited"; } else { echo $domaininfo->maxalias;}?></td>
        </tr>
        <tr>
          <td background="images/butonbackground.jpg" class="text">Messages in Quarantine: </td>
          <td class="text">
<? 
$domain = $_SESSION['domain'];
$quarantine_query = $quarantine_query . " AND recipient.email like '%$domain'";
if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if ($results = $mysqli->query($quarantine_query)) {
			$rowcount = $results->num_rows;
		  	echo $rowcount;
		} else {
		  	echo "HOly Shit batman it didn't work: " . $mysqli->error;
	  	}
	$mysqli->close();
} else { 
	$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
	mysql_select_db($postfixdatabase) or die('Could not select database');
		if ($results = mysql_query($quarantine_query)) {
			$rowcount = mysql_num_rows($results);
			echo $rowcount;
		} else {
		  	echo "HOly Shit batman it didn't work: " . $mysqli->error;
	  	}
	mysql_close($link);
}
		  
		  		  
		  ?></td>
        </tr>
      </table>
      <br />
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
