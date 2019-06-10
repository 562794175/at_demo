/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : atsys

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2019-06-10 16:55:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for market_1d
-- ----------------------------
DROP TABLE IF EXISTS `market_1d`;
CREATE TABLE `market_1d` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of market_1d
-- ----------------------------

-- ----------------------------
-- Table structure for market_1h
-- ----------------------------
DROP TABLE IF EXISTS `market_1h`;
CREATE TABLE `market_1h` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='市场1H价格';

-- ----------------------------
-- Records of market_1h
-- ----------------------------

-- ----------------------------
-- Table structure for market_4h
-- ----------------------------
DROP TABLE IF EXISTS `market_4h`;
CREATE TABLE `market_4h` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of market_4h
-- ----------------------------

-- ----------------------------
-- Table structure for market_record
-- ----------------------------
DROP TABLE IF EXISTS `market_record`;
CREATE TABLE `market_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(50) DEFAULT NULL COMMENT '日期',
  `time` varchar(50) DEFAULT NULL COMMENT '时间',
  `locked` int(11) DEFAULT '0' COMMENT '0未锁1锁定',
  `locked_time` int(11) DEFAULT NULL COMMENT '锁住时间戳',
  `created_time` int(11) DEFAULT NULL,
  `updated_time` int(11) DEFAULT NULL,
  `strategy_id` int(11) DEFAULT NULL,
  `strategy_ids` varchar(50) DEFAULT NULL COMMENT '命中多个策略',
  `profit` float(11,0) DEFAULT NULL COMMENT '利润',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date, time` (`date`,`time`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='市场记录';

-- ----------------------------
-- Records of market_record
-- ----------------------------
INSERT INTO `market_record` VALUES ('1', '2019-06-06', '00:00', '0', null, '1559785508', '1559785508', null, null, null);
INSERT INTO `market_record` VALUES ('2', '2019-06-07', '09:00', '0', null, '1559785553', '1559785553', null, null, null);
INSERT INTO `market_record` VALUES ('3', '2019-06-06', '03:00', '0', null, '1559820660', '1559820660', null, null, null);
INSERT INTO `market_record` VALUES ('4', '2019-06-10', '00:00', '0', null, '1560129311', '1560129311', null, null, null);

-- ----------------------------
-- Table structure for market_record_target
-- ----------------------------
DROP TABLE IF EXISTS `market_record_target`;
CREATE TABLE `market_record_target` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) DEFAULT NULL,
  `peroid` varchar(50) DEFAULT NULL,
  `state_code` int(11) DEFAULT NULL,
  `target_code` int(11) DEFAULT NULL,
  `target_items` varchar(255) DEFAULT NULL,
  `target_images` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `record_id, peroid` (`record_id`,`peroid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='市场记录';

-- ----------------------------
-- Records of market_record_target
-- ----------------------------
INSERT INTO `market_record_target` VALUES ('1', '1', '1H', '21', null, null, null);
INSERT INTO `market_record_target` VALUES ('2', '1', '4H', '31', null, null, null);
INSERT INTO `market_record_target` VALUES ('3', '1', '1D', '31', null, null, null);
INSERT INTO `market_record_target` VALUES ('4', '2', '1H', '42', null, null, null);
INSERT INTO `market_record_target` VALUES ('5', '2', '4H', '31', null, null, null);
INSERT INTO `market_record_target` VALUES ('6', '2', '1D', '11', null, null, null);
INSERT INTO `market_record_target` VALUES ('7', '3', '1H', '11', null, null, null);
INSERT INTO `market_record_target` VALUES ('8', '3', '4H', '11', null, null, null);
INSERT INTO `market_record_target` VALUES ('9', '3', '1D', '22', null, null, null);
INSERT INTO `market_record_target` VALUES ('10', '4', '1H', '31', null, null, null);
INSERT INTO `market_record_target` VALUES ('11', '4', '4H', '11', null, null, null);
INSERT INTO `market_record_target` VALUES ('12', '4', '1D', '11', null, null, null);

-- ----------------------------
-- Table structure for market_state
-- ----------------------------
DROP TABLE IF EXISTS `market_state`;
CREATE TABLE `market_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `code` int(11) DEFAULT NULL COMMENT '编号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of market_state
-- ----------------------------
INSERT INTO `market_state` VALUES ('1', 'State1', '10');
INSERT INTO `market_state` VALUES ('2', 'State2', '20');
INSERT INTO `market_state` VALUES ('3', 'State3', '30');
INSERT INTO `market_state` VALUES ('4', 'State4', '40');

-- ----------------------------
-- Table structure for market_state_target
-- ----------------------------
DROP TABLE IF EXISTS `market_state_target`;
CREATE TABLE `market_state_target` (
  `state_code` int(11) DEFAULT NULL,
  `target_code` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  UNIQUE KEY `state_code, target_code` (`state_code`,`target_code`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of market_state_target
-- ----------------------------

-- ----------------------------
-- Table structure for market_strategy
-- ----------------------------
DROP TABLE IF EXISTS `market_strategy`;
CREATE TABLE `market_strategy` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `enabled` int(11) DEFAULT NULL COMMENT '是否启用',
  `deleted` int(11) DEFAULT NULL COMMENT '是否删除',
  `action` int(11) DEFAULT NULL COMMENT '1B2S'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of market_strategy
-- ----------------------------

-- ----------------------------
-- Table structure for market_strategy_target
-- ----------------------------
DROP TABLE IF EXISTS `market_strategy_target`;
CREATE TABLE `market_strategy_target` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `peroid` varchar(50) DEFAULT NULL,
  `state_code` int(11) DEFAULT NULL,
  `target_code` int(11) DEFAULT NULL,
  `target_items` varchar(255) DEFAULT NULL,
  `strategy_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='市场记录';

-- ----------------------------
-- Records of market_strategy_target
-- ----------------------------

-- ----------------------------
-- Table structure for market_target
-- ----------------------------
DROP TABLE IF EXISTS `market_target`;
CREATE TABLE `market_target` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='市场对象';

-- ----------------------------
-- Records of market_target
-- ----------------------------
INSERT INTO `market_target` VALUES ('1', 'Section1', '111');
INSERT INTO `market_target` VALUES ('2', 'Section2', '112');
INSERT INTO `market_target` VALUES ('3', 'Section3', '113');
INSERT INTO `market_target` VALUES ('4', 'Section4', '114');
INSERT INTO `market_target` VALUES ('5', 'Section5', '115');
INSERT INTO `market_target` VALUES ('6', 'Section6', '116');
INSERT INTO `market_target` VALUES ('7', 'Section7', '117');
INSERT INTO `market_target` VALUES ('8', 'Section8', '118');
INSERT INTO `market_target` VALUES ('9', 'Section9', '119');
INSERT INTO `market_target` VALUES ('10', 'Section10', '120');
INSERT INTO `market_target` VALUES ('11', 'Section11', '121');

-- ----------------------------
-- Table structure for market_target_item
-- ----------------------------
DROP TABLE IF EXISTS `market_target_item`;
CREATE TABLE `market_target_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `group` int(11) DEFAULT NULL,
  `grouptype` int(255) DEFAULT NULL COMMENT '1radio2checkbox',
  `target_code` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='市场对象对应的项目';

-- ----------------------------
-- Records of market_target_item
-- ----------------------------
