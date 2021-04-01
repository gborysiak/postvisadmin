<?php
 require_once("../functions.inc.php");
 require '../check_login.php';

if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "/index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
}
require_once("../config/config.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin</title>
<link href="../style2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
-->
</style>
</head>

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
          <td class='text'>Spamassassin Rules</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <?php include('adminmenu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">
      <?
	  $query = "SELECT * FROM sa_rules ORDER BY rule";
	  
	  if ($dbconfig == "mysqli") { 
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		
		if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
		}
		$result = $mysqli->query($query);
		$num_rows = $result->num_rows;
	} else { 
		$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
		mysql_select_db($postfixdatabase) or die('Could not select database');
		$result = mysql_query($query);
		$num_rows = mysql_num_rows($result);
		
	}	
	  
	  ?>
      
      <? 
	  if ($num_rows > 0) {
	  ?>
	  
      <table width="100%" border="1" cellpadding="0" cellspacing="0" class="main">
        <tr>
          <td colspan="2" bgcolor="#003366"><div align="center" class="boldwhitetext">Spamassassin Rules</div></td>
          </tr>
        <tr>
          <td bgcolor="#CCCCCC" class="footertext">Rule</td>
          <td bgcolor="#CCCCCC" class="footertext">Description</td>
        </tr>
       
          <? 
		  $i = 0;
		if ($dbconfig == "mysqli") {
			while ($row=$result->fetch_array(MYSQLI_NUM)) {
				if ($i == 1){
					$background = "bgcolor='#F2F2F2'";
					$i=0;
				} else {
					$background = "bgcolor = '#FFFFFF'";
					$i=1;
				}	
				echo "<tr $background class='footertext'><td>$row[0]</td><td>$row[1]</td></tr>";
			}
		} else {
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) { 	
				if ($i == 1){
					$background = "bgcolor='#F2F2F2'";
					$i=0;
				} else {
					$background = "bgcolor = '#FFFFFF'";
					$i=1;
				}	
		  		echo "<tr $background class='footertext'><td>$row[0]</td><td>$row[1]</td></tr>";
			}
		}
		?>
          
      </table>
      <?
	  } else {
	  	$url = $siteurl . "admin/updaterules.php";
		echo "<div class='boldtext'>No Rules Loaded into Database! Please run <a href='$url'>$url</a></div>";
	} ?>
    </div>    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
