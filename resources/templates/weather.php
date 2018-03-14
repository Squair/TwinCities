<html>
    <head>
        <style>
            .section {
                background-color: #f0f2f3;
                border-radius: 5px;
                padding: 5px;
            }
            
            .temp {
                float: left;
                display: block;
            }
            
            .time {
                float: right;
            }
            
            .weather {
                
            }
        </style>
    </head>
    
</html>


<?php
$apiKey = "05768b694a7d28135a53d7463726dce6";
$city = $_GET['city'];

$cityId = $_GET['city'] === "Birmingham" ? 2655603 : 4887398;

//2655603 Birmingham ID, 4887398 Chicago ID

$json = file_get_contents("http://api.openweathermap.org/data/2.5/forecast?id=" . $cityId . "&mode=json&units=metric&appid=".$apiKey);
$phpData = json_decode($json, true);
//echo $phpData['list']['0']['main']['temp'];
//var_dump($phpData);

foreach($phpData['list'] as $result) {
    echo "<div class='section'>";
    
        echo "<div class='temp'>";
            echo "Temperature: " . $result['main']['temp'] . "°C"; //Temperature
        echo "</div>";
    
        echo "<div class='time'>";
            echo $result['dt_txt']; //Date & Time
        echo "</div>";
    
            echo "<br><br>";
            foreach ($result['weather'] as $weather) {
                echo "<div class='weather'>";
                    echo "Weather Condition: <b>" . $weather['main'] . " - "; // Main Weather Condition
                    echo $weather['description'] . "</b>"; //Description
                echo "</div>";
            }
    echo "</div>";

    echo "<br><br>";
}
echo "<p> Test</p>";


?>