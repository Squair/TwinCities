<?php
$city = $_GET['city'];

$query = "SELECT * FROM city WHERE name='$city'";
$result = $connection->query($query);
$row = $result->fetch(PDO::FETCH_ASSOC);

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
	
		var marker = new google.maps.Marker({
		position: uluru,
		map: map
		});
}

</script>