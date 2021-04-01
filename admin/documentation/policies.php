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
          <td width="79%" valign="top" class="text"><p><strong>Amavisd-New Policies</strong></p>
            <p>Amavisd-New has the ability to store &quot;presets&quot; for spam filtering options. You can adjust them under the Policies section on the right. This provides you with complete control on a &quot;preset&quot; level how amavis scans emails.</p>
            <p>When you go to the page you are presented with an option to select a preset to edit. PostVis Admin comes with 6 default presets:<br />
            </p>
            <table width="100%" border="1" cellspacing="1" cellpadding="1">
              <tr>
                <td width="19%" background="../../images/butonbackground.jpg" class="footertext"><strong>All Off</strong></td>
                <td width="81%" class="footertext">All Filtering options off</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Normal</strong></td>
                <td class="footertext">All filtering options turned on, with medium score requirements for SPAM. Blocks most SPAM</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Virus Filtering Only</strong></td>
                <td class="footertext">Only filter viruses. Spam Filtering Off</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Spam Filtering Only</strong></td>
                <td class="footertext">Only filter spam. Virus Filtering Off</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>High</strong></td>
                <td class="footertext">Blocks Almost All spam, can potentially block legit email as well</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Low</strong></td>
                <td class="footertext">Blocks some spam but not all.</td>
              </tr>
            </table>
            <p>You can create or edit an exsisting policy from the policy page. Here is an explaination on the options you have in front of you:</p>
            <table width="100%" border="1" cellspacing="1" cellpadding="1">
              <tr>
                <td width="21%" background="../../images/butonbackground.jpg" class="footertext"><strong>Policy Name</strong></td>
                <td width="79%" class="footertext">Give it a Unique Name to explain what it is</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Receive SPAM</strong></td>
                <td class="footertext">(Y/N) Will deliver spam even if it is flagged as spam. DOES NOT DISABLE SPAM SCANNING. Messages are still scored, just delivered</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Receive Viruses</strong></td>
                <td class="footertext">(Y/N) Will deliver emails containing viruses. This is not recomended to be set to 'Yes'. Messages are scanned, but not filtered.</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Receive Banned Files</strong></td>
                <td class="footertext">Amavisd-New can block potential harmfull files based on the Amavisd-New config files. This option will disable that feature so ALL files are received (.VBS files etc)</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Receive Bad Headers</strong></td>
                <td class="footertext">Emails that fail a standard header check will still be delivered. This usually safe to do either way</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Disable Virus Scan</strong></td>
                <td class="footertext">Completely disables virus scanning (NOT RECOMMENDED)</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Disable Spam Filter</strong></td>
                <td class="footertext">Completely disables spam filtering (NOT RECOMMENDED)</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Disable Banned Files</strong></td>
                <td class="footertext">Completely disables Banned File scanning</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Disable Bad Header</strong></td>
                <td class="footertext">Completely disables Bad header checking</td>
              </tr>
            </table>
            <p>Thos above options give the basic features of your preset. The next section adjusts the actual score levels that Amavis will treat messages as spam. These can be adjusted at any time without restarting amavisd-new.</p>
            <table width="100%" border="1" cellspacing="1" cellpadding="1">
              <tr>
                <td width="23%" background="../../images/butonbackground.jpg" class="footertext"><strong>SPAM Tag Level</strong></td>
                <td width="77%" class="footertext">Score at which amavis will add its information in the headers</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Rewrite Subject Level</strong></td>
                <td class="footertext">Score at which amavis will rewrite the subject(Dependant on Modify Subject for spam setting)</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Spam Quarantine Level</strong></td>
                <td class="footertext">Score at which amavis will start quarantine the message and bounce message generated (Depends on Amavis-new config file most set to D_DISCARD)</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Spam Cutoff Level</strong></td>
                <td class="footertext">Score at which the message will be blocked and return message not generated </td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Modiy Subject for Spam</strong></td>
                <td class="footertext">Will amavis rewrite the subject if email scores lower than qurantine score but higher than Rewrite subject score</td>
              </tr>
              <tr>
                <td background="../../images/butonbackground.jpg" class="footertext"><strong>Spam Subject Tag</strong></td>
                <td class="footertext">What to rewrite the subject with</td>
              </tr>
            </table>
            <p>Once you submit changes or add a preset, they become available right away without having to restart amavisd-new.</p></td>
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
