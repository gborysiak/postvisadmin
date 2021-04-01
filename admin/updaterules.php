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
          <td class='text'>Updating Spamassassin Rule Description Database...</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <? include('adminmenu.php'); ?>    </td>
    <td valign="top" class="text"><? 
	$sa_rules = popen("ls $sa_rule_paths | xargs grep -h '^describe'",'r');

while (!feof($sa_rules)) {
	 $line = rtrim(fgets($sa_rules,4096));
	 preg_match("/^describe\s+(\S+)\s+(.+)$/",$line,$regs);
	 if ($regs[1] && $regs[2]) {
	  	echo "".htmlentities($regs[1])."  ".htmlentities($regs[2])."<br \>";
	  	$query = "REPLACE INTO sa_rules VALUES ('$regs[1]','$regs[2]')";
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
	  	$results = $mysqli->query($query);
		$mysqli->close();
	 } else {
    	echo "$line - did not match regexp, not inserting into database<br><br>";
  	 }
}
	
	?></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
