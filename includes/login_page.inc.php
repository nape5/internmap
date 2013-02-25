<?php # Script 11.1 - login_page.inc.php

// This page prints any errors associated with logging in
// and it creates the entire login page, including the form.

// Print any error messages, if they exist:
if (!empty($errors)) {
	echo '<h1>Uh oh!</h1>';
	foreach ($errors as $msg) {
		echo " - $msg<br />\n";
	}
}

// Display the form:
?>
<h1>Login</h1>
<form action="login.php" method="post" autocomplete="off">
	<h3>Email Address</h3>
	<input type="text" class="input_box" name="email" size="20" maxlength="80" />
	<h3>Password</h3>
	<input type="password" class="input_box" name="pass" size="20" maxlength="20" />
	<input type="submit" class="submit_button" name="submit" value="Login" />
	<input type="hidden" name="submitted" value="TRUE" />
</form>

<a id="button" href="register.php"><div>Register</div></a>

<br /><br /><br />

<a href="forgotten.php">Forgotten password?</a>

<br />
<?php
include('includes/footer.php');
?>
