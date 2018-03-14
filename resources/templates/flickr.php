<html>

   <head>
      <title>Flickr</title>
      <script type = 'text/javascript' src = 'http://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js'></script>
       
       
       <style>
           #images {
                display: flex;
                width: 100%;
                height: 50%;
           }
           
        .imageContainer {
            position: relative;
            width: 100%;
            cursor: pointer;
            
        }
        .image {
            opacity: 1;
            display: block;
            width: 100%;
            transition: .5s ease;
            backface-visibility: hidden;
        }
        .imageText {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%)
        }
        .imageContainer:hover .image, .selection:hover .barberImage {
            opacity: 0.3;
        }
        .imageContainer:hover .imageText, .selection:hover .imageText{
            opacity: 1;
        }
        .text {
            color: black;
            font-size: 14px;
            letter-spacing: 3px;
            text-align: center;
        }
        
       </style>
        
       
   </head>
	
   <body>
	
       
    <?php 
       
       //Create a PHP Session
       session_start();
       
       if (isset($_SESSION[$_GET['city']])) { //If a session for that city exists
           if (time() - $_SESSION[$_GET['city']] > 10) { //Every Day Make API Call 86400 (10 seconds for presentation purposes)
                print "API CALL";
                makeApiCall(); //Calls The API
                $_SESSION[$_GET['city']] = time(); // update last activity time stamp
           } else {
                print "DB CALL";
                getCachedImages(); //Grabs images from Database
           }
       } else 
            $_SESSION[$_GET['city']] = time(); // Set Initial Cached Time Stamp
       
        /* Grabs & Parases Images From API URL */
        function makeApiCall() {
			require("db_connection.php"); //Establish DB Connection
            
			$city = $_GET['city']; //Get the City Name
            
            /* Get the Cities Latitude & Longitude */
			$sql = "SELECT * FROM city WHERE name='$city'"; 
			$result = $connection->query($sql);
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			$lat = $row['latitude'];
			$lon = $row['longitude'];
			/* API URL For Latitude & Longitude */
            $query = "https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=f4ee116742bf19b59d294611cb7b834b&lat=" . $lon . "&lon=" . $lat . "&format=json&jsoncallback=?";
            
            $ch = curl_init(); // open curl session
            curl_setopt($ch, CURLOPT_URL, $query); //Get Data from API URL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Returns Data as a string
            $data = curl_exec($ch); // Execute the curl session
            curl_close($ch); // Closes the curl session
            $data = str_replace( 'jsonFlickrApi(', '', $data ); //Remove Text 
            $data = substr( $data, 0, strlen( $data ) - 1 ); //strip out last paren
            $object = json_decode($data, true); //Decode JSON (Convert To An Array)
            
            
            
            $arrayLength = sizeof($object['photos']['photo']); //The Length of the returned results
            
            
            //Delete the past cached images based on the city
            $deleteRecords = $connection->prepare("DELETE FROM flickr WHERE CITY_NAME='" . $_GET['city'] . "'"); 
            $deleteRecords->execute();
            
            print "<div class='images'>"; 
            for ($i = 0; $i < $arrayLength; $i++) {
                if ($i >= 10) //Return only 10 results
                    break;
                $element = $object['photos']['photo'][$i]; //Return the whole JSON element
                //The Image URL Parased
                $image = "https://farm" . $element['farm'] . ".staticflickr.com/" . $element['server'] . "/" . $element['id'] . "_" . $element['secret'] . "_b.jpg";
                //Cache the images to the DB
                cacheImages($image, $element['title']);
                //Display the images to screen
                displayImage($image, $element['title']);
            }
            print "</div>";
        }
       
       /* Caches the Image URL's + Data To The Database */
       function cacheImages($imgUrl, $caption) {
            require("db_connection.php"); //Create DB Connection
            if (isset($connection)) { //Make sure the connection is established
                $query = $connection->prepare("INSERT INTO flickr (IMAGE_URL, CAPTION, TIME_CACHED, CITY_NAME) VALUES (?, ?, ?, ?)");
                $query->execute(array($imgUrl, $caption, date("Y-m-d H:i:s"), $_GET['city'])); //Insert Image Data into DB
            }
       }
       
       /* Grabs all the cached images from the database & displays to screen */
       function getCachedImages() {
            require_once("db_connection.php");
           	if (isset($connection)) {
                $getImages = $connection->prepare("SELECT * FROM flickr WHERE CITY_NAME='".$_GET['city']."'");
                $getImages->execute();
                $images = $getImages->fetchAll();
				
                foreach ($images as $image) {
                    displayImage($image['IMAGE_URL'], $image['CAPTION']);
                }
			}
        }
       
        /* Displays the images to screen */
        function displayImage($imgUrl, $caption) {
            
            print "<div class='imageContainer''>";
                print "<img src='" . $imgUrl . "' class='image''/>";
                print "<div class='imageText' >";
                    print "<div class='text'>" . $caption . "</div>";
                print "</div>";
            print "</div>";
        }
        
    ?>
		
		
   </body>
	
</html>