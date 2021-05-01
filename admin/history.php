<?php

require '../check_login.php';

if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "/index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
} 

require_once('../config/config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin - Mail History</title>
<style type="text/css">
<!--
.style5 {font-family: Verdana; font-size: 9px; }
-->
</style>
<link href="../style2.css" rel="stylesheet" type="text/css" />
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
          <td class='text'>Mail Stats </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">  <?php include('adminmenu.php'); ?><br />    </td>
    <td valign="top" class="main"><div id="main">
      <table width="100%" border="1" cellpadding="0" cellspacing="0" class="main">
        <tr>
          <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="none">
            <tr>
              <td colspan="3" bgcolor="#003366" class="boldtext"><div align="center" class="boldwhitetext style6">Top 20 Clean Senders</div></td>
            </tr>
            <tr>
              <td width="15%" background="../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Count</strong></div></td>
              <td width="30%" background="../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Avg Spam level </strong></div></td>
              <td width="54%" background="../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Domain</strong></div></td>
            </tr>
            <?php
if ($dbconfig == "mysqli") { 
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase); 
		if ( $mysqli->connect_errno) {
			printf("Connect failed: %s\n", $mysqli->connect_error);
			exit();
		}
	$result = $mysqli->query($query_clean);
	$i = 0;
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
   die("configuration error");
}
?>
          </table></td>
          <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="3" bgcolor="#003366" class="boldtext"><div align="center" class="boldwhitetext style6">Top 20 Quarantined Domains w/ Avg Score</div></td>
            </tr>
            <tr>
              <td width="15%" background="../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Count</strong></div></td>
              <td width="30%" background="../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Avg Spam level </strong></div></td>
              <td width="54%" background="../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Domain</strong></div></td>
            </tr>
            <?php 
if ($dbconfig == "mysqli") {
	
	$result = $mysqli->query($query_spamavg);
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
   die("configuration error");
}
?>
          </table></td>
        </tr>
      </table>
      <br />
      <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="5" bgcolor="#003366"><div align="center" class="boldwhitetext style6"><strong>Recent Mail Activity </strong></div></td>
          </tr>
        <tr>
          <td width="18%" background="../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Age in Seconds </strong></div></td>
          <td width="19%" background="../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Status</strong></div></td>
          <td width="22%" background="../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Subject</strong></div></td>
          <td width="19%" background="../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Spam Level </strong></div></td>
          <td width="22%" background="../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Sender</strong></div></td>
          </tr>
<?php
if ($dbconfig == "mysqli") {
	$result = $mysqli->query($query_latestmail);
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
?>
		<tr>
          <td class="style5"><div align="center"><?php echo $row[0]; ?></div></td>
          <td class="style5"><div align="center">
              <?php if ($row[2] == "C") {
		  	echo "Clean";
			} elseif ($row[2] == "S") {
			echo "Spam Quarantined";
			} elseif ($row[2] == "s") {
			echo "Spam Delievered";
			} elseif ($row[2] == "H") {
			echo "Bad Headers";
			} elseif ($row[2] == "V") {			
			echo "Virus Quarantined";
			} elseif ($row[2] == "M") {			
			echo "Bad Mime"; 
			} elseif ($row[2] == "O") {			
			echo "Oversized"; 
			} elseif ($row[2] == "B") {			
			echo "Banned";
			}
			?>
          </div></td>
          <td class="style5"><div align="center"><?php echo $row[9]; ?></div></td>
          <td class="style5"><div align="center"><?php echo $row[5]; ?></div></td>
          <td class="style5"><div align="center"><?php echo $row[7]; ?></div></td>
        </tr>
<?php }
} else {
   die("configuration error");
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
<?php
if ($dbconfig == "mysqli") {
	$result->close();
	$mysqli->close();
} else { 
	die("configuration error");
}

?>

</body>

</html>
