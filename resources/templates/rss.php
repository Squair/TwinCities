<?php

function connect() {
    return new PDO('mysql:host=localhost;dbname=fet15015248', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
}
 
$pdo = connect();
 
$sql = 'SELECT * FROM places ORDER BY name DESC';
$query = $pdo->prepare($sql);
$query->execute();
$rs_post = $query->fetchAll();
 
// XML Structure.
$data = '<?xml version="1.0" encoding="UTF-8" ?>';
$data .= '<rss version="2.0">';
$data .= '<channel>';
$data .= '<title>RSS Feed for Birmingham and Chicago!</title>';
$data .= '<link>www.localhost.com</link>';
$data .= '<description>Some Desc</description>';

// City
$sql = 'SELECT * FROM city ORDER BY name DESC';
$query = $pdo->prepare($sql);
$query->execute();
$rs_post = $query->fetchAll();

foreach ($rs_post as $row) {
    $data .= '<item>';
    $data .= '<idCity>'.$row['idCity'].'</idCity>';
    $data .= '<name>'.$row['name'].'</name>';
    $data .= '<area>'.$row['area'].'</area>';
    $data .= '<latitude>'.$row['latitude'].'</latitude>';
    $data .= '<longitude>'.$row['longitude'].'</longitude>';
    $data .= '</item>';
}

// Comments
$sql = 'SELECT * FROM comments ORDER BY time DESC';
$query = $pdo->prepare($sql);
$query->execute();
$rs_post = $query->fetchAll();


foreach ($rs_post as $row) {
    $data .= '<item>';
    $data .= '<idComment>'.$row['idComment'].'</idComment>';
    $data .= '<idCity>'.$row['idCity'].'</idCity>';
    $data .= '<comment>'.$row['comment'].'</comment>';
    $data .= '<name>'.$row['name'].'</name>';
    $data .= '<time>'.$row['time'].'</time>';

    $data .= '</item>';
}

// Places
$sql = 'SELECT * FROM places ORDER BY name DESC';
$query = $pdo->prepare($sql);
$query->execute();
$rs_post = $query->fetchAll();

foreach ($rs_post as $row) {
    $data .= '<item>';
    $data .= '<idPlace>'.$row['idPlace'].'</idPlace>';
    $data .= '<name>'.$row['name'].'</name>';
    $data .= '<url>'.$row['url'].'</url>';
    $data .= '<phone>'.$row['phone'].'</phone>';
    $data .= '<address>'.$row['address'].'</address>';
    $data .= '<dateAdded>'.$row['dateAdded'].'</dateAdded>';
    $data .= '</item>';
}

// Photos
$sql = 'SELECT * FROM place_photos ORDER BY idPlace DESC';
$query = $pdo->prepare($sql);
$query->execute();
$rs_post = $query->fetchAll();

foreach ($rs_post as $row) {
    $data .= '<item>';
    $data .= '<idPhoto>'.$row['idPhoto'].'</idPhoto>';
    $data .= '<idPlace>'.$row['idPlace'].'</idPlace>';
    $data .= '<maxWidth>'.$row['maxWidth'].'</maxWidth>';
    $data .= '</item>';
}


// Reviews
$sql = 'SELECT * FROM place_reviews ORDER BY idPlace DESC';
$query = $pdo->prepare($sql);
$query->execute();
$rs_post = $query->fetchAll();

foreach ($rs_post as $row) {
    $data .= '<item>';
    $data .= '<idReview>'.$row['idReview'].'</idReview>';
    $data .= '<idPlace>'.$row['idPlace'].'</idPlace>';
    $data .= '<author>'.$row['author'].'</author>';
    $data .= '<rating>'.$row['rating'].'</rating>';
    $data .= '<text>'.$row['text'].'</text>';
    $data .= '<timeAgo>'.$row['timeAgo'].'</timeAgo>';
    $data .= '</item>';
}

// place Type
$sql = 'SELECT * FROM place_type ORDER BY idPlace DESC';
$query = $pdo->prepare($sql);
$query->execute();
$rs_post = $query->fetchAll();

foreach ($rs_post as $row) {
    $data .= '<item>';
    $data .= '<idType>'.$row['idType'].'</idType>';
    $data .= '<idPlace>'.$row['idPlace'].'</idPlace>';
    $data .= '</item>';
}

// Type
$sql = 'SELECT * FROM type ORDER BY idType DESC';
$query = $pdo->prepare($sql);
$query->execute();
$rs_post = $query->fetchAll();

foreach ($rs_post as $row) {
    $data .= '<item>';
    $data .= '<idType>'.$row['idType'].'</idType>';
    $data .= '<name>'.$row['name'].'</name>';
    $data .= '</item>';
}

$data .= '</channel>';
$data .= '</rss> ';
 
header('Content-Type: application/xml');

// Searches for an & in a string and replaces with the HTML code version.
$data=preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $data);
echo $data;

?>
