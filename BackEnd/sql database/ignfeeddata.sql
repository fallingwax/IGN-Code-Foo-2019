-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 24, 2019 at 07:06 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ignfeeddata`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_articles`
--

DROP TABLE IF EXISTS `tbl_articles`;
CREATE TABLE IF NOT EXISTS `tbl_articles` (
  `Article_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Content_id` varchar(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Headline` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Publish_date` datetime NOT NULL,
  `Slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Author_1` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Author_2` varchar(40) NOT NULL,
  `State` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Tag_1` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Tag_2` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Tag_3` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Article_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_thumbnails`
--

DROP TABLE IF EXISTS `tbl_thumbnails`;
CREATE TABLE IF NOT EXISTS `tbl_thumbnails` (
  `Thumbnail_id` int(11) NOT NULL AUTO_INCREMENT,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Content_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Thumbnail_1_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Thumbnail_1_size` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Thumbnail_1_width` int(6) NOT NULL,
  `Thumbnail_1_height` int(6) NOT NULL,
  `Thumbnail_2_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Thumbnail_2_size` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Thumbnail_2_width` int(6) NOT NULL,
  `Thumbnail_2_height` int(6) NOT NULL,
  `Thumbnail_3_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Thumbnail_3_size` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Thumbnail_3_width` int(6) NOT NULL,
  `Thumbnail_3_height` int(6) NOT NULL,
  PRIMARY KEY (`Thumbnail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_videos`
--

DROP TABLE IF EXISTS `tbl_videos`;
CREATE TABLE IF NOT EXISTS `tbl_videos` (
  `Video_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Content_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description` varchar(1024) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Publish_date` datetime NOT NULL,
  `Slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Duration` int(11) NOT NULL,
  `Video_series` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `State` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Tag_1` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Tag_2` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Tag_3` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Video_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
