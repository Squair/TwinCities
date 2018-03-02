<?php
require_once("../templates/db_connection.php"); 
$placeId = $_GET['placeId'];
$cityId = $_GET['cityId'];
//This array prevents open ssl error from occuring.
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
); 

//Check database for place ID.
if (isset($connection)){
	$sql = "SELECT * FROM places WHERE idPlace='$placeId'";
	$result = $connection->query($sql);
	$row = $result->fetch(PDO::FETCH_ASSOC);
	if (!$row){
		
		//Proccess new place into database.
		
		//Use this for UWE servers with ssl.
		//$json = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $placeId . "&key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc");

		//Use this for local development (XAMPP freaks out over ssl).
		$json = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $placeId . "&key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc", false, stream_context_create($arrContextOptions));
		
		$phpData = json_decode($json, true);

		if (isset($phpData['result'])){
			$name = $phpData['result']['name'];
			$address = $phpData['result']['formatted_address'];

			if (isset($phpData['result']['website'])){
				$url = $phpData['result']['website'];
			} else {
				$url = NULL;
			}

			if (isset($phpData['result']['formatted_phone_number'])){
				$phone = $phpData['result']['formatted_phone_number'];
			} else {
				$phone = NULL;
			}


			//Check place types in database.
			foreach ($phpData['result']['types'] as $type){
				$sql = "SELECT name FROM type WHERE name='$type'";
				$result = $connection->query($sql);
				$row = $result->fetch(PDO::FETCH_ASSOC);
				//If it doesnt exist, add it.
				if (!$row){
					$sql = "INSERT INTO type (name) VALUES ('$type')";
					$connection->query($sql);
				}
			}

			//If place_type doesn't exist for the type of the place in database, add it.
			foreach ($phpData['result']['types'] as $type){
				$sql = "SELECT idPlace FROM place_type WHERE idPlace='$placeId' AND idType=(SELECT idType FROM type WHERE name='$type')";
				$result = $connection->query($sql);
				$row = $result->fetch(PDO::FETCH_ASSOC);
				//Add all types refering to each place.
				if (!$row){
					$sql = "INSERT INTO place_type (idType, idPlace) VALUES ((SELECT idType FROM type WHERE name='$type'), '$placeId')";
					$connection->query($sql);	
				}
			}

			//Add into city_places
			$sql = "SELECT idPlace FROM city_places WHERE idPlace='$placeId'";
			$result = $connection->query($sql);
			$row = $result->fetch(PDO::FETCH_ASSOC);
			if (!$row){
				$sql = "INSERT INTO city_places (idCity, idPlace) VALUES ('$cityId', '$placeId')";
				$connection->query($sql);
			}

			//Add place into place table		
			$sth = $connection->prepare("INSERT INTO places (idPlace, name, url, phone, address) VALUES (?,?,?,?,?)");
			$sth->execute(array($placeId, $name, $url, $phone, $address));
		}
		//Add photo reference for each place
		if (isset($phpData['result']['photos'])){
			foreach($phpData['result']['photos'] as $photo){
				$photoRef = $photo['photo_reference'];
				$maxWidth = $photo['width'];
				$sql = "SELECT idPhoto FROM place_photos WHERE idPhoto='$photoRef' AND idPlace='$placeId'";
				$result = $connection->query($sql);
				$row = $result->fetch(PDO::FETCH_ASSOC);
				//If photo reference doesnt exist for that place
				if (!$row){
					$sql = "INSERT INTO place_photos (idPhoto, idPlace, maxWidth) VALUES ('$photoRef','$placeId', '$maxWidth')";
					$connection->query($sql);
					 
					file_put_contents("../place_photos/" . $photo['photo_reference'] . ".jpg", file_get_contents("https://maps.googleapis.com/maps/api/place/photo?maxwidth=" . $photo['width'] . "&photoreference=" . $photo['photo_reference'] . "&key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc", false, stream_context_create($arrContextOptions)));
				}
			}
		}
		//Add place reviews for place
		if (isset($phpData['result']['reviews'])){
			foreach($phpData['result']['reviews'] as $review){
				$author = $review['author_name'];
				$rating = $review['rating'];
				$text = $review['text'];
				$timeAgo = $review['relative_time_description'];
				$sth = $connection->prepare("INSERT INTO place_reviews (idPlace, author, rating, text, timeAgo) VALUES (?,?,?,?,?)");
				$sth->execute(array($placeId,$author,$rating,$text,$timeAgo));
				
			}
		}
	} else if ($row){
		echo "<h4>" . $row['name'] . "</h4>";
		echo "<p>" . $row['address'] . "</p>";
		echo "<p> Phone - " . $row['phone'] . "</p>";
		echo "<p><a href='" . $row['url'] . "'>" . $row['url'] . "</a></p>";
		
		$sql = "SELECT idPhoto, maxWidth FROM place_photos WHERE idPlace='$placeId'";
		$result = $connection->query($sql);
		
		foreach ($result as $row){
			echo "<img src=../resources/place_photos/". $row['idPhoto'] . ".jpg style='height: 175px; width: 175px;'/>";
			//echo "<img src=https://maps.googleapis.com/maps/api/place/photo?maxwidth=" . $row['maxWidth'] . "&photoreference=" . $row['idPhoto'] . "&key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc style='height: 175px; width: 175px;'/>";
		}
		
		$sql = "SELECT * FROM place_reviews WHERE idPlace='$placeId'";
		$result = $connection->query($sql);
		foreach ($result as $review){
			echo "<hr>";
			echo "<h2>" . $review['rating'] . " - " . $review['author'] . "</h2>";
			echo "<p>" . $review['text'] . "</p>";
			echo "<p style='font-weight:bold;'>" . $review['timeAgo'] . "</p>";
		}
	}
} else {
	echo "Couldn't connect to database.";
}
//If call had to be made to find place, print out data from call to save querying the database.
if (isset($phpData['result']['name'])){
	echo "<h4>" . $phpData['result']['name'] . "</h4>";

}
if (isset($phpData['result']['formatted_address'])){
	echo "<p>" . $phpData['result']['formatted_address'] . "</p>";

}

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
		echo "<hr>";
		echo "<h2>" . $review['rating'] . " - " . $review['author_name'] . "</h2>";
		echo "<p>" . $review['text'] . "</p>";
		echo "<p style='font-weight:bold;'>" . $review['relative_time_description'] . "</p>";

	}
}

?>