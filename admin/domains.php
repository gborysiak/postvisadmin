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
<script type="text/javascript">
<!--
function roll(obj, highlightcolor, textcolor){
                obj.style.borderColor = highlightcolor;
                //obj.style.color = textcolor;
            }
-->
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin</title>
<style type="text/css">
<!--
.style5 {font-family: Verdana; font-size: 9px; }
-->
</style>
<link href="../style2.css" rel="stylesheet" type="text/css" /><style type="text/css">
<!--

-->
</style></head>

<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0" bgcolor="#FFFFFF">
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
          <td class='text'>Domain Management</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">
      <?php include('adminmenu.php'); ?>    </td>
    <td valign="top" class="main"><div id="main">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" class="text"><center>
		    <?php
if (isset($_POST['editdomain'])) {
	$domain = $_POST['editdomain'];
	$aliases = $_POST['aliases2'];
	$accounts = $_POST['accounts2'];
	$quota = $_POST['quota2'];
	$description = $_POST['description2'];
	$editdomainquery = "UPDATE domain set aliases = '$aliases', mailboxes= '$accounts', maxquota = '$quota', description='$description', modified = NOW() WHERE domain = '$domain'";
	
	if ($dbconfig == "mysqli") { 
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
		}
		
		$result = $mysqli->query($editdomainquery);
		$rows_affected  = $mysqli->affected_rows;
		
		$mysqli->close();
		
	} else {
		$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
		mysql_select_db($postfixdatabase) or die('Could not select database');
		$result = mysql_query($editdomainquery) or die('Query failed: ' . mysql_error());
		$rows_affected = mysql_affected_rows();
	}
	echo "<table width='100%' class='sample'><tr><td width='22'><img src='/images/ok.png' /></td><td>Updated Domain: $domain</td></tr></table>";
}




if (isset($_POST['adddomain']))  {
	$domain = $_POST['domain'];
	$aliases = $_POST['aliases'];
	$accounts = $_POST['accounts'];
	$description = $_POST['description'];
	$quota = $_POST['quota'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$maildirectory = $domain . "/postmaster@" . $domain . "/";
	
	if ($password == "" or $password2== "") {
		echo "<table width='100%' class='sample'><tr><td width='22'><img src='/images/no.png' /></td><td>Password didn't match, please try again.</td></tr></table>";
	} elseif ($password != $password2) {
		echo "<table width='100%' class='sample'><tr><td width='22'><img src='/images/no.png' /></td><td>Password didn't match, please try again.</td></tr></table>";
	} elseif ($domain =="") {
		echo "<table width='100%' class='sample'><tr><td width='22'><img src='/images/no.png' /></td><td>Please enter in a domain to add</td></tr></table>";
	} elseif (($password == $password2) and ($domain != "")) {
		$encrypt_pass = cryptpassword($password);
		$insertquery = "INSERT INTO domain(domain,description,aliases,mailboxes,maxquota,transport,backupmx,created,modified,active) VALUES('$domain', '$description', '$aliases', '$accounts', '$quota', 'virtual', '0', NOW(), NOW(), '1')";
		$admininsert = "INSERT INTO admin VALUES('postmaster@$domain',SHA1('$password'),'$domain',NOW(),NOW(),'0000-00-00 00:00:00','1','0')";
		$postfixinsert = "INSERT INTO mailbox(username,password,name,maildir,domain,created,modified,active) VALUES('postmaster@$domain','$encrypt_pass', 'Postmaster Account', '$maildirectory', '$domain', NOW(), NOW(), '1')";	
		$postfixaliasinsert = "INSERT INTO alias(address,goto,domain,created,modified,active) VALUES('postmaster@$domain', 'postmaster@$domain', '$domain', NOW(), NOW(), '1')";
		$amavisinsert = "INSERT INTO users(priority, policy_id, email, fullname, local) VALUES('7', '2', 'postmaster@$domain', 'Postmaster for $domain', 'Y')";
		
		
		
		if ($dbconfig == "mysqli") { 
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);		
		
				if (mysqli_connect_errno()) {
					printf("Connect failed: %s\n", mysqli_connect_error());
					exit();
				}		
			$results = $mysqli->query($insertquery);
			$rowsaffected = $mysqli->affected_rows;
			if ($rowsaffected == '1') {
				echo "<table width='100%' class='sample'><tr><td width='22'><img src='/images/ok.png' /></td><td>Domain Added: " . $_POST['domain'] . "";
				$results = $mysqli->query($admininsert);
				$rowsaffected = $mysqli->affected_rows;
					if ($rowsaffected == '1') {
						echo "<br />Admin added for new domain";
						$postmastercreate = $mysqli->query($postfixinsert);
						$postcreate = $mysqli->affected_rows;	
						$postmasteralias = $mysqli->query($postfixaliasinsert);
						$aliascreate = $mysqli->affected_rows;
												
						if ($postcreate == '1' and $aliascreate == '1') {
							echo "<br />Postmaster Account Created";
								$postmasterfilter = $mysqli->query($amavisinsert);
								$filteraffected = $mysqli->affected_rows;
									if ($filteraffected  == '1') {
										echo "<br />Set Postmaster to Unsensored filtering</td></tr></table>";
									}
						}
						
					} else {
						echo "<br />There was a problem adding the administrator for the domain</center></div>";
					}	
			} else {
				echo "There was an Error, Please Try again";	
			}
			$mysqli->close();
		} else { 
		$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
		mysql_select_db($postfixdatabase) or die('Could not select database');
		$result = mysql_query($insertquery) or die('Query failed: ' . mysql_error());
		$rowsaffected  = mysql_affected_rows();
			if ($rowsaffected == '1') {
				echo "<br />Admin added for new domain";
				$postmastercreate =  mysql_query($postfixinsert);
				$postcreate = mysql_affected_rows();
				$postmasteralias = mysql_query($postfixaliasinsert);
				$aliascreate = mysql_affected_rows();
				
				if ($postcreate == '1' and $aliascreate == '1') {
						echo "<br />Postmaster Account Created";
						$postmasterfilter = mysql_query($amavisinsert);
						$filteraffected = mysql_affected_rows();
							if ($filteraffected  == '1') {
										echo "<br />Set Postmaster to Unsensored filtering</center></div>";
							}
				}
			}
		}	
	}	
}	
		  ?>		  </center></td>
          </tr>
        <tr>
          <td width="47%" valign="top"><table width="95%" border="0" align="center" cellpadding="0" class="main">
            <tr>
              <td bgcolor="#003366" class="boldwhitetext" colspan='2'>Domain List: </td>
            </tr>
            
<?php
$domainlistquery = "SELECT * FROM domain ORDER BY domain";

if ($dbconfig == "mysqli") { 

	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);

	if ($results = $mysqli->query($domainlistquery)) {
		while ($rowdomains = $results->fetch_array(MYSQLI_NUM)) {
			echo '<tr class="text"><td><a href="users.php?domain=' , $rowdomains[0] . '">' . $rowdomains[0] . '</a></td><td><center><a href="domains.php?edit=Yes&domain=' . $rowdomains[0] . '">Edit</a> / <a href="deletedomain.php?domain=' . $rowdomains[0] . '">Delete</a></center></td></tr>';
		}
	} else {
		echo '<font color="red">MySQL Error: ' . $mysqli->error . '<br /><br /> Query: ' . $domainlistquery . '</font>';
	}
} else { 
	$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
	mysql_select_db($postfixdatabase) or die('Could not select database');
		if ($results = mysql_query($domainlistquery)) {
			while ($rowdomains = mysql_fetch_array($results, MYSQL_NUM)) {
				echo '<tr class="text"><td><a href="users.php?domain=' , $rowdomains[0] . '">' . $rowdomains[0] . '</a></td><td><center><a href="domains.php?edit=Yes&domain=' . $rowdomains[0] . '">Edit</a> / <a href="deletedomain.php?domain=' . $rowdomains[0] . '">Delete</a></center></td></tr>';
			}
		}
}
?>			 
          </table>
            <br />
            <?php if (isset($_GET['edit']) and $_GET['edit'] == "Yes") { 
			$domaininfo = new DomainInfo;
			
			?>
			<table width="95%" border="0" align="center" cellpadding="3" cellspacing="0" class="main">
              <tr>
                <td bgcolor="#003366" class="boldwhitetext">Edit  Domain: </td>
              </tr>
              <tr>
                <td class="text"><form id="form2" name="form2" method="post" action="">
                    <table width="100%" border="0" cellpadding="0" class="main">
                      <tr>
                        <td width="28%" background="../images/butonbackground.jpg">Domain:
                          <input name="editdomain" type="hidden" id="editdomain" value="<?php echo $domaininfo->domain; ?>" />						  </td>
                        <td width="72%"><?php echo $domaininfo->domain; ?></td>
                      </tr>
                      <tr>
                        <td background="../images/butonbackground.jpg">Aliases:</td>
                        <td><input name="aliases2" type="text" class="style5" id="aliases2" value="<?php echo $domaininfo->maxalias; ?>" /></td>
                      </tr>
                      <tr>
                        <td background="../images/butonbackground.jpg">Accounts:</td>
                        <td><input name="accounts2" type="text" class="style5" id="accounts2" value="<?php echo $domaininfo->maxaccounts; ?>"/></td>
                      </tr>
                      <tr>
                        <td background="../images/butonbackground.jpg">Max Quota </td>
                        <td><input name="quota2" type="text" class="style5" id="quota2" value="<?php echo $domaininfo->quota; ?>"/></td>
                      </tr>
                      <tr>
                        <td background="../images/butonbackground.jpg">Description</td>
                        <td><input name="description2" type="text" class="style5" id="description2" value="<?php echo $domaininfo->description; ?>" /></td>
                      </tr>
                      <tr>
                        <td colspan="2" bgcolor="#003366"><div align="center">
                            <input name="Submit2" type="submit" class="style5" value="Submit" />
                        </div></td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
            </table>
			<?php } ?></td>
          <td width="53%" valign="top"><table width="95%" border="0" align="center" cellpadding="3" cellspacing="0" class="main">
            <tr>
              <td bgcolor="#003366" class="boldwhitetext">Add Domain: </td>
            </tr>
            <tr>
              <td valign="top" class="text"><form id="form1" name="form1" method="post" action="">
                <table width="100%" border="0" cellpadding="0" class="main">
                  <tr>
                    <td width="37%" background="../images/butonbackground.jpg">Domain:
                      <input name="adddomain" type="hidden" id="adddomain" value="adddomain" /></td>
                    <td width="63%"><input name="domain" type="text" class="style5" id="domain" size="40" /></td>
                  </tr>
                  <tr>
                    <td background="../images/butonbackground.jpg">Aliases:</td>
                    <td><input name="aliases" type="text" class="style5" id="aliases" value="<?php echo $default_aliases; ?>" /></td>
                  </tr>
                  <tr>
                    <td background="../images/butonbackground.jpg">Accounts:</td>
                    <td><input name="accounts" type="text" class="style5" id="accounts" value="<?php echo $default_accounts; ?>"/></td>
                  </tr>
                  <tr>
                    <td background="../images/butonbackground.jpg">Max Quota:</td>
                    <td><input name="quota" type="text" class="style5" id="quota" value="<?php echo $default_quota; ?>"/></td>
                  </tr>
                  <tr>
                    <td background="../images/butonbackground.jpg">Description:</td>
                    <td><input name="description" type="text" class="style5" id="description" /></td>
                  </tr>
                  <tr>
                    <td background="../images/butonbackground.jpg">Admin Password:</td>
                    <td><label>
                      <input name="password" type="password" class="style5" id="password" />
                    </label></td>
                  </tr>
                  <tr>
                    <td background="../images/butonbackground.jpg">Confirm Password:</td>
                    <td><input name="password2" type="password" class="style5" id="password2" /></td>
                  </tr>
                  <tr>
                    <td colspan="2" bgcolor="#003366"><div align="center">
                      <input name="Submit" type="submit" class="style5" value="Submit" />
                    </div></td>
                    </tr>
                </table>
                            </form>              </td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><div align="center" class="text">When Editing or making changed to Quota or Max Alias/Accounts allowed:<br />
            -1 = Disabled<br />
            0 = Unlimited</div></td>
          </tr>
      </table>
      <br /></div></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
