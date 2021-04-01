<?php

require '../../check_login.php';

if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "/index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
} 
require_once 'Mail/mimeDecode.php';
require_once("../../functions.inc.php");
require_once('../../config/config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin - Mail Report</title>
<link href="../../style2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style6 {
	font-size: 12px
}
.style7 {font-weight: bold}
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
    <td valign="top" class="main"><div id="main"><span class="text">NOTE: You must run the Update Rules DB Page at least ONCE before running this!</span><br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" bgcolor="#003366" class="boldtext"><div align="center" class="boldwhitetext">Spammassassin Rules Hit</div></td>
        </tr>
        <tr>
          <td width="17%" background="../../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Rule</strong></div></td>
          <td width="8%" background="../../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Count</strong></div></td>
          <td width="18%" background="../../images/butonbackground.jpg" class="footertext"><div align="center"><strong>Score Assigned</strong></div></td>
          <td width="57%" background="../../images/butonbackground.jpg" class="footertext style7"><div align="center">Description</div></td>
        </tr>
        <? 
$i=0;
if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase); 
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$query = "SELECT * from sa_rules";
	$result = $mysqli->query($query);
	$rules = array();
		while ($row = $result->fetch_object()) {
			$rules[$row->rule] = 0;
		}
	//print_r($rules);
	
	$query = "SELECT distinct mail_id, mail_text FROM quarantine group by mail_id";
	
	$results = $mysqli->query($query);
	while ($rows = $results->fetch_array(MYSQLI_ASSOC)) {
		$string = $rows["mail_text"];
		$params['include_bodies'] = false;
		$params['decode_bodies']  = true;
		$params['decode_headers'] = true;
		$params['input']          = $string;
		$params['crlf']           = "\r\n";
		$structure = Mail_mimeDecode::decode($params);
		$headers = $structure->headers;
		$received = $structure->headers["received"];
		
		
		if ($structure->headers["x-spam-flag"] != "NO") {
			if (is_array($structure->headers["x-spam-status"])) {
				$xspamstatus = $structure->headers["x-spam-status"];
				$value = str_replace("<", "&lt;", $xspamstatus[0]);
				$sa_tests = $value;
			} else {
				$value = str_replace("<", "&lt;", $structure->headers["x-spam-status"]);
				$sa_tests = $value;
			}
			
			$sa_tests = substr(strrchr($sa_tests, 'tests'), 4);
			$sa_tests = str_replace("]","",$sa_tests);
			$sa_tests = str_replace(" ","",$sa_tests);
			
			$sa_rules = explode(",",$sa_tests);
			$sa_count = count($sa_rules);
			
			for ($z=0;$z<$sa_count;$z++) {
				
				$sa_rule = explode("=",$sa_rules[$z]);
				$rule_name = $sa_rule[0];
				
				
				if ($rule_name != NULL or $rule_name != "") {
				//echo $rule_name . "<br />";
				$rules[$rule_name] = $rules[$rule_name] + 1;
				//echo $rules[$rule_name];
				}
			}
			//print_r($sq_rule);
			
		}
		}
		// Print results
		//print_r($rules);
	arsort($rules);
	foreach ($rules as $key => $value) {
		
		if ($value > 0 and $key != "") {
			if ($i == 1){
			$background = "bgcolor='#F2F2F2'";
			$i=0;
		} else {
			$background = "bgcolor = '#FFFFFF'";
			$i=1;
		}
			
			$query = "SELECT * FROM sa_rules WHERE rule = '$key'";
			if ($dbconfig == "mysqli") { 
				$result = $mysqli->query($query);
				$row = $result->fetch_array(MYSQLI_NUM);
			} else {
				$results = mysql_query($query);
				$row = mysql_fetch_array($results, MYSQL_NUM);
			}
			echo "<tr $background class='style5'><td class='style5'><div align='center'>$key</div></td><td><center>$value</td><td></td><td>$row[1]</td></tr>";
		}
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
