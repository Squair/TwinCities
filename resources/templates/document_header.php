<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
<script src='https://code.jquery.com/jquery-3.1.0.min.js'></script>

<link type="text/css" rel="stylesheet" href="css/stylesheet.css">
<title>Squair Site</title>
<script>
	$(document).ready(function(){
			$(".projectLink").click(function(){ //When nav bar links are clicked.
				//When clicking new titles, toggle it off then fade the new one in.
					$("#contentTitle").toggle();
					$("#contentInfo").toggle();
					$("#contentTitle").fadeIn(500);	
					$("#contentInfo").fadeIn(500);
				var newTitle = $(this).text(); //Get the text.
				console.log(newTitle);

				$("#contentTitle").text(newTitle); //Set text in content panel.
				switch ($(this).text()){
					case "Recent Tweets":
						
						$("#contentInfo").text(<?php require_once("getTweets.php")?>);
						break;
					case "Twinned Cities":
						$("#contentInfo").text("Group project assignment for data, schemes and application due 2018.");
						break;
					case "Hobbies":
						$("#contentInfo").text("Cycling, skiing, cooking, lots of things really.");
						break;
					case "Education":
						$("#contentInfo").text("GCSE's done, College done, currently at uni.");
						break;
					case "Goals":
						$("#contentInfo").text("Become a good web developer!");
						break;
					
				}
		});
	});	
</script>
</head>
 
<body>

