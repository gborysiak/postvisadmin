<?php

require_once("config/config.php");
require 'check_login.php';

if (isset($_GET['upgrade'])) {
	$sql1 = "CREATE TABLE IF NOT EXISTS `configuration` (`id` int(11) NOT NULL,`option` varchar(10) character set latin1 NOT NULL,`value` varchar(10) character set latin1 NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	$sql2 = "INSERT INTO `configuration` (`id`, `option`, `value`) VALUES(0, 'version', '1.3.2');";
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
	$mysqli->query($sql1);
	$mysqli->query($sql2);
	$sql3= "ALTER TABLE `mailbox` ADD `quarantine_notify` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `modified` ;";
	$mysqli->query($sql3);
	
	$error = "Database Successfully updated!";
	
}

 require_once("functions.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin</title>
<link href="style2.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td class="boldtext"><div align="left"><img src="/images/postvisadmin.png" width="288" height="41" /></div></td>
  </tr>
  <tr>
    <td class="text"><div id="title">
      <table class='sample' width='100%'>
        <tr>
          <td class='text'>PostVis Admin Installation/Upgrade</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top"><table width="100%" border="1" class="admin">
      <tr>
        <td valign="top" class="text"><p>&nbsp;</p>
          <p align="center">
          <? if (isset($_GET['upgrade'])) { 
		  	echo $error;
			} else {
			?>
          
          This will upgrade your PostVis Admin 1.3.1 Database to version 1.4. Please press continue to complete the process.</p>
          <p>&nbsp;</p>          <p align="center"><a href="upgrade.php?upgrade=yes">Continue</a></p></td>
        <? } ?>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>

