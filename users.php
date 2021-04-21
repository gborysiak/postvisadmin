<?php include("functions.inc.php");

require 'check_login.php';

if ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
} 
$domain = $_SESSION['domain'];

// Add User Script
$domaininfo = new DomainInfo;

if (isset($_POST['addalias'])) {
	$address = $_POST['address'];
	$aliased = $_POST['aliased'];
	$domain = $_POST['domain'];
	$aliascount = $_POST['aliascount'];
	$maxalias = $_POST['maxalias'];
	if ($address == "") {
		$error = "<img src='images/no.png' /></td><td class='errortext'>Add Alias Error: No Address Provided! Please try again.";
	} elseif ($aliascount >= $maxalias and $maxalias !=0) {
		$error = "<img src='images/no.png' /></td><td class='errortext'>Add Alias Error: Maximum Aliases in use! Please remove one then try again.";
	
	} elseif ($aliased == "") {
		$error = "<img src='images/no.png' /></td><td class='errortext'>Add Alias Error: No Alias Destination Provided! Please try again.";
	} elseif ($domain == "") {
		$error = "<img src='images/no.png' /></td><td class='errortext'>Add Alias Error: No Domain Provided! Please try again.";
	} else {
		$aliasinsert = aliasadd($address,$aliased,$domain);
		if ($aliasinsert = "1") {
			$aliasadd = "<tr><td colspan=='4' class='text'><center>Alias Entered</center></td></tr>";
		}
	}
	
}

if (isset($_POST['addusername'])) {



$username = $_POST['user'];
$fullname = $_POST['name'];
$pass = $_POST['pass'];
$pass1 = $_POST['pass2'];
$domain = $_POST['domain'];
$amavispolicy = $_POST['policy_id'];
$amavispriority = $_POST['priority'];
$quarantine_notify = $_POST['quarantine_notify'];
$name = $_POST['name'];
$quota = $_POST['quota'];
if(!isset($maildir_style) || $maildir_style == 0) {
               $maildirectory = $domain . "/" . $username . "@" . $domain . "/";
    } else  {
               $maildirectory = $domain . "/" . $username . "/";
    }

$numberaccounts = $_POST['numberaccounts'];
$maxaccounts = $_POST['maxaccounts'];
	if ($pass!=$pass1) {
		$error =  "<img src='images/no.png' /></td><td class='errortext'>Add User Error: Passwords do no match.  Please try again";
	} elseif ($numberaccounts >= $maxaccounts and $maxaccounts !=0) {
		$error = "<img src='images/no.png' /></td><td class='errortext'>Add User Error: Maximum number of email addresses setup, please remove one and try again.";
	} elseif ($username ==""){
		$error =  "<img src='images/no.png' /></td><td class='errortext'>Add User Error: Username was not entered, please try again";
	} elseif ($pass == "" or $pass1=="") {
		$error =  "<img src='images/no.png' /></td><td class='errortext'>Add User Error: Password was not entered or was not verified, please try again";
	} elseif (($quota > $domaininfo->quota) and ($domaininfo->quota <> 0)) {
		$error =  "<img src='images/no.png' /></td><td class='errortext'>Add User Error: Quota is higher than allowed maximum of : ".$domaininfo->quota . " kbytes";
	} else {
		$password = cryptpassword($pass);
		$error = adduser($username,$password,$domain,$amavispolicy,$amavispriority,$name,$maildirectory,$quota,$quarantine_notify);
	}
}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo "PostVis Admin - Domain Details: $domain"; ?></title>
<style type="text/css">
<!--
.style5 {font-family: Verdana; font-size: 9px; }
-->
</style>
<link href="style2.css" rel="stylesheet" type="text/css" />
</head>
<script type="text/javascript">

function roll(obj, highlightcolor, textcolor){
                obj.style.borderColor = highlightcolor;
                //obj.style.color = textcolor;
            }
       
</script>

<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="2" class="boldtext"><div align="left"><img src="images/postvisadmin.png" alt="" width="288" height="41" /></div></td>
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
          <td class='text'>PostVis Admin </td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">  <?php include('menu.php'); ?><br />    </td>
    <td valign="top" class="main"><?php 
	 
if ($domaininfo->maxalias == 0) {
	$maxalias = "Unlimited";
} else {
	$maxalias = $domaininfo->maxalias;
}

if ($domaininfo->maxaccounts == 0) {
	$maxaccounts = "Unlimited";
} else {
	$maxaccounts = $domaininfo->maxaccounts;
}
	  
	  ?>
      
      <?php 

if (isset($error)) {
	echo "<table class='sample' width='100%'><tr><td class='text' width='22'>$error</td></tr></table>";
}


?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
        <tr>
          <td class="text"><?php echo "Domain Details: " . $domaininfo->domain; ?></td>
          <td class="text"><?php echo "Aliases: " . $domaininfo->aliascount . "/" . $maxalias; ?></td>
          <td class="text"><?php echo "Accounts: " . $domaininfo->numberaccounts . "/" . $maxaccounts; ?>		  </td>
        </tr>
      </table>
	  <p>
<?php
echo "<table class='main' width='100%'><tr><td colspan='5' class='boldwhitetext' bgcolor='#003366'>Alias List</td></tr><tr background='images/butonbackground.jpg'><td class='text'>From</td><td class='text'>To</td><td class='text'>Last Modified</td><td class='text'>Edit/Delete</td></tr>";
$aliasquery = "SELECT * FROM `alias` WHERE address != goto and domain = '$domain'";	


if ($dbconfig == "mysqli") {
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if ($resultalias = $mysqli->query($aliasquery)) {
			while ($rowalias = $resultalias->fetch_array(MYSQLI_NUM)) {
				echo "<tr class='style5'><td>$rowalias[0]</td><td>$rowalias[1]</td><td>$rowalias[4]</td><td><a href='editalias.php?address=$rowalias[0]&goto=$rowalias[1]&domain=$domain'>Edit</a> / <a href='delalias.php?address=$rowalias[0]&goto=$rowalias[1]&domain=$domain'>Delete</a></td></tr>";
			}
		}
echo "</table><br />";
echo "<table class='main' width='100%'><tr><td colspan='6' class='boldwhitetext' bgcolor='#003366'>User List</td></tr>";
echo "<tr background='..images/butonbackground.jpg'><td class='footertext'>Email Address</td><td class='footertext'>Name</td><td class='footertext'>Last Modified</td><td class='footertext'>Active</td><td class='footertext'>Amavis Setup</td><td class='footertext'>Edit/Delete</td></tr>";
	
	$query = "SELECT * FROM mailbox WHERE domain = '$domain'";
	
	if ($result = $mysqli->query($query)) {
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			echo "<tr class='style5' $background><td>".$row['username']."</td><td>".$row['name']."</td><td>".$row['modified']."</td><td>";
				if ($row['active'] == 1) {
					echo "<center><img src='images/ok.png' height='14' width='14'/></center></td>";
				} else {
					echo "<center><img src='images/no.png'  height='14' width='14'/></center></td>";
				}
			$query1 = "SELECT * FROM users LEFT JOIN policy ON policy.id = users.policy_id  WHERE users.email = '".$row['username']."'";
			$result1 = $mysqli->query($query1);
				if ($result1->num_rows > 0) {
					$row1 = $result1->fetch_array(MYSQLI_ASSOC);
					echo "<td><center>".$row1['policy_name']."</center></td>";
				} else {
					echo "<td><center><img src='..images/no.png' width='22' height='22' /></center></td>";
				}
			echo "<td><a href='edituser.php?username=".$row['username']."&domain=$domain'>Edit</a> / <a href='deluser.php?username=".$row['username']."&domain=$domain'>Delete</a></td></tr>";
			
		}
	}
	echo "</table>";
	
} else {
   die("Configuration error");
}	
?>
	    <br />
	  </p>
	  <form id="form1" name="form1" method="post" action="">
	    <table width="77%" border="0" align="center" class="main">
          <tr>
            <td colspan="3" bgcolor="#003366"><div align="center" class="boldwhitetext">Add User </div></td>
          </tr>
		  <?php if (isset($useradd)) {
		  		echo $useradd;
			}
			?>
		  
		  
          <tr>
            <td class="text">Full Name </td>
            <td class="text"><input name="name" type="text" class="style5" id="name" value="<?php echo $_POST['name'];?>" /></td>
            <td width="42%" class="text"><input name="addusername" type="hidden" id="addusername" /></td>
          </tr>
          <tr>
            <td width="28%" class="text">Username</td>
            <td width="30%" class="text"><input name="user" type="text" class="style5" id="user"  value="<?php echo $_POST['user'];?>"/></td>
            <td class="text"><?php 
			
			
						echo "@$domain";
					
					
			
			?>
                      <input name="domain" type="hidden" id="domain" value="<?php echo $domain; ?>" /></td>
          </tr>
          <tr>
            <td class="text">Password</td>
            <td class="text"><input name="pass" type="password" class="style5" id="pass" /></td>
            <td class="text"><input name="numberaccounts" type="hidden" id="numberaccounts" value="<?php echo $domaininfo->numberaccounts ?>" />
            <input name="maxaccounts" type="hidden" id="maxaccounts" value="<?php echo $domaininfo->maxaccounts; ?>" /></td>
          </tr>
          <tr>
            <td class="text">Retype Password </td>
            <td class="text"><input name="pass2" type="password" class="style5" id="pass2" /></td>
            <td class="text">&nbsp;</td>
          </tr>
          <tr>
            <td class="text">Qurantine Notification</td>
            <td class="text"><input name="quarantine_notify" type="checkbox" id="quarantine_notify" value="1" /></td>
            <td class="text">&nbsp;</td>
          </tr>
          <tr>
            <td class="text">Quota</td>
            <td class="text"><label>
              <input name="quota" type="text" class="style5" id="quota" value="<?php echo $domaininfo->quota; ?>"/>
            </label></td>
            <td class="text"><em>Can not be higher than default</em></td>
          </tr>
          <tr>
            <td class="text">&nbsp;</td>
            <td class="text">Default Amavis Settings: </td>
            <td class="text"><select name="policy_id" class="text" id="policy_id">
              <?php 
			$policyquery = "SELECT * FROM policy";
			$previouspolicy = $_POST['policy_id'];
			if ($dbconfig == "mysqli") {
				
				if ($resultdomains = $mysqli->query($policyquery)) {
					while ($rowdomains = $resultdomains->fetch_array(MYSQLI_NUM)) {
						if ($previouspolicy == $rowdomains[0]) {
							echo "<option value='$rowdomains[0]' selected>$rowdomains[1]</option>";
						} elseif ($rowdomains[1] == "Normal") {
							echo "<option value='$rowdomains[0]' selected>$rowdomains[1]</option>";
						} else {
							echo "<option value='$rowdomains[0]'>$rowdomains[1]</option>";
						}
					}
				}
				$mysqli->close();
			} else {
            die("Configuration error");
			}
			?>
                        </select>
              <input name="priority" type="hidden" id="priority" value="7" /></td>
          </tr>
          <tr>
            <td colspan="3" background="images/butonbackground.jpg" class="text"><div align="center">
              <input name="Submit" type="submit" class="style5" value="Submit" />
            </div>              </td>
          </tr>
        </table>
    </form>      
	  <form id="form2" name="form2" method="post" action="">
	    <table width="77%" border="0" align="center" class="main">
          <tr>
            <td colspan="2" bgcolor="#003366"><div align="center" class="boldwhitetext">Add Alias </div></td>
          </tr>
		  <?php if (isset($aliasadd)) {
		  		echo $aliasadd;
			}
			?>
		  
		  
          <tr>
            <td class="text">Email Address:</td>
            <td width="63%" class="text"><input name="address" type="text" class="style5" id="address" />
              <?php 
			
			
						echo "@" . $_SESSION['domain'];
					
					
			
			?>
              <input name="domain" type="hidden" id="domain" value="<?php echo $_SESSION['domain']; ?>" />
              <input name="addalias" type="hidden" id="addalias" /></td>
          </tr>
          <tr>
            <td width="37%" class="text">Aliased to: </td>
            <td class="text"><input name="aliased" type="text" class="style5" id="aliased" />
            <input name="aliascount" type="hidden" id="aliascount" value="<?php echo $domaininfo->aliascount; ?>" />
            <input name="maxalias" type="hidden" id="maxalias" value="<?php echo $domaininfo->maxalias; ?>" /></td>
          </tr>
          <tr>
            <td colspan="2" background="images/butonbackground.jpg" class="text"><div align="center">
              <input name="Submit2" type="submit" class="style5" id="Submit2" value="Submit" />
            </div>              </td>
          </tr>
          <tr>
            <td colspan="2" background="images/butonbackground.jpg" class="text"><div align="center"><em>Leave Email Address Blank for CATCH ALL</em></div></td>
          </tr>
        </table>
    </form>	</td>
  </tr>
  <tr>
   <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>