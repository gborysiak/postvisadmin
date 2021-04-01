<?php

 require_once("../../config/config.php");
require '../../check_login.php';

if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "/index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
}

require_once("../../functions.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">
<!--
function roll(obj, highlightcolor, textcolor){
                obj.style.borderColor = highlightcolor;
                //obj.style.color = textcolor;
            }
-->
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin</title>
<link href="../../style2.css" rel="stylesheet" type="text/css" /><style type="text/css">
<!--
body {
	background-color: #003366;
}
.style6 {font-weight: bold}
-->
</style></head>

<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td colspan="2" class="boldtext"><img src="../../images/postvisadmin.png" width="288" height="41" /></td>
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
          <td class='text'>Help Documentation</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <? include('../adminmenu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">
      <table width="100%" border="1" cellpadding="1" cellspacing="1" class="main">
        <tr>
          <td height="20" colspan="2" valign="top" background="../../images/butonbackground.jpg" class="text style6">Select Category</td>
          </tr>
        <tr>
          <td width="21%" valign="top" class="text"><table width="100%" border="1" cellpadding="1" cellspacing="1" class="sample">
            <tr class="main">
              <td background="../../images/butonbackground.jpg" class="errortext"><a href="policies.php">Amavisd-New Policies</a></td>
            </tr>
            <tr class="main">
              <td background="../../images/butonbackground.jpg" class="text"><a href="quarantine.php">Quarantine</a></td>
            </tr>
            <tr class="main">
              <td background="../../images/butonbackground.jpg" class="text"><a href="domainmanagement.php">Domain Management</a></td>
            </tr>
            <tr class="main">
              <td background="../../images/butonbackground.jpg" class="text"><a href="mailhistory.php">Mail History</a></td>
            </tr>
            <tr class="main">
              <td background="../../images/butonbackground.jpg" class="text"><a href="maintainence.php">Maintenance</a></td>
            </tr>
            <tr class="main">
              <td background="../../images/butonbackground.jpg" class="text"><a href="domainadmins.php">Domain Admins</a></td>
            </tr>
            <tr class="main">
              <td background="../../images/butonbackground.jpg" class="text"><a href="bayes.php">Bayesian Filtering</a></td>
            </tr>
            <tr class="main">
              <td background="../../images/butonbackground.jpg" class="text"><a href="awl.php">Auto-Whitelist</a></td>
            </tr>
            <tr class="main">
              <td background="../../images/butonbackground.jpg" class="text"><a href="blwl.php">Blacklist/Whitelist </a></td>
            </tr>
          </table></td>
          <td width="79%" valign="top" class="text"><p><strong>Blacklisting and Whitelisting</strong></p>
            <p>Postvis Admin supports blacklisting and whitelisting on a domain level. Click on the Blacklist/Whitelist link on the left, and you will be presented with a list of current blacklists and whitelists setup, as well as a form to add more.</p>
            <p>You can fill out the form and it will add that blacklist or whitelist to the server.</p>
            <p>Blacklist: Do Not Allow any email </p>
            <p>Whitelist: Allow all email no matter what, messages are still scanned for viruses</p></td>
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
