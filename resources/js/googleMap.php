<?php

if (isset($connection)){
$city = $_GET['city'];
$query = "SELECT * FROM city WHERE name='$city'";
$result = $connection->query($query);
$row = $result->fetch(PDO::FETCH_ASSOC);

} else {
	die("Could not connect to db.");
}

?>
<script>
function initMap() {
	var uluru = {lat: <?php echo $row['longitude']; ?>, lng: <?php echo $row['latitude']; ?>};
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 8,
	    center: uluru
	});
	// Add the circle for this city to the map.
    var cityCircle = new google.maps.Circle({
    	strokeColor: '#000000',
    	strokeOpacity: 0.8,
    	strokeWeight: 1,
    	fillColor: '#73956F',
    	fillOpacity: 0.35,
		map: map,
    	center: {lat: <?php echo $row['longitude']; ?>, lng: <?php echo $row['latitude']; ?>},
    	radius: <?php echo $row['area'] * 100; ?>
    });
	

}
	

var map;
var infowindow;

function initPlacesMap(placeType) {
var city = {lat: <?php echo $row['longitude']; ?>, lng: <?php echo $row['latitude']; ?>};

map = new google.maps.Map(document.getElementById('map'), {
  center: city,
  zoom: 9
});

var cityCircle = new google.maps.Circle({
	strokeColor: '#000000',
	strokeOpacity: 0.8,
	strokeWeight: 1,
	fillColor: '#73956F',
	fillOpacity: 0.35,
	map: map,
	center: {lat: <?php echo $row['longitude']; ?>, lng: <?php echo $row['latitude']; ?>},
	radius: <?php echo $row['area'] * 100; ?>
});

infowindow = new google.maps.InfoWindow();
var service = new google.maps.places.PlacesService(map);
service.nearbySearch({
  location: city,
  radius: <?php echo $row['area'] * 100; ?>,
  type: [placeType]
}, callback);
}


function callback(results, status) {
	if (status === google.maps.places.PlacesServiceStatus.OK) {
	  for (var i = 0; i < results.length; i++) {
		createMarker(results[i]);
	  }
	}
}

function createMarker(place) {
	var placeLoc = place.geometry.location;
	var marker = new google.maps.Marker({
	  map: map,
	  position: place.geometry.location
});

var contentString = '<div id="content">'+
	'<div id="siteNotice">'+
	'</div>'+
	'<p id="firstHeading" class="firstHeading">' + place.name + '</p>'+
	'<div id="bodyContent">'+
	'<img style="width: 50px; height: 50px;" src="' + place.icon + '">' +
	'<p>' + place.vicinity + '</p>' +
	'</div>'+
	'</div>';
	
var http = createRequestObject();

function makeGetRequestObject(){
	http.open('get', '../resources/js/placeDetails.php?placeId=' + place.place_id + '&cityId=<?php echo $row['idCity'];?>');
	
	http.onreadystatechange = processResponse;
	
	http.send(null);
}

function processResponse(){
	if (http){
		http.onreadystatechange = function(){
			if (http.readyState = 4){
				document.getElementById("loading").style.display = 'none';
				var response = http.responseText;
				document.getElementById("markerInfo").innerHTML = response;

			}
		}
	}
}
	
google.maps.event.addListener(marker, 'mouseover', function() {
  infowindow.setContent(contentString);
  infowindow.open(map, this);
});

google.maps.event.addListener(marker, 'mouseout', function() {
  infowindow.close();
});
	
google.maps.event.addListener(marker, 'click', function() {
  document.getElementById("loading").style.display = 'block';
  makeGetRequestObject();
  processResponse();
});
}
</script>