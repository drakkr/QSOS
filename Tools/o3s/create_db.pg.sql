CREATE DATABASE o3s;
\c o3s;
\set ON_ERROR_STOP on

CREATE TABLE users(
	id serial,
	login varchar(50) NOT NULL,
	pass_md5 text NOT NULL,
	mail varchar(50) NOT NULL,
	contributions int NOT NULL,
	status varchar(50) DEFAULT 'users',
	PRIMARY KEY  (id)
);

CREATE TABLE evaluations (
  id serial,
  qsosappfamily varchar(50) NOT NULL ,  
  qsosspecificformat varchar(15) NOT NULL,
  qsosappname varchar(50) NOT NULL DEFAULT 'noname' ,  
  release varchar(15) NOT NULL DEFAULT '0',
  appname varchar(80) NOT NULL DEFAULT 'noname' ,  
  language varchar(5) NOT NULL ,  
  file varchar(100) NOT NULL,
  licensedesc varchar(50) NOT NULL ,  
  creation varchar(12) DEFAULT NULL,
  validation varchar(12) DEFAULT NULL,
  sections smallint DEFAULT NULL ,  
  criteria smallint DEFAULT NULL ,  
  criteria_scorable real DEFAULT NULL ,
  criteria_scored smallint DEFAULT NULL ,  
  criteria_notscored smallint DEFAULT NULL ,  
  comments smallint DEFAULT NULL ,  
  criteria_commented real DEFAULT NULL ,
  criteria_notcommented smallint DEFAULT NULL,
  uploader varchar(50) DEFAULT NULL,
  repo varchar(20) DEFAULT NULL,
  PRIMARY KEY (id)
);
COMMENT ON COLUMN evaluations.language IS 'Language on two characters';
COMMENT ON COLUMN evaluations.licensedesc IS 'License name';
COMMENT ON COLUMN evaluations.sections IS 'Number of sections';
COMMENT ON COLUMN evaluations.criteria IS 'Total number of criteria';
COMMENT ON COLUMN evaluations.criteria_scorable IS 'Number of scorable criteria';
COMMENT ON COLUMN evaluations.criteria_scored IS 'Number of criteria actually scored';
COMMENT ON COLUMN evaluations.criteria_notscored IS 'Number of criteria not yet scored';
COMMENT ON COLUMN evaluations.comments IS 'Number of criteria that could be commented';
COMMENT ON COLUMN evaluations.criteria_commented IS 'Number of commented criteria';
COMMENT ON COLUMN evaluations.appname IS 'Long application name';
COMMENT ON COLUMN evaluations.qsosappname IS 'Short application name';
COMMENT ON COLUMN evaluations.qsosappfamily IS 'software family';

CREATE TABLE templates (
  id serial,
  qsosappfamily varchar(50) NOT NULL ,  
  qsosspecificformat varchar(15) NOT NULL ,  
  language varchar(5) NOT NULL,
  file varchar(100) NOT NULL,
  creation varchar(12) DEFAULT NULL,
  update varchar(12) DEFAULT NULL,
  uploader varchar(50) NOT NULL,
  repo varchar(20) NOT NULL,
  PRIMARY KEY (id)
);
COMMENT ON COLUMN templates.qsosspecificformat IS 'Template''s version';
COMMENT ON COLUMN templates.qsosappfamily IS 'Template''s domain name';

INSERT INTO users(login, pass_md5, status, mail, contributions) VALUES('root', '63a9f0ea7bb98050796b649e85481845', 'admin', 'root@localhost', 0);

