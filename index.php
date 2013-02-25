<?php

require_once ('../../mysqli_connect.php');

session_start(); // Start the session.

$page_title = 'Intern Map';

include('includes/header.php');
?>

		<p>
		InternMap.com is a Hyper Island student produtcion. Have you ever wanted to get that inside contact, or the real story about an agency? </p>
		
		<p>Use this website to find the right person to ask! This is a space where students can log the agencies they work in, and see what the world can offer in terms of media and digital agencies.</p>
		
		<br />
		Log in to add an internship, or feel free to browse different agencies and students. 
		<p/>
		<hr />
		
		<a id="button" href="login.php"><div>Login</div></a><a id="button" href="register.php"><div>Register</div></a>

<?php
include('includes/footer.php');
?>
