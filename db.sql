CREATE DATABASE `watchdog` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */ /*!80016 DEFAULT ENCRYPTION='N' */

CREATE TABLE `computer` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `hostname` varchar(255) COLLATE utf8_bin NOT NULL,
 `ip` varchar(255) COLLATE utf8_bin NOT NULL,
 `description` varchar(255) COLLATE utf8_bin NOT NULL,
 `enable` int(11) NOT NULL,
 `alarm` int(11) NOT NULL,
 `warning` int(11) NOT NULL,
 `iisth` varchar(255) COLLATE utf8_bin NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin


CREATE TABLE `data` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `computer_id` int(11) NOT NULL,
 `record_type` int(11) NOT NULL,
 `value` varchar(255) COLLATE utf8_bin NOT NULL,
 `time_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100203 DEFAULT CHARSET=utf8 COLLATE=utf8_bin