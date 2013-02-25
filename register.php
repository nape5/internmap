<?php

require_once ('../../mysqli_connect.php');

$page_title = 'Register';

include('includes/header.php');

if (isset($_POST['submitted'])) {
	
	require_once ('includes/randompass.php');

	$random = createRandomPassword(); 
		
	$errors = array(); // Initialize an error array.
	
	//check first is entered
	if (empty($_POST['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}
	
	//check last name is entered
	if (empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	}
	
	//check email is entered
	if (empty($_POST['hyper_email'])) {
		$errors[] = 'You forgot to enter your email.';
	} else {
		$hyper_email = mysqli_real_escape_string($dbc, trim($_POST['hyper_email']));
	}
	
	
	
	//change website to nothing if its just www.
	if (($_POST['website'] == 'www.')||(empty($_POST['website']))) {
		$website = 'None';
	}
	else{
		$website = mysqli_real_escape_string($dbc, trim($_POST['website']));
	}
	
	//check for hyperisland email address
	$pieces = explode("@", $_POST['hyper_email']);
	$server = $pieces[1]; // piece2
	
	$pieces = explode(".", $server);
	$hyper = $pieces[0]; // piece2
	
	if($hyper!=='hyperisland'){
		$errors[] = 'The email address you entered is not a Hyper Island email address.';
	}
	
	//  Test for unique email address:
	$q = "SELECT first_name FROM users WHERE hyper_email='$hyper_email'";
	$r = @mysqli_query($dbc, $q);
	if (mysqli_num_rows($r) !== 0) {
		$errors[] = 'The email address has already been used. Click <a href="forgotten.php">here</a> if you have forgotten your password.';
	}
	
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

		
	
		// Register the user in the database…
		
		// Make the query:
		
		$q = "INSERT INTO users (first_name, last_name, hyper_email, website, password, verify) VALUES ('$first_name', '$last_name', '$hyper_email', '$website', SHA1('$np'), '$random')";	
		$r = mysqli_query($dbc, $q);
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<h1>Thank you!</h1>
			<p>Thank you for registering, to complete the process please verify your email address.</p>
			<p>An email has been sent to ' . $hyper_email . ', please check your inbox to finish the registration.</p><p><br /></p><hr />';	
			
			/* *******************************************MAIL STUFF********************************************* */
			
		
			/* 
			The next lines build your email
			*/
			
			$sendto= "$hyper_email";
			$headers.= "MIME-Version: 1.0\r\n";
			$headers.= "Content-type: text/html; ";
			$headers.= "charset=iso-8859-1\r\n";
			$headers.= "From: Internmap <contact@internmap.com>";
			$subject= "Confirm your email - Internmap.com";
			
			// Build the email body text
			$emailcontent = "
			<html>
			<head>
			<style type='text/css'>
			body {
				background: #000;
				width: 500px;
				height: 800px;
				margin: 10px auto;
				padding: 20px;
				font-family: Georgia, 'Times New Roman', Times, serif;
				font-style: italic;
				font-size: 16px;
				color: #ffffff;
			}
			
			a{text-decoration:none; font-style:italic; border:none; color:#fae91a;}
			a:visited{}
			a:hover{border-bottom: 1px solid #fae91a;}
			a:active{}
			
			img#logo{margin: 0 0 -10px 10px;}
			
			#logotext{
				font-family: sans-serif;
				color: #fff; 
				font-size:43px;
				text-decoration: none;
				font-style: normal;
				font-weight: bolder;
			}
			
			hr{
				width: 100%;
				height: 1px;
				margin: 18px 0 5px 0;
				background-color: #fae91a;
				border: 0px;
			}
			</style>
			</head>
			<body>
			<span id='logotext'>INTERNMAP</span><img id='logo' src='http://internmap.com/images/logo.png' alt='intern map' /><br /><br />
			Welcome to InternMap $firstname,<br /><br />
			To complete your registration, please click <a href='http://internmap.com/verify.php?pass=$random&hyper_email=$hyper_email'>here</a>.<br /><br />
			Thank you.
			<hr />
			</body>
			</html>";
			
			//  This is the form response page incuding elements of the php code
			
			
			mail($sendto, $subject, $emailcontent, $headers);

		
			/* *******************************************MAIL STUFF********************************************* */
		
		} 
		
		else { // If it did not run OK.
			
			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologise for any inconvenience.</p>'; 
			
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

} // End of the main Submit conditional.
?>

		
		<h1>Register</h1>
		<form action="register.php" method="post">
			<h3>First Name</h3>
			<input type="text" class="input_box" name="first_name" size="30" maxlength="20" />
			<h3>Last Name</h3>
			<input type="text" class="input_box" name="last_name" size="30" maxlength="40" />
			<h3>Hyper Island Email</h3>
			<input type="text" class="input_box" name="hyper_email" size="40" maxlength="60"/>
			<h3>Homepage</h3>
			<input type="text" class="input_box" name="website" size="40" maxlength="60" value="www."/>
			<h3>Password</h3>
			<input type="password" class="input_box" name="pass1" size="10" maxlength="20" />
			<h3>Confirm Password</h3>
			<input type="password" class="input_box" name="pass2" size="10" maxlength="20" />
			<input type="submit" class="submit_button" name="submit" value="Register" />
			<input type="hidden" name="submitted" value="TRUE" />
		</form>
<?php
include('includes/footer.php');
?>