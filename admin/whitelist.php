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

if (isset($_POST['addlist'])) {
	$recipient = $_POST['user'];
	$sender = $_POST['sender'];
	$wb = $_POST['wb'];
	$error = addlist($recipient,$sender,$wb);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin - Server Blacklist/Whitelist</title>
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
      <?php include('adminmenu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">
      <form id="form1" name="form1" method="post" action="">
<?php
if (isset($error)) {
	echo "<table class='sample' width='100%'><tr class='text'><td class='text' width='22'>$error</td></tr></table>";
}
?>
		<table width="332" align="center" class="main">
          <tr>
            <td colspan="2" bgcolor="#003366"><div align="center" class="boldwhitetext"><strong>Quick Add Whitelist/Blacklist</strong></div></td>
          </tr>
          <tr>
            <td width="112" background="../images/butonbackground.jpg" class="text">Recipient:</td>
            <td width="204" class="text"><label>
              <select name="user" class="style5" id="user">
                <option>Select User...</option>
<?php 
$query = "SELECT id, email FROM users";

if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$results = $mysqli->query($query);
	while ($row=$results->fetch_array(MYSQLI_NUM)) {
		echo "<option value='$row[0]'>$row[1]</option>";
	}
} else { 
   die("Configuration error");
}

?>
			  </select>
            </label></td>
          </tr>
          <tr>
            <td background="../images/butonbackground.jpg" class="text">Sender:</td>
            <td class="text"><label>
              <input name="sender" type="text" class="style5" id="sender" />
            </label></td>
          </tr>
          <tr>
            <td background="../images/butonbackground.jpg" class="text">Whitelist/Blacklist</td>
            <td class="text"><label>
              <select name="wb" class="style5" id="wb">
                <option value="W" selected="selected">Whitelist</option>
                <option value="B">Blacklist</option>
              </select>
              <input name="addlist" type="hidden" id="addlist" />
            </label></td>
          </tr>
          <tr>
            <td colspan="2" bgcolor="#003366" class="text"><div align="center">
              <label>
              <input name="Submit" type="submit" class="style5" value="Submit" />
              </label>
              <label>
              <input name="Submit2" type="reset" class="style5" value="Reset" />
              </label>
            </div></td>
            </tr>
        </table>
        <br />
      </form>
      <table width="697" align="center" class="main">
        <tr>
          <td colspan="3" bgcolor="#003366" class="text"><div align="center" class="boldwhitetext"><strong>Whitelist</strong></div></td>
          </tr>
        <tr>
          <td width="244" background="../images/butonbackground.jpg" class="text"><div align="center">Sender</div></td>
          <td width="267" background="../images/butonbackground.jpg" class="text"><div align="center">Recipient</div></td>
          <td width="170" background="../images/butonbackground.jpg" class="text"><div align="center">Delete</div></td>
        </tr>
        
          <?php 		
if ($dbconfig == "mysqli") {
	if ($results=$mysqli->query($whitelist_query)) {
  		$rows_affected = $results->num_rows;
			if ($rows_affected > 0) {
				while ($row=$results->fetch_array(MYSQLI_NUM)) {
					echo "<tr class='text'><td><center>$row[3]</center></td><td><center>$row[4]</center></td><td><center><a href = 'deletelist.php?rid=$row[0]&sid=$row[1]&wb=$row[2]'>Delete</a></center></td></tr>";
				}
			} else {
				echo "<tr class='text'><td colspan='3'><center>Whitelist is Empty</center></td></tr>";
			}
	}
} else {
   die("Configuration error");
}
		  
		 
		 ?>
      </table>
      <br />
    </div>
      <table width="697" align="center" class="main">
        <tr>
          <td colspan="3" bgcolor="#003366" class="text"><div align="center" class="boldwhitetext"><strong>Blacklist</strong></div></td>
        </tr>
        <tr>
          <td width="244" background="../images/butonbackground.jpg" class="text"><div align="center">Sender</div></td>
          <td width="267" background="../images/butonbackground.jpg" class="text"><div align="center">Recipient</div></td>
          <td width="170" background="../images/butonbackground.jpg" class="text"><div align="center">Delete</div></td>
        </tr>
        <?php 

if ($dbconfig == "mysqli") { 
	if ($results=$mysqli->query($blacklist_query)) {
		$rows_affected = $results->num_rows;
		if ($rows_affected > 0) {
			while ($row=$results->fetch_array(MYSQLI_NUM)) {
				echo "<tr class='text'><td><center>$row[3]</center></td><td><center>$row[4]</center></td><td><center><a href = 'deletelist.php?rid=$row[0]&sid=$row[1]&wb=$row[2]'>Delete</a></center></td></tr>";
			}
		$mysqli->close();
		} else {
			echo "<tr class='text'><td colspan='3'><center>Blacklist is Empty</center></td></tr>";
		}
	}
} else { 
   die("Configuration error");
}
		  
		  
		  ?>
      </table>      <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
