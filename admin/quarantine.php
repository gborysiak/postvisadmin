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

if (isset($_GET['rowsperpage'])) {
	$rowsPerPage = $_GET['rowsperpage'];
} else {
	$rowsPerPage = 20;
}

$pageNum = 1;

if(isset($_GET['page']))
{
    $pageNum = $_GET['page'];
}
$offset = ($pageNum - 1) * $rowsPerPage;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin - Server Quarantine</title>
<style type="text/css">

</style>
<link href="../style2.css" rel="stylesheet" type="text/css" /></head>

<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="2" class="boldtext"><div align="center">
      <div align="left"><img src="../images/postvisadmin.png" alt="" width="288" height="41" /></div>
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
          <td width="59%" class='text'>Server Wide Quarantine </td>
          <td width="41%" class='text'>          </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <?php include('adminmenu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">
     <form id="form1" name="form1" method="GET" action="">
            <table align="center" border="0">
              <tr>
                <td  class='style5'>Search: </td>
                <td >
                  <select class="style5" name="searchfield">
					<option value="recipient">To:</option>
					<option value="sender">From:</option>
					<option value="subject">Subject:</option>
					</select>                </td>
                <td ><input name="search" type="text" class="style5" value="<?php if (isset($_GET['search'])) {echo $_GET['search'];}?>" /></td>
				<td class="style5">Rows: 
				<select  class="style5" name="rowsperpage">
					<option value="20" <?php if ($rowsPerPage=="20") { echo "selected"; } ?>>20</option>
					<option value="40" <?php if ($rowsPerPage=="40") { echo "selected"; } ?>>40</option>
					<option value="60" <?php if ($rowsPerPage=="60") { echo "selected"; } ?>>60</option>
					<option value="80" <?php if ($rowsPerPage=="80") { echo "selected"; } ?>>80</option>
					<option value="100" <?php if ($rowsPerPage=="100") { echo "selected"; } ?>>100</option>
				</select>				</td>
				<td>
				<input type="submit" name="submit" class="style5" id="submit" value="Search" /></td>
           	  <td>			  </tr>
            </table>
                    </form>
			<?php
if (isset($_GET['mail_id'])) {
	$mail_id = $_GET['mail_id'];
	$secret_id = $_GET['secret_id'];
	$request = $_GET['request'];
   	
	$fp = fsockopen($amavisserver, $policy_port, $errno, $errstr, 30);
	if (!$fp) {
   		echo "$errstr ($errno)<br />\n";
	} else {
   		$out = "request=" . $request . "\r\n";
		$out .= "mail_id=" . $mail_id . "\r\n";
		$out .= "secret_id=" . $secret_id . "\r\n\r\n";
		fwrite($fp, $out);
   
		fclose($fp);
		if ($request == "release") {
			$query = "UPDATE msgrcpt set rs = 'R' WHERE mail_id = \"$mail_id\"";
		} elseif ($request == "delete") {
			$query = "UPDATE msgrcpt set rs = 'D' WHERE mail_id = \"$mail_id\"";
		}
		if ($dbconfig == "mysqli") {
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
			if ($results = $mysqli->query($query)) {
				$row_affected = $mysqli->affected_rows;
				$mysqli->close();
			}		
		} else { 
         die("configuration error");
		}
		
		if ($row_affected > "0" and $request == "release") {
			$error =  "<img src='../images/ok.png' /></td><td class='text'>Spam Released</td>";
		} elseif ($row_affected > "0" and $request == "delete") {
			$error =  "<img src='../images/ok.png' /></td><td class='text'>Spam Deleted</td>";
		} else {
		 	$error = "<img src='../images/no.png' /></td><td class='text'>Message Not Released, Contact Administrator</td>";
		}
							
	}
}
if (isset($error)) {
	echo "<table class='sample' width='100%'><tr><td class='text' width='22'>$error</tr></table>";
}	        
		
		?>	
	  
	  <table width="100%" border="0" align="center" class="main">
		
		<tr>
          <td width="150" bgcolor='#003366' class="whitefooter">To: </td>
          <td width="210" bgcolor='#003366' class="whitefooter">From: </td>
          <td width="160" bgcolor='#003366' class="whitefooter">Subject</td>
          <td width="110" bgcolor='#003366' class="whitefooter">Date</td>
          <td width="10" bgcolor='#003366' class="whitefooter">Q</td>
		  <td  width="50"bgcolor='#003366' class="whitefooter">Release</td>
        </tr>
      <?php 
if (isset($_GET['searchfield'])) {
	$searchfield = $_GET['searchfield'];
	$search = $_GET['search'];
		if ($searchfield == "recipient") {
			$quarantine_query = $quarantine_query . " AND recipient.email LIKE '%$search%'";
		} elseif ($searchfield == "sender") {
			$quarantine_query = $quarantine_query . " AND sender.email LIKE '%$search%'"; 
		} elseif ($searchfield == "subject") {
			$quarantine_query = $quarantine_query . " AND subject LIKE '%$search%'";
		}
}


$query = $quarantine_query;
$quarantine_query = $quarantine_query . " ORDER BY time_iso DESC LIMIT $offset, $rowsPerPage ";


if ($dbconfig == "mysqli") { 

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
	if (mysqli_connect_errno()) {
   		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	$result = $mysqli->query($query);
	$numrows = $result->num_rows;
	
	if ($quarantineresults = $mysqli->query($quarantine_query)) {
		$i = 0;
		while ($row=$quarantineresults->fetch_array(MYSQLI_ASSOC)) {
			$secretid = urlencode($row["secret_id"]);
			$mailid = urlencode($row["mail_id"]);
			$receiveddate = $row["time_iso"];
			$receiveddate = strftime("%b %d %I:%M %p", $receiveddate);
			if ($i == 1){
				$background = "bgcolor='#F2F2F2'";
				$i=0;
			} else {
				$background = "bgcolor = '#FFFFFF'";
				$i=1;
			}
			echo "<tr class='style5'><td $background><a href='messageview.php?mail_id=$mailid'>". $row["recipient"]."</a></td><td $background>".$row["sender"]."</td><td $background>".$row['subject']."</td><td $background>$receiveddate</td><td $background>".$row['quaratinefor']."</td>";
			
			echo "<td $background><a href='quarantine.php?mail_id=$mailid&secret_id=$secretid&request=release'>Rel</a> / <a href='quarantine.php?mail_id=$mailid&secret_id=$secretid&request=delete'>Del</a></td></tr>";
		}
	} else {
		echo "<tr style='text'><td colspan='5'>There was an error: " . $mysqli->error . "</td></tr>";
	}
	$mysqli->close();
} else {
   die("configuration error");
}

	  ?>
	  </tr>
	  <tr><td colspan="7" bgcolor='#003366' class="whitefooter"><center>
<?php
$maxPage = ceil($numrows/$rowsPerPage);
$self = $_SERVER['PHP_SELF'];

if ($pageNum > 1)
{
   $page  = $pageNum - 1;
   if (isset($_GET['search'])) {
   		$prev  = " <a href=\"$self?page=$page&rowsperpage=$rowsPerPage&searchfield=". $_GET['searchfield'] . "&search=" . $_GET['search'] . "\" class='whitefooter'>[Prev]</a> ";
        $first = " <a href=\"$self?page=1&rowsperpage=$rowsPerPage&searchfield=". $_GET['searchfield'] . "&search=" . $_GET['search'] . "\" class='whitefooter'>[First Page]</a> ";
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
		$next  = " <a href=\"$self?page=$page&rowsperpage=$rowsPerPage&searchfield=". $_GET['searchfield'] . "&search=" . $_GET['search'] . "\" class='whitefooter'>[Next]</a> ";
		$last = " <a href=\"$self?page=$maxPage&rowsperpage=$rowsPerPage&searchfield=". $_GET['searchfield'] . "&search=" . $_GET['search'] . "\" class='whitefooter'>[Last Page]</a> ";
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
echo $first . $prev . " Showing page $pageNum of $maxPage pages " . $next . $last;
?>	  
	  </center></td></tr></table>
      <br />
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
