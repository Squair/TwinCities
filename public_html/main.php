<?php require_once("../resources/templates/document_header.php")?>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc&callback=initMap"></script> <!-- Google maps -->
<script src="../resources/js/googleMap.js"></script> <!-- Google maps function call -->
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
		<h1 id="contentTitle">Map</h1> <!-- On display on page, load map first so that it renders correctly. -->
		<p id="contentInfo">Here is a map.</p>
		<div id="map" style="width: 100%; height: 400px;"></div>
		<img id="contentImg">
	</div>
</main>

<?php require_once("../resources/templates/footer.php") ?>