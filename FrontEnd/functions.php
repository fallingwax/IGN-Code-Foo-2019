<?php

/*
*function clean, remove spaces and replace with hyphens and remove special chars
*@param $string string
*@return $string string
*/
function clean($string) {
   $string = str_replace(' ', '-', $string); 
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); 
}

/*
*function get_latest_content_load, used on initial load of page to pull first set of content
*@param $uri string (api location)
*/
function get_latest_content_load($uri) {
    //get contents from uri
    $json_api_data = file_get_contents($uri);
    //decode json into array
    $array_api_data = json_decode($json_api_data, true);
    //set markers for pagination
    $pagination_markers = array_splice($array_api_data,0, 2);
    //set and format current timestamp
    $nowDate = date("Y-m-d H:i:s");
    $now = new DateTime($nowDate);
    
    foreach ($array_api_data as $array_data) {
        foreach($array_data as $array) {
            //get publish date
            $date = new DateTime($array['metadata']['publishDate']);
            //set short name 
            $contentId = $array['contentId'];
            //get the difference between now and publish date
            $interval = $now->diff($date);
            //set hours off from $interval
            $hoursOff = $interval->format('%hh');
            //set mninutes off from $interval
            $minutesOff = $interval->format('%im');
            //set hours off from $interval
            if (intval($hoursOff) <= 1) {
                $hoursOff = $interval->format('%hh');
            }
            //set days off from $interval
            $daysOff = $interval->format('%dd');
            //format date
            $dateDisplay = $date->format('M jS, Y');
            //check if content type is video
            if ($array['contentType'] == 'video') {
                //set short names
                $headline = $array['metadata']['title'];
                $duration = $array['metadata']['duration'];
                //trim duration to minutes:seconds
                $video_length = ltrim(gmdate("i:s",$duration),0);
                //echo html 
                 echo '
                 <div class="row video feed-content-row">
                    <div class="col-sm-4 image-section">
                        <a href=""><div class="thumbnail-container"><img class="thumbnail" src="'.$array['thumbnails'][0]['url'].'" alt="'.clean($headline).'" style="max-width: 306 !important;"><div class="duration-image"><i class="fas fa-play-circle"></i><span class="duration">'.$video_length.'</span></div>
                        </img></div></a>
                    </div>';
                
            } else {
                //if content is article set headling
                $headline = $array['metadata']['headline'];
                //echo html
                echo '
                 <div class="row article feed-content-row">
                    <div class="col-sm-4 image-section">
                        <a href=""><div class="thumbnail-container"><img class="thumbnail" src="'.$array['thumbnails'][0]['url'].'" alt="'.clean($headline).'" style="max-width: 306 !important;"></img></div></a>
                    </div>';
            }
                //echo html
                echo '
                <div class="col-sm-4 headline-section">
                        <div class="row">
                            <div class="date-stamp">';
                            //set time interval and display
                            if ((intval($hoursOff)+(intval($daysOff) * 24)) > 24) {
                                    echo $daysOff;
                                } else {
                                    if (intval($hoursOff) <= 1) {
                                        echo $minutesOff;
                                    } else {
                                        echo $hoursOff;
                                    }
                                }
                            //echo html for comment count and headline data
                            echo ' - <a href="" class="comments" ><i class="far fa-comment comment-icon"><span class="comment-count">&nbsp';
                            echo get_comment_count($contentId);
                            echo ' </span></i></a>
                            </div>
                            </div>
                            <div class="row">
                            <a href=""><p class="headline" id="'.$contentId.'">'.$headline.'
                            </p></a>
                        </div>
                    </div>
                    </div>
                    <hr>
                    ';
        }
    }
}

/*
*function get_latest_content, used when latest is requested
*@param $uri string (api location)
*@param $screenWidth int (used to change formating dependent on screen size)
*/
function get_latest_content($uri, $screenWidth) {
    //get contents from uri
    $json_api_data = file_get_contents($uri);
    //decode json into array
    $array_api_data = json_decode($json_api_data, true);
    //set markers for pagination
    $pagination_markers = array_splice($array_api_data,0, 2);
    //set and format current timestamp
    $nowDate = date("Y-m-d H:i:s");
    $now = new DateTime($nowDate);
    
    foreach ($array_api_data as $array_data) {
        foreach($array_data as $array) {
            //get publish date
            $date = new DateTime($array['metadata']['publishDate']);
            //set short name 
            $contentId = $array['contentId'];
            //get the difference between now and publish date
            $interval = $now->diff($date);
            //set hours off from $interval
            $hoursOff = $interval->format('%hh');
            //set mninutes off from $interval
            $minutesOff = $interval->format('%im');
            //set hours off from $interval
            if (intval($hoursOff) <= 1) {
                $hoursOff = $interval->format('%hh');
            }
            //set days off from $interval
            $daysOff = $interval->format('%dd');
            //format date
            $dateDisplay = $date->format('M jS, Y');
            //check if content type is video
            if ($array['contentType'] == 'video') {
                
                    //set short names
                    $headline = $array['metadata']['title'];
                    $duration = $array['metadata']['duration'];
                //trim duration to minutes:seconds
                    $video_length = ltrim(gmdate("i:s",$duration),0);
                //check if screen width less 991px
                if ($screenWidth <= 991) {
                     //echo html content for screen size
                     echo '
                     <div class="row video feed-content-row">
                        <div class="col-6 image-section">
                            <a href=""><div class="thumbnail-container"><img class="thumbnail" src="'.$array['thumbnails'][0]['url'].'" alt="'.clean($headline).'" style="max-width: 306 !important;"><div class="duration-image"><i class="fas fa-play-circle"></i><span class="duration">'.$video_length.'</span></div>
                            </img></div></a>
                        </div>';
                } else {
                    //echo html content for screen size
                    echo '
                     <div class="row video feed-content-row">
                        <div class="col-sm-4 image-section">
                            <a href=""><div class="thumbnail-container"><img class="thumbnail" src="'.$array['thumbnails'][0]['url'].'" alt="'.clean($headline).'" style="max-width: 306 !important;"><div class="duration-image"><i class="fas fa-play-circle"></i><span class="duration">'.$video_length.'</span></div>
                            </img></div></a>
                        </div>';
                }
                
            } else {
                //if content is article set headling
                $headline = $array['metadata']['headline'];
                //check screen size for articles
                //echo html
                if ($screenWidth <= 991) { 
                    //echo html
                    echo '
                     <div class="row article feed-content-row">
                        <div class="col-6 image-section">
                            <a href=""><div class="thumbnail-container"><img class="thumbnail" src="'.$array['thumbnails'][0]['url'].'" alt="'.clean($headline).'" style="max-width: 306 !important;"></img></div></a>
                        </div>';
                } else {
                    //echo html
                    echo '
                     <div class="row article feed-content-row">
                        <div class="col-sm-4 image-section">
                            <a href=""><div class="thumbnail-container"><img class="thumbnail" src="'.$array['thumbnails'][0]['url'].'" alt="'.clean($headline).'" style="max-width: 306 !important;"></img></div></a>
                        </div>';
                }
            }
                //check screen size
                if ($screenWidth <= 991) { 
                    echo '
                    <div class="col-6 headline-section">
                            <div class="row">
                                <div class="date-stamp">';
                                //set time interval and display
                                if ((intval($hoursOff)+(intval($daysOff) * 24)) > 24) {
                                        echo $daysOff;
                                    } else {
                                        if (intval($hoursOff) <= 1) {
                                            echo $minutesOff;
                                        } else {
                                            echo $hoursOff;
                                        }
                                    }
                                //echo html for comment count and headline data
                                echo ' - <a href="" class="comments" ><i class="far fa-comment comment-icon"><span class="comment-count">&nbsp';
                                echo get_comment_count($contentId);
                                echo ' </span></i></a>
                                </div>
                                </div>
                                <div class="row">
                                <a href=""><p class="headline" id="'.$contentId.'">'.$headline.'
                                </p></a>
                            </div>
                        </div>
                        </div>
                        <hr>
                        ';
                } else {
                    echo '
                    <div class="col-sm-4 headline-section">
                            <div class="row">
                                <div class="date-stamp">';
                                //set time interval and display
                                if ((intval($hoursOff)+(intval($daysOff) * 24)) > 24) {
                                        echo $daysOff;
                                    } else {
                                        if (intval($hoursOff) <= 1) {
                                            echo $minutesOff;
                                        } else {
                                            echo $hoursOff;
                                        }
                                    }
                                //echo html for comment count and headline data
                                echo ' - <a href="" class="comments" ><i class="far fa-comment comment-icon"><span class="comment-count">&nbsp';
                                echo get_comment_count($contentId);
                                echo ' </span></i></a>
                                </div>
                                </div>
                                <div class="row">
                                <a href=""><p class="headline" id="'.$contentId.'">'.$headline.'
                                </p></a>
                            </div>
                        </div>
                        </div>
                        <hr>
                        ';
                }
        }
    }
}

/*
*function get_article_content, used when article is requested
*@param $uri string (api location)
*@param $screenWidth int (used to change formating dependent on screen size)
*/
function get_article_content($uri, $screenWidth) {
    $json_api_data = file_get_contents($uri);
    $array_api_data = json_decode($json_api_data, true);
    $pagination_markers = array_splice($array_api_data,0, 2);
    $nowDate = date("Y-m-d H:i:s");
    $now = new DateTime($nowDate);
    
    foreach ($array_api_data as $array_data) {
        foreach($array_data as $array) {
            $date = new DateTime($array['metadata']['publishDate']);
            $contentId = $array['contentId'];
            $interval = $now->diff($date);
            $hoursOff = $interval->format('%hh');
            $minutesOff = $interval->format('%im');
            if (intval($hoursOff) <= 1) {
                $hoursOff = $interval->format('%hh');
            }
            $daysOff = $interval->format('%dd');
            $dateDisplay = $date->format('M jS, Y');
            
            if ($array['contentType'] != 'video') {
                
                if ($screenWidth <= 991) {
                    $headline = $array['metadata']['headline'];

                echo '
                 <div class="row article feed-content-row">
                    <div class="col-6 image-section">
                        <a href=""><div class="thumbnail-container"><img class="thumbnail" src="'.$array['thumbnails'][0]['url'].'" alt="'.clean($headline).'" style="max-width: 306 !important;"></img></div></a>
                    </div>';
                echo '
                <div class="col-6 headline-section">
                        <div class="row">
                            <div class="date-stamp">';
                            if ((intval($hoursOff)+(intval($daysOff) * 24)) > 24) {
                                    echo $daysOff;
                                } else {
                                    if (intval($hoursOff) <= 1) {
                                        echo $minutesOff;
                                    } else {
                                        echo $hoursOff;
                                    }
                                }

                            echo ' - <a href="" class="comments" ><i class="far fa-comment comment-icon"><span class="comment-count">&nbsp';
                            echo get_comment_count($contentId);
                            echo ' </span></i></a>
                            </div>
                            </div>
                            <div class="row">
                            <a href=""><p class="headline" id="'.$contentId.'">'.$headline.'
                            </p></a>
                        </div>
                    </div>
                    </div>
                    <hr>
                    ';
                } else {

                    $headline = $array['metadata']['headline'];

                    echo '
                     <div class="row article feed-content-row">
                        <div class="col-sm-4 image-section">
                            <a href=""><div class="thumbnail-container"><img class="thumbnail" src="'.$array['thumbnails'][0]['url'].'" alt="'.clean($headline).'" style="max-width: 306 !important;"></img></div></a>
                        </div>';
                    echo '
                    <div class="col-sm-4 headline-section">
                            <div class="row">
                                <div class="date-stamp">';
                                if ((intval($hoursOff)+(intval($daysOff) * 24)) > 24) {
                                        echo $daysOff;
                                    } else {
                                        if (intval($hoursOff) <= 1) {
                                            echo $minutesOff;
                                        } else {
                                            echo $hoursOff;
                                        }
                                    }

                                echo ' - <a href="" class="comments" ><i class="far fa-comment comment-icon"><span class="comment-count">&nbsp';
                                echo get_comment_count($contentId);
                                echo ' </span></i></a>
                                </div>
                                </div>
                                <div class="row">
                                <a href=""><p class="headline" id="'.$contentId.'">'.$headline.'
                                </p></a>
                            </div>
                        </div>
                        </div>
                        <hr>
                        ';
                }
            }
        }
    }
}

/*
*function get_video_content, used when video is requested
*@param $uri string (api location)
*@param $screenWidth int (used to change formating dependent on screen size)
*/
function get_video_content($uri, $screenWidth) {
    $json_api_data = file_get_contents($uri);
    $array_api_data = json_decode($json_api_data, true);
    $pagination_markers = array_splice($array_api_data,0, 2);
    $nowDate = date("Y-m-d H:i:s");
    $now = new DateTime($nowDate);
    
    foreach ($array_api_data as $array_data) {
        foreach($array_data as $array) {
            $date = new DateTime($array['metadata']['publishDate']);
            $contentId = $array['contentId'];
            $interval = $now->diff($date);
            $hoursOff = $interval->format('%hh');
            $minutesOff = $interval->format('%im');
            if (intval($hoursOff) <= 1) {
                $hoursOff = $interval->format('%hh');
            }
            $daysOff = $interval->format('%dd');
            $dateDisplay = $date->format('M jS, Y');
            if ($array['contentType'] == 'video') {
                $headline = $array['metadata']['title'];
                $duration = $array['metadata']['duration'];
                $video_length = ltrim(gmdate("i:s",$duration),0);
                if ($screenWidth <= 991) { 
                    echo '
                 <div class="row video feed-content-row">
                    <div class="col-6 image-section">
                        <a href=""><div class="thumbnail-container"><img class="thumbnail" src="'.$array['thumbnails'][0]['url'].'" alt="'.$headline.'" style="max-width: 306 !important;"><div class="duration-image"><i class="fas fa-play-circle"></i><span class="duration">'.$video_length.'</span></div>
                        </img></div></a>
                    </div>';
                    echo '
                <div class="col-6 headline-section">
                        <div class="row">
                            <div class="date-stamp">';
                            if ((intval($hoursOff)+(intval($daysOff) * 24)) > 24) {
                                    echo $daysOff;
                                } else {
                                    if (intval($hoursOff) <= 1) {
                                        echo $minutesOff;
                                    } else {
                                        echo $hoursOff;
                                    }
                                }

                            echo ' - <a href="" class="comments" ><i class="far fa-comment comment-icon"><span class="comment-count">&nbsp';
                            echo get_comment_count($contentId);
                            echo ' </span></i></a>
                            </div>
                            </div>
                            <div class="row">
                            <a href=""><p class="headline" id="'.$contentId.'">'.$headline.'
                            </p></a>
                        </div>
                    </div>
                    </div>
                    <hr>
                    ';
                } else {
                 echo '
                 <div class="row video feed-content-row">
                    <div class="col-sm-4 image-section">
                        <a href=""><div class="thumbnail-container"><img class="thumbnail" src="'.$array['thumbnails'][0]['url'].'" alt="'.$headline.'" style="max-width: 306 !important;"><div class="duration-image"><i class="fas fa-play-circle"></i><span class="duration">'.$video_length.'</span></div>
                        </img></div></a>
                    </div>';
                
                 echo '
                <div class="col-sm-4 headline-section">
                        <div class="row">
                            <div class="date-stamp">';
                            if ((intval($hoursOff)+(intval($daysOff) * 24)) > 24) {
                                    echo $daysOff;
                                } else {
                                    if (intval($hoursOff) <= 1) {
                                        echo $minutesOff;
                                    } else {
                                        echo $hoursOff;
                                    }
                                }

                            echo ' - <a href="" class="comments" ><i class="far fa-comment comment-icon"><span class="comment-count">&nbsp';
                            echo get_comment_count($contentId);
                            echo ' </span></i></a>
                            </div>
                            </div>
                            <div class="row">
                            <a href=""><p class="headline" id="'.$contentId.'">'.$headline.'
                            </p></a>
                        </div>
                    </div>
                    </div>
                    <hr>
                    ';
                
                } 
            }
        }
    }
}

/*
*function get_comment_count, used to retrieve comment count from api
*@param $contentID string (used for api call)
*@return $srray_comment_data int
*/
function get_comment_count($contentID) {
    $callURI = "https://ign-apis.herokuapp.com/comments?ids=".$contentID;
    $json_comment_data = file_get_contents($callURI); 
    $array_comment_data = json_decode($json_comment_data, true);
    return $array_comment_data['content']['0']['count'];
}

?>