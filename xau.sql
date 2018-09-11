/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : at_demo

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-09-11 17:08:04
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
  `data` varchar(1024) NOT NULL COMMENT 'JSON格式',
  `peroid` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL COMMENT '类别',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
