<?php

require_once ('../../mysqli_connect.php');

$page_title = 'Confirm Password';

include('includes/header.php');

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	require_once ('../../mysqli_connect.php'); // Connect to the db.
		
	$errors = array(); // Initialize an error array.
	
	// Check for a new password and match 
	// against the confirmed password:
	if (!empty($_POST['pass1'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = 'The two passwords did not match.';
		} else {
			$np = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
		}
	} 
	
	else {
		$errors[] = 'You forgot to enter your new password.';
	}
	
	if (empty($errors)) { // If everything's OK.
	
		extract($_GET);

		$q = "SELECT hyper_email FROM users WHERE password='$pass'";
		$r = @mysqli_query($dbc, $q);
		
		// Get the user_id:
		$row = mysqli_fetch_array($r, MYSQLI_NUM);
		$email = $row[0];
		
		$q = "UPDATE users SET password=SHA1('$np') WHERE hyper_email='$email'";		
		$r = @mysqli_query($dbc, $q);
		
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.			
			// Print a message.
			echo '<h1>Thank you!</h1>
			<p>Your password has been updated.<br /><br />Click <a href="login.php">here</a> to log in</p>';			
		} 
		
		else { // If it did not run OK.		
			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">Your password could not be changed due to a system error.</p>
			<p>Please contact <a href="mailto:hello@alastairwright.com">support</a> and we will gladly help.'; 
		}
		
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
		
} // End of the main Submit conditional.


extract($_GET);
echo'
<h1>Choose Your Password</h1>
<form action="confirm.php?pass=' . $pass . '" method="post">
<h3>Password</h3>
<input type="password" class="input_box" name="pass1" size="10" maxlength="20" />
<h3>Confirm Password</h3>
<input type="password" class="input_box" name="pass2" size="10" maxlength="20" />
<input type="submit" class="submit_button" name="submit" value="Submit" />
<input type="hidden" name="submitted" value="TRUE" />
</form>
';


?>
<?php
include('includes/footer.php');
?>

