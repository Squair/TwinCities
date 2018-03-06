<?php require_once("../resources/templates/document_header.php")?>
<?php $tempKey = "AIzaSyA4KZhYCdAR-r1lBaoTVB7cvXh3uiMLPyA"; ?>
<?php $normalKey = "AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc"; ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4KZhYCdAR-r1lBaoTVB7cvXh3uiMLPyA&libraries=places"></script> <!-- Google maps api script-->
<?php require_once("../resources/js/googleMap.php"); ?> <!-- Google maps function call -->
<script src="../resources/js/sideBarControl.js"></script> <!-- Controlls the side bars scrolling out. -->

<?php
	//displays side panels depending on which side the iframe is on.
    if ($_GET['id'] == "left"){
        require_once("../resources/templates/left_panel.php");
		$weatherLink = "https://forecast7.com/en/52d49n1d89/birmingham/";
    } else if ($_GET['id'] == "right"){
        require_once("../resources/templates/right_panel.php");
		$weatherLink = "https://forecast7.com/en/41d88n87d63/chicago/";
    }
	
	if (isset($_GET['city'])){
		$city = $_GET['city'];
	} else {
		$city = "No city selected";
	}
?>

<?php require_once("../resources/templates/header.php")?>				
      
<main id="main">
	<div id="contentGrid">
		<h1 id="contentTitle">Map</h1> 
		<!-- On display of page, load map first so that it renders correctly. -->
		<p id="contentInfo">Here is a map of <?php echo $city ?></p>
		<div id="map"></div>
		
		<div id="userComments">
		<form action="main.php?id=<?php echo $_GET['id'];?>&city=<?php echo $_GET['city'];?>" method="post">
			<input class="commentInput" name="commentName" type="username" placeholder="Display name">
			<input class="commentInput" id="commentField" name="commentText" type="text" placeholder="Write your comment here!">
			<input class="commentInput" type="submit" name="commentSubmit">
		</form>
		<?php 
			if (isset($connection)){
				require_once("../resources/js/getComments.php"); 
				if (isset($_POST['commentSubmit'])){
					$sql = "SELECT idCity FROM city WHERE name='$city'";
					$result = $connection->query($sql);
					$row = $result->fetch(PDO::FETCH_ASSOC);
					if ($row){
						$idCity = $row['idCity'];
						$comment = $_POST['commentText'];
						$name = $_POST['commentName'];

						$sth = $connection->prepare("INSERT INTO comments (idCity, comment, name) VALUES (?, ?, ?)");
						$sth->execute(array($idCity, $comment, $name));
					}
				}
			}
		?>
		</div>
		<div id="weather" style="width: 100%; height: 80%; overflow:scroll">
            
        </div>
		<div id="markerInfo"></div>
		<!-- Pull tweets into below div -->
		<div id="tweetBox" style="width: 100%; height: 80%; overflow:scroll"></div>
        

		
		<img id="contentImg">
	</div>
</main>

<script>
initMap(); //Normal map initialisation
</script>
