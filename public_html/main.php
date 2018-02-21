<?php require_once("../resources/templates/document_header.php")?>
<?php require_once("../resources/templates/db_connection.php"); ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc&libraries=places"></script> <!-- Google maps -->
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
        
        <!-- Show weather widget -->
		<div id="weather" style="width: 200%; height: 100%; overflow:scroll;">
            <a class="weatherwidget-io" href="https://forecast7.com/en/52d49n1d89/birmingham/" data-label_1="BIRMINGHAM" data-label_2="WEATHER" data-theme="original" >BIRMINGHAM WEATHER</a>
		</div>
		
		<img id="contentImg">
	</div>
</main>

<!-- Need to move this somewhere -->
<script>
	initMap();
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
</script>
