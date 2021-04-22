<?php

require_once("../config/config.php");
require '../check_login.php';

if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "login.php";
	header('Location:'. $url);
}

if (isset($_POST['Cancel'])) {
	$domain= $_POST['domain'];
	$url = $siteurl . "admin/domains.php";
	header("Location: $url");
}

require("../functions.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin - Delete Domain</title>
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
          <td class='text'>Delete Domain: </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <? include('adminmenu.php'); ?>    </td>
    <td valign="top" class="main">


    <div id="main">
    
    
<?php
$domain = $_GET['domain'];

if (isset($_POST['Submit'])) {
	$domain = $_POST['domain'];
	echo "<table class='main' width='75%' align='center'><tr><td class='text'>";
	deldomain($domain);
	echo "</td></tr></table";
} else {

$domaininfo = new DomainInfo();

echo '<form id="form1" name="form1" method="post" action="">';
echo "<table class='main' width='75%' align='center'><tr><td bgcolor='#003366' class='boldwhitetext'><strong><center>Are you sure you want to remove:</center></strong></td></tr>";
echo "<tr><td class='text'>Domain: $domain</td></tr><tr><td class='text'>Accounts: $domaininfo->aliascount</td></tr>";
echo "<tr><td class='text'>Aliases: $domaininfo->numberaccounts</td></tr>";
echo '<input name="domain" type="hidden" value="' . $_GET['domain'] . '" />';
echo '<tr><td bgcolor="#003366"><center><input type="submit" name="Submit" value="Submit" /><input name="Cancel" type="submit" id="Cancel" value="Cancel" /></center></td></tr>';
echo '</form></table>';




    
}
    
    
?>    
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
