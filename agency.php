<?php
require_once ('../../mysqli_connect.php');

// show agecny details if a agencies profile is selected
if($_GET['agencyid']){

	$agency_id = $_GET['agencyid'];
	
	$sql_query = "SELECT * FROM agency WHERE agency_id='$agency_id'";
	$result = mysqli_query($dbc, $sql_query);
	
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		if (mysqli_num_rows($result) == 1) {
			$name = $row['name'];
			$city = $row['city'];
			$agency_name = $name . ' ' . $city;
			$website = $row['website'];
			$zoom_lat = $row['lat'];
			$zoom_lng = $row['lng'];
		}
	}
	
	$page_title = $agency_name;
	
	include('includes/header.php');
	
	
	echo '<h1>' . $agency_name . '</h1>';
	echo "\n";
	echo '<a href="http://' . $website . '" target="_blank">' . $website . '</a><br /><br />';
	echo "\n";
	echo 'Student Interns:<br />';
	
	echo "\n";
	echo '<div id="agency_list">';
	echo '<hr>';	
	
	// for some reason you need to state this again because it forgets from the one at the top of this script???
	$agency_id = $_GET['agencyid'];
	
	// get the users internship dates
	$sql_query = "SELECT * FROM  user_agency WHERE agency_id='$agency_id' ORDER BY date_start DESC";
	$user_agency_result = mysqli_query($dbc, $sql_query);
	
	while($row = mysqli_fetch_array($user_agency_result, MYSQLI_ASSOC)){
		$user_id = $row['user_id'];
		$date_start = strftime('%B %Y', strtotime($row['date_start']));
		
		$present = $row['present'];
		if($present == 'true'){
			$date_finish = 'Present';
		}
		else{
			$date_finish = strftime('%B %Y', strtotime($row['date_finish']));
		}
		
		
		// get the name of the student stated
		$sql_query = "SELECT * FROM  users WHERE user_id='$user_id'";
		$user_result = mysqli_query($dbc, $sql_query);
		
		while($row = mysqli_fetch_array($user_result, MYSQLI_ASSOC)){
				
				$firstname = $row['first_name'];
				$lastname = $row['last_name'];
				$fullname = $firstname . ' ' . $lastname;
		}
		
		
		echo '<span style="text-transform:capitalize;"><a href="user.php?userid=' . $user_id . '">' . $fullname . '</a></span><br />';
		echo "\n";
		echo $date_start . ' - ' . $date_finish ;	
		echo "\n";
		echo '<br />';
		echo '<hr />';	
	}	
	echo '</div>';#agency_list
	
} else{ // if no agency id is selected, show a list of agencies
	include('includes/header.php');
	
	if($_GET['sort']){
		$sort = $_GET['sort'];
	} else{
		$sort = 'name';
	}
	
	if($sort == 'name'){
		$sort = 'name, city';
		$align = 'left';
	} else {
		$align = 'right';
		$sort = 'city, name';
	}

	echo "<div style='text-align:" . $align . "; margin-right:10px; padding-right:10px;'>";
	echo "\n";
	echo '<h1>Agencies</h1>';
	echo "\n";
	echo 'Sort by: <a href="?sort=name">Agency</a> | <a href="?sort=city">City</a><br /><br />';
	echo "\n";
	echo '</div>';
	echo "\n";
	echo "<div id='agency_list' class='flexcroll' style='text-align:" . $align . "; margin-right:10px; padding-right:10px;'>";
	echo "\n";
	
	// get the details of the user
	$sql_query = "SELECT * FROM agency ORDER BY $sort ASC";
	$user_result = mysqli_query($dbc, $sql_query);
			
	// loop through the students locations and echo them out as javascript
	while($row = mysqli_fetch_array($user_result, MYSQLI_ASSOC)){
		$name = $row['name'];
		$city = $row['city'];
		$agency_name = $name . ', ' . $city;
		$agecny_id = $row['agency_id'];
		
		echo '<a class="white" href="agency.php?agencyid=' . $agecny_id . '">' . $agency_name . '</a>';
		echo "\n";
		echo '<hr class="agency" /><br />';
		echo "\n";
	}
	echo '</div>';

}
include('includes/footer.php');
?>
