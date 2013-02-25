<?php 
session_start();
// Check to see if logged in
if (isset($_SESSION['user_id'])) {
	$loggedin = true;
	$session_id = $_SESSION['user_id'];
}
?>

<!DOCTYPE html>

<!--
***********************************************************************************

internmap.com || version 2.1 || 

***********************************************************************************

Developed by Alastair Wright for endless hours! www.alastairwright.com

Thanks for your interest in my source code, feel free to have a browse around....

But please don't steal :)

***********************************************************************************
--> 
  
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title><?php echo $page_title ;?></title>

<meta name="description" content="InternMap.com is a Hyper Island student produtcion. Have you ever wanted to get that inside contact, or the real story about an agency? Use this website to find the person to ask! This is a space where Hyper Island students can log the agencies they work in, and see what the world can offer in terms of media and digital agencies." />

<!-- keywords  -->
<meta name="keywords" content="alastair, wright, intern, map, intern map,  hyper island, hyper, island, digital, media, agency, agencies, advertising, adverts, web agencies, digital agencies, digital agency, students, interns" />
<meta http-equiv="author" content="http://www.internmap.com/" />

<link href="styles.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="images/icon.png"/>

<!-- script for the lovley custom scroll bar -->
<script type="text/javascript" src="js/flexcroll.js"></script>

<script type="text/javascript">
// Stop chrome's autocomplete from making your input fields that nasty yellow. Yuck.
if (navigator.userAgent.toLowerCase().indexOf("chrome") >= 0) {
	$(window).load(function(){
		$('input:-webkit-autofill').each(function(){
			var text = $(this).val();
			var name = $(this).attr('name');
			$(this).after(this.outerHTML).remove();
			$('input[name=' + name + ']').val(text);
		});
	});
}
</script>

<!-- Scripts to make the map work -->
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="js/infobox.js"></script>
<script type="text/javascript">
function initialize() {

	var latlng = new google.maps.LatLng(<?php if($zoom_lat){echo $zoom_lat . ',' . $zoom_lng;} else {echo '30, 10';} ?>);
 	var myOptions = {
    center: latlng,
    zoom: <?php if($zoom_lat){echo '13';}else {echo '3';} ?>,
    minZoom: 2,
	panControl: false,
	panControlOptions: {
	position: google.maps.ControlPosition.RIGHT_TOP 
	},
	zoomControl: true,
	zoomControlOptions: {
	position: google.maps.ControlPosition.RIGHT_TOP
	},
	
	scaleControl: false,
	mapTypeId: google.maps.MapTypeId.SATELLITE
  }
  var map = new google.maps.Map(document.getElementById("map_canvas"),
                                myOptions);

  setMarkers(map, locations);
  
  
}

/**
 * Data for the markers consisting of a name, a LatLng and a zIndex for
 * the order in which these markers should display on top of each
 * other.
*/

var locations = [

<?php
if($set_type){
	$show = $set_type;
} else{
	$show = 'agency';
} 

if ($show == 'agency') {
	include('includes/marker_agency.php');
} else{
	include('includes/marker_student.php');
}
?>

</head>

<!-- start the map with the body -->
<body onload="initialize()">
<div id="map_canvas"></div>

<!-- a hidden div to boost my own seo :) --> 
<h1 id="alastairwright"><a href="http://www.alastairwright.com">www.alastairwright.com</a></h1> 

<!-- start of the left control menu -->
<div id="left">

<?php  
// if logged in, show name and logout button, else show login button
//check to make sure the page needs a login button though
if (($page_title !== 'Intern Map')&&($page_title !== 'Log In')&&($page_title !== 'Register')&&($page_title !== 'Verify Email')&&($page_title !== 'Forgotten Password')&&($page_title !== 'Confirm Password')&&($page_title !== 'Logged Out')){
	if($loggedin){
		echo "<div id='toolbar'><a href='user.php?userid=" . $session_id . "'>{$_SESSION['first_name']}</a> | <a href='logout.php'>Logout</a></div>"; 
	} else{
		echo "<div id='toolbar'><a href='login.php'>Login</a></div>";
	}
} else{
	echo "<div id='toolbar'></div>";
}
echo "\n";

if($loggedin){
	echo '<div id="title"><a id="logotext" class="nohover" href="user.php?userid=' . $session_id .	'">INTERNMAP</a><a  class="nohover" href="user.php?userid=' . $session_id .	'"><img id="logo" src="images/logo.png" alt="intern map" /></a></div>';

} else{
	echo '<div id="title"><a id="logotext" class="nohover" href="/">INTERNMAP</a><a href="/"  class="nohover"><img id="logo" src="images/logo.png" alt="intern map" /></a></div>';
}
?>

<hr />
