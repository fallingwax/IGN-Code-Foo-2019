<?php
/*
*This page serves as an API call to search IGN Feed data from MySQL and returns the results in JSON format. 
*Using GET method, two parameters need to be supplied to retreive results 
*@param content, accepted values are articles or videos
*@param search, any term is excepted and is used to search the description field.
*
*example call is /localhost/BackEnd/search.php?content=videos&search=DC.  This will have to be changed when moved to a production server
*/


//import class for database connection and queries
include "class_dbconnection.php";
//import config file for database connection
include "db_config.php";

//set up pdo object
$pdo = new PDOWrapper\DBConnection($dsn_config);

//assign short names to get variables
$contentType = $_GET['content'];
$searchTerm = $_GET['search'];

//check content type, articles, videos, or none
if ($contentType == "articles") {
    $searchTable = "tbl_articles";
    $query = "SELECT * from $searchTable `st` INNER JOIN tbl_thumbnails `th` ON `st`.Content_id = `th`.Content_id WHERE `Description` LIKE :SearchTerm";
    $searchTerm = "%$searchTerm%";
    //send json data of returned array
    echo json_encode($pdo->Query($query, array("SearchTerm" => $searchTerm)));
} else if ($contentType == "videos") {
    $searchTable = "tbl_videos";
    $query = "SELECT * from $searchTable `st` INNER JOIN tbl_thumbnails `th` ON `st`.Content_id = `th`.Content_id WHERE `Description` LIKE :SearchTerm ";
    $searchTerm = "%$searchTerm%";
    //send json data of returned arr
    echo json_encode($pdo->Query($query, array("SearchTerm" => $searchTerm)));
} else {
    //if content term is invalid return blank
    echo json_encode(array());
}
?>