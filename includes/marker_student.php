<?php
	// search for users present agencies
	$sql_query = "SELECT * FROM user_agency WHERE present = 'true' ORDER BY user_id DESC";
	$user_present_result = mysqli_query($dbc, $sql_query);
	
	// the zindex of each marker
	$marker_zindex = 1;
	
	while($row = mysqli_fetch_array($user_present_result, MYSQLI_ASSOC)){
	
		$marker_agency_id = $row['agency_id'];
		$marker_user_id = $row['user_id'];
				
		// get the location of their current internship
		$sql_query = "SELECT * FROM agency WHERE agency_id='$marker_agency_id'";
		$present_location_result = mysqli_query($dbc, $sql_query);
		
		while($row = mysqli_fetch_array($present_location_result, MYSQLI_ASSOC)){
			$marker_companyname = $row['name'];
			$marker_city = $row['city'];
			$marker_lat = $row['lat'];
			$marker_lng = $row['lng'];
		}
		
		// get the details of the user
		$sql_query = "SELECT * FROM users WHERE user_id='$marker_user_id'";
		$user_details_result = mysqli_query($dbc, $sql_query);
				
		// loop through the students locations and echo them out as javascript
		while($row = mysqli_fetch_array($user_details_result, MYSQLI_ASSOC)){
			$marker_firstname = $row['first_name'];
			$marker_lastname = $row['last_name'];
			$marker_hyperemail = $row['hyper_email'];
			$marker_website = $row['website'];
		}
		
		// concatenate the first and last names into one
		$marker_fullname = $marker_firstname . ' ' . $marker_lastname;
		
		echo "['" . $marker_fullname . "', ";
		echo $marker_lat . ", ";
		echo $marker_lng . ", ";
		echo $marker_zindex . ", ";
		echo $marker_user_id . ", ";
		echo "'";
		
		// get the users internship dates
		$sql_query = "SELECT * FROM  user_agency WHERE user_id='$marker_user_id' ORDER BY date_start DESC";
		$marker_user_agency_result = mysqli_query($dbc, $sql_query);
		while($row = mysqli_fetch_array($marker_user_agency_result, MYSQLI_ASSOC)){
			
			$new_marker_agency_id = $row['agency_id'];
			$marker_date_start = strftime('%B %Y', strtotime($row['date_start']));
			
			$marker_present = $row['present'];
			//if intern is set to present, don't show the finish date
			if($marker_present == 'true'){
				$marker_date_finish = 'Present';
			}
			else{
				$marker_date_finish = strftime('%B %Y', strtotime($row['date_finish']));
			}
			
			// get the details of all their internships internship
			$sql_query = "SELECT * FROM agency WHERE agency_id='$new_marker_agency_id'";
			$marker_present_location_result = mysqli_query($dbc, $sql_query);
			
			while($row = mysqli_fetch_array($marker_present_location_result, MYSQLI_ASSOC)){
				$new_marker_companyname = $row['name'];
				$new_marker_city = $row['city'];
				$new_marker_agency = $new_marker_companyname . ', ' . $new_marker_city;
			}
			
			echo "<a class=";
			echo '"';
			echo "bubble";
			echo '" ';
			echo "href=";
			echo '"';
			echo "agency.php?agencyid=";
			echo $new_marker_agency_id;
			echo '"';
			echo ">" . $new_marker_agency . "</a>:<br />";
			echo " " . $marker_date_start . ' - ' . $marker_date_finish ;
			echo "<br /><hr class=";
			echo '"';
			echo "bubble";
			echo '"';
			echo "/>";
			
			/*echo '<span style="text-transform:capitalize;"><a href="agency.php?agencyid=' . $agency_id . '">' . $marker_agency . '</a></span>';
			echo "\n";
			
			echo '<br />';
			echo "\n";
			echo $date_start . ' - ' . $date_finish ;
			echo "\n";
			echo '<br />' . $date;
			echo "\n";
			echo '<hr />';	*/
		}		
		
		echo "'";
		echo "],";
		echo "\n";
		$marker_zindex++;
	}
	
/*var locations = [
['Bondi Beach', -33.890542, 151.274856, 4],
['Coogee Beach', -33.923036, 151.259052, 5],
['Cronulla Beach', -34.028249, 151.157507, 3],
['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
['Maroubra Beach', -33.950198, 151.259302, 1]
];*/
?>

];

function setMarkers(map, locations) {
  // Add markers to the map

  // Marker sizes are expressed as a Size of X,Y
  // where the origin of the image (0,0) is located
  // in the top left of the image.

  // Origins, anchor positions and coordinates of the marker
  // increase in the X direction to the right and in
  // the Y direction down.
  var image = new google.maps.MarkerImage('images/man.png',
      // This marker is 20 pixels wide by 32 pixels tall.
      new google.maps.Size(40, 50),
      // The origin for this image is 0,0.
      new google.maps.Point(0,0),
      // The anchor for this image is the base of the flagpole at 0,32.
      new google.maps.Point(20, 45));
  var shadow = new google.maps.MarkerImage('images/man_shadow.png',
      // The shadow image is larger in the horizontal dimension
      // while the position and offset are the same as for the main image.
      new google.maps.Size(60, 50),
      new google.maps.Point(0,0),
      new google.maps.Point(25, 50));
      // Shapes define the clickable region of the icon.
      // The type defines an HTML &lt;area&gt; element 'poly' which
      // traces out a polygon as a series of X,Y points. The final
      // coordinate closes the poly by connecting to the first
      // coordinate.
      		
	var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        shadow: shadow,
        icon: image,
        title: location[0],
        zIndex: location[3]
      });
      
     
      	var boxText = document.createElement("div");
      	 google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
		boxText.style.cssText = "border: none; margin-top: 8px; background: black; color:white; padding: 15px;";
		boxText.innerHTML = '<span style="color:#fff; text-transform:capitalize;"><h4><a class="bubble_header" href="user.php?userid=' + locations[i][4] + '">' + locations[i][0] + '</a></h4><hr class="bubble" />' +  locations[i][5] + '</span>';
		
		
		
      }
      })(marker, i));
        	
      	var myOptions = {
			 content: boxText
			,alignBottom: false
			,disableAutoPan: true
			,maxWidth: 0
			,pixelOffset: new google.maps.Size(-143, 0)
			,zIndex: null
			,boxStyle: { 
			  background: "url('images/tipbox.gif') no-repeat"
			  ,opacity: 0.8
			  ,width: "300px"
			 }
			,closeBoxMargin: "12px 5px 5px 5px"
			,closeBoxURL: "images/close.gif"
			,infoBoxClearance: new google.maps.Size(1, 1)
			,isHidden: false
			,pane: "floatPane"
			,enableEventPropagation: false
		};
      
      	google.maps.event.addListener(marker, "click", function (e) {
			infowindow.open(map, this);
		});

		var infowindow = new InfoBox(myOptions);
    }
}
</script>