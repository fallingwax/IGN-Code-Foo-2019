<?php 
//include functions page
include "functions.php";

//set short names for POST variables
$startIndex = $_REQUEST['startIndex'];
$screenWidth =  $_REQUEST['screenWidth'];
$count = "10";
$uri = "https://ign-apis.herokuapp.com/content?startIndex=$startIndex&count=10";

//echo the results from api call
echo get_latest_content($uri, $screenWidth);

?>