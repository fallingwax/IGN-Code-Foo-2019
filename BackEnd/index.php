<?php
//start session
session_start();

//check if session msg isset and assign short names
if (isset($_SESSION['msg'])) {
    $message = $_SESSION['msg'];
    $level = $_SESSION['level'];
}

//destroy session to remove msg from displaying if page needs reload
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="IGN CSV Uploader" content="Grabbing data from a csv provided by IGN and importing into MySql">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IGN CSV Uploader</title>
    <!-- Custom Style Sheets -->
    <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
    <!-- Bootstrap -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script> 
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
</head>
<body>
<div class="container">
    <h1>Upload IGN Data to MySQL</h1>
    <form name="uploadForm" id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="csvFileUpload">Select File to Upload</label>
                    <input class="form-control mb-2" type="file" name="csvFileUpload" id="csvFileUpload" accept=".csv">
                    <input class="btn btn-primary" type="submit" value="Load Data" id="submit" name="submit"> 
                </div>
            </div>
        </div>
    </form>
    <div class="message">
        <p class="d-none" id="loading-message">Uploading Data... This may take a little while</p>
        
        <?php 
        //check if message is set and display upon load
        if(isset($message)) {
            if ($level == "error") {
                echo "<p class=\"text-danger\">$message</p>";
            } else {
                echo "<p class=\"text-success\">$message</p>";
            }
        }

        ?>
    </div>
    <div class="preview"></div>
    <div class="progress" style="display:none">
        <div class="progress-bar progress-bar-striped active" role="progressbar"
            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%
        </div>
</div>
</div>
</body>
<script>
//remove display none on loading message
$('#submit').on("click", function() {
    $('#loading-message').removeClass('d-none');
})
    
</script>
</html>