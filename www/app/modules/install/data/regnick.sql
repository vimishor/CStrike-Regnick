DROP TABLE IF EXISTS `{prefix}groups`;
:: split ::

CREATE TABLE `{prefix}groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `access` varchar(50) NOT NULL,
  `public` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
:: split ::

INSERT INTO `{prefix}groups` VALUES ('1', 'Default', 'r', '0');
:: split ::
UPDATE `{prefix}groups` SET `ID` = '0' WHERE (`ID`='1');
:: split ::
ALTER TABLE {prefix}groups AUTO_INCREMENT = 1;
:: split ::

DROP TABLE IF EXISTS `{prefix}options`;
:: split ::

CREATE TABLE `{prefix}options` (
  `name` varchar(64) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
:: split ::

INSERT INTO `{prefix}options` VALUES ('app_version', '2.0.3-alpha');
:: split ::
INSERT INTO `{prefix}options` VALUES ('db_version', '09072012');
:: split ::

DROP TABLE IF EXISTS `{prefix}servers`;
:: split ::
CREATE TABLE `{prefix}servers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
:: split ::

INSERT INTO `{prefix}servers` VALUES ('1', 'All servers', 'All servers');
:: split ::
UPDATE `{prefix}servers` SET `ID` = '0' WHERE (`ID`='1');
:: split ::
ALTER TABLE {prefix}servers AUTO_INCREMENT = 1;
:: split ::

DROP TABLE IF EXISTS `{prefix}users`;
:: split ::
CREATE TABLE `{prefix}users` (
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
:: split ::

INSERT INTO `{prefix}users` VALUES ('1', 'administrator', 'admin', 'admin@example.com', '1331499421', '1', 'FROM_INSTALL', 'abf');
:: split ::

DROP TABLE IF EXISTS `{prefix}users_access`;
:: split ::

CREATE TABLE `{prefix}users_access` (
  `access_ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) NOT NULL,
  `server_ID` int(11) NOT NULL,
  `group_ID` int(11) NOT NULL,
  PRIMARY KEY (`access_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
:: split ::

CREATE TABLE `{prefix}migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
:: split ::

INSERT INTO `{prefix}migrations` VALUES ('2');
:: split ::