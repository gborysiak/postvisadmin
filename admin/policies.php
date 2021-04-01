<?php 
require '../check_login.php';

if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "/index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
} 
require('../config/config.php'); ?>
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
<script type="text/javascript">
<!--
function roll(obj, highlightcolor, textcolor){
                obj.style.borderColor = highlightcolor;
                //obj.style.color = textcolor;
            }
-->
</script>
<link href="../style2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style6 {color: #FF0000}
-->
</style>
</head>

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
          <td class='text'>Policy Editor 
 <?



if (isset($_POST['addpolicy'])) {
		  
	$policyname = $_POST['policy_name2'];
	$spamlover = $_POST['spam_lover2'];
	$viruslover = $_POST['virus_lover2'];
	$bannedfileslover = $_POST['banned_files_lover2'];
	$badheaderlover = $_POST['bad_headers_lover2'];
	$bypassvirusscan = $_POST['bypass_virus_check2'];
	$bypassspamchecks = $_POST['bypass_spam_check2'];
	$bypassbannedchecks = $_POST['bypass_banned_check2'];
	$bypassheaderchecks = $_POST['bypass_header_check2'];
	$spamtaglevel = $_POST['spam_tag_level2'];
	$spamtag2level = $_POST['spam_tag2_level2'];
	$spamkilllevel = $_POST['spam_kill_level2'];
	$spamdsncutofflevel = $_POST['spam_dsn_cutoff_level2'];
	$modifysubj = $_POST['spam_modifies_subj2'];
	$spamsubjecttag2 = $_POST['spam_subject_tag22'];
	$spam_quarantine_cutoff_level = $_POST['spam_quarantine_cutoff_level'];
	$warnvirusrecip = $_POST['warnvirusrecip'];
	$warnbannedrecip = $_POST['warnbannedrecip2'];
	$warnbadheader = $_POST['warnbadheader2'];
	$messagesizelimit = $_POST['messagesizelimit2'];
	
	$addpolicyquery = "INSERT into policy(policy_name, virus_lover, spam_lover, banned_files_lover, bad_header_lover, bypass_virus_checks, bypass_spam_checks, bypass_banned_checks, bypass_header_checks, spam_tag_level, spam_tag2_level, spam_kill_level, spam_dsn_cutoff_level, spam_quarantine_cutoff_level, spam_modifies_subj, spam_subject_tag2, warnvirusrecip, warnbannedrecip, warnbadhrecip, message_size_limit) VALUES('$policyname', '$viruslover', '$spamlover', '$bannedfileslover', '$badheaderlover', '$bypassvirusscan', '$bypassspamchecks', '$bypassbannedchecks', '$bypassheaderchecks' , '$spamtaglevel' ,'$spamtag2level', '$spamkilllevel' , '$spamdsncutofflevel' , '$spam_quarantine_cutoff_level','$modifysubj','$spamsubjecttag2' , '$warnvirusrecip' , '$warnbannedrecip' , '$warnbadheader' , '$messagesizelimit')";
		  
	if ($dbconfig == "mysqli") {
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if ($result = $mysqli->query($addpolicyquery)) {
			$error = "<table width='100%' class='sample'><tr><td width='22'><img src='../images/ok.png' /></td><td class='text'>Policy Successfully Inserted: $policyname</td></tr></table>";
		} else {
	 	 echo '<font color="red">MySQL Error: ' . $mysqli->error() . '<br /><br /> Query: ' . $addpolicyquery . '</font>';
	 	} 
	} else { 
		$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
		mysql_select_db($postfixdatabase) or die('Could not select database');
		if ($result = mysql_query($addpolicyquery)) { 
			$error = "<table width='100%' class='sample'><tr><td width='22'><img src='../images/ok.png' /></td><td class='text'>Policy Successfully Inserted: $policyname</td></tr></table>";
		} else {
			echo '<font color="red">MySQL Error: ' . mysql_error($link) . '<br /><br /> Query: ' . $addpolicyquery . '</font>';
		}
	}
} ?>		  </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">  <? include('adminmenu.php'); ?><br />    </td>
    <td valign="top" class="main"><div id="main">
      <form id="form1" name="form1" method="post" action="">
            <table width="100%" border="0" cellpadding="0" class="main">
              <tr>
                <td height="20" bgcolor="#003366"><div align="right" class="boldwhitetext">
                  <div align="center"><strong>Select Policy to Edit</strong>: </div>
                </div></td>
              </tr>
              <tr>
                <td height="26"><div align="center">
                  <?

// Print and Fill in Policy Drop Down Selector

$policyquery = "SELECT * FROM policy";
if ($dbconfig == "mysqli") { 
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
	if ($policyresults = $mysqli->query($policyquery)) {
		echo '<select name="policy" class="style5" id="policy">';
		while ($rowpolicy = $rows=$policyresults->fetch_array(MYSQLI_NUM)) {
			if (isset($_POST['policy'])) {
				$policy = $_POST['policy'];
				if ($policy == $rowpolicy[0]) {
					echo '<option value="' . $rowpolicy[0] . '" selected>' . $rowpolicy[1] . '</option>';
				} else {
					echo '<option value="' . $rowpolicy[0] . '">' . $rowpolicy[1] . '</option>';
				}
			} else {
				echo '<option value="' . $rowpolicy[0] . '" selected>' . $rowpolicy[1] . '</option>';
			}
		}
		
		echo '</select>';
	} else {
		echo '<font color="red">MySQLi Error: <br /><br /> Query: ' . $domainlistquery . '</font>';
	}
} else { 
	if ($policyresults = mysql_query($policyquery)) { 
		echo '<select name="policy" class="style5" id="policy">';
		while ($rowpolicy = mysql_fetch_array($policyresults, MYSQL_NUM)) { 
			if (isset($_POST['policy'])) {
				$policy = $_POST['policy'];
				if ($policy == $rowpolicy[0]) {
					echo '<option value="' . $rowpolicy[0] . '" selected>' . $rowpolicy[1] . '</option>';
				} else {
					echo '<option value="' . $rowpolicy[0] . '">' . $rowpolicy[1] . '</option>';
				}
			} else {
				echo '<option value="' . $rowpolicy[0] . '" selected>' . $rowpolicy[1] . '</option>';
			}
		}
		echo '</select>';
	} else { 
		echo '<font color="red">MySQL2 Error: ' . mysql_error($link) . '<br /><br /> Query: ' . $domainlistquery . '</font>';
	}
}

?>
                  <input name="Submit" type="submit" class="style5" value="Submit" />
                </div></td>
              </tr>
      </table>
      </form>          
      <br />
      <?
	  
	  //Update Policy Query  
	  
	  
	  
	  if (isset($_POST['spam_lover'])) {
	  	
	  
	  
	  $policyname = $_POST['policy_name'];
	  $spamlover = $_POST['spam_lover'];
	  $viruslover = $_POST['virus_lover'];
	  $bannedfileslover = $_POST['banned_files_lover'];
	  $badheaderlover = $_POST['bad_header_lover'];
	  $bypassvirusscan = $_POST['bypass_virus_checks'];
	  $bypassspamchecks = $_POST['bypass_spam_checks'];
	  $bypassbannedchecked = $_POST['bypass_banned_checks'];
	  $bypassheaderchecks = $_POST['bypass_header_checks'];
	  $spamtaglevel = $_POST['spam_tag_level'];
	  $spamtag2level = $_POST['spam_tag2_level'];
	  $spamkilllevel = $_POST['spam_kill_level'];
	  $spamdsncutofflevel = $_POST['spam_dsn_cutoff_level'];
	  $modifysubj = $_POST['spam_modifies_subj'];
	  $spamsubjecttag2 = $_POST['spam_subject_tag2'];
	  $policyid = $_POST['policyid'];
	  $spam_quarantine_cutoff_level = $_POST['spam_quarantine_cutoff_level'];
	  $messagesizelimit = $_POST['messagesizelimit'];
	  $warnvirusrecip = $_POST['warnvirusrecip'];
	  $warnbannedrecip = $_POST['warnbannedrecip'];
	  $warnbadheader = $_POST['warnbadheader'];
	  
	  $query = "UPDATE policy SET policy_name='$policyname', spam_lover='$spamlover', virus_lover='$viruslover', banned_files_lover='$bannedfileslover', bypass_virus_checks='$bypassvirusscan', bypass_spam_checks='$bypassspamchecks', bypass_header_checks='$bypassheaderchecks', spam_tag_level='$spamtaglevel', spam_tag2_level='$spamtag2level', spam_kill_level='$spamkilllevel', spam_dsn_cutoff_level='$spamdsncutofflevel' , spam_quarantine_cutoff_level = '$spam_quarantine_cutoff_level' , message_size_limit = '$messagesizelimit', warnvirusrecip = '$warnvirusrecip' , warnbannedrecip = '$warnbannedrecip' , warnbadhrecip = '$warnbadheader' , spam_modifies_subj='$modifysubj' , spam_subject_tag2='$spamsubjecttag2', bad_header_lover='$badheaderlover', bypass_banned_checks='$bypassbannedchecked' WHERE id = '$policyid'";
	  
	if ($dbconfig == "mysqli") { 
	  
  		if ($result = $mysqli->query($query)) {
	  		echo "<table width='100%' class='sample'><tr><td width='22'><img src='../images/ok.png' /></td><td class='text'>Policy Successfully Updated</td></tr></table>";
	    } else {
	  		echo "<table width='100%'> class='sample'<tr><td width='22'><img src='../images/no.png' /></td><td>MySQL Error: " . $mysqli->error() . "</td></tr></table>";
	   	}
	} else { 
		if ($result = mysql_query($query)) { 
			echo "<table width='100%'> class='sample'<tr><td width='22'><img src='../images/ok.png' /></td><td>Policy Successfully Updated</td></tr></table>";
		} else {
	  		echo "<table width='100%'> class='sample'<tr><td width='22'><img src='../images/no.png' /></td><td>MySQL Error: " . mysql_error($link) . "</td></tr></table>";
	   	}
	}
}
	  ?>
	  <? 
	  if (isset($error)) {
	  	echo $error;
	  }
	 
	if (isset($_POST['policy'])) 
	{
	  
	$policy = $_POST['policy'];
	$poilicyquery = "SELECT * FROM policy WHERE id = '" . $policy . "'";
	if ($dbconfig == "mysqli") { 
		$policyedit = $mysqli->query($poilicyquery);
		$row_policy = $policyedit->fetch_array(MYSQLI_NUM);
	} else { 
		$policyedit = mysql_query($poilicyquery);
		$row_policy = mysql_fetch_array($policyedit, MYSQL_NUM);
	}	  
?>
        <form id="form2" name="form2" method="post" action="">
          <table width="100%" border="0" cellpadding="0" class="admin">
            <tr>
              <td colspan="2" bgcolor="#003366" class="text"><div align="center" class="boldwhitetext"><strong>Policy Edit</strong>: <?php echo $row_policy[1]; ?>
                  <input name="policyid" type="hidden" id="policyid" value="<?php echo $row_policy[0]; ?>" />
              </div>              </td>
            </tr>
            <tr>
              <td width="28%" background="../images/butonbackground.jpg" class="text">Policy Name </td>
              <td width="72%"><input name="policy_name" type="text" class="style5" id="policy_name" value="<?php echo $row_policy[1]; ?>" /></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Receive Spam </td>
              <td><select name="spam_lover" class="style5" id="spam_lover">
                <option value="Y" <? if ($row_policy[3] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[3] == "N") { echo "selected"; } ?>>No</option>
              </select>
                <span class="text"><em>Bypass Spam Checks</em></span></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Receive Viruses </td>
              <td><select name="virus_lover" class="style5" id="virus_lover">
                <option value="Y" <? if ($row_policy[2] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[2] == "N") { echo "selected"; } ?>>No</option>
              </select> 
                <span class="text"><em>Bypass Virus Checks (<span class="style6">NOT RECOMMENDED</span>)           </em></span></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Receive Banned Files </td>
              <td><select name="banned_files_lover" class="style5" id="banned_files_lover">
                <option value="Y" <? if ($row_policy[4] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[4]== "N") { echo "selected"; } ?>>No</option>
              </select> 
                <span class="text"><em>Bypass Banned File checks</em></span></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Receive Bad Header Emails </td>
              <td><select name="bad_header_lover" class="style5" id="bad_header_lover">
                <option value="Y" <? if ($row_policy[5] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[5] == "N") { echo "selected"; } ?>>No</option>
              </select> 
                <span class="text"><em>Bypass Banned Header Checks</em></span></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Disable Virus Scan </td>
              <td><select name="bypass_virus_checks" class="style5" id="bypass_virus_checks">
                <option value="Y" <? if ($row_policy[6] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[6] == "N") { echo "selected"; } ?>>No</option>
              </select>
                <span class="text"><em> (<span class="style6">NOT RECOMMENDED</span>)</em></span></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Disable Spam Filter </td>
              <td><select name="bypass_spam_checks" class="style5" id="bypass_spam_checks">
                <option value="Y" <? if ($row_policy[7] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[7] == "N") { echo "selected"; } ?>>No</option>
              </select></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Disable Banned File Filter </td>
              <td><select name="bypass_banned_checks" class="style5" id="bypass_banned_checks">
                <option value="Y" <? if ($row_policy[8] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[8] == "N") { echo "selected"; } ?>>No</option>
              </select></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Disable Bad Headers Filter </td>
              <td><select name="bypass_header_checks" class="style5" id="bypass_header_checks">
                <option value="Y" <? if ($row_policy[9] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[9] == "N") { echo "selected"; } ?>>No</option>
              </select></td>
            </tr>
            <tr>
              <td colspan="2" bgcolor="#003366" class="text"><div align="center" class="whitetext">Spamassassin Options </div></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Spam Tag Level </td>
              <td><input name="spam_tag_level" type="text" class="style5" id="spam_tag_level" value="<?php echo $row_policy[15]; ?>" />
              <span class="text"><em>When to Rewrite the Headers </em></span></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Rewrite Subject Level </td>
              <td><input name="spam_tag2_level" type="text" class="style5" id="spam_tag2_level" value="<?php echo $row_policy[16]; ?>" />
                <span class="text"><em>Add Spam Tag in Subject Line</em></span></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Spam Quarantine Level</td>
              <td><input name="spam_kill_level" type="text" class="style5" id="spam_kill_level" value="<?php echo $row_policy[17]; ?>" />
                <span class="text"><em>Scores Higher will be Quarantined</em></span></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Spam DSN Cutoff Level </td>
              <td><input name="spam_dsn_cutoff_level" type="text" class="style5" id="spam_dsn_cutoff_level" value="<?php echo $row_policy[18]; ?>" />
                <span class="text"><em>Scores higher will be sent a DSN</em></span></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Spam Quarantine Cutoff Level</td>
              <td><input name="spam_quarantine_cutoff_level" type="text" class="style5" id="spam_quarantine_cutoff_level" value="<?php echo $row_policy[19]; ?>" />
                <span class="text"><em>Scores higher will not be quarantined</em></span></td>
            </tr>
            <tr>
              <td height="25" background="../images/butonbackground.jpg" class="text">Modiy Subject for Spam </td>
              <td><select name="spam_modifies_subj" class="style5" id="spam_modifies_subj">
                <option value="Y" <? if ($row_policy[10] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[10] == "N") { echo "selected"; } ?>>No</option>
              </select></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Spam Subject Tag </td>
              <td><input name="spam_subject_tag2" type="text" class="style5" id="spam_subject_tag2" value="<?php echo $row_policy[33]; ?>" /></td>
            </tr>
            <tr>
              <td colspan="2" bgcolor="#003366" class="text"><div align="center" class="boldwhitetext">Misc Options</div></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Warn Virus Recipient</td>
              <td><select name="warnvirusrecip" class="style5" id="warnvirusrecip">
                <option>NA</option>
                <option value="Y" <? if ($row_policy[24] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[24] == "N") { echo "selected"; } ?>>No</option>
                                                        </select></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Warn Banned File Recipient</td>
              <td><select name="warnbannedrecip" class="style5" id="warnbannedrecip">
                <option>NA</option>
                <option value="Y" <? if ($row_policy[25] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[25] == "N") { echo "selected"; } ?>>No</option>
                            </select></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Warn Bad Header Recipient</td>
              <td><select name="warnbadheader" class="style5" id="warnbadheader">
                <option>NA</option>
                <option value="Y" <? if ($row_policy[26] == "Y") { echo "selected"; } ?>>Yes</option>
                <option value="N" <? if ($row_policy[26] == "N") { echo "selected"; } ?>>No</option>
                            </select></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Message Size Limit</td>
              <td><label>
                <input name="messagesizelimit" type="text" class="style5" id="messagesizelimit" value="<? echo $row_policy[34]; ?>" />
                <span class="text"><em>'0' to disable</em></span></label></td>
            </tr>
            <tr>
              <td colspan="2" bgcolor="#003366" class="text"><div align="center">
                <input name="Submit2" type="submit" class="style5" id="Submit2" value="Update" />
              </div></td>
            </tr>
          </table>
      </form>
        <? 
		} else {
	
		 
	
		
		// Show if recordset not empty ?>
        
<form id="form3" name="form3" method="post" action="">
          <table width="100%" border="0" cellpadding="0" class="main">
            <tr>
              <td colspan="2" bgcolor="#003366" class="text"><div align="center" class="boldwhitetext"><strong>QuickAdd Policy Preset </strong>
                <input name="addpolicy" type="hidden" id="addpolicy" value="yes" />
              </div>              </td>
            </tr>
            <tr>
              <td width="28%" background="../images/butonbackground.jpg" class="text">Policy Name </td>
              <td width="72%"><input name="policy_name2" type="text" class="style5" id="policy_name2" /></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Receive Spam </td>
              <td><select name="spam_lover2" class="style5" id="spam_lover2">
                <option value="Y" >Yes</option>
                <option value="N" selected="selected">No</option>
              </select>
              <em class="text">Bypass Spam Checks</em></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Receive Viruses </td>
            <td><select name="virus_lover2" class="style5" id="select2">
                  <option value="Y" >Yes</option>
                  <option value="N" selected="selected" >No</option>
                              </select>
              <em class="text">Bypass Virus Checks (<span class="style6">NOT RECOMMENDED</span>) </em></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Receive Banned Files </td>
            <td><select name="banned_files_lover2" class="style5" id="select3">
                  <option value="Y" >Yes</option>
                  <option value="N" selected="selected" >No</option>
                            </select>
              <em class="text">Bypass Banned File checks</em></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Receive Bad Header Emails </td>
            <td><select name="bad_headers_lover2" class="style5" id="select4">
                  <option value="Y" >Yes</option>
                  <option value="N" selected="selected" >No</option>
                            </select>
              <em class="text">Bypass Banned Header Checks</em></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Disable Virus Scan </td>
            <td><select name="bypass_virus_check2" class="style5" id="select5">
                  <option value="Y" >Yes</option>
                  <option value="N" selected="selected" >No</option>
                            </select>
              <em class="text">(<span class="style6">NOT RECOMMENDED</span>)</em></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Disable Spam Filter </td>
            <td><select name="bypass_spam_check2" class="style5" id="select6">
                  <option value="Y" >Yes</option>
                  <option value="N" selected="selected">No</option>
                            </select></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Disable Banned File Filter </td>
            <td><select name="bypass_banned_check2" class="style5" id="select7">
                  <option value="Y" >Yes</option>
                  <option value="N" selected="selected" >No</option>
                            </select></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Disable Bad Headers Filter </td>
            <td><select name="bypass_header_check2" class="style5" id="select8">
                  <option value="Y">Yes</option>
                  <option value="N" selected="selected">No</option>
                            </select></td>
            </tr>
            <tr>
              <td colspan="2" bgcolor="#003366" class="text"><div align="center" class="whitetext">Spamassassin Options </div></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Spam Tag Level </td>
              <td><input name="spam_tag_level2" type="text" class="style5" id="spam_tag_level2">
              <em class="text">When to Rewrite the Headers </em></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Rewrite Subject Level </td>
              <td><input name="spam_tag2_level2" type="text" class="style5" id="spam_tag2_level2">
              <em class="text">Add Spam Tag in Subject Line</em></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Spam Quarantine Level</td>
              <td><input name="spam_kill_level2" type="text" class="style5" id="spam_kill_level2">
              <em class="text">Scores Higher will be Quarantined</em></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Spam DSN Cutoff Level </td>
              <td><input name="spam_dsn_cutoff_level2" type="text" class="style5" id="spam_dsn_cutoff_level2" a="a" />
              <span class="text"><em> Scores higher will be sent a DSN</em></span></td>
            </tr>
            <tr class="admin">
              <td background="../images/butonbackground.jpg" class="text">Spam Quarantine Cutoff Level</td>
              <td><input name="spam_quarantine_cutoff_level" type="text" class="style5" id="spam_quarantine_cutoff_level" />
              <em class="text">Scores higher will not be quarantined</em></td>
            </tr>
            <tr>
              <td height="25" background="../images/butonbackground.jpg" class="text">Modiy Subject for Spam </td>
            <td><select name="spam_modifies_subj2" class="style5" id="select9">
                  <option value="Y">Yes</option>
                  <option value="N">No</option>
            </select> 
              <span class="text"><em>If score higher than Rewrite Subject Level</em></span></td>
            </tr>
            <tr>
              <td background="../images/butonbackground.jpg" class="text">Spam Subject Tag </td>
              <td><input name="spam_subject_tag22" type="text" class="style5" id="spam_subject_tag22" value="[SPAM]"></td>
            </tr>
            <tr class="admin">
              <td colspan="2" bgcolor="#003366" class="text"><div align="center" class="boldwhitetext">Misc Options</div></td>
            </tr>
            <tr class="admin">
              <td background="../images/butonbackground.jpg" class="text">Warn Virus Recipient</td>
              <td><select name="warnvirusrecip" class="style5" id="warnvirusrecip">
                  <option value="Y" <? if ($row_policy[24] == "Y") { echo "selected"; } ?>>Yes</option>
                  <option value="N" <? if ($row_policy[24] == "N") { echo "selected"; } ?>>No</option>
                            </select></td>
            </tr>
            <tr class="admin">
              <td background="../images/butonbackground.jpg" class="text">Warn Banned File Recipient</td>
              <td><select name="warnbannedrecip2" class="style5" id="warnbannedrecip2">
                  <option value="Y" <? if ($row_policy[25] == "Y") { echo "selected"; } ?>>Yes</option>
                  <option value="N" <? if ($row_policy[25] == "N") { echo "selected"; } ?>>No</option>
                            </select></td>
            </tr>
            <tr class="admin">
              <td background="../images/butonbackground.jpg" class="text">Warn Bad Header Recipient</td>
              <td><select name="warnbadheader2" class="style5" id="warnbadheader2">
                  <option value="Y" <? if ($row_policy[26] == "Y") { echo "selected"; } ?>>Yes</option>
                  <option value="N" <? if ($row_policy[26] == "N") { echo "selected"; } ?>>No</option>
                            </select></td>
            </tr>
            <tr class="admin">
              <td background="../images/butonbackground.jpg" class="text">Message Size Limit</td>
              <td><label>
                <input name="messagesizelimit2" type="text" class="style5" id="messagesizelimit2" />
                <span class="text"><em>'0' to disable</em></span></label></td>
            </tr>
            

            <tr>
              <td colspan="2" bgcolor="#003366" class="text"><div align="center">
                  <input name="Submit22" type="submit" class="style5" id="Submit22" value="Submit" />
              </div></td>
            </tr>
          </table>
      </form>
        <? } ?>    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
<?php



?>
