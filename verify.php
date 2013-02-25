<?php

require_once ('../../mysqli_connect.php');

$page_title = 'Verify Email';

include('includes/header.php');

$errors = array(); // Initialize an error array.

extract($_GET);

// check for the pass link
if (empty($pass)) {
	$errors[] = 'Sorry, there was an error with the email link.';
} 

$q = "SELECT * FROM users WHERE hyper_email='$hyper_email'";
$r = @mysqli_query($dbc, $q);

while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
	$verified = $row['verify'];
}

if ($verified == 'yes'){
	$errors[] = 'Your email has already been verified.<br /><br /> Click <a href="login.php">here</a> to log in.';
}

if ($verified !== $pass){
	$errors[] = 'Your email does not match the unique link we emailed to you.';
}

if (empty($errors)) { // If everything's OK.

	extract($_GET);
	
	$q = "UPDATE users SET verify='yes' WHERE verify='$pass'";		
	$r = @mysqli_query($dbc, $q);
	
	if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.			
		// Print a message.
		echo '<h1>Thank you!</h1>
		<p>Your email has been verified.<br /><br />Click <a href="login.php">here</a> to log in.</p>';
					
	} 
	
	else { // If it did not run OK.		
		// Public message:
		echo '<h1>System Error</h1>
		<p class="error">Your account could not be verified due to a system error.</p>
		<p>Please contact <a href="mailto:hello@alastairwright.com">support</a> and we will gladly help.'; 
	}
	
	include('includes/footer.php');
	
	mysqli_close($dbc); // Close the database connection.

	// Include the footer and quit the script:
	exit();
	

}
	
else { // Report the errors.

	echo '<h1>Uh oh!</h1>';
	foreach ($errors as $msg) { // Print each error.
		echo "$msg<br />\n";
	}
	
} // End of if (empty($errors)) IF.

mysqli_close($dbc); // Close the database connection.

include('includes/footer.php');
?>

