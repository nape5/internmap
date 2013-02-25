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

$user_id = $_SESSION['user_id'];

$page_title = 'Add Agency';

include('includes/header.php');

if (isset($_POST['submitted'])) {
	
	require_once ('../../mysqli_connect.php'); // Connect to the db.
	
	$errors = array(); // Initialize an error array.
	
	//check email is entered
	if (empty($_POST['name'])) {
		$errors[] = 'You forgot to enter the agency name.';
	} else {
		$name = mysqli_real_escape_string($dbc, trim($_POST['name']));
	}
	
	//check email is entered
	if (empty($_POST['city'])) {
		$errors[] = 'You forgot to enter the agency city.';
	} else {
		$city = mysqli_real_escape_string($dbc, trim($_POST['city']));
	}
	
	//check email is entered
	if (empty($_POST['code'])) {
		$errors[] = 'You forgot to enter the agency area code.';
	} else {
		$code = mysqli_real_escape_string($dbc, trim($_POST['code']));
	}
	
	//check website is entered
	if (($_POST['website'] == 'www.')||(empty($_POST['website']))) {
		$errors[] = 'You forgot to enter the agency website.';
	} else {
		$website = mysqli_real_escape_string($dbc, trim($_POST['website']));
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
		
		// Make the query:
		
		$xml = simplexml_load_file('http://maps.googleapis.com/maps/api/geocode/xml?address=' . $city . ',' . $code . '&sensor=true');
		// for each xml item assign variable and insert into database
		foreach($xml->result->geometry->location as $address) {		
		$lat = $address->lat;
		$lng = $address->lng;
		}
		
		// get the last agency_id
		$sql_query = "SELECT * FROM agency ORDER BY agency_id DESC LIMIT 1;";
		$agency_result = mysqli_query($dbc, $sql_query);
				
		while($row = mysqli_fetch_array($agency_result, MYSQLI_ASSOC)){
			$old_agency_id = $row['agency_id'];
		}
		
		$agency_id = $old_agency_id + 1;
		
		$q = "INSERT INTO agency (agency_id, name, city, lat, lng, website) VALUES ('$agency_id', '$name', '$city', '$lat', '$lng', '$website')";	
		$r = mysqli_query($dbc, $q);
		
		$date_start = $startDateYear . '-' . $startDateMonth . '-01';
		
		if($present=='false'){
			$date_finish = $finishDateYear . '-' . $finishDateMonth . '-01';
		}
		
		$q = "INSERT INTO user_agency (user_id, agency_id, date_start, date_finish, present) VALUES ('$user_id', '$agency_id', '$date_start', '$date_finish', '$present')";	
		$r = mysqli_query($dbc, $q);
		
		if ($r) { // If it ran OK.
		
			// get the last intern_id
			$sql_query = "SELECT * FROM user_agency ORDER BY intern_id DESC LIMIT 1;";
			$intern_id_result = mysqli_query($dbc, $sql_query);
					
			while($row = mysqli_fetch_array($intern_id_result, MYSQLI_ASSOC)){
				$intern_id = $row['intern_id'];
			}
		
			// Print a message:
			echo '<h1>Thank you!</h1>
			<p>Thank you, your internship details have been added.</p>';
			echo "\n";
			echo '<br />';
			echo "\n";
			echo 'Click <a href="agency.php?agencyid=' . $agency_id . '">here</a> to view your internship on the map';	
			echo "\n";

			include('includes/footer.php');
			} 
		
		else { // If it did not run OK.
			
			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">Your agency could not be registered due to a system error. Sorry!</p>'; 
			
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

<form action="addagency.php" method="post">
	<h3>Agency Name</h3><input type="text" class="input_box" name="name" size="20" maxlength="80" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>"/>
	<h3>City</h3><input type="text" class="input_box" name="city" size="20" maxlength="80" value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>"/>
	<h3>Post Code</h3><input type="text" class="input_box" name="code" size="20" maxlength="80" value="<?php if (isset($_POST['code'])) echo $_POST['code']; ?>"/>
	<h3>Agency Website</h3><input type="text" class="input_box" name="website" size="20" maxlength="80" value="www."/>
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

<input type="text" value="Year" name="startDateYear" maxlength="4" class="input_box_year" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">


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
	
	</span><br /><br />
	<input type="submit" class="submit_button" name="submit" value="Submit" />
	<input type="hidden" name="submitted" value="TRUE" />
</form>

<?php
include('includes/footer.php');
?>