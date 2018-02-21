<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
<script src='https://code.jquery.com/jquery-3.1.0.min.js'></script>
<link type="text/css" rel="stylesheet" href="css/stylesheet.css">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc&libraries=places"></script> 
<title>DSA Twinned Cities</title>
<script>
	$(document).ready(function(){
            $("#weather").hide(); //Hide by default
			$("#tweetBox").hide(); //Hide by default.
			$(".projectLink").click(function(){ //When nav bar links are clicked.
				//When clicking new titles, toggle it off then fade the new one in.
					$("#contentTitle").toggle();
					$("#contentInfo").toggle();
					$("#contentTitle").fadeIn(500);	
					$("#contentInfo").fadeIn(500);
					$("#map").hide();
					$("#tweetBox").hide();
					$("#weather").hide()
					
				var newTitle = $(this).text(); //Get the text.
				console.log(newTitle);

				$("#contentTitle").text(newTitle); //Set text in content panel.
				switch ($(this).text()){
					case "Recent tweets":
						$("#contentInfo").text("Here are the most recent 50 tweets from <?php echo $_GET['city'];?>" )
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
						$("#contentInfo").text("Pull weather data here.");
						break;
					case "Restaurants":
						
						initPlacesMap("restaurant");
						$("#map").fadeIn(500);
						$("#contentInfo").text("In <?php echo $_GET['city'];?>");
						break;
					case "Night life":
						
						initPlacesMap("night_club");
						$("#map").fadeIn(500);
						$("#contentInfo").text("In <?php echo $_GET['city'];?>");
						break;
					case "Cafes":
						
						initPlacesMap("cafe");
						$("#map").fadeIn(500);
						$("#contentInfo").text("In <?php echo $_GET['city'];?>");
						break;
					case "Churchs":
						initPlacesMap("church");
						$("#map").fadeIn(500);
						$("#contentInfo").text("In <?php echo $_GET['city'];?>");
						break;
					case "Libraries":
						initPlacesMap("library");
						$("#map").fadeIn(500);
						$("#contentInfo").text("In <?php echo $_GET['city'];?>");
						break;
					
				}
		});
	});	
</script>
</head>
 
<body>

