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
          <td class='text'>Maintenance Scripts </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <?php include('adminmenu.php'); ?>    </td>
    <td valign="top" class="text"><div id="text">
<?php 


if (isset($_POST['msglogcleanup'])) {
	$msglog = $_POST['msglogcleanup'];
	$quarantinelog = $_POST['quarantinecleanup'];
	$awlcleanup = $_POST['awlcleanup'];
	if ($dbconfig == "mysqli") { 
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
} else { 
   die("configuration error");
 } 	
	
	if ($msglog == "yes") {
		if ($dbconfig == "mysqli") { 
			$result = $mysqli->query($msgscleanup1);
			$result = $mysqli->query($msgscleanup2);
			
		} else { 
         die("configuration error");
		}
	
		echo "Mail Server log Cleaned up";
		echo "<br /><br />";
	} 
	if ($quarantinelog =="yes") {
		if ($dbconfig == "mysqli") {
			$result = $mysqli->query($quarantinecleanup);
			$result = $mysqli->query($quarantinecleanup2);
			$result = $mysqli->query($quarantinecleanup3);
		} else {
         die("configuration error");
		}
		echo "Quarantine Cleaned up";
	}
	
	if ($awlcleanup == "yes") {
		if ($dbconfig == "mysqli") {
			$result = $mysqli->query($awlcleanup1);
			$result = $mysqli->query($awlcleanup2);
			$mysqli->close();
		} else { 
         die("configuration error");
		}
		echo "<br /><br />Auto-Whitelist is Cleaned up";
	}	
	
	if ($msglog != "yes" and $quarantinelog != "yes" and $awlcleanup != "yes") {
		echo "You didn't select an option, please press your browser back button and make a selection!";
	}
	
	 
} else {
	  ?>
	  <form id="form1" name="form1" method="post" action="">
        <table width="49%" border="0" align="center">
          <tr>
            <td width="93%" bgcolor="#003366" class="whitefooter">Message Log Cleanup</td>
            <td width="7%"><input name="msglogcleanup" type="checkbox" id="msglogcleanup" value="yes" /></td>
            </tr>
          <tr>
            <td bgcolor="#003366" class="whitefooter">Quarantine Cleanup </td>
            <td><label>
              <input name="quarantinecleanup" type="checkbox" id="quarantinecleanup" value="yes" />
            </label></td>
            </tr>
          <tr>
				<td bgcolor="#003366" class="whitefooter">AWL Cleanup</td>          
			  <td><input name="awlcleanup" type="checkbox" id="awlcleanup" value="yes" /></td>
          </tr>          
				<tr>
            <td colspan="2"><div align="center">
              <label>
              <input name="Submit" type="submit" class="text" value="Submit" />
              </label>
            </div></td>
            </tr>
        </table>
        </form>
		<?php } ?>
      <br />
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
