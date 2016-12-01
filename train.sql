/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50627
Source Host           : localhost:3306
Source Database       : train

Target Server Type    : MYSQL
Target Server Version : 50627
File Encoding         : 65001

Date: 2016-11-10 23:29:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for carriages
-- ----------------------------
DROP TABLE IF EXISTS `carriages`;
CREATE TABLE `carriages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `carriageType` enum('stand','second','first','business') NOT NULL,
  `maxNum` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1601 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for carriage_group
-- ----------------------------
DROP TABLE IF EXISTS `carriage_group`;
CREATE TABLE `carriage_group` (
  `groupId` int(10) NOT NULL,
  `carriageId` int(10) NOT NULL,
  KEY `groupId` (`groupId`),
  KEY `carriageId` (`carriageId`),
  CONSTRAINT `carriageId` FOREIGN KEY (`carriageId`) REFERENCES `carriages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `groupId` FOREIGN KEY (`groupId`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for group
-- ----------------------------
DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for routes
-- ----------------------------
DROP TABLE IF EXISTS `routes`;
CREATE TABLE `routes` (
  `departStation` varchar(255) NOT NULL,
  `arriveStation` varchar(255) NOT NULL,
  `trainNo` varchar(255) NOT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `departStation` (`departStation`),
  KEY `arriveStation` (`arriveStation`),
  CONSTRAINT `arriveStation` FOREIGN KEY (`arriveStation`) REFERENCES `stations` (`station`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `departStation` FOREIGN KEY (`departStation`) REFERENCES `stations` (`station`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10207 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for schedule
-- ----------------------------
DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `trainNo` varchar(255) NOT NULL,
  `departTime` datetime(6) NOT NULL,
  `trainId` int(10) NOT NULL,
  `groupId` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `schedule_train` (`trainId`),
  KEY `schedule_group` (`groupId`),
  CONSTRAINT `schedule_group` FOREIGN KEY (`groupId`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `schedule_train` FOREIGN KEY (`trainId`) REFERENCES `trains` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1282 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for seats
-- ----------------------------
DROP TABLE IF EXISTS `seats`;
CREATE TABLE `seats` (
  `scheduleId` int(10) NOT NULL,
  `carriageId` int(10) NOT NULL,
  `seatNumber` int(10) NOT NULL,
  `stationOrder` int(10) NOT NULL,
  `state` int(10) NOT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `schedule` (`scheduleId`),
  KEY `seat_carriage` (`carriageId`),
  CONSTRAINT `schedule` FOREIGN KEY (`scheduleId`) REFERENCES `schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `seat_carriage` FOREIGN KEY (`carriageId`) REFERENCES `carriages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6239521 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for stations
-- ----------------------------
DROP TABLE IF EXISTS `stations`;
CREATE TABLE `stations` (
  `station` varchar(255) NOT NULL,
  PRIMARY KEY (`station`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tickets
-- ----------------------------
DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `departStation` varchar(255) NOT NULL,
  `arriveStation` varchar(255) NOT NULL,
  `carriageOrder` int(10) NOT NULL,
  `seatNum` int(10) NOT NULL,
  `scheduleId` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tickets_schedule` (`scheduleId`),
  KEY `tickets_departStation` (`departStation`),
  KEY `tickets_arriveStation` (`arriveStation`),
  CONSTRAINT `tickets_arriveStation` FOREIGN KEY (`arriveStation`) REFERENCES `stations` (`station`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tickets_departStation` FOREIGN KEY (`departStation`) REFERENCES `stations` (`station`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tickets_schedule` FOREIGN KEY (`scheduleId`) REFERENCES `schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for timetable
-- ----------------------------
DROP TABLE IF EXISTS `timetable`;
CREATE TABLE `timetable` (
  `trainNo` varchar(255) NOT NULL,
  `station` varchar(255) NOT NULL,
  `stationOrder` int(10) NOT NULL,
  `arriveTime` int(10) NOT NULL,
  `departTime` int(10) NOT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `station` (`station`),
  CONSTRAINT `station` FOREIGN KEY (`station`) REFERENCES `stations` (`station`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1858 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trains
-- ----------------------------
DROP TABLE IF EXISTS `trains`;
CREATE TABLE `trains` (
  `trainName` varchar(255) NOT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8;
