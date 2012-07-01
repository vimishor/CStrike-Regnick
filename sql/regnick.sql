-- ----------------------------
-- cstrike-regnick MySQL schema
-- @version = see `db_version`
-- www.gentle.ro
-- ----------------------------
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `regnick_groups`
-- ----------------------------
DROP TABLE IF EXISTS `regnick_groups`;
CREATE TABLE `regnick_groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `access` varchar(50) NOT NULL,
  `public` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of regnick_groups
-- ----------------------------
INSERT INTO `regnick_groups` VALUES ('1', 'Default', 'z', '0');
UPDATE `regnick_groups` SET `ID` = '0' WHERE (`ID`='1');

-- ----------------------------
-- Table structure for `regnick_options`
-- ----------------------------
DROP TABLE IF EXISTS `regnick_options`;
CREATE TABLE `regnick_options` (
  `name` varchar(64) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of regnick_options
-- ----------------------------
INSERT INTO `regnick_options` VALUES ('app_version', '2.0.3-alpha');
INSERT INTO `regnick_options` VALUES ('db_version', '21062012');
INSERT INTO `regnick_options` VALUES ('export_key', '');

-- ----------------------------
-- Table structure for `regnick_servers`
-- ----------------------------
DROP TABLE IF EXISTS `regnick_servers`;
CREATE TABLE `regnick_servers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of regnick_servers
-- ----------------------------
INSERT INTO `regnick_servers` VALUES ('1', 'All servers', 'All servers');
UPDATE `regnick_servers` SET `ID` = '0' WHERE (`ID`='1');
ALTER TABLE regnick_servers AUTO_INCREMENT = 1;

-- ----------------------------
-- Table structure for `regnick_users`
-- ----------------------------
DROP TABLE IF EXISTS `regnick_users`;
CREATE TABLE `regnick_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(100) NOT NULL,
  `register_date` int(10) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `activation_key` varchar(60) NOT NULL,
  `account_flags` varchar(12) NOT NULL DEFAULT 'ab',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of regnick_users
-- ----------------------------
INSERT INTO `regnick_users` VALUES ('1', 'administrator', 'admin', 'admin@example.com', '1331499421', '1', 'FROM_INSTALL', 'abf');

-- ----------------------------
-- Table structure for `regnick_users_access`
-- ----------------------------
DROP TABLE IF EXISTS `regnick_users_access`;
CREATE TABLE `regnick_users_access` (
  `access_ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) NOT NULL,
  `server_ID` int(11) NOT NULL,
  `group_ID` int(11) NOT NULL,
  PRIMARY KEY (`access_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of regnick_users_access
-- ----------------------------
