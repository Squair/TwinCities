<?php require_once("../resources/templates/document_header.php")?>
<script>
	function openNav(){
		var sideBar = document.getElementById("sideBar"); //Select the side bar from its id.
		sideBar.style.width = "250px"; //Will give the illusion that it slides out with a css transition.
		document.getElementById("headerTitle").style.marginLeft = "260px"; //Move title with side bar
		document.getElementById("sideBarText").style.display = "block"; 
		document.getElementById("main").style.background = "rgba(252, 237, 237, 0.8)";
		document.getElementById("contentGrid").style.background = "rgba(115, 149, 111, 0.8)";
		//Change opacity of main content to get focus onto side bar when active.
	}
	
	function closeNav(){
		var sideBar = document.getElementById("sideBar");
		//Push elements back to their origin position.
		sideBar.style.width = "0"; 
		document.getElementById("headerTitle").style.marginLeft = "10px";
		document.getElementById("sideBarText").style.display = "none"; //So that the text doesnt wrap as container closes.
		document.getElementById("main").style.background = "rgba(252, 237, 237, 1)"; //Remove opacity.
		document.getElementById("contentGrid").style.background = "rgba(115, 149, 111, 1)";
	}
	

	
</script>




<?php require_once("../resources/templates/left_panel.php")?>

<?php require_once("../resources/templates/header.php")?>				
      

<main id="main">
	<div id="contentGrid">
		<h1 id="contentTitle">Choose an option in the left panel...</h1>
		<p id="contentInfo">...And I'll do the rest</p>
		<img id="contentImg">
	</div>
</main>
<?php require_once("../resources/templates/footer.php") ?>