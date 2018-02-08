<?php require_once("../resources/templates/document_header.php")?>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc&callback=initMap"></script>

<script>
			  function initMap() {
				var uluru = {lat: 50, lng: 50};
				var map = new google.maps.Map(document.getElementById('map'), {
				  zoom: 4,
				  center: uluru
				});
				var marker = new google.maps.Marker({
				  position: uluru,
				  map: map
				});
			  }
</script>
<script>
	function openLeftNav(){
		var sideBar = document.getElementById("leftSideBar"); //Select the side bar from its id.
		sideBar.style.width = "250px"; //Will give the illusion that it slides out with a css transition.
		document.getElementById("headerTitle").style.marginLeft = "260px"; //Move title with side bar
		document.getElementById("leftSideBarText").style.display = "block"; 
		document.getElementById("main").style.background = "rgba(252, 237, 237, 0.8)";
		document.getElementById("contentGrid").style.background = "rgba(115, 149, 111, 0.8)";
		//Change opacity of main content to get focus onto side bar when active.
	}
	
	function openRightNav(){
		var sideBar = document.getElementById("rightSideBar"); //Select the side bar from its id.
		sideBar.style.width = "250px"; //Will give the illusion that it slides out with a css transition.
		document.getElementById("headerTitle").style.marginRight = "260px"; //Move title with side bar
		document.getElementById("rightSideBarText").style.display = "block"; 
		document.getElementById("main").style.background = "rgba(252, 237, 237, 0.8)";
		document.getElementById("contentGrid").style.background = "rgba(115, 149, 111, 0.8)";
		//Change opacity of main content to get focus onto side bar when active.
	}
	
	function closeLeftNav(){
		var sideBar = document.getElementById("leftSideBar");
		//Push elements back to their origin position.
		sideBar.style.width = "0"; 
		document.getElementById("headerTitle").style.marginLeft = "10px";
		document.getElementById("leftSideBarText").style.display = "none"; //So that the text doesnt wrap as container closes.
		document.getElementById("main").style.background = "rgba(252, 237, 237, 1)"; //Remove opacity.
		document.getElementById("contentGrid").style.background = "rgba(115, 149, 111, 1)";
	}
	
	function closeRightNav(){
		var sideBar = document.getElementById("rightSideBar");
		//Push elements back to their origin position.
		sideBar.style.width = "0"; 
        document.getElementById("headerTitle").style.marginRight = "10px";
		document.getElementById("rightSideBarText").style.display = "none"; //So that the text doesnt wrap as container closes.
		document.getElementById("main").style.background = "rgba(252, 237, 237, 1)"; //Remove opacity.
		document.getElementById("contentGrid").style.background = "rgba(115, 149, 111, 1)";
	}
	
</script>



<?php
    if ($_GET['id'] == "left"){
        require_once("../resources/templates/left_panel.php");

    } else {
        require_once("../resources/templates/right_panel.php");

    }
?>

<?php require_once("../resources/templates/header.php")?>				
      

<main id="main">
	<div id="contentGrid">
		<h1 id="contentTitle">Map</h1>
		<p id="contentInfo">Here is a map.</p>
		<div id="map" style="width: 100%; height: 400px;"></div>
		<img id="contentImg">
	</div>
</main>
<?php require_once("../resources/templates/footer.php") ?>