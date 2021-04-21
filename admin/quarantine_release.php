<?php

require_once("../config/config.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin - Quarantine Release</title>
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
		<td width="160" class="text"></td>
		<td width="728"><div id="title">
			<table class='sample' width='100%'>
        		<tr>
          			<td width="100%" class='text'>Quarantine Release</td>	
			</tr>
      			</table>
		</div></td>
	</tr>
  	<tr>
		<td valign="top"></td>
    		<td valign="top" class="main"><div id="main">
     
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
		if ($request == 'release') {
			$query = "UPDATE msgrcpt set rs = 'R' WHERE mail_id = \"$mail_id\"";
		}
		else {
			$query = "UPDATE msgrcpt set rs = 'D' WHERE mail_id = \"$mail_id\"";
		}
		if ($dbconfig == "mysqli") {
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
			if ($results = $mysqli->query($query)) {
				$row_affected = $mysqli->affected_rows;
				$mysqli->close();
			}		
		} else { 
         die("Configuration error");
		}

		if ($request == 'release') {
                	$request_msg = 'Released';
                }
                else {
                	$request_msg = 'Deleted';
                }

		if ($row_affected == "1") {
			$error =  "<img src='../images/no.png' /></td><td class='text'>Spam " . $request_msg . "</td>";
		} else {
		 	$error = "<img src='../images/no.png' /></td><td class='text'>Message Not " . $request_msg . ", Contact Administrator</td>";
		}
							
	}
}	
if (isset($error)) {
	echo "<table class='sample' width='100%'><tr><td class='text' width='22'>$error</tr></table>";
}	
		?>	
	  
	 
      <br/>
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>
</html>

 	  	 
