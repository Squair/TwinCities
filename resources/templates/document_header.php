<?php require_once("db_connection.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
<script src='https://code.jquery.com/jquery-3.1.0.min.js'></script>

<link type="text/css" rel="stylesheet" href="css/stylesheet.css">

<title>DSA Twinned Cities</title>
<script>
	
function createRequestObject(){
	var tmpXmlHttpObject;
	
	if(window.XMLHttpRequest){
		//Moz, saf
		tmpXmlHttpObject = new XMLHttpRequest();
	} else if (window.ActiveXObject){
		//IE
		tmpXmlHttpObject = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return tmpXmlHttpObject;
}	

var http = createRequestObject();

function makeGetRequestObject(resource){
	http.open('get', resource);
	
	http.onreadystatechange = processResponse;
	
	http.send(null);
}

function processResponse(){
	if (http.readyState = 4){
		var response = http.responseText;
		document.getElementById("tweetBox").innerHTML = response;
	}
}	

	$(document).ready(function(){
            $("#weather").hide(); //Hide by default
			$("#tweetBox").hide(); //Hide by default.
			$("#markerInfo").hide();
			$(".projectLink").click(function(){ //When nav bar links are clicked.
				//When clicking new titles, toggle it off then fade the new one in.
					$("#contentTitle").toggle();
					$("#contentInfo").toggle();
					$("#contentTitle").fadeIn(500);	
					$("#contentInfo").fadeIn(500);
					$("#map").hide();
					$("#markerInfo").hide();
					$("#tweetBox").hide();
					$("#weather").hide()
					
				var newTitle = $(this).text(); //Get the text.
				console.log(newTitle);

				$("#contentTitle").text(newTitle); //Set text in content panel.
				switch ($(this).text()){
					case "Recent tweets":
						//Make request for tweets here instead
						$("#contentInfo").text("Here are the most recent 50 tweets from <?php echo $_GET['city'];?>, about <?php echo $_GET['city'];?>" )
						makeGetRequestObject('../resources/templates/getTweets.php?city=<?php echo $_GET['city']; ?>');
						processResponse();
						$("#tweetBox").fadeIn(500);

						break;
					case "Map":
						initMap();
						$("#contentInfo").text("Here is a map of <?php echo $_GET['city'];?>")
						$("#map").fadeIn(500);
						break;
					case "Photos":
						$("#contentInfo").text("Pull all photos with photo api here.");
						break;
					case "Weather":
                        $("#weather").fadeIn(500);
						$("#contentInfo").text("In <?php echo $_GET['city'];?>");
						break;
					case "Restaurants":
						
						initPlacesMap("restaurant");
						$("#markerInfo").fadeIn(500);
						$("#map").fadeIn(500);
						$("#contentInfo").text("In <?php echo $_GET['city'];?>");
						break;
					case "Night life":
						
						initPlacesMap("night_club");
						$("#markerInfo").fadeIn(500);
						$("#map").fadeIn(500);
						$("#contentInfo").text("In <?php echo $_GET['city'];?>");
						break;
					case "Cafes":
						
						initPlacesMap("cafe");
						$("#markerInfo").fadeIn(500);
						$("#map").fadeIn(500);
						$("#contentInfo").text("In <?php echo $_GET['city'];?>");
						break;
					case "Churchs":
						initPlacesMap("church");
						$("#markerInfo").fadeIn(500);
						$("#map").fadeIn(500);
						$("#contentInfo").text("In <?php echo $_GET['city'];?>");
						break;
					case "Libraries":
						initPlacesMap("library");
						$("#markerInfo").fadeIn(500);
						$("#map").fadeIn(500);
						$("#contentInfo").text("In <?php echo $_GET['city'];?>");
						break;
					
				}
		});
	});	
</script>
</head>
 
<body>

