<?php # Script 11.8 - login.php #3

require_once ('../../mysqli_connect.php');

if (isset($_POST['submitted'])) { 
	require_once ('includes/login_functions.inc.php');
	
	list ($check, $data) = check_login($dbc, $_POST['email'], $_POST['pass']);
	
	if ($check) { // OK!
			
		// Set the session data:.
		session_start();
		$_SESSION['user_id'] = $data['user_id'];
		$_SESSION['first_name'] = $data['first_name'];
		
		// Redirect:
		// url was loggedin.php, but its a needless page, now go straight to the users profile
		$url = absolute_url ("user.php?userid={$_SESSION['user_id']}");
		header("Location: $url");
		exit();
			
	} else { // Unsuccessful!
		$errors = $data;
	}
		
	mysqli_close($dbc);

} // End of the main submit conditional.

$page_title = 'Log In';

include('includes/header.php');

include ('includes/login_page.inc.php'); 

?>
