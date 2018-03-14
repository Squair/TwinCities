<?php
	$xml = new DomDocument("1.0","UTF-8");
	

	//Api Keys
	$container = $xml->createElement("APIkeys");
	$container = $xml->appendChild($container);

	//Google
	$google = $xml->createElement("google");
	$google = $container->appendChild($google);
	$key = $xml->createElement("key", "AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc");
	$key = $google->appendChild($key);

	$pUrl = "https://maps.googleapis.com/maps/api/place/details/json?placeid=[placeid]&#038key=[API_KEY]";
	$path = $xml->createElement("path", $pUrl);
	$path = $google->appendChild($path);

	//Twitter
	$twitter = $xml->createElement("twitter");
	$twitter = $container->appendChild($twitter);
	$oauth_access_token = $xml->createElement("oauth_access_token", "894090696-Hp9REyDObGsLWUdcf5wcdUo1AsAf3BqiHRHo2ATO");
	$oauth_access_token = $twitter->appendChild($oauth_access_token);
	$oauth_access_token_secret = $xml->createElement("oauth_access_token_secret", "drAFg6J4bSAqP0kpSQEltWC1eEJhUSEyAP3JpDAoDvKPy");
	$oauth_access_token_secret = $twitter->appendChild($oauth_access_token_secret);
	$consumer_key = $xml->createElement("consumer_key", "OmqyJdvCo9F1loqZwlLltLwxf");
	$consumer_key = $twitter->appendChild($consumer_key);
	$consumer_secret = $xml->createElement("consumer_secret", "brQ7eW7GO8ZH6Usfh2SY3xYFEXHvv2VLlKixNx27FdvUF7D82W");
	$consumer_secret = $twitter->appendChild($consumer_secret);
	$path = $xml->createElement("path", "https://api.twitter.com/1.1/search/tweets.json?q=[cityName]&#038geocode=[longitude],[latitude],[area]km&#038count=[numberOfTweets]&#038tweet_mode=extended");
	$path = $twitter->appendChild($path);

	//Flikr
	$key = $xml->createElement("flikr","Jake's key here");
	$key = $container->appendChild($key);
	$path = $xml->createElement("path", "Jakes API path here");
	$path = $twitter->appendChild($path);


	$xml->FormatOutput = true;
	$string_value = $xml->saveXML();

	$xml->save("../xml/API.xml");
	
	$file = "../xml/API.xml";
	header('Location: ../xml/API.xml');	
?>
