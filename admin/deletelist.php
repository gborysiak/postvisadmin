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
if (isset($_POST['Cancel'])) {
	$domain= $_POST['domain'];
	$url = $siteurl . "/admin/whitelist.php";
	header("Location: $url");
}
if (isset($_POST['deletelist'])) {
	$rid = $_GET['rid'];
	$sid = $_GET['sid'];
	$query = "DELETE FROM wblist WHERE rid = '$rid' and sid = '$sid' LIMIT 1";
	if ($dbconfig == "mysqli") {
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		$results = $mysqli->query($query);
		$rows_affected = $mysqli->affected_rows;
		if ($rows_affected == 1) { 
			$url = $siteurl . "/admin/whitelist.php";
			header('Location:'. $url);
		} else {
			echo "There was a problem removing listed recorded, please try again:<br>" . $mysqli->error . "<br>Query: " . $query;
		}
	} else { 
      die("Configuration error");
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin - Delete Whitelist/Blacklist</title>
<link href="../style2.css" rel="stylesheet" type="text/css" />
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
          <td class='text'>Server Blacklist/Whitelist </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <? include('adminmenu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">
      <div align="center">
        <form id="form1" name="form1" method="post" action="">
          <input id="rid" name="rid" type="hidden" value="<? echo $_POST['rid'];?>" />
		  <input id = "sid" name="sid" type="hidden" value="<? echo $_POST['sid'];?>" />
		  <p><span class="text">Are You sure you want to delete:<br />
            <br />
            <? 
$sender = $_GET['sid'];
$recipient = $_GET['rid'];
$wb = $_GET['wb'];
$wb_query = $wb_query . " WHERE wblist.rid = '$recipient' and wblist.sid = '$sender' and wblist.wb = '$wb'";
if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
	if ($results=$mysqli->query($wb_query)) {
  		$rows_affected = $results->num_rows;
			if ($rows_affected > 0) {
				while ($row=$results->fetch_array(MYSQLI_NUM)) {
					echo "Sender = $row[3]<br>Recipient: $row[4]<br>Listed as: $row[2]";
				}
			} else {
				echo "<tr class='text'><td colspan='3'><center>Nothing Selected</center></td></tr>";
			}
	}
} else {
   die("Configuration error");}
?>
           </span> <br />
            <label>
            <input name="deletelist" type="submit" id="deletelist" value="Yes" />
            </label>
            <label>
            <input name="Cancel" type="submit" id="Cancel" value="Cancel" />
            </label>
          </p>
          </form>
        <p>&nbsp; </p>
      </div>
    </div>
      <p align="center">&nbsp;</p></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
