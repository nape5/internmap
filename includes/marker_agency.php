<?php
$sql_query = "SELECT * FROM agency";
$agency_location_result = mysqli_query($dbc, $sql_query);

// the zindex of each marker
$marker_zindex = 1;

// loop through the agencies and echo them out as javascript
while($row = mysqli_fetch_array($agency_location_result, MYSQLI_ASSOC)){
	$marker_agency_id = $row['agency_id'];
	$marker_name = $row['name'];
	$marker_city = $row['city'];
	$marker_lat = $row['lat'];
	$marker_lng = $row['lng'];
	
	echo "['" . $marker_name . "', ";
	echo $marker_lat . ", ";
	echo $marker_lng . ", ";
	echo $marker_zindex . ", ";
	echo "'" . $marker_city . "', ";
	echo $marker_agency_id . ", ";
	echo "'";	
	
	// get the users internship dates
	$sql_query = "SELECT * FROM  user_agency WHERE agency_id='$marker_agency_id' ORDER BY date_start DESC";
	$marker_user_agency_result = mysqli_query($dbc, $sql_query);
	$marker_num_rows = mysqli_num_rows($marker_user_agency_result);
	
	while($row = mysqli_fetch_array($marker_user_agency_result, MYSQLI_ASSOC)){
		$marker_user_id = $row['user_id'];
		$marker_date_start = strftime('%B %Y', strtotime($row['date_start']));
		
		$marker_present = $row['present'];
		if($marker_present == 'true'){
			$marker_date_finish = 'Present';
		}
		else{
			$marker_date_finish = strftime('%B %Y', strtotime($row['date_finish']));
		}
		
		
		// get the name of the student stated
		$sql_query = "SELECT * FROM  users WHERE user_id='$marker_user_id'";
		$marker_user_result = mysqli_query($dbc, $sql_query);
		
		while($row = mysqli_fetch_array($marker_user_result, MYSQLI_ASSOC)){
				
				$marker_firstname = $row['first_name'];
				$marker_lastname = $row['last_name'];
				$marker_fullname = $marker_firstname . ' ' . $marker_lastname;
		}
		
		echo "<a class=";
		echo '"';
		echo "bubble";
		echo '" ';
		echo "href=";
		echo '"';
		echo "user.php?userid=";
		echo $marker_user_id;
		echo '"';
		echo ">" . $marker_fullname . "</a>:";
		echo " " . $marker_date_start . ' - ' . $marker_date_finish ;
		echo "<br /><hr class=";
		echo '"';
		echo "bubble";
		echo '"';
		echo "/>";
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
  var image = new google.maps.MarkerImage('../../images/pin.png',
      // This marker is x pixels wide by x pixels tall.
      new google.maps.Size(40, 50),
      // The origin for this image is 0,0.
      new google.maps.Point(0,0),
      new google.maps.Point(20, 45));
  var shadow = new google.maps.MarkerImage('../../images/pin_shadow.png',
      // The shadow image is larger in the horizontal dimension
      // while the position and offset are the same as for the main image.
      new google.maps.Size(60, 50),
      new google.maps.Point(0,0),
      new google.maps.Point(20, 45));
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
		boxText.innerHTML = '<span style="color:#fff; text-transform: capitalize;"><h4><a class="bubble_header" href="agency.php?agencyid=' + locations[i][5] + '">' + locations[i][0] + ',&nbsp;' + ' ' + locations[i][4] + '</h4><hr class="bubble" />' + locations[i][6] +'</a></span>';
      }
      })(marker, i));
        	
      	var myOptions = {
			 content: boxText
			,alignBottom: false
			,disableAutoPan: true
			,maxWidth: 0
			,pixelOffset: new google.maps.Size(-138, 0)
			,zIndex: null
			,boxStyle: { 
			  background: "url('images/tipbox.gif') no-repeat"
			  ,opacity: 0.8
			  ,width: "380px"
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