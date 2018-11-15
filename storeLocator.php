<?php
$username="root";
$password="";
$database="wp";
// Get parameters from URL
$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];
$radius = $_GET["radius"];
$properityType = $_GET["properityType"];
$description = $_GET["description"];
$contractType = $_GET["contractType"];
$priceInputStart = $_GET["priceInputStart"];
$priceInputEnd = $_GET["priceInputEnd"];
$showSize = $_GET["showSize"];
$sortBy = $_GET["sortBy"];
// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);
// Opens a connection to a mySQL server
$connection=mysqli_connect ("localhost", $username, $password);
if (!$connection) {
  die("Not connected : " . mysqli_error());
}
// Set the active mySQL database
$db_selected = mysqli_select_db($connection, $database);
if (!$db_selected) {
  die ("Can\'t use db : " . mysqli_error());
}
if($showSize > 20){
	$showSize = 20;
}
$condition = '1=1';
if($properityType != 'Any'){
	$condition = $condition." and propertytype='".$properityType."'";
}
if($contractType != 'Any'){
	$condition = $condition." and contracttype='".$contractType."'";
}
$likeSqlQuery = '';
if(isset($description) && $description){
	$likeSqlQuery = " and description like '%".$description."%'";
}

if(isset($priceInputStart) && $priceInputStart){
	$condition = $condition." and price > ".$priceInputStart;
}

if(isset($priceInputEnd) && $priceInputEnd){
	$condition = $condition." and price <".$priceInputEnd;
}
$sortByCondition = ',lastupdated desc';
if($sortBy == 1){
	$sortByCondition = ',lastupdated asc';
}

// Search the rows in the markers table
$query = sprintf("SELECT id, name, address, lat, lng, ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM wp_markers where ".$condition." %s HAVING distance < '%s' ORDER BY distance ".$sortByCondition." LIMIT 0 , %d",
  mysqli_real_escape_string($connection,$center_lat),
  mysqli_real_escape_string($connection,$center_lng),
  mysqli_real_escape_string($connection,$center_lat),
  $likeSqlQuery,
  mysqli_real_escape_string($connection,$radius),
  $showSize);
  
 $result = mysqli_query($connection,$query);
if (!$result) {
  die("Invalid query: " . mysqli_error($connection));
}
header("Content-type: text/xml");
// Iterate through the rows, adding XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id", $row['id']);
  $newnode->setAttribute("name", $row['name']);
  $newnode->setAttribute("address", $row['address']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
  $newnode->setAttribute("distance", $row['distance']);
}
echo $dom->saveXML();
?>