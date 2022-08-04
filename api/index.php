<?php
//echo PHP_OS;
function wp_normalize_path( $path ) {
    $path = str_replace( '\\', '/', $path );
    $path = preg_replace( '|(?<=.)/+|', '/', $path );
    if ( ':' === substr( $path, 1, 1 ) ) {
        $path = ucfirst( $path );
    }
    return $path;
}

$path = wp_normalize_path(__DIR__);

// include required php files
require $path . "/../config/bootstrap.php";
// include main configuration file
require PROJECT_ROOT_PATH . "config/config.php";


//echo "$$ " .  PROJECT_ROOT_PATH;
//echo "<br/>$$ " . PROJECT_ROOT_PATH . "config\\config.php";

function check_login($dbhost, $dbuser, $dbpass, $authentdatabase, $username, $password ) {
	$bValid = false;
	
	$query = "SELECT password, isadmin, domain, active FROM mailbox WHERE username = ?";
	
	//echo "** check_login  $dbhost, $dbuser, $dbpass, $authentdatabase<br/>";
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $authentdatabase);

	if( $stmt = $mysqli->prepare($query) ) {
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$numrows = $result->num_rows;
		//echo "** numrows " . $numrows; 
	} else {
		error_log("** check_login error  " . $mysqli->error);
		die( $mysqli->error);
	}

	//error_log("** 1");
	if( $numrows > 0 and password_verify($password, $row["password"]) ) {
		//echo "** mdp valid"; 
		$bValid = true;
	}
	return $bValid;
}

//echo "auth user " . $_SERVER['PHP_AUTH_USER'] . "/ passwd " . $_SERVER['PHP_AUTH_PW'];

//echo "$$ " . $_SERVER['REQUEST_METHOD'];

//echo "$$ host " . $dbhost;

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) ) {
	
	// validate user and password	
	// the user is authenticated and handle the rest api call here
	//echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
	//echo "** $dbhost, $dbuser, $dbpass, $authentdatabase<br/>";

	if( ! check_login( $dbhost, $dbuser, $dbpass, $authentdatabase, $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] ) ) {
		header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');
		exit;
	}


	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri = explode( '/', $uri );

	//var_dump($uri);
	 
	if (! isset($uri[4]) || !isset($uri[5])) {
		echo "Method not found";
		header("HTTP/1.1 404 Not Found");
		exit();
	}
	 
	//echo "1 call usercontroller<br/>";

	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		require PROJECT_ROOT_PATH . "\api\UserController.php";
	}
	else {
		require PROJECT_ROOT_PATH . "/api/UserController.php";
	}
	
	//echo "$$ method " . $uri[4];
	if( $uri[4] == 'user' ) {
		//echo "2 call usercontroller<br/>";
		$objFeedController = new UserController($dbhost, $dbuser, $dbpass, $authentdatabase);
		//echo "3 call usercontroller<br/>";
		
		$strMethodName = $uri[5] . $_SERVER['REQUEST_METHOD'];
		//echo "4 call usercontroller with $strMethodName <br/>";
		$objFeedController->{$strMethodName}();
		//echo "5 call usercontroller<br/>";
	} elseif( $uri[4] == 'blacklist' ) {
		//echo "2 call usercontroller<br/>";
		$objFeedController = new BlacklistController($dbhost, $dbuser, $dbpass, $postfixdatabase);
		//echo "3 call usercontroller<br/>";
		
		$strMethodName = $uri[5] . $_SERVER['REQUEST_METHOD'];
		//echo "4 call usercontroller with $strMethodName <br/>";
		$objFeedController->{$strMethodName}();
		//echo "5 call usercontroller<br/>";		
	} else {
		echo "Method not found";
		header("HTTP/1.1 404 Not Found");
		exit();
	}
} else {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    //echo 'Text to send if user hits Cancel button';
    exit;

}
?>