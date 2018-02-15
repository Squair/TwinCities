<?php
if (isset($connection)){
$city = $_GET['city'];
$query = "SELECT * FROM city WHERE name='$city'";
$result = $connection->query($query);
$row = $result->fetch(PDO::FETCH_ASSOC);
} else {
	die("Could not connect to db.");
}


        //Based on code by James Mallison, see https://github.com/J7mbo/twitter-api-php
        ini_set('display_errors', 1);
        require_once('TwitterAPIExchange.php');
        
        header('Content-Type: text/html; charset="UTF-8"');
        
        /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
        $settings = array(
            'oauth_access_token' => "894090696-Hp9REyDObGsLWUdcf5wcdUo1AsAf3BqiHRHo2ATO",
            'oauth_access_token_secret' => "drAFg6J4bSAqP0kpSQEltWC1eEJhUSEyAP3JpDAoDvKPy",
            'consumer_key' => "OmqyJdvCo9F1loqZwlLltLwxf",
            'consumer_secret' => "brQ7eW7GO8ZH6Usfh2SY3xYFEXHvv2VLlKixNx27FdvUF7D82W"
        );
        
        /** Perform a GET request and echo the response **/
        /** Note: Set the GET field BEFORE calling buildOauth(); **/
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $getfield = '?q=&geocode=' . $row['longitude'] . ',' . $row['latitude'] . ',' . $row['area'] . 'km&count=50';
        $requestMethod = 'GET';
        $twitter = new TwitterAPIExchange($settings);
        $data=$twitter->setGetfield($getfield)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();
        
        //Use this to look at the raw JSON
        //echo($data);
        
        // Read the JSON into a PHP object
        $phpdata = json_decode($data, true);
        
        // Debug - check the PHP object
        //var_dump($phpdata);
        
        //Loop through the status updates and print out the text of each
		echo "<br>";
        foreach ($phpdata["statuses"] as $status){
			echo("<div class='tweet'>");
			echo("<p class='date'>" . $status["created_at"] . "</p>");
			echo("<img src='" . $status["user"]["profile_image_url"] . "'>");
			echo("<h3>" . $status["user"]['screen_name'] . "</h3>");
			
        	echo("<p>" . $status["text"] . "</p>");
			echo("</div>");
        }
        
        ?>