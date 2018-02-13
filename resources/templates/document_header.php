<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
<script src='https://code.jquery.com/jquery-3.1.0.min.js'></script>
<link type="text/css" rel="stylesheet" href="css/stylesheet.css">
<title>DSA Twinned Cities</title>
<script>
	$(document).ready(function(){
			$("#tweetBox").hide();
			$(".projectLink").click(function(){ //When nav bar links are clicked.
				//When clicking new titles, toggle it off then fade the new one in.
					$("#contentTitle").toggle();
					$("#contentInfo").toggle();
					$("#contentTitle").fadeIn(500);	
					$("#contentInfo").fadeIn(500);
					$("#map").hide();
					
					
				var newTitle = $(this).text(); //Get the text.
				console.log(newTitle);

				$("#contentTitle").text(newTitle); //Set text in content panel.
				switch ($(this).text()){
					case "Recent tweets":
						$("contentInfo").text("Here are the most recent tweets.")
						$("#tweetBox").show();
						break;
					case "Map":
						$("#contentInfo").text("Here is a map.");
						$("#map").fadeIn(500);
						break;
					case "Photos":
						$("#contentInfo").text("Pull all photos with photo api here.");
						break;
					case "Weather":
						$("#contentInfo").text("Pull weather data here.");
						break;
					case "Places of intrest":
						$("#contentInfo").text("Pull places here.");
						break;
					
				}
		});
	});	
</script>
</head>
 
<body>

