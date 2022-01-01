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

require_once("../functions.inc.php");

$domaininfo = new DomainInfo();
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
<title>PostVis Admin - Main Page</title>
<link href="../style2.css" rel="stylesheet" type="text/css" /><style type="text/css">
<!--
body {
	background-color: #003366;
}
.style6 {font-weight: bold}
.style9 {font-size: 10px}
-->
</style></head>

<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0" bgcolor="#FFFFFF">
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
          <td class='text'>Server Status </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <?php include('adminmenu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">      
      <div align="center" class="text"><span class="style6">Welcome <?php echo $_SESSION['username']; ?>!</span><br />
          <strong>Current Date/Time:</strong> <?php echo date("n/j/y g:iA"); ?>      
      </div>
      <table width="45%" border="1" align="center" class="main">
        <tr>
          <td colspan="2" bgcolor="#003366" class="text"><div align="center" class="boldwhitetext">Account Status</div></td>
          </tr>
        <tr>
          <td width="62%" background="../images/butonbackground.jpg" class="boldtext">Domain: </td>
          <td width="38%" class="text"><?php echo $domaininfo->domain; ?></td>
          </tr>
        <tr>
          <td background="../images/butonbackground.jpg" class="boldtext">MailBoxes            </td>
          <td class="text"><?php echo $domaininfo->numberaccounts . "/"; 
		  if ($domaininfo->maxaccounts == 0) { echo "Unlimited"; } else { echo $domaininfo->maxaccounts;}?></td>
          </tr>
        <tr>
          <td background="../images/butonbackground.jpg" class="boldtext">Aliases</td>
          <td class="text"><?php echo $domaininfo->aliascount . "/";
		   if ($domaininfo->maxalias == 0) { echo "Unlimited"; } else { echo $domaininfo->maxalias;}?></td>
        </tr>
      </table>
      </br>
      <?php if ($enable_status_checks == "yes") { ?>
      <table width="45%" border="1" align="center" cellpadding="1" cellspacing="1" class="main">
        <tr>
          <td colspan="2" bgcolor="#003366" class="boldwhitetext"><div align="center">Service Status</div></td>
          </tr>
        <tr>
          <td width="62%" background="../images/butonbackground.jpg" class="boldtext">Postfix Status:</td>
          <td width="38%" class="text"><div align="center">
            <?php
if ($enable_postfix_test == "yes") {

$service_check = servicecheck("postfix");
echo $service_check;
} else {
echo "Check Disabled";
}
?>
          </div></td>
        </tr>
        <tr>
          <td background="../images/butonbackground.jpg" class="boldtext">Amavis Status:</td>
          <td class="text"><div align="center">
            <?php

if ($enable_amavis_test == "yes") {
$service_check = servicecheck("amavis");
echo $service_check;
} else {
echo "Check Disabled";
}
?>
          </div></td>
        </tr>
        <tr>
          <td background="../images/butonbackground.jpg" class="boldtext">MySQL Status:</td>
          <td class="text"><div align="center">
            <?php
if ($enable_mysql_test == "yes") {
$service_check = servicecheck("mysql");
echo $service_check;
} else {
echo "Check Disabled";
}
?>
          </div></td>
        </tr>
        <tr>
          <td background="../images/butonbackground.jpg" class="boldtext">ClamAV Status:</td>
          <td class="text"><div align="center">
            <?php

if ($enable_clamav_test == "yes") {
$service_check = servicecheck("clamd");
echo $service_check;
} else {
echo "Check Disabled";
}


?>
          </div></td>
        </tr>
      </table>
      <?php } ?>
      <br />
      <table width="45%" border="1" align="center" cellpadding="1" cellspacing="1" class="main">
        <tr>
          <td colspan="2" bgcolor="#003366" class="boldtext"><div align="center" class="boldwhitetext">Email and Server Stats</div></td>
          </tr>
        <tr>
          <td width="63%" background="../images/butonbackground.jpg" class="boldtext">Messages In Quarantine:</td>
          <td width="37%" class="text">
<?php if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if ($results = $mysqli->query($quarantine_query)) {
			$rowcount = $results->num_rows;
		  	echo $rowcount;
		} else {
		  	echo "Error in Query: " . $mysqli->error;
	  	}
	
} else { 
   die("configuration error");
}
?>          </td>
        </tr>
        <tr>
          <td background="../images/butonbackground.jpg" class="boldtext">Active Email Addresses:</td>
          <td class="text">
<?php
if ($dbconfig == "mysqli") {
	if ($results = $mysqli->query("SELECT * FROM users")) {		
          $rowcount = $results->num_rows;
		  echo $rowcount;
	} else {
		  	echo "Error in Query: " . $mysqli->error;
	}
	$mysqli->close();
} else {
   die("configuration error");
}
?>		</td>
        </tr>
        <tr>
          <td background="../images/butonbackground.jpg" class="boldtext">Server Load:</td>
          <td class="text"><?php 
$load = explode(" ", exec("cat /proc/loadavg"));
//var_dump(count($load));
if(count($load) > 1) {
   echo $load[0].'&nbsp;&nbsp;'.$load[1].'&nbsp;&nbsp;'.$load[2];
} else {
   echo '0&nbsp;&nbsp;0&nbsp;&nbsp;0';
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
