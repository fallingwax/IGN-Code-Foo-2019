<?php 
//include functions page
include "functions.php";

//set short names for POST variables
$startIndex = $_REQUEST['startIndex'];
$screenWidth =  $_REQUEST['screenWidth'];
$uri = "https://ign-apis.herokuapp.com/content?startIndex=$startIndex&count=20";

//echo the results from api call
echo get_article_content($uri, $screenWidth);

?>