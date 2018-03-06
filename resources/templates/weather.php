<?php
$apiKey = "05768b694a7d28135a53d7463726dce6";
$city = $_GET['city'];

$json = file_get_contents("http://api.openweathermap.org/data/2.5/forecast?id=3333125&mode=json&appid=".$apiKey);
$phpData = json_decode($json, true);
//echo $phpData['list']['0']['main']['temp'];
//var_dump($phpData);

foreach($phpData['list'] as $result){
    echo $result['main']['temp'];
    echo "<br>";
    echo $result['dt_txt'];
    foreach ($result['weather'] as $weather){
        echo $weather['main'];
        echo $weather['description'];
    }

    echo "<br>";
}
echo "<p> Test</p>";


?>