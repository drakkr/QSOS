CREATE DATABASE o3s;
USE o3s;

CREATE TABLE users(
	id int(11) NOT NULL auto_increment,
	login varchar(50) NOT NULL,
	pass_md5 text NOT NULL,
	mail varchar(50) NOT NULL,
	contributions int(11) NOT NULL,
	status varchar(50) DEFAULT "users",
	PRIMARY KEY  (id)
);

CREATE TABLE evaluations (
  id int(11) NOT NULL AUTO_INCREMENT,
  qsosappfamily varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'software family',
  qsosspecificformat varchar(5) CHARACTER SET utf8 NOT NULL,
  qsosappname varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT 'noname' COMMENT 'Short application name',
  `release` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  appname varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT 'noname' COMMENT 'Long application name',
  language varchar(5) CHARACTER SET utf8 NOT NULL COMMENT 'Language on two characters',
  file varchar(100) CHARACTER SET utf8 NOT NULL,
  licensedesc varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'License name',
  creation varchar(12) CHARACTER SET latin1 DEFAULT NULL,
  validation varchar(12) CHARACTER SET latin1 DEFAULT NULL,
  sections tinyint(4) DEFAULT NULL COMMENT 'Number of sections',
  criteria tinyint(4) DEFAULT NULL COMMENT 'Total number of criteria',
  criteria_scorable tinyint(4) DEFAULT NULL COMMENT 'Number of scorable criteria',
  criteria_scored tinyint(4) DEFAULT NULL COMMENT 'Number of criteria actually scored',
  criteria_notscored tinyint(4) DEFAULT NULL COMMENT 'Number of criteria not yet scored',
  comments tinyint(4) DEFAULT NULL COMMENT 'Number of criteria that could be commented',
  criteria_commented tinyint(4) DEFAULT NULL COMMENT 'Number of commented criteria',
  criteria_notcommented tinyint(4) DEFAULT NULL,
  uploader varchar(50) DEFAULT NULL,
  repo varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE templates (
  id int(11) NOT NULL AUTO_INCREMENT,
  qsosappfamily varchar(50) NOT NULL COMMENT 'Template''s domain name',
  qsosspecificformat varchar(5) NOT NULL COMMENT 'Template''s version',
  language varchar(5) NOT NULL,
  file varchar(100) NOT NULL,
  creation varchar(12) DEFAULT NULL,
  `update` varchar(12) DEFAULT NULL,
  uploader varchar(50) NOT NULL,
  repo varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO users(login, pass_md5, status) VALUES("root", "63a9f0ea7bb98050796b649e85481845", "admin");

