<?php

// PostVis Admin functions.inc.php
// PostVis Admin
// functions.inc.php - Written by rogersmith@lazytechs.com
// Version 1.0 - RC

require_once("config/config.php");
function servicecheck($service) {
require("config/config.php");
if ($service == "amavis") {
	$fp = fsockopen($amavisserver, $service_port, $errno, $errstr, 30);
	if (!$fp) {
		$error = "<img src='/images/no.png' height='14' width='14'/>";
	} else {
		$error =  "<img src='$siteurl/images/ok.png' height='14' width='14'/>";
	}
} elseif ($service == "postfix") {
	$fp = fsockopen($postfix_server, "25", $errno, $errstr, 30);
	if (!$fp) {
		$error = "<img src='$siteurl/images/no.png' height='14' width='14'/>";
	} else {
		$error =  "<img src='$siteurl/images/ok.png' height='14' width='14'/>";
	}

} elseif ($service == "mysql") {
	if ($dbconfig == "mysqli") {
		if ($mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase)) {
			$error =  "<img src='$siteurl/images/ok.png' height='14' width='14'/>";
			$mysqli->close();
		} else {
			$error = "<img src='$siteurl/images/no.png' height='14' width='14'/>";
		}
	} else {
      die("Configuration error");
		}
	}
} elseif ($service == "clamd") {
	$clamwatch = $clamwatch_path;
	$clamdstatus = exec($clamwatch);
	if ($clamdstatus == "Clamd Running") {
		$error =  "<img src='$siteurl/images/ok.png' height='14' width='14'/>";
	} else {
		$error = "<img src='$siteurl/images/no.png' height='14' width='14' />";
	}
}
return $error; 

}



function aliasadd($address,$alias,$domain)
	{
		require("config/config.php");
		$query = "INSERT INTO alias(address, goto, domain, created, modified,active) VALUES('$address@$domain','$alias','$domain', NOW(), NOW(), '1');";
		if ($dbconfig == "mysqli") {
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
			if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			$result = $mysqli->query($query);
			$rowsaffected = $mysqli->rows_affected;
			return $rowsaffected;
		} else {
         die("Configuration error");
		}
	
	}

function cryptpassword($pass)
  {
	require("config/config.php");
	if ($password_encryption == "md5-crypt") {
		$string = randomkey(8);
		$string = "$1$" . $string . "$";
		$pass = crypt($pass, $string);
		return $pass;
	} elseif ($password_encryption == "md5") {
		$pass = md5($pass);
		return $pass;
	} elseif ($password_encryption == "SHA1") {
		$pass = sha1($pass);
		return $pass;
	} elseif ($password_encryption == "plain") {
		return $pass;
	}
  }
  
  
function randomkey($length)
  {
   $key = "";
   $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
   for($i=0;$i<$length;$i++)
   {
     $key .= $pattern[rand(0,35)];
   }
   return $key;
  }



// User Info Class -- Grabs various infromation about a user

class UserInfo
{
	var $user;
	
	function __construct()
	{
	$user = $_GET['username'];
	$this->getuserinfo($user);
	}
	
	function getuserinfo($user)
	{
		include('config/config.php');
		
		$query = "SELECT * FROM `mailbox` LEFT JOIN users ON mailbox.username = users.email LEFT JOIN policy ON users.policy_id = policy.id WHERE mailbox.username = '$user'";
		
		if ($dbconfig == "mysqli") {
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
			
			if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
		
			$result = $mysqli->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$result->close();
			$mysqli->close();
		} else { 
         die("Configuration error");
		}
			$this->username = $row['username'];
			$this->name = $row['name'];
			$this->maildir = $row['maildir'];
			$this->quota = $row['quota'];
			$this->domain = $row['domain'];
			$this->created = $row['created'];
			$this->modified = $row['modified'];
			$this->active = $row['active'];
			$this->priority = $row['priority'];
			$this->filterpolicy = $row['policy_name'];
			$this->quarantine_notify = $row['quarantine_notify'];
					
	}
};

// AliasInfo Class -- Grabs information about selected alias

class AliasInfo
{
	var $address;
	var $goto;
	function __construct()
	{
		if (isset($_POST['address'])) {
			$address = $_POST['address'];
			$goto = $_POST['gotoaddress'];
			$domain = $_POST['domain'];
			
		} else {
			$address = $_GET['address'];
			$goto = $_GET['goto'];
			
		}
	$this->getaliasinfo($address,$goto);
	}
	
	function getaliasinfo($address,$goto)
	{
		include('config/config.php');
		$query = "SELECT * FROM `alias` WHERE address = '$address' AND goto = '$goto'";
		
		if ($dbconfig == "mysqli") { 
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
				
				if (mysqli_connect_errno()) {
					printf("Connect failed: %s\n", mysqli_connect_error());
					exit();
				}
			$result = $mysqli->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$result->close();
			$mysqli->close();
			
		} else { 
         die("Configuration error");
		}
		
		$this->address = $row['address'];
		$this->gotoaddress = $row['goto'];
		$this->domain = $row['domain'];
		$this->created = $row['created'];
		$this->modified = $row['modified'];
		$this->active = $row['active'];
				
		
	}
};


class DomainInfo
{
	var $domain;
   var $description;
	var $maxaccounts;
	var $maxalias;
	var $created;
	var $quota;
	var $modified;
	var $active;
	var $numberaccounts;
	var $aliascount;
		
	function __construct()
	{ 
	$this->time = time();
    if (isset($_GET['domain'])) {
		$domain = $_GET['domain'];
	}else{
		$domain = $_SESSION['domain'];
	}
	$this->getdomaininfo($domain);
   
	}
	
	function getdomaininfo($domain)
	{
		include("config/config.php");
		$this->domain=$domain;
      //echo $domain;
		$domainquery="SELECT * FROM domain WHERE domain='$domain'";
		$userscountquery = "SELECT count(domain) as cnt FROM mailbox WHERE domain='$domain'";
		$aliascountquery = "SELECT count(domain) as cnt FROM alias WHERE domain='$domain' AND address != goto";
		
		if ($dbconfig == "mysqli") {
		
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);	
         /*
         if ($resultdomaininfo = $mysqli->query($domainquery)) {
            $row_domaininfo = $resultdomaininfo->fetch_array(MYSQLI_ASSOC);
         } else {
            die($mysqli->error);
         } 

 
         if ($accountcount = $mysqli->query($userscountquery)) {
               $row_accounts = $accountcount->fetch_array(MYSQLI_NUM);
         } else {
            die($mysqli->error);
         }            
 
            
         if ($aliasaccount = $mysqli->query($aliascountquery)) {
               $row_aliases = $aliasaccount->fetch_array(MYSQLI_NUM);               
         } else {
            die($mysqli->error);
         }            
         */		
		} else { 
         die("erreur de configuration");  
		}
			
		/*      
		$this->description = $row_domaininfo['description'];
		$this->domain = $row_domaininfo['domain'];
		$this->maxaccounts = $row_domaininfo['mailboxes'];
		$this->maxalias = $row_domaininfo['aliases'];
		$this->created = $row_domaininfo['created'];
		$this->quota = $row_domaininfo['maxquota'];
		$this->modified = $row_domaininfo['modified'];
		$this->active = $row_domaininfo['active'];
		$this->numberaccounts = $row_accounts[0];
		$this->aliascount = $row_aliases[0];
      */
      
		$this->description = "";
		$this->domain = "";
		$this->maxaccounts = "";
		$this->maxalias = "";
		$this->created = "";
		$this->quota = "";
		$this->modified = "";
		$this->active = "";
		$this->numberaccounts = 0;
		$this->aliascount = 0;
      
	}
};
		
function adduser($username,$password,$domain,$amavispolicy,$amavispriority,$name,$maildirectory,$quota,$quarantine_notify) 
{
	include("config/config.php");
	$postfixinsert = "INSERT INTO mailbox(username,password,name,maildir,quota,domain,created,modified,active,quarantine_notify) VALUES('$username@$domain','$password', '$name', '$maildirectory','$quota', '$domain', NOW(), NOW(), '1','$quarantine_notify')";
	$postfixaliasinsert = "INSERT INTO alias(address,goto,domain,created,modified,active) VALUES('$username@$domain', '$username@$domain', '$domain', NOW(), NOW(), '1')";
	$amavisinsert = "INSERT INTO users(priority, policy_id, email, fullname, local) VALUES('$amavispriority', '$amavispolicy', '$username@$domain', '$fullname', 'Y')";
	
	if ($dbconfig == "mysqli") {
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if ($resultdomain = $mysqli->query($postfixinsert)) {
    		$resultaliasdomain = $mysqli->query($postfixaliasinsert);
			if ($resultamavis = $mysqli->query($amavisinsert)) {
			
			} else {
				$error =  '<font color="red">MySQL Error: ' . $mysqli->error . '<br /><br /> Query: ' . $amavisinsert . '</font>';
			}
		} else {
			$error = '<font color="red">MySQL Error: ' . $mysqli->error . '<br /><br /> Query: ' . $postfixinsert . '</font>';
		}
		$mysqli->close();
	} else { 
      die("Configuration error");
	}
	if ($maildir_create=="yes") {
		$to = $username . "@" . $domain;
		$headers = 'From: postmaster@' . $domain . "\r\n" . 'Reply-To: postmaster@' . $domain . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n";
		$headers .= "MIME-Version: 1.0 \r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1';
		$headers .= "\r\nContent-Transfer-Encoding: quoted-printable \r\n\r\n";
		mail($to, $welcome_mail_subject, $welcome_mail, $headers);
		$error = "<img src='$siteurl/images/ok.png' /></td><td class='greentext'>User Added: $username@$domain, MailDir Created";
	} else {
		$error = "<img src='$siteurl/images/ok.png' /></td><td class='greentext'>User Added: $username@$domain";
	}
		return $error;

}

function deluser($user)
{
	include("config/config.php");
		
	$username = $user;
	
	$query1 = "DELETE FROM mailbox WHERE username = '$username' LIMIT 1";
	$query2 = "DELETE FROM alias WHERE goto = '$username'";
	$query3 = "DELETE FROM users WHERE email = '$username' LIMIT 1";
	
	if ($dbconfig == "mysqli") { 
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
		}
		$result = $mysqli->query($query1);
		$result = $mysqli->query($query2);
		$result = $mysqli->query($query3);
		$mysqli->close();
	} else { 
      die("Configuration error");
	}
}

function delalias($address,$gotoaddress)
{
	include("config/config.php");
	$query1 = "DELETE FROM alias WHERE address = '$address' and goto = '$gotoaddress' LIMIT 1";
	
	if ($dbconfig == "mysqli") { 
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		$result = $mysqli->query($query1);
		$mysqli->close();
	} else {
      die("Configuration error");
	}
	
}

function addadmin($username, $password, $domain, $active, $superadmin)
{
	include("config/config.php");
		
	$query = "INSERT into admin(username,password,domain,created,active,superadmin) VALUES('$username',SHA1('$password'),'$domain',NOW(),'$active','$superadmin')";
	
	if ($dbconfig == "mysqli") {
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
		}
		$result = $mysqli->query($query);
		$rowsaffected = $mysqli->affected_rows;
	} else { 
      die("Configuration error");
	}
		
	return $rowsaffected;
}

function deladmin($user)
{
	include("config/config.php");
	$query1 = "DELETE FROM admin WHERE username = '$user' LIMIT 1";
	
	
	if ($dbconfig == "mysqli") { 
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		
		if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
		}
		$result = $mysqli->query($query1);
		$mysqli->close();
	} else { 
      die("Configuration error");
	}	
}


function deldomain($domain) {
	include("config/config.php");
	$domainquery = "DELETE FROM domain WHERE domain = '$domain'";
	$usersquery = "DELETE FROM mailbox WHERE domain = '$domain'";
	$aliasquery = "DELETE FROM alias WHERE domain = '$domain' ";
	$policyquery = "DELETE FROM policy WHERE policy_name LIKE '%$domain'";
	$filterquery = "DELETE FROM users WHERE email LIKE '%$domain'";
	$adminquery = "DELETE FROM admin WHERE domain = '$domain'";
	echo "<table width='100%' class='sample'>";
	if ($dbconfig == "mysqli") { 
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		
		if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
		}	
		
		if ($domaindelete = $mysqli->query($domainquery)) {
			echo "<tr><td width='22'><img src='$siteurl/images/ok.png' /></td><td class='text'>Domain Records for $domain deleted</td></tr>";
		} else {
			printf("<tr><td width='22'><img src='$siteurl/images/no.png' /></td><td class='text'>Error During Delete: %s\n</td></tr>", $mysqli->error);
		}
		
		if ($usersdelete = $mysqli->query($usersquery)) {
			echo "<tr><td width='22'><img src='$siteurl/images/ok.png' /></td><td class='text'>Users for $domain deleted</td></tr>";
		} else {
			printf("<tr><td width='22'><img src='$siteurl/images/no.png' /></td><td class='text'>Error During Delete: %s\n</td></tr>", $mysqli->error);
		}
		
		if ($aliasdelete = $mysqli->query($aliasquery)) {
			echo "<tr><td width='22'><img src='$siteurl/images/ok.png' /></td><td class='text'>Aliases for $domain deleted</td></tr>";
		} else {
			printf("<tr><td width='22'><img src='$siteurl/images/no.png' /></td><td class='text'>Error During Delete: %s\n</td></tr>", $mysqli->error);
		}
		
		if ($policydelete = $mysqli->query($policyquery)) {
			echo "<tr><td width='22'><img src='$siteurl/images/ok.png' /></td><td class='text'>Policies for $domain deleted</td></tr>";
		} else {
			printf("<tr><td width='22'><img src='$siteurl/images/no.png' /></td><td class='text'>Error During Delete: %s\n,/td></tr>", $mysqli->error);
		}
		
		if ($filterdelete = $mysqli->query($filterquery)) {
			echo "<tr><td width='22'><img src='$siteurl/images/ok.png' /></td><td class='text'>Filting for users for $domain deleted</td></tr>";
		} else {
			printf("<tr><td width='22'><img src='$siteurl/images/no.png' /></td><td class='text'>Error During Delete: %s\n</td></tr>", $mysqli->error);
		}
		
		if ($admindelete = $mysqli->query($adminquery)) {
			echo "<tr><td width='22'><img src='$siteurl/images/ok.png' /></td><td class='text'>Administrators for $domain deleted</td></td>";
		} else {
			printf("<tr><td width='22'><img src='$siteurl/images/no.png' /></td><td class='text'>Error During Delete: %s\n</td></td>", $mysqli->error);
		}
	} else { 
      die("Configuration error");
	}
	echo "</table>";
	return;
}

function addlist($recipient,$sender,$wb) {
	include("config/config.php");
	$senderquery = "SELECT * FROM mailaddr where email = '$sender'";
	if ($dbconfig == "mysqli") {
		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
		$result = $mysqli->query($senderquery);
		$num_rows = $result->num_rows;
		
	} else {
		$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
		mysql_select_db($postfixdatabase) or die('Could not select database');	
		$result = mysql_query($senderquery);
		$num_rows = mysql_num_rows($result);
		
	}
	
	if ($num_rows == 0) {
		$senderinsert = "INSERT INTO mailaddr(priority, email) VALUES('9','$sender')";
		$senderfind = "SELECT * FROM mailaddr WHERE email ='$sender'";
		if ($dbconfig == "mysqli") {
			$result = $mysqli->query($senderinsert);
			$result = $mysqli->query($senderfind);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$sender_id = $row['id'];
			$listinsert = "INSERT INTO wblist(rid, sid, wb) VALUES('$recipient','$sender_id','$wb')";
			if ($result = $mysqli->query($listinsert)) {
				$status = "<img src='$siteurl/images/ok.png' /></td><td>Listing Add Successfully";
			} else {
				$status = "Error: " . $mysqli->error;
			}
		} else {
			$result = mysql_query($senderinsert);
			$result = mysql_query($senderquery);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$sender_id = $row['id'];
			$listinsert = "INSERT INTO wblist(rid, sid, wb) VALUES('$recipient','$sender_id','$wb')";
			if ($result = mysql_query($listinsert)) {
				$status = "<img src='$siteurl/images/ok.png' /></td><td>Listing Add Successfully";
			} else { 
				$status = "Error: " . mysql_error($link);
			}
		}
	} else {
		if ($dbconfig == "mysqli") {
			$result = $mysqli->query($senderquery);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$sender_id = $row['id'];
			$listinsert = "INSERT INTO wblist(rid, sid, wb) VALUES('$recipient','$sender_id','$wb')";
			if ($result = $mysqli->query($listinsert)) {
				$status = "<img src='$siteurl/images/ok.png' /></td><td>Listing Add Successfully";
			} else {
				$status = "Error: " . $mysqli->error;
			}
		} else {
			$result = mysql_query($senderquery);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$sender_id = $row['id'];
			$listinsert = "INSERT INTO wblist(rid, sid, wb) VALUES('$recipient','$sender_id','$wb')";
			if ($result = mysql_query($listinsert)) {
				$status = "<img src='$siteurl/images/ok.png' /></td><td>Listing Add Successfully";
			} else { 
				$status = "Error: " . mysql_error($link);
			}
		}
	}	
	return $status;
}


?>