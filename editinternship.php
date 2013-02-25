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

$page_title = 'Edit Internship';

include('includes/header.php');

if (isset($_POST['submitted'])) {
	
	require_once ('../../mysqli_connect.php'); // Connect to the db.
		
	$errors = array(); // Initialize an error array.
	
	//check the editor is the owner
	if ($edit_session_id !== $edit_user_id) {
		$errors[] = 'Ummmmm, are you sure this is your internship?<br />';
	}
	
	//check email is entered
	if (empty($_POST['agency'])) {
		$errors[] = 'You have not selected an agency';
	} else {
		$agency_id = mysqli_real_escape_string($dbc, trim($_POST['agency']));
	}
	
	//check email is entered
	if (empty($_POST['startDateMonth'])) {
		$errors[] = 'You forgot choose a start month.';
	} else {
		$startDateMonth = mysqli_real_escape_string($dbc, trim($_POST['startDateMonth']));
	}
	
	//check email is entered
	if (empty($_POST['startDateYear'])) {
		$errors[] = 'You forgot to enter your start year.';
	} else {
		$startDateYear = mysqli_real_escape_string($dbc, trim($_POST['startDateYear']));
	}
	
	if (isset($_POST['present'])){
		$present = 'true';	
		$date_finish = '0000-00-00';
	} else {
		$present = 'false';
		if (empty($_POST['finishDateMonth'])) {
			$errors[] = 'You forgot to enter your finish month.';
		} else {
			$finishDateMonth = mysqli_real_escape_string($dbc, trim($_POST['finishDateMonth']));
		}
		if (empty($_POST['finishDateYear'])) {
			$errors[] = 'You forgot to enter your finish year.';
		} else {
			$finishDateYear = mysqli_real_escape_string($dbc, trim($_POST['finishDateYear']));
		}		
	}
	
	if (empty($errors)) { // If everything's OK.
		
		$date_start = $startDateYear . '-' . $startDateMonth . '-01';
		
		if($present=='false'){
			$date_finish = $finishDateYear . '-' . $finishDateMonth . '-01';
		}
		
		$intern_id = mysqli_real_escape_string($dbc, trim($_POST['internid']));
		
		$q = "UPDATE user_agency SET agency_id='$agency_id', date_start='$date_start', date_finish='$date_finish', present='$present' WHERE intern_id='$intern_id';";
		
		$r = mysqli_query($dbc, $q);
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<h1>Thank you!</h1>
			<p>Thank you, your internship details have been updated.</p>';

			include('includes/footer.php');
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
else{
	$intern_id = $_GET['internid'];
	// get details of internship data submitted already
	$sql_query = "SELECT * FROM user_agency WHERE intern_id = '$intern_id'";
	$agency_result = mysqli_query($dbc, $sql_query);
			
	while($row = mysqli_fetch_array($agency_result, MYSQLI_ASSOC)){
		$agency_id = $row['agency_id'];
		$Month = strftime('%B', strtotime($row['date_start']));
		$MonthValue = strftime('%m', strtotime($row['date_start']));
		$Year = strftime('%Y', strtotime($row['date_start']));
		
		$sql_query = "SELECT * FROM agency WHERE agency_id = '$agency_id'";
		$agency_result = mysqli_query($dbc, $sql_query);
		while($row = mysqli_fetch_array($agency_result, MYSQLI_ASSOC)){
			$name = $row['name'];
			$city = $row['city'];
			
			$agency_name = $name . ', ' . $city;
		}
	}
}
?>

<!-- START THE FORM -->

<h1>Edit Internship</h1>
<form action="editinternship.php?internid=<?php echo $intern_id; ?>" method="post">
	<input type="hidden" name="internid" value="<?php echo $intern_id; ?>" />
	
	<h3>Agency</h3>
	<select name="agency" style="margin: 5px 0 0 -2px;">
	<option value="<?php echo $agency_id; ?>" selected><?php echo $agency_name; ?></option>
	<?php 
	
	$sql_query = "SELECT * FROM agency ORDER BY name ASC";
	$agency_result = mysqli_query($dbc, $sql_query);
			
	while($row = mysqli_fetch_array($agency_result, MYSQLI_ASSOC)){
		$agency_id = $row['agency_id'];
		$name = $row['name'];
		$city = $row['city'];
		
		$agency_name = $name . ', ' . $city;
	
		echo '<option value="' . $agency_id . '">' . $agency_name . '</option>';
	}

	?>
	</select>
	<br /><br />
	
	<!-- Script to hide and show dates depending on check box -->	
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.js"></script>
	<script type="text/javascript">//<![CDATA[ 
	$(window).load(function(){
	$('.other').click(function() {
	
	    if ($(this).is(':checked')) {
	        $(this).parents('form').children(".to-present").show();
	        $(this).parents('form').children(".to-date").hide();
	    }
	    else {
	        $(this).parents('form').children(".to-present").hide();
	        $(this).parents('form').children(".to-date").show();
	    }
	});
	});
	
	</script>
	<h3>Time Period</h3>
	<label><input value="1" type="checkbox" name="present" class="other"> I currently work here</label><br />
	
	
<select name="startDateMonth">
	<option value="<?php echo $MonthValue; ?>" selected><?php echo $Month; ?></option>
	<option value="1">January</option>
	<option value="2">February</option>
	<option value="3">March</option>
	<option value="4">April</option>
	<option value="5">May</option>
	<option value="6">June</option>
	<option value="7">July</option>
	<option value="8">August</option>
	<option value="9">September</option>
	<option value="10">October</option>
	<option value="11">November</option>
	<option value="12">December</option>
</select>

<input type="text" value="<?php echo $Year; ?>" name="startDateYear" maxlength="4" class="input_box_year" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">


	<span class="to-present">to present</span>
	<span class="to-date">to 
	
	
	
	
<select name="finishDateMonth">
	<option value="" selected>Choose...</option>
	<option value="1">January</option>
	<option value="2">February</option>
	<option value="3">March</option>
	<option value="4">April</option>
	<option value="5">May</option>
	<option value="6">June</option>
	<option value="7">July</option>
	<option value="8">August</option>
	<option value="9">September</option>
	<option value="10">October</option>
	<option value="11">November</option>
	<option value="12">December</option>
</select>

<input type="text" value="Year" name="finishDateYear" maxlength="4" class="input_box_year" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
	
	</span>
	
	<input type="submit" class="submit_button" name="submit" value="Submit" />
	<input type="hidden" name="submitted" value="TRUE" />
	<a id="button" href="remove.php?internid=<?php echo $intern_id; ?>"><div>Remove</div></a>
</form>
<br />
<?php
include('includes/footer.php');
?>