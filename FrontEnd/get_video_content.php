<?php 
//include functions page
include "functions.php";

//set short names for POST variables
$screenWidth =  $_REQUEST['screenWidth'];
$startIndex = $_REQUEST['startIndex'];
$uri = "https://ign-apis.herokuapp.com/content?startIndex=$startIndex&count=20";

//echo the results from api call
echo get_video_content($uri, $screenWidth);

?>