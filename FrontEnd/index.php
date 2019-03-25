<!-- API NOTES
{"welcome_message":"Hi there! Good luck!",
 "warning":"Occasionally this API may go down for a brief period. Retry your request if you see this behavior. Catch the error if you're feeling confident :).",
 "endpoints":[
     {"methods":["GET"],
      "route":["/content"],
      "description":"Returns a list of content (articles and videos) sorted by publish date (descending) as JSON",
      "supportedParameters":
          {"startIndex":"The index of the first record to return in the response. Used for pagination. Default is 0, min is 0, max is 300",
           "count":"The number of records to return in the response. Default is 10, min is 1, max is 20.",
           "callback":"JSONP support"},
      "contentType":"application/json; charset=utf-8",
      "sampleRequest":"https://ign-apis.herokuapp.com/content?startIndex=30\u0026count=5"},
     {"methods":["GET","POST"],
      "route":["/comments"],
      "description":"Returns a list of the number of comments a piece of content has",
      "supportedParameters":
        {"ids":"ON GET: A comma delimited string of contentIds.  ON POST: A JSON array of contentIds.  Max of 20",
        "callback":"JSONP support"},
        "contentType":"application/json; charset=utf-8",
        "sampleRequest":"https://ign-apis.herokuapp.com/comments?ids=3de45473c5662f25453551a2e1cb4e6e,63a71f01cca67c9bbf5e7b6f091d551d"}]}
-->
<?php
//include functions page
include "functions.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="IGN Content Feed" content="Daily feed of video and written content from IGN">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IGN Content Feed</title>
    <!-- Custom Style Sheets -->
    <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
    <!-- Bootstrap -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
</head>
<body>
    <div class="container">
        <div class="heading align-bottom">
            <h1 class="heading-text">Latest News</h1>
            <div class="d-none select-list">
            <select class="list-group-select" name="list-group-selection">
                <option class="latest" id="latest-dropdown" tabindex="1" value="Latest">Latest</option>
                <option class="videos pt-4" id="videos-dropdown" tabindex="1" value="Videos">Videos</option>
                <option class="articles" id="articles-dropdown" tabindex="1" value="Articles">Articles</option>
            </select>
        </div>
        </div> 
        <hr>
        <div class="row">
            <div class="col-sm-3 nav-links">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item latest" id="latest" tabindex="1"><span class="icon latest" latest><i class="far fa-clock fa-lg latest"></i></span><span class="filter-text latest">Latest</span></li>
                    <li class="list-group-item videos" id="videos" tabindex="1"><span class="icon videos"><i class="fas fa-play fa-lg videos"></i></span><span class="filter-text videos">Videos</span></li>
                    <li class="list-group-item articles" id="articles" tabindex="1"><span class="icon articles"><i class="fas fa-file-alt fa-lg articles"></i></span><span class="filter-text articles">Articles</span></li>
                </ul>
            </div>         
            <div class="col-sm-9 ign-feed-content" id="load-content">
                <!--- initial load of content from api -->
                <?php get_latest_content_load("https://ign-apis.herokuapp.com/content?startIndex=0&count=10");?>
            </div>
        </div>   
    </div>
</body>
<script>

//set start index for pulling new content
var startIndex = 1;
    
$(document).ready( function() {
    //focus on the latest div on start up
    $('#latest').focus();
    
    //check screen width and change class if less thatn 991px
    if($(document).width() < 991) {
        $('.nav-links').removeClass("col-sm-3");
        $('.headline-section').removeClass("col-sm-4");
        $('.headline-section').addClass("col-6");
        $('.image-section').removeClass("col-sm-4");
        $('.image-section').addClass("col-6");
        $('.ign-feed-content').removeClass("col-sm-9");
        $('.ign-feed-content').addClass("col");
        $('.select-list').removeClass("d-none");
    } else {
        $('.nav-links').addClass("col-sm-3");
        $('.headline-section').addClass("col-sm-4");
        $('.headline-section').removeClass("col-6");
        $('.image-section').addClass("col-sm-4");
        $('.image-section').removeClass("col-6");
        $('.ign-feed-content').addClass("col-sm-9");
        $('.ign-feed-content').removeClass("col");
        $('.select-list').addClass("d-none");
    }
    
    //set click handlers
    $('.list-group-item').click( click_handler);
    $('.list-group-select').click( select_handler);

});
    
    
$(window).scroll( function() {
    var latest = $('.latest');
    var videos = $('.videos');
    var articles = $('.articles');
    //load more content when bottom of the screen has been reached
    if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight) {
//    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
       // ajax call get data from server and append to the div
        startIndex += 10;
        
        if (latest.is(":focus") || latest.is(":selected") && !$('.select-list').hasClass("d-none")) {
            loadData(startIndex);
        }
        if (videos.is(":focus") || videos.is(":selected")) {
            loadVideoData(startIndex);
        }
        if (articles.is(":focus") || articles.is(":selected")) {
            loadArticleData(startIndex);
        }
        
    }
});
    
$(window).resize( function() {
    //check screen width and change class if less than 991px on screen resize
    if($(document).width() < 991) {
        $('.nav-links').removeClass("col-sm-3");
        $('.headline-section').removeClass("col-sm-4");
        $('.headline-section').addClass("col-6");
        $('.image-section').removeClass("col-sm-4");
        $('.image-section').addClass("col-6");
        $('.ign-feed-content').removeClass("col-sm-9");
        $('.ign-feed-content').addClass("col");
        $('.select-list').removeClass("d-none");
    } else {
        $('.nav-links').addClass("col-sm-3");
        $('.headline-section').addClass("col-sm-4");
        $('.headline-section').removeClass("col-6");
        $('.image-section').addClass("col-sm-4");
        $('.image-section').removeClass("col-6");
        $('.ign-feed-content').addClass("col-sm-9");
        $('.ign-feed-content').removeClass("col");
        $('.select-list').removeClass("mt-4");
        $('.select-list').addClass("d-none");
    }
})    


//function loadData, ajax call to get_contet.php and append the returning html     
function loadData(startIndex) 
{
    $.ajax({
        url: 'get_content.php',
        type: "POST",
        dataType: "html",
        async:true,
        data: {
            startIndex: startIndex,
            screenWidth: $(document).width()
            
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        },

        success: function(data) {
            $('#load-content').append(data);
        }

    });
}

//function loadArticleData, ajax call to get_contet.php for only article informtation and append the returning html
function loadArticleData(startIndex) 
{
    $.ajax({
        url: 'get_article_content.php',
        type: "POST",
        dataType: "html",
        async:true,
        data: {
            startIndex: startIndex,
            screenWidth: $(document).width()
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        },

        success: function(data) {
            $('#load-content').append(data);
        }

    });
}

//function loadVideoData, ajax call to get_contet.php for only video informtation and append the returning html
function loadVideoData(startIndex) 
{
    $.ajax({
        url: 'get_video_content.php',
        type: "POST",
        dataType: "html",
        async:true,
        data: {
            startIndex: startIndex,
            screenWidth: $(document).width()
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        },

        success: function(data) {
            $('#load-content').append(data);
        }

    });
}
    
//set click handler for select div 
function click_handler(event) {
    var target = $(event.target);
    if (target.is('.videos')) {
        $('#load-content').empty();
        loadVideoData(0);
    }   
    if (target.is('.articles')) {
        $('#load-content').empty();
        loadArticleData(0);
    }
    if (target.is('.latest')) {
        location.reload();
    }
}  

//set click handler for dropdown select div    
function select_handler(event) {
    var target = $(event.target);
    
    target.on("change",  function(){
        if (target.val() == "Videos") {
            $('#load-content').empty();
            loadVideoData(0);
        }   
        if (target.val() == "Articles") {
            $('#load-content').empty();
            loadArticleData(0);
        }
        if (target.val() == "Latest") {
            location.reload();
        }
    }
    
    );
    
}  
    
</script>
    
</html>
