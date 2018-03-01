<?php require_once("../resources/templates/document_header.php")?>
<?php //require_once("../resources/templates/db_connection.php"); ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc&libraries=places"></script> <!-- Google maps api script-->
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
			<input class="commentInput" name="commentName" type="username" placeholder="Display name"></input>
			<input class="commentInput" id="commentField" name="commentText" type="text" placeholder="Write your comment here!"></input>
			<input class="commentInput" type="submit" name="commentSubmit"></input>
		</form>
		<?php
		if (isset($connection)){
			$city = $_GET['city'];
			$query = "SELECT comments.name, comment FROM comments INNER JOIN city ON comments.idCity=city.idCity AND city.name='$city'";
			echo "<table>";
			foreach ($connection->query($query) as $row) {
				echo "<tr>";
				echo "<td style='width:25%;'><p>" . $row['name'] . "</p></td>";
				echo "<td style='width:85%;'><p>" . $row['comment'] . "</p></td>";
				echo "<tr>";
			}
			echo "</table>";
			if (isset($_POST['commentSubmit'])){
				if ($city == "Birmingham"){
					$idCity = 1;
				} else if ($city == "Chicago"){
					$idCity = 2;
				}
				$comment = $_POST['commentText'];
				$name = $_POST['commentName'];
				$sql = "INSERT INTO comments (idCity, comment, name) VALUES ('$idCity', '$comment', '$name')";
				$connection->query($sql);
				header("main.php?id=" . $_GET['id'] . "&city=" . $_GET['city']);

			}
		} 
		
		?>
		</div>
		
		<div id="markerInfo"></div>
		<!-- Pull tweets into below div -->
		<div id="tweetBox" style="width: 100%; height: 80%; overflow:scroll"></div>
        
        <!-- Show weather widget -->
		<div id="weather" style="width: 200%; height: 100%; overflow:scroll;">
            <a class="weatherwidget-io" href="<?php echo $weatherLink; ?>" data-label_1="<?php echo $city; ?>" data-label_2="WEATHER" data-theme="original" ><?php echo $city; ?> WEATHER</a>
		</div>
		
		<img id="contentImg">
	</div>
</main>

<!-- Need to move this somewhere -->
<script>
	
	initMap();
	
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
</script>
