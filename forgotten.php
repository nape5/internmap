<?php

require_once ('../../mysqli_connect.php');

$page_title = 'Forgotten Password';

include('includes/header.php');

if (isset($_POST['submitted'])) {
	
	require_once ('includes/randompass.php');

	$random = createRandomPassword(); 
		
	$errors = array(); // Initialize an error array.
	
		
	//check email is entered
	if (empty($_POST['hyper_email'])) {
		$errors[] = 'You forgot to enter your email.';
	} else {
		$hyper_email = mysqli_real_escape_string($dbc, trim($_POST['hyper_email']));
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
	$q = "SELECT * FROM users WHERE hyper_email='$hyper_email'";
	$forgotten_result = @mysqli_query($dbc, $q);
	if (mysqli_num_rows($forgotten_result) == 0) {
		$errors[] = 'This email does not exist yet, please <a href="register.php">register</a> as normal.';
	} else{
		while($row = mysqli_fetch_array($forgotten_result, MYSQLI_ASSOC)){
			$forgotten_user_id = $row['user_id'];
			$firstname = $row['first_name'];
		}
	}
	
	
	if (empty($errors)) { // If everything's OK.

		
	
		// Update the database…
		
		$q = "UPDATE users SET password='$random' WHERE user_id='$forgotten_user_id';";
		$r = mysqli_query($dbc, $q);
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<h1>Thank you!</h1>
			<p>Thank you, an email has been sent to ' . $hyper_email . ', please check your inbox to complete the process.</p><p><br /></p><hr />';	
			
			/* *******************************************MAIL STUFF********************************************* */
			
			/* 
			The next lines build your email
			*/
			
			$sendto= "$hyper_email";
			$headers.= "MIME-Version: 1.0\r\n";
			$headers.= "Content-type: text/html; ";
			$headers.= "charset=iso-8859-1\r\n";
			$headers.= "From: Internmap <contact@internmap.com>";
			$subject= "Forgotten Password - Internmap.com";
			
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
			<span id='logotext'>INTERNMAP</span><img id='logo' src='http://internmap.alastiarwright.com/images/logo.png' alt='intern map' /><br /><br />
			Hello again $firstname,<br /><br />
			To choose your new password, please click <a href='http://internmap.alastiarwright.com/confirm.php?pass=$random'>here</a>.<br /><br />
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

		
		<h1>Reset Password</h1>
		<form action="forgotten.php" method="post">
			<h3>Hyper Island Email</h3>
			<input type="text" class="input_box" name="hyper_email" size="40" maxlength="60"/>
			<input type="submit" class="submit_button" name="submit" value="Sumbit" />
			<input type="hidden" name="submitted" value="TRUE" />
		</form>
<?php
include('includes/footer.php');
?>