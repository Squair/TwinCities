<?php require_once("../resources/templates/document_header.php")?>
<?php require_once("../resources/templates/db_connection.php"); ?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc&callback=initMap"></script> <!-- Google maps -->
<?php require_once("../resources/js/googleMap.php"); ?> <!-- Google maps function call -->
<script src="../resources/js/sideBarControl.js"></script> <!-- Controlls the side bars scrolling out. -->

<?php
	//displays side panels depending on which side the iframe is on.
    if ($_GET['id'] == "left"){
        require_once("../resources/templates/left_panel.php");
    } else if ($_GET['id'] == "right"){
        require_once("../resources/templates/right_panel.php");
    }
?>

<?php require_once("../resources/templates/header.php")?>				
      
<main id="main">
	<div id="contentGrid">
		<h1 id="contentTitle">Map</h1> 
		<!-- On display of page, load map first so that it renders correctly. -->
		<p id="contentInfo">Here is a map of <?php echo $_GET['city'];?></p>
		<div id="map" style="width: 100%; height: 400px;"></div>
		
		<!-- Pull tweets into below div -->
		<div id="tweetBox" style="width: 100%; height: 80%; overflow:scroll">
		<?php require_once("../resources/templates/getTweets.php"); ?>
		</div>
		
		<img id="contentImg">
	</div>
</main>

<?php require_once("../resources/templates/footer.php") ?>