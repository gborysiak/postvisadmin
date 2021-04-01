<?php

require '../../check_login.php';

if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "/index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
} 

require_once('../../config/config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin - Mail Report</title>
<style type="text/css">
<!--
.style5 {font-family: Verdana; font-size: 9px; }
-->
</style>
<link href="../../style2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style6 {
	font-size: 12px
}
-->
</style>
</head>
<script type="text/javascript">
<!--
function roll(obj, highlightcolor, textcolor){
                obj.style.borderColor = highlightcolor;
                //obj.style.color = textcolor;
            }
-->
</script>
<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="2" class="boldtext"><div align="center">
      <div align="left"><img src="../../images/postvisadmin.png" width="288" height="41" /></div>
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
          <td class='text'>Mail Reports</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">  <? include('../adminmenu.php'); ?><br />    </td>
    <td valign="top" class="main"><div id="main"><br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" bgcolor="#003366" class="boldtext"><div align="center" class="boldwhitetext style6"><span class="boldwhitetext">Sender by Volume</span></div></td>
        </tr>
        <tr>
          <td width="15%" background="../../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Count</strong></div></td>
          <td width="30%" background="../../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Avg Spam level </strong></div></td>
          <td width="54%" background="../../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Domain</strong></div></td>
        </tr>
        <? 
if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase); 
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$result = $mysqli->query($query_volume);
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		if ($i == 1){
			$background = "bgcolor='#F2F2F2'";
			$i=0;
		} else {
			$background = "bgcolor = '#FFFFFF'";
			$i=1;
		}
		echo "<tr $background><td class='style5'><div align='center'>$row[0]</div></td>";
		$score = $row[1];
		$score = round($score, 2);
		echo "<td class='style5'><div align='center'>$score</div></td>";
		$domain = explode(".", $row[2]);
		$domain = array_reverse($domain);
		$domain = implode(".",$domain);
		echo "<td class='style5'><div align='center'>$domain</div></td></tr>";

	}
	
} else { 
	$count = 0;
	$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
	mysql_select_db($postfixdatabase) or die('Could not select database');
	$result = mysql_query($query_volume);
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		$count = $count + $row[0];
		if ($i == 1){
			$background = "bgcolor='#F2F2F2'";
			$i=0;
		} else {
			$background = "bgcolor = '#FFFFFF'";
			$i=1;
		}
		echo "<tr $background><td class='style5'><div align='center'>$row[0]</div></td>";
		echo "<td class='style5'><div align='center'>$row[1]</div></td>";
		echo "<td class='style5'><div align='center'>$row[2]</div></td></tr>";
	
	}
	
}
?>
      </table>
      <p>&nbsp;</p>
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
<?
if ($dbconfig == "mysqli") {
	$result->close();
	$mysqli->close();
} else { 
	mysql_free_result($result);
}

?>

</body>

</html>
