<?php
	$city = $_GET['city'];
	$query = "SELECT comments.name, comment, time FROM comments INNER JOIN city ON comments.idCity=city.idCity AND city.name='$city' ORDER BY time DESC";
	echo "<table>";
	foreach ($connection->query($query) as $row) {
		echo "<tr>";
		echo "<td style='width:25%;'><p>" . $row['name'] . "</p></td>";
		echo "<td style='width:10%;'><p>" . date("Y-m-d h:i", strtotime($row['time'])) . "</p></td>";
		echo "<td style='width:85%;'><p>" . $row['comment'] . "</p></td>";
		echo "<tr>";
	}
	echo "</table>";


?>