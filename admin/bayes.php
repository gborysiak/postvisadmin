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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin - Bayes Statistics</title>
<link href="../style2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style6 {font-size: 12px}
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
          <td class='text'>Bayes Statistics</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <?php include('adminmenu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">
      <table width="75%" border="0" align="center" cellpadding="0" class="main">
        <tr>
          <td colspan="2" bgcolor="#003366"><div align="center" class="boldwhitetext style6">Current Bayes Stats </div></td>
          </tr>
<?php

$query = "SELECT * FROM bayes_vars";
if ($dbconfig == "mysqli") { 
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
	echo "";
		if ($results=$mysqli->query($query)) {
			$rows=$results->fetch_array(MYSQLI_NUM);
			echo "<tr class='text' ><td background='../images/butonbackground.jpg'>Spam Count:</td><td>$rows[2]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Ham Count:</td><td>$rows[3]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Token Count:</td><td>$rows[4]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Last Expire:</td><td>" . date('F dS Y h:i:s A',$rows[5]) . "</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Last At Time Delta:</td><td>$rows[6]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Last Expire Reduce:</td><td>$rows[7]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Oldest Token Age:</td><td>" . date('F dS Y h:i:s A',$rows[8]) . "</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Newest Token Age:</td><td>" . date('F dS Y h:i:s A',$rows[9]) . "</td></tr>";
		
		} else {
			echo "There was an error: " . $mysqli->error;
		}
	$results->close();
	$mysqli->close();
} else { 
	$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
	mysql_select_db($postfixdatabase) or die('Could not select database');
		if($results = mysql_query($query)) {
			$rows = mysql_fetch_array($results, MYSQL_NUM);
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Spam Count:</td><td>$rows[2]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Ham Count:</td><td>$rows[3]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Token Count:</td><td>$rows[4]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Last Expire:</td><td>$rows[5]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Last At Time Delta:</td><td>$rows[6]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Last Expire Reduce:</td><td>$rows[7]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Oldest Token Age:</td><td>$rows[8]</td></tr>";
			echo "<tr class='text'><td background='../images/butonbackground.jpg'>Newest Token Age:</td><td>$rows[9]</td></tr>";
			
		} else { 
			echo "There was an error: " . mysql_error();
		}
	mysql_free_result($results);
}

        
        
        
?>
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
