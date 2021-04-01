<? 
$dbhost = 'localhost';  // Database Host to connect to
$dbuser = 'postfix';  // Username that has access to your Postfix Database
$dbpass = 'postfix';     // Password that has access to your Postfix Database
$postfixdatabase = 'postfix'; // Postfix Database
$dbconfig = "mysqli";

// Using time_iso instead of time_num in MySQL
$msgscleanup1 = "DELETE FROM msgs WHERE time_iso < now() - INTERVAL 1 hour AND content IS NULL;";
$msgscleanup2 = "DELETE FROM maddr WHERE NOT EXISTS (SELECT 1 FROM msgs WHERE sid=id) AND NOT EXISTS (SELECT 1 FROM msgrcpt WHERE rid=id);";
$msgscleanup3 = "DELETE FROM maddr WHERE NOT EXISTS (SELECT 1 FROM msgs WHERE sid = id) AND NOT EXISTS (SELECT 1 FROM msgrcpt WHERE rid = id)";
$msgscleanup4 = "DELETE FROM msgs WHERE time_iso < UTC_TIMESTAMP() - INTERVAL 30 day";

$quarantinecleanup = "DELETE FROM quarantine WHERE NOT EXISTS (SELECT 1 FROM msgs WHERE mail_id=quarantine.mail_id)"; 
$quarantinecleanup2 = "DELETE FROM msgrcpt WHERE NOT EXISTS (SELECT 1 FROM msgs WHERE mail_id=msgrcpt.mail_id)";
$quarantinecleanup3 = "DELETE FROM msgs WHERE time_iso < now()- INTERVAL 14 day AND (content='V' OR content='S')";




$awlcleanup1 = "DELETE FROM awl WHERE lastupdate <= DATE_SUB(SYSDATE(), INTERVAL 6 MONTH)";
$awlcleanup2 = "DELETE FROM awl WHERE count=1 AND lastupdate <= DATE_SUB(SYSDATE(), INTERVAL 30 DAY)";

if ($dbconfig == "mysqli") { 
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
			$result = $mysqli->query($msgscleanup1);
			$result = $mysqli->query($msgscleanup2);
			$result = $mysqli->query($msgscleanup3);
			$result = $mysqli->query($msgscleanup4);	
			$result = $mysqli->query($quarantinecleanup);
			$result = $mysqli->query($quarantinecleanup2);
			$result = $mysqli->query($quarantinecleanup3);
			$result = $mysqli->query($awlcleanup1);
			$result = $mysqli->query($awlcleanup2);
		} else { 
			$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
			mysql_select_db($postfixdatabase) or die('Could not select database');
			$result = mysql_query($msgscleanup1);
			$result = mysql_query($msgscleanup2);
			$result = mysql_query($msgscleanup3);
			$result = mysql_query($msgscleanup4);
			$result = mysql_query($quarantinecleanup);
			$result = mysql_query($quarantinecleanup2);
			$result = mysql_query($quarantinecleanup3);
			$result = mysql_query($awlcleaup1);
			$result = mysql_query($awlcleanup2);
		}
?>
