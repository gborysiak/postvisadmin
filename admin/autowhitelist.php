<?php

 require_once("../config/config.php");
require '../check_login.php';
$self = $_SERVER['PHP_SELF'];
if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "/index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
}
if (isset($_GET['rowsperpage'])) {
	$rowsPerPage = $_GET['rowsperpage'];
} else {
	$rowsPerPage = 20;
}

$pageNum = 1;

if(isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}
$offset = ($pageNum - 1) * $rowsPerPage;
require_once("../functions.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin - AutoWhitelist</title>
<style type="text/css">
<!--
.style5 {font-family: Verdana; font-size: 9px; }
-->
</style>
<link href="../style2.css" rel="stylesheet" type="text/css" /></head>

<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="2" class="boldtext"><div align="left"><img src="../images/postvisadmin.png" width="288" height="41" /></div></td>
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
          <td height="20" class='text'>Autowhitelist Stats: </td>
          </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <?php include('adminmenu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">
      
         <?php 
if (isset($_GET['email'])) {
	$email = $_GET['email'];
	$deletequery = "DELETE FROM awl WHERE email = '$email' LIMIT 1";			  
	if ($dbconfig == "mysqli") {
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		$result = $mysqli->query($deletequery);
		echo "<table class='sample' width='100%'><tr><td width='22'><img src='/images/ok.png' /></td><td class='text'>'$email' -- Record Deleted</td></tr></table>";
	} else {
      die("Configuration error");
	}
}
?>
      
      <div align="center">
        <form id="form1" name="form1" method="get" action="">
            <div align="center"><span class="footertext">Search Email Address:</span>
              <input name="search" type="text" class="footertext" id="search" />
              <span class="footertext">Rows Per Page:</span>
            <select  class="footertext" name="rowsperpage">
              <option value="20" <?php if ($rowsPerPage=="20") { echo "selected"; } ?>>20</option>
              <option value="40" <?php if ($rowsPerPage=="40") { echo "selected"; } ?>>40</option>
              <option value="60" <?php if ($rowsPerPage=="60") { echo "selected"; } ?>>60</option>
              <option value="80" <?php if ($rowsPerPage=="80") { echo "selected"; } ?>>80</option>
              <option value="100" <?php if ($rowsPerPage=="100") { echo "selected"; } ?>>100</option>
            </select>
            
            
            
              <input name="Submit" type="submit" class="footertext" value="Submit" />
          </div>
        </form>
        
      <table width="95%" border="0" align="center" class="main">
        <tr>
          <td colspan="6" bgcolor="#003366"><div align="center" class="boldwhitetext">Current Autowhitelist Information

	
</div>		  </td>
        </tr>
        <tr class="text" background="../images/buttonbackground.jpg">
				<td width="46%" background="../images/butonbackground.jpg" class="style5"><div align="center">Email</div></td>
			<td width="11%" background="../images/butonbackground.jpg" class="style5"><div align="center">IP Address</div></td>
			<td width="6%" background="../images/butonbackground.jpg" class="style5"><div align="center">Count</div></td>
			<td width="11%" background="../images/butonbackground.jpg" class="style5"><div align="center">Total Score</div></td>
			<td width="20%" background="../images/butonbackground.jpg" class="style5"><div align="center">Last Update</div></td>        
            <td width="6%" background="../images/butonbackground.jpg" class="style5"><div align="center"></div></td>
        </tr> 
<?php
$query = "SELECT * FROM awl";

if (isset($_GET['search'])) {
	$search = $_GET['search'];
	$query1 = $query . " WHERE email LIKE '%$search%'";
} else {
	$query1 = $query;
}

$query = $query1 . " LIMIT $offset, $rowsPerPage";
if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
	$result = $mysqli->query($query1);
	$numrows = $result->num_rows;
	echo "";
		if ($results=$mysqli->query($query)) {
			$i = 0;
			while($rows=$results->fetch_array(MYSQLI_ASSOC)) {
				if ($i == 1){
					$background = "bgcolor='#F2F2F2'";
					$i=0;
				} else {
					$background = "bgcolor = '#FFFFFF'";
					$i=1;
				}				
				echo "<tr class='style5' $background><td>".$rows["email"]."</td><td>".$rows["ip"]."</td><td>".$rows["count"]."</td><td>".$rows["totscore"]."</td><td>".$rows["lastupdate"]."</td><td><a href='$self?email=".$rows["email"]."&search=$search'>Remove<a></td></tr>";
			}
		} else {
			echo "There was an error: " . $mysqli->error;
		}
	$results->close();
	$mysqli->close();
} else { 
   die("Configuration error");
}
        
        
?>
      <tr><td colspan="6" bgcolor="#003366" class="whitefooter"> <center>   
        
          <?php
	  $maxPage = ceil($numrows/$rowsPerPage);


if ($pageNum > 1)
{
   $page  = $pageNum - 1;
   if (isset($_GET['search'])) {
   		$prev  = " <a href=\"$self?page=$page&rowsperpage=$rowsPerPage&search=" . $_GET['search'] . "\" class='whitefooter'>[Prev]</a> ";
        $first = " <a href=\"$self?page=1&rowsperpage=$rowsPerPage&search=" . $_GET['search'] . "\" class='whitefooter'>[First Page]</a> ";
	} else {
		$prev = " <a href=\"$self?page=$page&rowsperpage=$rowsPerPage\" class='whitefooter'>[Prev]</a>";
		$first = " <a href=\"$self?page=1&rowsperpage=$rowsPerPage\" class='whitefooter'>[First Page]</a> ";
	}
}
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   if (isset($_GET['search'])) {
		$next  = " <a href=\"$self?page=$page&rowsperpage=$rowsPerPage&search=" . $_GET['search'] . "\" class='whitefooter'>[Next]</a> ";
		$last = " <a href=\"$self?page=$maxPage&rowsperpage=$rowsPerPage&search=" . $_GET['search'] . "\" class='whitefooter'>[Last Page]</a> ";
	} else {
		$next = " <a href=\"$self?page=$page&rowsperpage=$rowsPerPage\" class='whitefooter'>[Next]</a>";
		$last = "<a href=\"$self?page=$maxPage&rowsperpage=$rowsPerPage\" class='whitefooter'>[Last Page]</a> ";
	}
}
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}
echo $first . $prev .
" Showing page $pageNum of $maxPage pages " . $next . $last;
?>
         
      </center> </td></tr></table>
      <br />
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
