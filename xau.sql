/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : at_demo

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-09-12 16:49:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for xau
-- ----------------------------
DROP TABLE IF EXISTS `xau`;
CREATE TABLE `xau` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `begin` datetime NOT NULL,
  `end` datetime NOT NULL,
  `peroid` int(11) DEFAULT NULL,
  `data_price` varchar(1024) NOT NULL COMMENT 'JSON格式',
  `data_price_class` tinyint(5) DEFAULT NULL,
  `data_volume` varchar(1024) NOT NULL,
  `data_volume_class` tinyint(5) DEFAULT NULL,
  `data_rsi` varchar(1024) DEFAULT NULL,
  `data_rsi_class` tinyint(5) DEFAULT NULL,
  `action` tinyint(5) DEFAULT NULL,
  `action_year` varchar(10) DEFAULT NULL,
  `action_month` varchar(10) DEFAULT NULL,
  `action_day` varchar(10) DEFAULT NULL,
  `action_hour` varchar(10) DEFAULT NULL,
  `action_minute` varchar(10) DEFAULT NULL,
  `action_second` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
