<?php

require_once ('../../mysqli_connect.php');

session_start(); // Start the session.

$page_title = 'Intern Map';

include('includes/header.php');
?>

		<p>
		InternMap.com is a Hyper Island student produtcion. See what past students have been up to since leaving Hyper Island. </p>
		
		<p>Use this website to easily navigate the alumni network! Log where you've worked and find Hyper Island students near to you.</p>
		
		<br />
		Log in to add a work place, past school or browse the different businesses and industries that Hyper Island students have moved onto. 
		<p/>
		<hr />
		
		<a id="button" href="login.php"><div>Login</div></a><a id="button" href="register.php"><div>Register</div></a>

<?php
include('includes/footer.php');
?>
