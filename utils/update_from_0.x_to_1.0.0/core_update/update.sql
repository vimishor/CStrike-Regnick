-- 
-- MySQL Data Transfer
-- Source Database: regnick
-- Source Table: admins
-- Spec: Update from v0.X to 1.0.0
-- 

-- --------------------------------
-- Alter table structure for admins
-- --------------------------------
ALTER TABLE {REGNICK_TABLE} modify auth VARCHAR(32) NOT NULL; 
ALTER TABLE {REGNICK_TABLE} ADD server_tag INT(2) NOT NULL default '1' AFTER email;
ALTER TABLE {REGNICK_TABLE} ADD activ INT(1) default '1' AFTER server_tag;
ALTER TABLE {REGNICK_TABLE} ADD `date` INT(11) NOT NULL default '464334000' AFTER activ;
ALTER TABLE {REGNICK_TABLE} ADD `key` VARCHAR(6) NOT NULL default 'UPDATE';

-- 
-- Gentle Software Solutions
-- CStrike Regnick
-- 