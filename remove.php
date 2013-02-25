<?php

require_once ('../../mysqli_connect.php');

session_start(); // Start the session.

// If no session value is present, redirect the user:
if (!isset($_SESSION['user_id'])) {
	require_once ('includes/login_functions.inc.php');
	$url = absolute_url();
	header("Location: $url");
	exit();	
}

$edit_session_id = $_SESSION['user_id'];

// find who the user is
$intern_id = $_GET['internid'];
// get details of internship data submitted already
$sql_query = "SELECT * FROM user_agency WHERE intern_id = '$intern_id'";
$agency_result = mysqli_query($dbc, $sql_query);
		
while($row = mysqli_fetch_array($agency_result, MYSQLI_ASSOC)){
	$edit_user_id = $row['user_id'];
}

$page_title = 'Remove Internship';

include('includes/header.php');
	
require_once ('../../mysqli_connect.php'); // Connect to the db.

$errors = array(); // Initialize an error array.

//check the editor is the owner
if ($edit_session_id !== $edit_user_id) {
	$errors[] = 'Ummmmm, are you sure this is your internship?<br />';
}

//check email is entered
if (empty($intern_id)) {
	$errors[] = 'Could not find this internship.';
}

if (empty($errors)) { // If everything's OK.
		
	$q = "DELETE FROM user_agency WHERE intern_id='$intern_id';";
	
	$r = mysqli_query($dbc, $q);
	if ($r) { // If it ran OK.
	
		// Print a message:
		echo '<h1>Thank you!</h1>
		<p>Thank you, this internship has been removed</p>';

		include('includes/footer.php');
		} 
	
	else { // If it did not run OK.
		
		// Public message:
		echo '<h1>System Error</h1>
		<p class="error">Oops something went wrong!.</p>
		<p>Please contact <a href="mailto:hello@alastairwright.com">support</a> and we will gladly help.'; 
		
		// Debugging message:
		// echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
					
	} // End of if ($r) IF.
	
	mysqli_close($dbc); // Close the database connection.

	// Include the footer and quit the script:
	exit();
	
} 

else { // Report the errors.

	echo '<h1>Uh oh!</h1>';
	foreach ($errors as $msg) { // Print each error.
		echo " - $msg<br />\n";
	}
	
} // End of if (empty($errors)) IF.

mysqli_close($dbc); // Close the database connection.
	
include('includes/footer.php');
?>