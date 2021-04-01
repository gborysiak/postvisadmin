<?php
require_once("functions.inc.php");
require 'check_login.php';

if ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
}
require_once("config/config.php");

if (isset($_POST['Cancel'])) {
	$domain= $_POST['domain'];
	$url = $siteurl . "users.php?domain=$domain";
	header("Location: $url");
}
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?

$aliasinfo = new AliasInfo;
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.style5 {font-family: Verdana; font-size: 9px; }
-->
</style>
<link href="style2.css" rel="stylesheet" type="text/css" /></head>

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
          <td class='text'>Confirm User Delete </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">  <? include('menu.php'); ?>
      <br />
        <div id="loginarea"></div>    </td>
    <td valign="top" class="main">
<? if (isset($_POST['Submit'])) {
	$address = $_POST['address'];
	$gotoaddress = $_POST['gotoaddress'];
	delalias($address,$gotoaddress);
	$domain = $_POST['domain'];
	echo "<div class='text'><center>Alias was successfully deleted</center>";
	echo "<center><br /><a href='users.php?domain=$domain'>Go back to Domain Admin</a></center></div>";
		
} else { ?>
	
	<form id="form1" name="form1" method="post" action="">
      <div id="main">
        <div align="center" class="text">Delete the following alias? <br />
            <br />
            <? 
	 echo "Address: " . $aliasinfo->address . "<br />";
	 echo "Forwarded To: " . $aliasinfo->gotoaddress . "<br />";
?>
            
			<input name="domain" type="hidden" value="<? echo $_GET['domain']; ?>" />
			<input name="address" type="hidden" value="<? echo $aliasinfo->address; ?>" />
			<input name="gotoaddress" type="hidden" value="<? echo $aliasinfo->gotoaddress; ?>" /><br />
            <label>
            <input type="submit" name="Submit" value="Submit" />
            </label>
            <label>
            <input name="Cancel" type="submit" id="Cancel" value="Cancel" />
            </label>
        </div>
      </div>
        </form>
		<? } ?>    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
