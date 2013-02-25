<?php
require_once ('../../mysqli_connect.php');

$first_name = ucwords($_SESSION['first_name']);

$page_title = $first_name;

$set_type= 'student';

// show users details if a users profile is selected
if($_GET['userid']){
	$user_id = $_GET['userid'];
	// get zoom details before the header
	$sql_query = "SELECT * FROM  user_agency WHERE user_id='$user_id' ORDER BY date_start DESC LIMIT 1";
	$user_agency_result = mysqli_query($dbc, $sql_query);
	while($row = mysqli_fetch_array($user_agency_result, MYSQLI_ASSOC)){
		
		// get the name of the agency stated
		$agency_id = $row['agency_id'];
		$sql_query = "SELECT * FROM  agency WHERE agency_id='$agency_id'";
		$agency_result = mysqli_query($dbc, $sql_query);
		
		while($row = mysqli_fetch_array($agency_result, MYSQLI_ASSOC)){		
			$zoom_lat = $row['lat'];
			$zoom_lng = $row['lng'];
		}
	}
	
	include('includes/header.php');
	
	// check to see if the user is on their own page
	if($session_id == $user_id){
		$access = 1;
	}
	
	$sql_query = "SELECT * FROM users WHERE user_id='$user_id'";
	$result = mysqli_query($dbc, $sql_query);
	
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		if (mysqli_num_rows($result) == 1) {
			$firstname = $row['first_name'];
			$lastname = $row['last_name'];
			$email = $row['hyper_email'];
			$website = $row['website'];
		}
	}
	echo '<span style="text-transform:capitalize;"><h1>' . $firstname . ' ' . $lastname . '</h1></span>';
	echo "\n";
	echo 'Email: <a href="mailto:' . $email . '">' . $email . '</a><br />';
	echo "\n";
	echo 'Website: ';
	// don't add a link if there is no website
	if ($website=='None'){echo '<a href="#">';}else{echo '<a href="http://' . $website . '" target="_blank">';}
	echo $website . '</a><br /><br />';
	echo "\n";
	echo 'Internships: ';
	// add button for user if its their page
	if (isset($access)) {
		echo '<a href="addinternship.php">Add Internship</a><br />';
	}	
	echo "\n";
	echo '<div id="agency_list" onload="';
	echo "$('.scrollPane').scrollbar({color: '#333', track: '#eee', width: '10px'});";
	echo '">';
	echo "\n";
	echo '<hr>';
	echo "\n";	
	
	// get the users internship dates
	$sql_query = "SELECT * FROM  user_agency WHERE user_id='$user_id' ORDER BY date_start DESC";
	$user_agency_result = mysqli_query($dbc, $sql_query);
	if (mysqli_num_rows($user_agency_result) == 0) {
		echo '<span style="text-transform:capitalize;">' . $firstname . '</span> has not added any internships yet.';
	}
	else{
		while($row = mysqli_fetch_array($user_agency_result, MYSQLI_ASSOC)){
			
			$date_start = strftime('%B %Y', strtotime($row['date_start']));
			$intern_id = $row['intern_id'];
			
			$present = $row['present'];
			//if intern is set to present, don't show the finish date
			if($present == 'true'){
				$date_finish = 'Present';
			}
			else{
				$date_finish = strftime('%B %Y', strtotime($row['date_finish']));
			}
			
			// get the name of the agency stated
			$agency_id = $row['agency_id'];
			$sql_query = "SELECT * FROM  agency WHERE agency_id='$agency_id'";
			$agency_result = mysqli_query($dbc, $sql_query);
			
			while($row = mysqli_fetch_array($agency_result, MYSQLI_ASSOC)){
				if (mysqli_num_rows($agency_result) == 1) {
					
					$name = $row['name'];
					$city = $row['city'];
					$agency_name = $name . ' ' . $city;
				}
			}
			
			
			echo '<span style="text-transform:capitalize;"><a href="agency.php?agencyid=' . $agency_id . '">' . $agency_name . '</a></span>';
			echo "\n";
			
			// add edit button for user if its their page
			if (isset($access)) {
				echo '<span style="float:right; margin: 0 10px 0 0"><a href="editinternship.php?internid=';
				echo $intern_id;
				echo '">Edit</a></span>';
				echo "\n";
			}
			
			echo '<br />';
			echo "\n";
			echo $date_start . ' - ' . $date_finish ;
			echo "\n";
			echo '<br />' . $date;
			echo "\n";
			echo '<hr />';	
		}
	}
	
	echo '</div>';#agency_list

} else{ // if not user id is selected, show a list of users
	
	include('includes/header.php');
	echo '<h1>Hyper Island Interns</h1>';
	echo "\n";
	echo "<div id='user_list' class='flexcroll'>";
	echo "\n";
	
	// get the details of the user
	$sql_query = "SELECT * FROM users ORDER BY first_name ASC";
	$user_result = mysqli_query($dbc, $sql_query);
			
	// loop through the students locations and echo them out as javascript
	while($row = mysqli_fetch_array($user_result, MYSQLI_ASSOC)){
		$firstname = $row['first_name'];
		$lastname = $row['last_name'];
		$user_id = $row['user_id'];
		
		$sql_query = "SELECT * FROM  user_agency WHERE user_id='$user_id' ORDER BY date_start DESC LIMIT 1";
		$user_agency_result = mysqli_query($dbc, $sql_query);
		if (mysqli_num_rows($user_agency_result) == 0) {
			$agency_name = 'No internships added';
			$agency_id = '#';
		} else{		
			while($row = mysqli_fetch_array($user_agency_result, MYSQLI_ASSOC)){
				if (mysqli_num_rows($user_agency_result) == 1) {
					// get the name of the agency stated
					$agency_id = $row['agency_id'];
					$sql_query = "SELECT * FROM  agency WHERE agency_id='$agency_id'";
					$agency_result = mysqli_query($dbc, $sql_query);
					
					while($row = mysqli_fetch_array($agency_result, MYSQLI_ASSOC)){
						if (mysqli_num_rows($agency_result) == 1) {
							
							$name = $row['name'];
							$city = $row['city'];
							$agency_name = $name . ' ' . $city;
						}
					}
				}
			}
		}
		echo '<a href="user.php?userid=' . $user_id . '">' . $firstname . ' ' . $lastname . '</a>:<br /><a class="white" href="agency.php?agencyid=' . $agency_id . '">' . $agency_name . '</a><br /><hr>';
		echo "\n";
	}
	echo '</div>';
}
include('includes/footer.php');
?>
