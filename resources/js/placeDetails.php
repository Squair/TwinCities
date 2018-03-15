<?php
require("../templates/db_connection.php"); 
$placeId = $_GET['placeId'];
$cityId = $_GET['cityId'];

$arrContextOptions=array( //This array when passed to file_get_contents prevents open ssl errors from occuring.
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
); 


if (isset($connection)){ //Check database for existing place ID that has been added within 30 days.
	$sql = "SELECT * FROM places WHERE idPlace='$placeId' AND dateAdded > NOW() - INTERVAL 30 DAY"; 
	$result = $connection->query($sql);
	$row = $result->fetch(PDO::FETCH_ASSOC);
	

	if ($row){ //If place ID was found in database, print it out.
		
		echo "<h4>" . $row['name'] . "</h4>";
		echo "<p>" . $row['floor'] . ", " . $row['street_number'] . ", " . $row['route'] . ", " . $row['locality'] . ", " . $row['region'] . ", " . $row['post_code'] . "</p>";
		echo "<p> Phone - " . $row['phone'] . "</p>";
		echo "<p><a href='" . $row['url'] . "'>" . $row['url'] . "</a></p>";
		
		//Get all place_types relating to the place and list them.
		$sql = "SELECT * FROM place_type INNER JOIN type ON place_type.idType=type.idType WHERE place_type.idPlace='$placeId'";
		$result = $connection->query($sql);
		
		$placeTypes = "<p>";
		foreach ($result as $row){
			$placeTypes .= $row['name'] . ", ";
		}
		$placeTypes .= "</p>";
		echo $placeTypes;
		
		//Pull photos relating to place ID.
		$sql = "SELECT idPhoto, maxWidth FROM place_photos WHERE idPlace='$placeId'";
		$result = $connection->query($sql);
		
		foreach ($result as $row){
			//echo "<img src=../place_photos/". $row['idPhoto'] . ".jpg style='height: 175px; width: 175px;'/>";
			echo "<img src=https://maps.googleapis.com/maps/api/place/photo?maxwidth=" . $row['maxWidth'] . "&photoreference=" . $row['idPhoto'] . "&key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc style='height: 175px; width: 175px;'/>";
		}
		//Pull reviews relating to place ID.
		$sql = "SELECT * FROM place_reviews WHERE idPlace='$placeId' ORDER BY timeAgo DESC";
		$result = $connection->query($sql);
		foreach ($result as $review){
			echo "<hr>";
			echo "<h2>" . $review['rating'] . " - " . $review['author'] . "</h2>";
			echo "<p>" . $review['text'] . "</p>";
			echo "<p style='font-weight:bold;'>" . $review['timeAgo'] . "</p>";
		}
	} else if (!$row){ //If place ID doesnt exist in database.
		
		//Proccess new place into database.
		
		//Use this for UWE servers with ssl.
		//$json = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $placeId . "&key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc");

		//Use this for local development (XAMPP freaks out over ssl).
		//Get details about place with place ID.
		$json = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $placeId . "&key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc", false, stream_context_create($arrContextOptions));
		
		$phpData = json_decode($json, true);

		if (isset($phpData['result'])){ //Check request returned something.
			$name = $phpData['result']['name'];
			$address = $phpData['result']['formatted_address'];

			if (isset($phpData['result']['website'])){
				$url = $phpData['result']['website'];
			} else {
				$url = NULL; //Some places dont have urls.
			}

			if (isset($phpData['result']['formatted_phone_number'])){
				$phone = $phpData['result']['formatted_phone_number'];
			} else {
				$phone = NULL; //Some places dont have phone numbers.
			}


			//Check place_types in database, if place type doesnt exist, add it.
			foreach ($phpData['result']['types'] as $type){
				$sql = "SELECT name FROM type WHERE name='$type'";
				$result = $connection->query($sql);
				$row = $result->fetch(PDO::FETCH_ASSOC);
				
				if (!$row){
					$sth = $connection->prepare("INSERT INTO type (name) VALUES (?)");
					$sth->execute(array($type));
				}
			}

			//If place_type doesn't exist for the type of the place in database, add it.
			foreach ($phpData['result']['types'] as $type){
				$sql = "SELECT idPlace FROM place_type WHERE idPlace='$placeId' AND idType=(SELECT idType FROM type WHERE name='$type')";
				$result = $connection->query($sql);
				$row = $result->fetch(PDO::FETCH_ASSOC);
				//Add all types refering to each place.
				if (!$row){
					$sth = $connection->prepare("INSERT INTO place_type (idType, idPlace) VALUES ((SELECT idType FROM type WHERE name=?), ?)");
					$sth->execute(array($type, $placeId));	
				}
			}

			//Seperates all address data into assoc, then to be inserted into db.
			$address = array("floor"=>NULL, "street_number"=>NULL, "route"=>NULL, "locality"=>NULL, "region"=>NULL, "post_code"=>NULL);
			foreach ($phpData['result']['address_components'] as $component){
				foreach ($component['types'] as $type){
					switch ($type){
						case "floor":
							$address['floor'] = $component['short_name'];
							break;
						case "street_number":
							$address['street_number'] = $component['short_name'];
							break;
						case "route":
							$address['route'] = $component['short_name'];
							break;
						case "locality":
							$address['locality'] = $component['short_name'];
							break;
						case "administrative_area_level_1":
							$address['region'] = $component['short_name'];
							break;
						case "postal_code":
							$address['post_code'] = $component['short_name'];	
							break;
					}
				}
			}
			//Add new place into the place table
			//Replace will update places that need to be cached.
			$sth = $connection->prepare("REPLACE INTO places (idPlace, idCity, name, url, floor, street_number, route, locality, region, post_code, phone, dateAdded) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
			$sth->execute(array($placeId, $cityId, $name, $url, $address['floor'], $address['street_number'], $address['route'], $address['locality'], $address['region'], $address['post_code'], $phone, date("Y-m-d H:i:s")));
		}
		//Add all photo reference's for each place
		if (isset($phpData['result']['photos'])){
				//Remove any previous entrys from db.
				$sql = "DELETE FROM place_photos WHERE idPlace='$placeId'";
				$connection->query($sql);
			foreach($phpData['result']['photos'] as $photo){
				$photoRef = $photo['photo_reference'];
				$maxWidth = $photo['width'];
				//Check if photo reference already exists.
				$sql = "SELECT idPhoto FROM place_photos WHERE idPlace='$placeId'";
				$result = $connection->query($sql);
				$row = $result->fetch(PDO::FETCH_ASSOC);
				if ($row){
					//Remove any photos previously save for that place.
					$path = "../place_photos/" . $row['idPhoto'] . ".jpg";
					//unlink($path);
				}

				//Add new photos.
					$sth = $connection->prepare("INSERT INTO place_photos (idPhoto, idPlace, maxWidth) VALUES (?, ?, ?)");
					$sth->execute(array($photoRef, $placeId, $maxWidth));
					
					$key = "AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc";
					//Save photo with photo reference as its name to server.
					file_put_contents("../place_photos/" . $photo['photo_reference'] . ".jpg", file_get_contents("https://maps.googleapis.com/maps/api/place/photo?maxwidth=" . $photo['width'] . "&photoreference=" . $photo['photo_reference'] . "&key=" . $key, false, stream_context_create($arrContextOptions)));
					//file_put_contents("../place_photos/" . $photo['photo_reference'] . ".jpg", file_get_contents("https://maps.googleapis.com/maps/api/place/photo?maxwidth=" . $photo['width'] . "&photoreference=" . $photo['photo_reference'] . "&key=" . $key));
					//file_put_contents("../place_photos/test.txt");
			}
		}
		//Add place reviews for place
		if (isset($phpData['result']['reviews'])){
			//Remove any older reviews to avoid duplication.
			$sql = "DELETE FROM place_reviews WHERE idPlace='$placeId'";
			$connection->query($sql);
			foreach($phpData['result']['reviews'] as $review){
				$author = $review['author_name'];
				$rating = $review['rating'];
				$text = $review['text'];
				$timeAgo = date("Y-m-d H:i:s", $review['time']);
				
				$sth = $connection->prepare("INSERT INTO place_reviews (idPlace, author, rating, text, timeAgo) VALUES (?,?,?,?,?)");
				$sth->execute(array($placeId,$author,$rating,$text,$timeAgo));
				
			}
		}
	}
} else {
	echo "Couldn't connect to database.";
}
//If call had to be made to find place, print out data from call to save querying the database.

if (isset($phpData['result']['name'])){ //Check place had name associated.
	echo "<h4>" . $phpData['result']['name'] . "</h4>";

}

$address = "<p>";
foreach ($phpData['result']['address_components'] as $component){
	$address .= $component['short_name'] . ", ";
}
$address .= "</p>";
echo $address;

if (isset($phpData['result']['formatted_phone_number'])){ //Check place had phone number associated.
	echo "<p> Phone - " . $phpData['result']['formatted_phone_number'] . "</p>";

}

if (isset($phpData['result']['website'])){ //Check place had url associated.
	echo "<p><a href='" . $phpData['result']['website'] . "'>" . $phpData['result']['website'] . "</a></p>";
} 

if (isset($phpData['result']['types'])){
	$placeTypes = "<p>";
	foreach($phpData['result']['types'] as $type){
		$placeTypes .= $type . ", ";
	}
	$placeTypes .= "</p>";
	echo $placeTypes;
}
			
if (isset($phpData['result']['photos'])){ //Check place had photos associated.
	foreach($phpData['result']['photos'] as $photo){
		//Commented just to save API calls for the moment.
		//echo "<img src=https://maps.googleapis.com/maps/api/place/photo?maxwidth=" . $photo['width'] . "&photoreference=" . $photo['photo_reference'] . "&key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc style='height: 175px; width: 175px;'/>";
	}
}

if (isset($phpData['result']['reviews'])){ //Check place had reviews associated.
	foreach($phpData['result']['reviews'] as $review){
		echo "<hr>";
		echo "<h2>" . $review['rating'] . " - " . $review['author_name'] . "</h2>";
		echo "<p>" . $review['text'] . "</p>";
		echo "<p style='font-weight:bold;'>" . date("Y-m-d H:i:s", $review['time']) . "</p>";

	}
}

?>