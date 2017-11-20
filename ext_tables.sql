--
--  Table structure for table 'tx_instagramimporter_domain_model_accesstoken'
--
CREATE TABLE tx_instagramimporter_domain_model_accesstoken (
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,

	token varchar(255) DEFAULT '' NOT NULL,
	description text,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

--
--  Table structure for table 'tx_instagramimporter_domain_model_post'
--
CREATE TABLE tx_instagramimporter_domain_model_post (
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,

	hidden tinyint(1) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(1) unsigned DEFAULT '0' NOT NULL,

	id varchar(255) DEFAULT '' NOT NULL,
	created_time int(11) unsigned DEFAULT '0' NOT NULL,
	likes int(11) unsigned DEFAULT '0' NOT NULL,
	filter varchar(255) DEFAULT '' NOT NULL,
	link varchar(255) DEFAULT '' NOT NULL,
	type varchar(255) DEFAULT '' NOT NULL,

	user int(11) unsigned DEFAULT '0' NOT NULL,
	images int(11) unsigned DEFAULT '0' NOT NULL,
	location int(11) unsigned DEFAULT '0' NOT NULL,
	comments int(11) unsigned DEFAULT '0' NOT NULL,
	tags int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY instagram_id (id)
);

--
--  Table structure for table 'tx_instagramimporter_domain_model_tag'
--
CREATE TABLE 'tx_instagramimporter_domain_model_tag' (
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

--
--  Table structure for table 'tx_instagramimporter_domain_model_image'
--
CREATE TABLE 'tx_instagramimporter_domain_model_image' (
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT NULL,
	width int(11) unsigned DEFAULT '0' NOT NULL,
	height int(11) unsigned DEFAULT '0' NOT NULL,
	url varchar(255) DEFAULT NULL,

	post int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

--
--  Table structure for table 'tx_instagramimporter_domain_model_comment'
--
CREATE TABLE 'tx_instagramimporter_domain_model_comment' (
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,

	id varchar(255) DEFAULT '' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY instagram_id (id)
);

--
--  Table structure for table 'tx_instagramimporter_domain_model_location'
--
CREATE TABLE 'tx_instagramimporter_domain_model_location' (
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,

	id varchar(255) DEFAULT '' NOT NULL,
	name varchar(255) DEFAULT NULL,
	latitude decimal(24,14) DEFAULT '0.00000000000000',
	longitude decimal(24,14) DEFAULT '0.00000000000000',

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY instagram_id (id)
);

--
--  Table structure for table 'tx_instagramimporter_domain_model_user'
--
CREATE TABLE 'tx_instagramimporter_domain_model_user' (
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,

	id varchar(255) DEFAULT '' NOT NULL,
	username varchar(255) DEFAULT NULL,
	full_name varchar(255) DEFAULT NULL,
	profile_picture varchar(255) DEFAULT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY instagram_id (id)
);

--
--  Table structure for table 'tx_instagramimporter_domain_model_post_tag'
--
CREATE TABLE 'tx_instagramimporter_domain_model_post_tag' (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
