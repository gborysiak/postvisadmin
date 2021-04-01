<?php include("../functions.inc.php");

require '../check_login.php';

if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "/index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
} 

// Add User Script
$domain = $_GET['domain'];

if (isset($_POST['addalias'])) {
	
	$address = $_POST['address'];
	$aliased = $_POST['aliased'];
	$domain = $_POST['domain'];
	$aliascount = $_POST['aliascount'];
	$maxalias = $_POST['maxalias'];
	
	if ($address == "" and aliased == "") {
		$error = "<img src='../images/no.png' /></td><td class='errortext'>Add Alias Error: No Address Provided! Please try again.";
	} elseif ($aliascount >= $maxalias and $maxalias !=0) {
		$error = "<img src='../images/no.png' /></td><td class='errortext'>Add Alias Error: Maximum Aliases in use! Please remove one then try again.";
	
	} elseif ($aliased == "") {
		$error = "<img src='../images/no.png' /></td><td class='errortext'>Add Alias Error: No Alias Destination Provided! Please try again.";
	} elseif ($domain == "") {
		$error = "<img src='../images/no.png' /></td><td class='errortext'>Add Alias Error: No Domain Provided! Please try again.";
	} elseif ($address == "" and $aliased != "") {
		$aliasinsert = aliasadd($address,$aliased,$domain);
		if ($aliasinsert = "1") {
			$error = "<img src='../images/ok.png' /></td><td class='text'>Catch all Entered";
		}
	
	} else {
		$aliasinsert = aliasadd($address,$aliased,$domain);
		if ($aliasinsert = "1") {
			$error = "<img src='../images/ok.png' /></td><td class='text'>Alias Entered: $address/$aliased/$domain";
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
	$maildirectory = $domain . "/" . $username . "@" . $domain . "/";
    if(!isset($maildir_style) || $maildir_style == 0) {
               $maildirectory = $domain . "/" . $username . "@" . $domain . "/";
    } else  {
               $maildirectory = $domain . "/" . $username . "/";
    }
	
	$numberaccounts = $_POST['numberaccounts'];
	$maxaccounts = $_POST['maxaccounts'];
	$quota = $_POST['quota'];

	if ($pass!=$pass1) {
		$error =  "<img src='../images/no.png' /></td><td class='errortext'>Add User Error: Passwords do no match.  Please try again";
	} elseif ($numberaccounts >= $maxaccounts and $maxaccounts !=0) {
		
		$error = "<img src='../images/no.png' /></td><td class='errortext'>Add User Error: Maximum number of email addresses setup, please remove one and try again.";
	
	} elseif ($username ==""){
		$error =  "<img src='../images/no.png' /></td><td class='errortext'>Add User Error: Username was not entered, please try again";
	} elseif ($pass == "" or $pass1=="") {
		$error =  "<img src='../images/no.png' /></td><td class='errortext'>Add User Error: Password was not entered or was not verified, please try again";
	} else {
		
		$password = cryptpassword($pass);
		$error = adduser($username,$password,$domain,$amavispolicy,$amavispriority,$name,$maildirectory,$quota,$quarantine_notify);
	}
	

}	
$domaininfo = new DomainInfo;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><? echo "PostVis Admin - Domain Details: $domain"; ?></title><link href="../style2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style6 {font-size: 10px}
-->
</style>
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
    <td colspan="2" class="boldtext"><div align="left"><img src="../images/postvisadmin.png" alt="" width="288" height="41" /></div></td>
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
          <td class='text'>Domain Management: <? echo $_GET['domain']; ?></td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td valign="top">  <? include('adminmenu.php'); ?><br />    </td>
    <td valign="top" class="main"><? 
	 
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
<? 

if (isset($error)) {
	echo "<table class='sample' width='100%'><tr><td class='text' width='22'>$error</td></tr></table>";
}


?>
      
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
        <tr>
          <td class="text"><? echo "Domain Details: " . $domaininfo->domain; ?></td>
          <td class="text"><? echo "Aliases: " . $domaininfo->aliascount . "/" . $maxalias; ?></td>
          <td class="text"><? echo "Accounts: " . $domaininfo->numberaccounts . "/" . $maxaccounts; ?>		  </td>
        </tr>
      </table>
	  <p>
	    <?php
	
	
echo "<table class='main' width='100%'><tr><td colspan='5' class='boldwhitetext' bgcolor='#003063'>Alias List</td></tr><tr background='../images/butonbackground.jpg' ><td class='text'>From</td><td class='text'>To</td><td class='text' width='20%'>Last Modified</td><td class='text'>Edit/Delete</td></tr>";
	

$domain = $_GET['domain'];
$aliasquery = "SELECT * FROM `alias` WHERE address != goto and domain = '$domain' ORDER BY address";
	
if ($dbconfig == "mysqli") {
	
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if ($resultalias = $mysqli->query($aliasquery)) {
			while ($rowalias = $resultalias->fetch_array(MYSQLI_ASSOC)) {
				echo "<tr class='style5'><td>".$rowalias['address']."</td><td>".$rowalias['goto']."</td><td>".$rowalias['modified']."</td><td><a href='editalias.php?address=".$rowalias['address']."&goto=".$rowalias['goto']."&domain=$domain'>Edit</a> / <a href='delalias.php?address=".$rowalias['address']."&goto=".$rowalias['goto']."&domain=$domain'>Delete</a></td></tr>";
			}
		}
} else { 
	$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
	mysql_select_db($postfixdatabase) or die('Could not select database');
		if ($resultalias = mysql_query($aliasquery)) { 
			while ($rowalias = mysql_fetch_array($resultalias, MYSQL_ASSOC)) {
				echo "<tr class='style5'><td>".$rowalias['address']."</td><td>".$rowalias['goto']."</td><td>$rowalias[4]</td><td><a href='editalias.php?address=".$rowalias['address']."&goto=".$rowalias['goto']."&domain=$domain'>Edit</a> / <a href='delalias.php?address=".$rowalias['address']."&goto=".$rowalias['goto']."&domain=$domain'>Delete</a></td></tr>";
			}
		}
}
	
echo "</table><br />";
echo "<table class='main' width='100%'><tr><td colspan='6' class='boldwhitetext' bgcolor='#003063'>User List</td></tr>";
echo "<tr background='../images/butonbackground.jpg' class='footertext'><td>Email Address</td><td>Name</td><td>Last Modified</td><td  width='22'>Active</td><td>Amavis Setup</td><td>Edit/Delete</td></tr>";
$domain = $_GET['domain'];
$query = "SELECT * FROM mailbox WHERE domain = '$domain' ORDER BY username";
$i = 0;	
if ($dbconfig == "mysqli") {
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				if ($i == 1){
				$background = "bgcolor='#F2F2F2'";
					$i=0;
					} else {
				$background = "bgcolor = '#FFFFFF'";
					$i=1;
				}
			echo "<tr class='style5' $background><td>".$row['username']."</td><td>".$row['name']."</td><td>".$row['modified']."</td><td>";
				if ($row['active'] == 1) {
					echo "<center><img src='../images/ok.png' width='14' height='14'/></center></td>";
				} else {
					echo "<center><img src='../images/no.png' width='14' height='14'/></center></td>";
				}
			$query1 = "SELECT * FROM users LEFT JOIN policy ON policy.id = users.policy_id WHERE email = '".$row['username']."'";
			$result1 = $mysqli->query($query1);
				if ($result1->num_rows > 0) {
					$row1 = $result1->fetch_array(MYSQLI_ASSOC);
					echo "<td><center>".$row1['policy_name']."</center></td>";
				} else {
					echo "<td><center><img src='..../images/no.png' width='14' height='14' /></center></td>";
				}
			echo "<td><a href='edituser.php?username=".$row['username']."&domain=$domain'>Edit</a> / <a href='deluser.php?username=".$row['username']."&domain=$domain'>Delete</a></td></tr>";
		}			
	}
} else { 
	if ($result = mysql_query($query)) { 
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {	
			if ($i == 1){
				$background = "bgcolor='#F2F2F2'";
					$i=0;
			} else {
				$background = "bgcolor = '#FFFFFF'";
					$i=1;
			}
			
			echo "<tr class='style5' $background><td>".$row['username']."</td><td>".$row['name']."</td><td>".$row['modified']."</td><td>";
				if ($row['active'] == 1) {
					echo "<img src='../images/no.png' width='14' height='14'/></td>";
				} else {
					echo "<img src='../images/no.png' width='14' height='14'/></td>";
				}
			$query1 = "SELECT * FROM users LEFT JOIN policy ON policy.id = users.policy_id WHERE email = '".$row['username']."'";
			$result1 = mysql_query($query1);
				if (mysql_num_rows($result1) > 0) {
					$row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
					echo "<td><center>".$row1['policy_name']."</center></td>";
				} else {
					echo "<td><center><img src='..../images/no.png' width='14' height='14' /></center></td>";
				}
			echo "<td><a href='edituser.php?username=".$row['username']."&domain=$domain'>Edit</a> / <a href='deluser.php?username=".$row['username']."&domain=$domain'>Delete</a></td></tr>";
		}
	}
}
echo "</table>";
	
	//mysqli_free_result($result1);
?>
	    <br />
	  </p>
	  <form id="form1" name="form1" method="post" action="">
	    <table width="77%" border="0" align="center" class="main">
          <tr>
            <td colspan="3" bgcolor="#003063"><div align="center" class="boldwhitetext">Add User </div></td>
          </tr>

		  
		  
          <tr>
            <td class="text">Full Name </td>
            <td class="text"><input name="name" type="text" class="style5" id="name" value="<? echo $_POST['name'];?>" /></td>
            <td width="33%" class="text"><input name="addusername" type="hidden" id="addusername" /></td>
          </tr>
          <tr>
            <td width="28%" class="text">Username</td>
            <td width="39%" class="text"><input name="user" type="text" class="style5" id="user" value="<? echo $_POST['user'];?>"/></td>
            <td class="text"><? echo $domaininfo->domain; ?><input name="domain" type="hidden" id="domain" value="<? echo $domaininfo->domain; ?>" /></td>
          </tr>
          <tr>
            <td class="text">Password</td>
            <td class="text"><input name="pass" type="password" class="style5" id="pass" /></td>
            <td class="text"><input name="numberaccounts" type="hidden" id="numberaccounts" value="<? echo $domaininfo->numberaccounts ?>" />
            <input name="maxaccounts" type="hidden" id="maxaccounts" value="<? echo $domaininfo->maxaccounts; ?>" /></td>
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
              <input name="quota" type="text" class="style5" id="quota" value="<? if (isset($_POST['quota'])) { echo $_POST['quota']; } else { echo $default_quota; } ?>" />
            </label></td>
            <td class="text"><em class="text style6">Quota in kbytes</em></td>
          </tr>
          <tr>
            <td class="text">&nbsp;</td>
            <td class="text">Default Amavis Settings: </td>
            <td class="text"><select name="policy_id" class="style5" id="policy_id">
<? 
$domainsquery = "SELECT * FROM policy";
$previouspolicy = $_POST['policy_id'];
if ($dbconfig == "mysqli") {
	if ($resultdomains = $mysqli->query($domainsquery)) {
		while ($rowdomains = $resultdomains->fetch_array(MYSQLI_NUM)) {
			if ($previouspolicy == $rowdomains[0]) {
				echo "<option value='$rowdomains[0]' selected>$rowdomains[1]</option>";
			} else {
				echo "<option value='$rowdomains[0]'>$rowdomains[1]</option>";
			}
		}
	}
} else {
	if ($resultdomains = mysql_query($domainsquery)) { 
		while ($rowdomains = mysql_fetch_array($resultdomains, MYSQL_NUM)) {
			if ($previouspolicy == $rowdomains[0]) {
				echo "<option value='$rowdomains[0]' selected>$rowdomains[1]</option>";
			} else {
				echo "<option value='$rowdomains[0]'>$rowdomains[1]</option>";
			}
		}
	}
}
			?>
            </select>            </td>
          </tr>
          <tr>
            <td class="text">&nbsp;</td>
            <td class="text">Server Priority</td>
            <td class="text"><select name="priority" class="style5" id="priority">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7" selected="selected">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
            </select>            </td>
          </tr>
          <tr>
            <td colspan="3" background="..../images/butonbackground.jpg" class="text"><div align="center">
              <input name="Submit" type="submit" class="text" value="Submit" />
            </div>              </td>
          </tr>
        </table>
        <br />
	  </form>      
	  <form id="form2" name="form2" method="post" action="">
	    <table width="77%" border="0" align="center" class="main">
          <tr>
            <td colspan="3" bgcolor="#003063"><div align="center" class="boldwhitetext">Add Alias </div></td>
          </tr>
		 
		  
		  
          <tr>
            <td class="text">Email Address:</td>
            <td class="text"><input name="address" type="text" class="style5" id="address" /></td>
            <td width="34%" class="text"><input name="addalias" type="hidden" id="addalias" />
              <? echo $domaininfo->domain; ?>
            <input name="domain" type="hidden" id="domain" value="<? echo $domaininfo->domain; ?>" /></td>
          </tr>
          <tr>
            <td width="20%" class="text">Aliased to: </td>
            <td width="46%" class="text"><input name="aliased" type="text" class="style5" id="aliased" /></td>
            <td class="text"><input name="aliascount" type="hidden" id="aliascount" value="<? echo $domaininfo->aliascount; ?>" />
            <input name="maxalias" type="hidden" id="maxalias" value="<? echo $domaininfo->maxalias; ?>" /></td>
          </tr>
          <tr>
            <td colspan="3" background="..../images/butonbackground.jpg" class="text"><div align="center">
              <input name="Submit2" type="submit" class="text" id="Submit2" value="Submit" />
            </div>              </td>
          </tr>
          <tr>
            <td colspan="3" background="..../images/butonbackground.jpg" class="text"><div align="center"><em>Leave Email Address Blank for CATCH ALL</em></div></td>
          </tr>
        </table>
        <br />
	  </form>	</td>
  </tr>
  <tr>
   <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>

</html>
