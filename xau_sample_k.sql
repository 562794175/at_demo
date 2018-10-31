/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-10-31 15:38:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for xau_sample_k
-- ----------------------------
DROP TABLE IF EXISTS `xau_sample_k`;
CREATE TABLE `xau_sample_k` (
  `id` int(11) NOT NULL,
  `orgin_id` int(11) DEFAULT NULL,
  `sample_pos` text,
  `sample` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of xau_sample_k
-- ----------------------------
