<?
// dircleanup.php
// PHP CLI script that compares Maildir structure to active email accounts.
// If account is not valid, will remove MailDir
// If account is valid will move on to next one. 
// Will check for valid domains as well and remove any orphaned domains
//
// PostVis Admin 1.3.2

$maildir_path = "/Storage/mail"; //Path to mail store
$dbhost = 'localhost';  // Database Host to connect to
$dbuser = 'postfix';  // Username that has access to your Postfix Database
$dbpass = 'postfix';     // Password that has access to your Postfix Database
$postfixdatabase = 'postfix'; // Postfix Database
function searchdir ( $path , $maxdepth = 2 , $mode = "DIRS" , $d = 0 )
{
   if ( substr ( $path , strlen ( $path ) - 2 ) != '/' ) { $path .= '/' ; }     
   $dirlist = array () ;
   if ( $mode != "FILES" ) { $dirlist[] = $path ; }
   if ( $handle = opendir ( $path ) )
   {
       while ( false !== ( $file = readdir ( $handle ) ) )
       {
           if ( $file != '.' && $file != '..' )
           {
               $file = $path . $file ;
               if ( ! is_dir ( $file ) ) { if ( $mode != "DIRS" ) { $dirlist[] = $file ; } }
               elseif ( $d >=0 && ($d < $maxdepth || $maxdepth < 0) )
               {
                   $result = searchdir ( $file . '' , $maxdepth , $mode , $d + 1 ) ;
                   $dirlist = array_merge ( $dirlist , $result ) ;
               }
       }
       }
       closedir ( $handle ) ;
   }
   if ( $d == 0 ) { natcasesort ( $dirlist ) ; }
   return ( $dirlist ) ;
}
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);
$maildir = searchdir($maildir_path);

foreach ($maildir as $key) {
	if ($key == ($maildir_path . "/")) {
		echo "Searching Mail Storage for orphaned accounts\n\n";
	} else {
	$string = str_replace($maildir_path,"",$key);
	$string2 = substr_replace($string, "", 0, 1);
	$query = "SELECT domain, maildir FROM mailbox WHERE maildir = '$string2'";
	$result = $mysqli->query($query);
	$num_rows = $result->num_rows;
		if ($num_rows == 1) {
			echo "User Exsists $string2 \n\n";
		} else {
			$string3 = str_replace("/","",$string2);
			$query2 = "SELECT domain FROM domain WHERE domain LIKE '$string3'";
			$result = $mysqli->query($query2);
			$num_rows = $result->num_rows;
			if ($num_rows == 0) {
				echo "Deleting MAILDIR: $key \n\n";
				exec("rm -r $key");
			} 	
				
		}
	}
}
?>