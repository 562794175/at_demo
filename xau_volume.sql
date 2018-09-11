/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : at_demo

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-09-11 17:08:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for xau_volume
-- ----------------------------
DROP TABLE IF EXISTS `xau_volume`;
CREATE TABLE `xau_volume` (
  `id` int(11) NOT NULL,
  `begin` datetime NOT NULL,
  `end` datetime NOT NULL,
  `data` varchar(1024) NOT NULL,
  `peroid` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `xau_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
