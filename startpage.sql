-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';

CREATE DATABASE `startpage` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `startpage`;

CREATE TABLE `links` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `link` varchar(126) NOT NULL,
  `head` varchar(126) NOT NULL,
  `category` varchar(126) NOT NULL,
  `description` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notes` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `note` longtext NOT NULL,
  `dateTime` varchar(17) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `noteSettings` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(256) NOT NULL,
  `locked` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `openweathermap` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `apiKey` varchar(256) NOT NULL,
  `city` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2017-10-09 21:35:08
