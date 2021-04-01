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
          <td width="79%" valign="top" class="text"><p><strong>Domain Management</strong></p>
            <p>Under the Domains link on the left, you are provided with a easy form to add domains. This form will automatcially create the domain in the postfix tables, create the postmaster@domain.tld email, and associated username and password to log into PostVis Admin to adjust their particular domain.</p>
            <p>On the left of the Domains, you are provided with a list of domains that are currently setup and easy access to edit the properties of those domains such as the number of email addresses, aliases, etc that are allowed.</p>
            <p>If you click on the Edit link, you are provided a page where you can edit all of the users and aliases for that particular domain. You can adjust filtering options for each user and change passwords as well. By default there is no Catch All alias setup for any of the domains, but this is easily done via the Add Alias form. </p>
            <p>&nbsp;</p></td>
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
