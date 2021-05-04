<?php
//define('DOCUMENTROOTPATH',substr($_SERVER['SCRIPT_FILENAME'],0, strlen($_SERVER['SCRIPT_FILENAME']) - strlen(basename($_SERVER['REQUEST_URI'])) ));
//echo  DOCUMENTROOTPATH . "<br>";
//echo $URLPART[0];
//echo basename($_SERVER['REQUEST_URI']);
//echo "document root " . $_SERVER['DOCUMENT_ROOT'];
require_once('../../config/config.php');
require '../../check_login.php';

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
    <td valign="top">  <?php include('../adminmenu.php'); ?><br />    </td>
    <td valign="top" class="main"><div id="main"><br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" bgcolor="#003366" class="boldtext"><div align="center" class="boldwhitetext style6"><span class="boldwhitetext">Spam by Volume</span></div></td>
        </tr>
        <tr>
          <td width="15%" background="../../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Count</strong></div></td>
          <td width="30%" background="../../images/butonbackground.jpg" class="footertext"><div align="center"><strong>IP</strong></div></td>
        </tr>

        <?php 
if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase); 
   if ($mysqli->connect_errno) {
      printf("Connect failed: %s\n", $mysqli->connect_error );
      exit();
   }
   
   $result = $mysqli->query($query_top_ip);
   $numrows =  $result->num_rows;
   //echo "numrows $numrows";
   $result->close();
   
   $temp_query = $query_top_ip . " LIMIT $offset, $rowsPerPage ";
	$result = $mysqli->query($temp_query);
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
		$ip = $row[1];
		echo "<td class='style5'><div align='center'>$ip</div></td>";

	}
	
} else { 
   die("configuration error");
}

$maxPage = ceil($numrows/$rowsPerPage);
$self = $_SERVER['PHP_SELF'];

if ($pageNum > 1)
{
   $page  = $pageNum - 1;
	$prev = " <a href=\"$self?page=$page&rowsperpage=$rowsPerPage\" class='whitefooter'>[Prev]</a>";
	$first = " <a href=\"$self?page=1&rowsperpage=$rowsPerPage\" class='whitefooter'>[First Page]</a> ";
}
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href=\"$self?page=$page&rowsperpage=$rowsPerPage\" class='whitefooter'>[Next]</a>";
   $last = "<a href=\"$self?page=$maxPage&rowsperpage=$rowsPerPage\" class='whitefooter'>[Last Page]</a> ";
	}
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}
?>
        <tr><td colspan="7" bgcolor='#003366' class="whitefooter"><center>
<?php
echo $first . $prev . " Showing page $pageNum of $maxPage pages " . $next . $last;
?>
      </center></td></tr>
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
	mysql_free_result($result);
}

?>

</body>

</html>
