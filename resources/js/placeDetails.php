<?php
$placeId = $_GET['placeId'];
echo file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $placeId . "&key=AIzaSyBhHPFmJmx7Irz6VwjeZYqjjZjS0tfo3mc");
?>