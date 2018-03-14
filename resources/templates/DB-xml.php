<?php
	require_once("db_connection.php"); 
	$xml = new DomDocument("1.0","UTF-8");
	

	//Database
	$container = $xml->createElement("Data");
	$container = $xml->appendChild($container);
	
	$sql = "SELECT * FROM city";
	$result = $connection->query($sql);
	
	//City table
	foreach ($result as $cityResult){
		$city = $xml->createElement("city");
		$city = $container->appendChild($city);

		$idCity = $xml->createElement("idCity", $cityResult['idCity']);
		$idCity = $city->appendChild($idCity);
		$name = $xml->createElement("name", $cityResult['name']);
		$name = $city->appendChild($name);
		$area = $xml->createElement("area", $cityResult['area']);
		$area = $city->appendChild($area);
		$latitude = $xml->createElement("latitude", $cityResult['latitude']);
		$latitude = $city->appendChild($latitude);
		$longitude = $xml->createElement("longitude", $cityResult['longitude']);
		$longitude = $city->appendChild($longitude);
	}

	$sql = "SELECT * FROM comments";
	$result = $connection->query($sql);
	
	//Comments table
	foreach ($result as $commentResult){
		$comment = $xml->createElement("comment");
		$comment = $container->appendChild($comment);

		$idComment = $xml->createElement("idComment", $commentResult['idComment']);
		$idComment = $comment->appendChild($idComment);
		$idCity = $xml->createElement("idCity", $commentResult['idCity']);
		$idCity = $comment->appendChild($idCity);
		$commentText = $xml->createElement("comment", $commentResult['comment']);
		$commentText = $comment->appendChild($commentText);
		$name = $xml->createElement("name", $commentResult['name']);
		$name = $comment->appendChild($name);
		$time = $xml->createElement("time", $commentResult['time']);
		$time = $comment->appendChild($time);
	}

	$sql = "SELECT * FROM flickr";
	$result = $connection->query($sql);

	foreach ($result as $flickrResult){
		$flickr = $xml->createElement("flickrPhoto");
		$flickr = $container->appendChild($flickr);
		
		$id = $xml->createElement("id", $flickrResult['ID']);
		$id = $flickr->appendChild($id);
		$IMAGE_URL = $xml->createElement("IMAGE_URL", $flickrResult['IMAGE_URL']);
		$IMAGE_URL = $flickr->appendChild($IMAGE_URL);
		$CAPTION = $xml->createElement("CAPTION", $flickrResult['CAPTION']);
		$CAPTION = $flickr->appendChild($CAPTION);
		$TIME_CACHED = $xml->createElement("TIME_CACHED", $flickrResult['TIME_CACHED']);
		$TIME_CHACHED = $flickr->appendChild($TIME_CACHED);
	}


	$sql = "SELECT * FROM places";
	$result = $connection->query($sql);
	
	//Places table
	foreach ($result as $placeResult){
		$place = $xml->createElement("place");
		$place = $container->appendChild($place);

		$idPlace = $xml->createElement("idPlace", $placeResult['idPlace']);
		$idPlace = $place->appendChild($idPlace);
		$idCity = $xml->createElement("idCity", $placeResult['idCity']);
		$idCity = $place->appendChild($idCity);
		$name = $xml->createElement("name", $placeResult['name']);
		$name = $place->appendChild($name);
		$url = $xml->createElement("url", $placeResult['url']);
		$url = $place->appendChild($url);
		$floor = $xml->createElement("floor", $placeResult['floor']);
		$floor = $place->appendChild($floor);
		$street_number = $xml->createElement("street_number", $placeResult['street_number']);
		$street_number = $place->appendChild($street_number);
		$route = $xml->createElement("route", $placeResult['route']);
		$route = $place->appendChild($route);
		$locality = $xml->createElement("locality", $placeResult['locality']);
		$locality = $place->appendChild($locality);
		$region = $xml->createElement("region", $placeResult['region']);
		$region = $place->appendChild($region);
		$post_code = $xml->createElement("post_code", $placeResult['post_code']);
		$post_code = $place->appendChild($post_code);
		$phone = $xml->createElement("phone", $placeResult['phone']);
		$phone = $place->appendChild($phone);
		$dateAdded = $xml->createElement("dateAdded", $placeResult['dateAdded']);
		$dateAdded = $place->appendChild($dateAdded);

	}

	$sql = "SELECT * FROM place_photos";
	$result = $connection->query($sql);
	
	//Place photo table
	foreach ($result as $photoResult){
		$photo = $xml->createElement("photo");
		$photo = $container->appendChild($photo);

		$idPhoto = $xml->createElement("idPhoto", $photoResult['idPhoto']);
		$idPhoto = $photo->appendChild($idPhoto);
		$idPlace = $xml->createElement("idPlace", $photoResult['idPlace']);
		$idPlace = $photo->appendChild($idPlace);
		$maxWidth = $xml->createElement("maxWidth", $photoResult['maxWidth']);
		$maxWidth = $photo->appendChild($maxWidth);

	}

	$sql = "SELECT * FROM place_reviews";
	$result = $connection->query($sql);
	
	//Place_reviews table
	foreach ($result as $reviewResult){
		$review = $xml->createElement("review");
		$review = $container->appendChild($review);

		$idReview = $xml->createElement("idReview", $reviewResult['idReview']);
		$idReview = $review->appendChild($idReview);
		$idPlace = $xml->createElement("idPlace", $reviewResult['idPlace']);
		$idPlace = $review->appendChild($idPlace);
		$author = $xml->createElement("authorh", $reviewResult['author']);
		$author = $review->appendChild($author);
		$rating = $xml->createElement("rating", $reviewResult['rating']);
		$rating = $review->appendChild($rating);
		$text = $xml->createElement("text", $reviewResult['text']);
		$text = $review->appendChild($text);
		$timeAgo = $xml->createElement("timeAgo", $reviewResult['timeAgo']);
		$timeAgo = $review->appendChild($timeAgo);

	}

	$sql = "SELECT * FROM place_type";
	$result = $connection->query($sql);
	
	//Place_type table
	foreach ($result as $placeTypeResult){
		$place_type = $xml->createElement("place_type");
		$place_type = $container->appendChild($place_type);

		$idType = $xml->createElement("idType", $placeTypeResult['idType']);
		$idType = $place_type->appendChild($idType);
		$idPlace = $xml->createElement("idPlace", $placeTypeResult['idPlace']);
		$idPlace = $place_type->appendChild($idPlace);

	}

	$sql = "SELECT * FROM type";
	$result = $connection->query($sql);
	
	//Place_type table
	foreach ($result as $typeResult){
		$type = $xml->createElement("type");
		$type = $container->appendChild($type);

		$idType = $xml->createElement("idType", $typeResult['idType']);
		$idType = $type->appendChild($idType);
		$name = $xml->createElement("name", $typeResult['name']);
		$name = $type->appendChild($name);

	}

	$xml->FormatOutput = true;
	$string_value = $xml->saveXML();

	$xml->save("../xml/Database.xml");
	header('Location: ../xml/Database.xml');
?>

