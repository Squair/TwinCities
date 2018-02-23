<?php
$placeId = $_GET['placeId'];
$json = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $placeId . "&key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc");
$phpData = json_decode($json, true);



echo "<h4>" . $phpData['result']['name'] . "</h4>";
echo "<p>" . $phpData['result']['formatted_address'] . "</p>";

if (isset($phpData['result']['formatted_phone_number'])){
	echo "<p> Phone - " . $phpData['result']['formatted_phone_number'] . "</p>";

}

if (isset($phpData['result']['website'])){
	echo "<p><a href='" . $phpData['result']['website'] . "'>" . $phpData['result']['website'] . "</a></p>";
} 

			
if (isset($phpData['result']['photos'])){
	foreach($phpData['result']['photos'] as $photo){
		echo "<img src=https://maps.googleapis.com/maps/api/place/photo?maxwidth=" . $photo['width'] . "&photoreference=" . $photo['photo_reference'] . "&key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc style='height: 175px; width: 175px;'/>";
	}
}

if (isset($phpData['result']['reviews'])){
foreach($phpData['result']['reviews'] as $review){
	echo "<br>";
	echo "<h2>" . $review['rating'] . " - " . $review['author_name'] . "</h2>";
	echo "<p>" . $review['text'] . "</p>";
	
}
}

?>