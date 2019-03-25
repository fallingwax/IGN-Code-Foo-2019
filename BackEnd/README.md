Back End

File List:

css/style.css
logs/
sql database/ignfeeddata.sql
uploads/
class_dbconnection.php
class_log.php
db_config.php
index.php
search.php
upload.php
codefoo.csv

This directory contains all files for the Back End requirement. 

Instructions for Uploading data to MySQL:

-MySQL Information
  -db_config.php is a storage file for saving credentials to the mysql database. It can be easily edited for a new user.
  -run ignfeeddata.sql in MySQL to create database for uploading data
  -data is stored and normalized by separating the content into three different tables.
    tbl_articles
    tbl_videos
    tbl_thumbnails
  -each table is indexed by primary key int(11) that is set to auto increment
  -santitaion is handled by using prepared statements using PDO and with cleaning up the entries during insert in the php file upload.php
  
-index.php is the main page of the project
  -you will be prompted to upload a csv file. I have included the codefoo.csv file in this directory to be uploaded
  
-upload.php handles the processing of the csv
  -class_dbconnection provides the pdo mysql connection and methods for running queries
  
Service Utilizing MySQL data

-I created a simple api to pull data from the mysql database and return it in JSON format. 
-data can be requested for either article or video with the content parameter 
-a search can be performed on the specified content with the search parameter. 
-a sample call looks like this on my local machine /localhost/BackEnd/search.php?content=videos&search=DC
-this returns any video content who's descrition contains DC

This service would be useful when trying to gather data about all articles or videos based on certain subject. In could be used to process a
search on the IGN website looking for all videos about Apex Legends. The JSON format allows easy access to the data and a standard format
for parsing through it. 
