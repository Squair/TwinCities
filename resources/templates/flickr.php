
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

       session_start();
	   require_once("db_connection.php");
       $city = $_GET['city'];
	   $sql = "SELECT * FROM city WHERE name='$city'";
	   $result = $connection->query($sql);
	   $row = $result->fetch(PDO::FETCH_ASSOC);
	   
       if (isset($_SESSION['CACHED'])) {
           if (time() - $_SESSION['CACHED'] > 10) { //Every Day Make API Call 86400
                print "API CALL";
                makeApiCall(); //Calls The API
                $_SESSION['CACHED'] = time(); // update last activity time stamp
           } else {
                print "DB CALL";
                getCachedImages($row['idCity']);
           }
       } else 
            $_SESSION['CACHED'] = time(); // Set Initial Cached Time Stamp


        //Check Session (If Visited Page before, Load URL's from Database)
       
       
        function makeApiCall() {
			require("db_connection.php");
			$city = $_GET['city'];
			$sql = "SELECT * FROM city WHERE name='$city'";
			$result = $connection->query($sql);
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			$idCity = $row['idCity'];
			$lat = $row['latitude'];
			$lon = $row['longitude'];

			
            $query = "https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=f4ee116742bf19b59d294611cb7b834b&lat=" . $lon . "&lon=" . $lat . "&format=json&jsoncallback=?";
            
            $ch = curl_init(); // open curl session

            curl_setopt($ch, CURLOPT_URL, $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
            $data = curl_exec($ch); // execute curl session
            curl_close($ch); // close curl session

            $data = str_replace( 'jsonFlickrApi(', '', $data ); //Remove Text 
            $data = substr( $data, 0, strlen( $data ) - 1 ); //strip out last paren

            $object = json_decode($data, true);
            
            
            
            $arrayLength = sizeof($object['photos']['photo']);
            
            
            
            $deleteRecords = $connection->prepare("DELETE FROM flickr WHERE idCity='$idCity'");
            $deleteRecords->execute();
            
            print "<div class='images'>";
            for ($i = 0; $i < $arrayLength; $i++) {
                if ($i >= 10)
                    break;
                $element = $object['photos']['photo'][$i];
                $image = "https://farm" . $element['farm'] . ".staticflickr.com/" . $element['server'] . "/" . $element['id'] . "_" . $element['secret'] . "_m.jpg";
                cacheImages($image, $element['title'], $row['idCity']);//Cache The Images
                
                displayImage($image, $element['title']); //Print Images To Screen
            }
            print "</div>";
        }
       
       function cacheImages($imgUrl, $caption, $idCity) {
            require("db_connection.php");
            if (isset($connection)) {
                $query = $connection->prepare("INSERT INTO flickr (idCity, IMAGE_URL, CAPTION, TIME_CACHED) VALUES (?, ?, ?, ?)");
                $query->execute(array($idCity, $imgUrl, $caption, date("Y-m-d H:i:s")));
            }
       }
       
       function getCachedImages($idCity) {
            require("db_connection.php");
				$sql = "SELECT * FROM flickr WHERE idCity='$idCity'";
				$result = $connection->query($sql);
				
				
                foreach ($result as $image) {
                    displayImage($image['IMAGE_URL'], $image['CAPTION']);
                }
			
       }
       
        function displayImage($imgUrl, $caption) {
            
            print "<div class='imageContainer''>";
                print "<img src='" . $imgUrl . "' class='image''/>";
                print "<div class='imageText' >";
                    print "<div class='text'>" . $caption . "</div>";
                print "</div>";
            print "</div>";
            
           // print "<div class='image'>";
            //    print "<img src='" . $imgUrl . "' class='images' caption='" . $caption . "'/>";
            //    print "<span class='caption'>" . $caption . "</span>";
           // print "</div>";
        }
        
    ?>
		
		
   </body>
	
</html>