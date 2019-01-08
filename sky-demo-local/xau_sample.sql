/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-10-23 18:10:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for xau_sample
-- ----------------------------
DROP TABLE IF EXISTS `xau_sample`;
CREATE TABLE `xau_sample` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orgin_id` int(11) DEFAULT NULL,
  `sample` text CHARACTER SET utf8,
  `detail` text CHARACTER SET utf8,
  `content` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orgin_id` (`orgin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of xau_sample
-- ----------------------------
INSERT INTO `xau_sample` VALUES ('1', '1', '2+4+3|9+11+3|12+14+3|15+22+5', '{\"2+4+3\":{\"W1\":\"2\",\"W2\":\"3\",\"W3\":\"4\"},\"9+11+3\":{\"W1\":\"9\",\"W2\":\"10\",\"W3\":\"11\"},\"12+14+3\":{\"W1\":\"12\",\"W2\":\"13\",\"W3\":\"14\"},\"15+22+5\":{\"W1\":\"15,16\",\"W2\":\"17\",\"W3\":\"18,19\",\"W4\":\"20\",\"W5\":\"21,22\"}}', '[1376.98,1374.1,1375.42,1374.95,1375.24,1376.21,1376.51,1375.88,1375.11,1375.77,1376.35,1376.22,1376.23,1375.02,1375.24,1374.73,1372.93,1372.79,1371.46,1370.03,1369.54,1370.07,1369.44,1370.02,1371.94,1372.97,1371.47,1374.54,1372.24,1371.64,1372.12,1371.87,1368.32,1370.39,1370.44,1366.09,1365.66,1369.4]');
INSERT INTO `xau_sample` VALUES ('3', '2', '61+34+2|5+6+3', '3', '[1369.4,1367.73,1371.59,1371.19,1372.29,1372.54,1371.3,1371.34,1372.84,1377.94,1376.35,1375.53,1374.89,1376.31,1376.92,1377.06,1376.84,1376.75,1376.84,1374.37,1375.03,1374.63,1374.95,1374.96,1375.4,1374.85,1374.8,1375.1,1378.09,1377.66,1379.5,1380.9,1381.65,1384.09,1381.52,1382.61,1383.42,1383.75,1384.38,1384.16,1384.56]');
INSERT INTO `xau_sample` VALUES ('4', '5724', '28', '', null);
INSERT INTO `xau_sample` VALUES ('27', '5723', '1+4+3', '', null);
INSERT INTO `xau_sample` VALUES ('28', '5722', '1+3+3', '', null);
INSERT INTO `xau_sample` VALUES ('29', '5718', '2+4+3', '', null);
INSERT INTO `xau_sample` VALUES ('30', '5727', '1+3+3', '{\"1+3+3\":{\"W1\":\"1\",\"W2\":\"2\",\"W3\":\"3\"}}', '');
INSERT INTO `xau_sample` VALUES ('31', '5726', '3+5+3', '{\"3+5+3\":{\"W1\":\"3\",\"W2\":\"4\",\"W3\":\"5\"}}', '');
INSERT INTO `xau_sample` VALUES ('32', '5721', '1+8+5|9+11+3|12+14+3', '{\"1+8+5\":{\"W1\":\"1,2,3\",\"W2\":\"4\",\"W3\":\"5\",\"W4\":\"6,7\",\"W5\":\"8\"},\"9+11+3\":[],\"12+14+3\":[]}', '');
INSERT INTO `xau_sample` VALUES ('35', '5720', '1+6+3|8+14+5|15+21+5', '', null);
INSERT INTO `xau_sample` VALUES ('41', '5719', '9+14+5|16+22+7', '', null);
INSERT INTO `xau_sample` VALUES ('43', '5716', '1+3+3', '', null);
INSERT INTO `xau_sample` VALUES ('44', '5713', '2+4+3', '', null);
INSERT INTO `xau_sample` VALUES ('60', '5703', '1+5+3|8+18+7', '{\"1+5+3\":[],\"8+18+7\":[]}', null);
INSERT INTO `xau_sample` VALUES ('61', '5699', '7+11+5|13+21+5|24+27+3|28+31+3', '{\"7+11+5\":[],\"13+21+5\":[],\"24+27+3\":[],\"28+31+3\":[]}', '');
INSERT INTO `xau_sample` VALUES ('66', '5697', '1+3+3', '{\"1+3+3\":[]}', null);
