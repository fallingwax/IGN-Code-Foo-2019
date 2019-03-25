<?php
/*
*Process form for csv data file upload
*Checks to confirm file is a csv, uploads to server, parses through csv to create array and loads data into mysql server.
*/

//start session to use session variables for delivering status messages
session_start();

//import class for database connection and queries
include "class_dbconnection.php";
//import config file for database connection
include "db_config.php";

//set up pdo object
$pdo = new PDOWrapper\DBConnection($dsn_config);

//set up variables for file names and locations
$target_dir = "uploads/";
$newFileName = "upload.csv";
$target_file = $target_dir . $newFileName;
//boolean value to determine processing status
$uploadOk = true;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

//check if post submit was used
if ($_POST['submit']) {
    
    //check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = false;
        setMessage("Sorry, file already exists.", "error");
    }
    
    //check if file is csv
    if ($fileType != "csv") {
        $uploadOk = false;
        setMessage("Only CSV file types allowed" , "error");

    }
    
    //unused for now
    if ($uploadOk == false) {
        setMessage("Your file was not uploaded. Please try again" , "error");
    } else {
        //if all good, move file and uploaded data
        if (move_uploaded_file($_FILES["csvFileUpload"]["tmp_name"], $target_file)) {
            $csv = "uploads/upload.csv";
            parseCSV($csv, $pdo);
        }
    }
}

/*
*setMessage, sets session variable msg and relocates to calling page
*@param message, string
*@param level, string
*/
function setMessage($message, $level) {
    $_SESSION['msg'] = $message;
    $_SESSION['level'] = $level;
    header('Location: index.php');
}

/*
*readCsv, converts csv in array
*@param csv, file
*
*/
function readCSV($csv) {
    $temp_array = array();
    $row = 1;
    $handle = fopen($csv, "r");
    while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
        $temp_array[] = $data;
    }
    fclose($handle);
    return $temp_array;
}

/*
*array_strip, convert multi-dimensional array to single array
*@param multi-dim-array, array
*
*/
function array_strip($multi_dim_array) {
    $temp_array = array();
    foreach ($multi_dim_array as $multi_array) {
        foreach ($multi_array as $array) {
            array_push($temp_array, $array);
        }
    }
    return $temp_array;
}

/*
*parseCSV, process csv file and load data in MySQL
*@param csv, file
*@param pdo, pdo object
*/
function parseCSV($csv, $pdo) {
    //set up variables
    $temp_array = array();
    $keyed_array = array();
    $dupeData = false;
    $csvArray = readCSV($csv , $pdo);
    //strip off first row of array to use for keys
    $keys = array_shift($csvArray);
    //rekey array to use Associative values
    foreach ($csvArray as $array) {
        $temp_array = array_combine($keys, $array);
        array_push($keyed_array, $temp_array);
    }
    
    //get content id's from each database to test against new values to prevent duplication
    $articles_content_id_array = array_strip($pdo->Query("select `Content_id` from `tbl_articles`"));
    $videos_content_id_array = array_strip($pdo->Query("select `Content_id` from `tbl_videos`"));
    $thumbnails_content_id_array = array_strip($pdo->Query("select `Content_id` from `tbl_thumbnails`"));
    
    foreach($keyed_array as $array) {
//        var_dump($array);
//        extract($array);
        //set up short names for all array variables, use this instead of extract($array) to visually see all names
        $content_id = $array['content_id'];
        $content_type = $array['content_type'];
        $title = $array['title'];
        $headline = $array['headline'];
        //encode special characters for later display
        $description = htmlspecialchars($array['description']);
        //normalize date field 
        $publish_date = date('Y-m-d h:i:s', strtotime($array['publish_date']));
        $slug = $array['slug'];
        $duration = $array['duration'];
        $video_series = $array['video_series'];
        $author_1 = $array['author_1'];
        $author_2 = $array['author_2'];
        $state = $array['state'];
        $tag_1 = $array['tag_1'];
        $tag_2 = $array['tag_2'];
        $tag_3 = $array['tag_3'];
        $thumbnail_1_URL = $array['thumbnail_1_URL'];
        $thumbnail_1_size = $array['thumbnail_1_size'];
        $thumbnail_1_width = ($array['thumbnail_1_width'] == null ? 0 : $array['thumbnail_1_width']);
        $thumbnail_1_height = ($array['thumbnail_1_height'] == null ? 0 : $array['thumbnail_1_height']);
        $thumbnail_2_URL = $array['thumbnail_2_URL'];
        $thumbnail_2_size = $array['thumbnail_2_size'];
        $thumbnail_2_width = ($array['thumbnail_2_width'] == null ? 0 : $array['thumbnail_2_width']);
        $thumbnail_2_height = ($array['thumbnail_2_height'] == null ? 0 : $array['thumbnail_2_height']);
        $thumbnail_3_URL = $array['thumbnail_3_URL'];
        $thumbnail_3_size = $array['thumbnail_3_size'];
        $thumbnail_3_width = ($array['thumbnail_3_width'] == null ? 0 : $array['thumbnail_3_width']);
        $thumbnail_3_height = ($array['thumbnail_3_height'] == null ? 0 : $array['thumbnail_3_height']);
        
        //query to insert data into tbl_articles
        $article_query = "insert into tbl_articles (`Content_id`, `Headline`, `Description`, `Publish_Date`, `Slug`, `Author_1`, `Author_2`, `State`, `Tag_1`, `Tag_2`, `Tag_3`) VALUE (:Content_id, :Headline, :Description, :Publish_date, :Slug, :Author_1, :Author_2, :State, :Tag_1, :Tag_2, :Tag_3)";    
        $article_params = array(
            "Content_id" => $content_id, 
            "Headline" => $headline,
            "Description" => $description,
            "Publish_date" => $publish_date,
            "Slug" => $slug,
            "Author_1" => $author_1,
            "Author_2" => $author_2,
            "State" => $state,
            "Tag_1" => $tag_1,
            "Tag_2" => $tag_2,
            "Tag_3" => $tag_3
        );
        
        //query to insert data into tbl_vidoes
        $video_query = "insert into tbl_videos (`Content_id`, `Title`, `Description`, `Publish_Date`, `Slug`, `Duration`, `Video_series`, `State`, `Tag_1`, `Tag_2`, `Tag_3`) VALUE (:Content_id, :Title, :Description, :Publish_date, :Slug, :Duration, :Video_series, :State, :Tag_1, :Tag_2, :Tag_3)";
        $video_params = array(
            "Content_id" => $content_id, 
            "Title" => $title,
            "Description" => $description,
            "Publish_date" => $publish_date,
            "Slug" => $slug,
            "Duration" => $duration,
            "Video_series" => $video_series,
            "State" => $state,
            "Tag_1" => $tag_1,
            "Tag_2" => $tag_2,
            "Tag_3" => $tag_3
        );
        
        //query to insert data into tbl_thumbnails
        $thumbnail_query = "insert into tbl_thumbnails (`Content_id`, `Thumbnail_1_url`, `Thumbnail_1_size`, `Thumbnail_1_width`, `Thumbnail_1_height`, `Thumbnail_2_url`, `Thumbnail_2_size`, `Thumbnail_2_width`, `Thumbnail_2_height`, `Thumbnail_3_url`, `Thumbnail_3_size`, `Thumbnail_3_width`, `Thumbnail_3_height`) VALUES (:Content_id, :Thumbnail_1_URL, :Thumbnail_1_size, :Thumbnail_1_width, :Thumbnail_1_height, :Thumbnail_2_URL, :Thumbnail_2_size, :Thumbnail_2_width, :Thumbnail_2_height, :Thumbnail_3_URL, :Thumbnail_3_size, :Thumbnail_3_width, :Thumbnail_3_height)";
        $thumbnail_params = array(
            "Content_id" => $content_id,
            "Thumbnail_1_URL" => $thumbnail_1_URL,
            "Thumbnail_1_size" => $thumbnail_1_size,
            "Thumbnail_1_width" => $thumbnail_1_width,
            "Thumbnail_1_height" => $thumbnail_1_height,
            "Thumbnail_2_URL" => $thumbnail_2_URL,
            "Thumbnail_2_size" => $thumbnail_2_size,
            "Thumbnail_2_width" => $thumbnail_2_width,
            "Thumbnail_2_height" => $thumbnail_2_height,
            "Thumbnail_3_URL" => $thumbnail_3_URL,
            "Thumbnail_3_size" => $thumbnail_3_size,
            "Thumbnail_3_width" => $thumbnail_3_width,
            "Thumbnail_3_height" => $thumbnail_3_height
        );
    
        //check content type and determine if current content_id is already in the database
        if ($content_type == "article" && !in_array($content_id, $articles_content_id_array)) {            
            $pdo->Query($article_query, $article_params);
            $dupeData = false;
            
        } else {
            $dupeData = true;
        }
        
        //check content type and determine if current content_id is already in the database
        if ($content_type == "video" && !in_array($content_id, $videos_content_id_array)) {
            $pdo->Query($video_query, $video_params); 
            $dupeData = false;
        } else {
            $dupeData = true;
        }
        
        //check if current content_id is already in the database
        if (!in_array($content_id, $thumbnails_content_id_array)) {
            $pdo->Query($thumbnail_query, $thumbnail_params);
            $dupeData = false;
        } else {
            $dupeData = true;
        }
    }
    
    //delete the upload.csv upon completion
    unlink($csv) or die("Couldn't delete file");
    
    if ($dupeData == true) {
        //if dupeData is true, return to calling page with message
        setMessage("Duplicate data, not uploaded", "error");
    } else {
        //return successful, return to calling page with message
        setMessage("Data uploaded successfully", "success");
    }
    

}


?>