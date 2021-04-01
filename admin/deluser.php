<?
require '../check_login.php';

if ($loggedin == 1 and $superadmin == 0) {
	$url = $siteurl . "/index.php";
	header('Location:'. $url);
} elseif ($loggedin == 0) {
	$url = $siteurl . "/login.php";
	header('Location:'. $url);
}
require_once("../config/config.php");
if (isset($_POST['Cancel'])) {
	$domain= $_POST['domain'];
	$url = $siteurl . "/admin/users.php?domain=$domain";
	header("Location: $url");
}

require_once("../functions.inc.php");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?

$userinfo = new UserInfo;
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PostVis Admin</title>
<style type="text/css">
<!--
.style5 {font-family: Verdana; font-size: 9px; }
-->
</style>
<link href="../style2.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="900" border="0" align="center" cellpadding="3" cellspacing="0">
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
            <td class='text'>Confirm User Delete </td>
          </tr>
        </table>
      </div></td>
  </tr>
  <tr>
    <td valign="top"><? include('adminmenu.php'); ?>
      <br />    </td>
    <td valign="top" class="main">
<? if (isset($_POST['Submit'])) {
	
	
	$username = $_POST['username'];
	
	deluser($username);
	$domain = $_POST['domain'];
	echo "<div class='text'><center>User $username was removed successfully</center>";
	echo "<center><br /><a href='users.php?domain=$domain'>Go back to Domain Admin</a></center></div>";
} else { ?>
      <form id="form1" name="form1" method="post" action="">
        <div id="main">
          <div align="center" class="text">Delete the following account and all aliases associated with it?<br />
            <br />
<? 
	 echo "Name: " . $userinfo->name . "<br />";
	 echo "Email: " . $userinfo->username . "<br />";
?>
            <input name="domain" type="hidden" value="<? echo $_GET['domain']; ?>" />
            <input name="username" type="hidden" value="<? echo $userinfo->username; ?>" />
            <br />
            
            <input type="submit" name="Submit" value="Submit" />
            
           
            <input name="Cancel" type="submit" id="Cancel" value="Cancel" />
          </div>
        </div>
      </form>
      <? } ?>    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $footer; ?></td>
  </tr>
</table>
</body>
</html>
