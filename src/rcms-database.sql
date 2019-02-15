CREATE TABLE Collection (
	id INT(11) NOT NULL AUTO_INCREMENT,
	is_displayed TINYINT(1) NOT NULL DEFAULT '1',
	name VARCHAR(60) NOT NULL,
	picture TEXT,
	PRIMARY KEY (id)
);

CREATE TABLE Element (
	name VARCHAR(60) NOT NULL,
	description TEXT,
	UNIQUE KEY name_unique (name)
);

CREATE TABLE Template (
	id INT(11) NOT NULL AUTO_INCREMENT,
	name VARCHAR(60) NOT NULL,
	file_name VARCHAR(60) NOT NULL,
	description TEXT,
	PRIMARY KEY (id)
);

CREATE TABLE Page (
	id INT(11) NOT NULL AUTO_INCREMENT,
	name VARCHAR(60) NOT NULL,
	display_name VARCHAR(60) DEFAULT NULL,
	url VARCHAR(60) NOT NULL,
	is_on_nav TINYINT(1) NOT NULL DEFAULT '0',
	nav_weight INT(11) NOT NULL DEFAULT '0',
	template INT(11) NOT NULL,
	PRIMARY KEY (id),
	KEY template (template),
	CONSTRAINT Page_ibfk_1 FOREIGN KEY (template) REFERENCES Template (id) ON UPDATE CASCADE
);

CREATE TABLE Content (
	id INT(11) NOT NULL AUTO_INCREMENT,
	page INT(11) NOT NULL,
	element VARCHAR(60) NOT NULL,
	content TEXT,
	is_searchable TINYINT(1) NOT NULL DEFAULT '0',
	is_displayed TINYINT(1) NOT NULL DEFAULT '0',
	weight INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (id),
	KEY page (page),
	KEY element (element),
	CONSTRAINT Content_ibfk_1 FOREIGN KEY (page) REFERENCES Page (id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT Content_ibfk_2 FOREIGN KEY (element) REFERENCES Element (name) ON UPDATE CASCADE
);

CREATE TABLE Doorway (
	id INT(11) NOT NULL AUTO_INCREMENT,
	url VARCHAR(60) NOT NULL,
	destination TEXT NOT NULL,
	enabled TINYINT(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (id),
	UNIQUE KEY url (url)
);

CREATE TABLE Permission (
	code CHAR(4) NOT NULL,
	display_name VARCHAR(60) NOT NULL,
	description TEXT NOT NULL,
	PRIMARY KEY (code),
	UNIQUE KEY display_name (display_name)
);

CREATE TABLE Project (
	id INT(11) NOT NULL AUTO_INCREMENT,
	collection INT(11) DEFAULT NULL,
	is_displayed TINYINT(1) NOT NULL DEFAULT '1',
	is_featured TINYINT(1) DEFAULT '0',
	name VARCHAR(60) NOT NULL,
	picture TEXT,
	content TEXT NOT NULL,
	PRIMARY KEY (id),
	KEY collection (collection),
	CONSTRAINT Project_ibfk FOREIGN KEY (collection) REFERENCES Collection(id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE Role (
	id INT(11) NOT NULL AUTO_INCREMENT,
	display_name VARCHAR(60) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE KEY display_name (display_name)
);

CREATE TABLE Role_Permission (
	role INT(11) NOT NULL,
	permission CHAR(4) NOT NULL,
	KEY role (role),
	KEY permission (permission),
	CONSTRAINT Role_Permission_ibfk_1 FOREIGN KEY (role) REFERENCES Role(id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT Role_Permission_ibfk_2 FOREIGN KEY (permission) REFERENCES Permission(code) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Setting (
	code CHAR(4) NOT NULL,
	display_name VARCHAR(60) NOT NULL,
	value TEXT NOT NULL,
	PRIMARY KEY(code)
);

CREATE TABLE Template_Element (
	template INT(11) NOT NULL,
	element VARCHAR(60) NOT NULL,
	KEY template (template),
	KEY element (element),
	CONSTRAINT Template_Element_ibfk_1 FOREIGN KEY (template) REFERENCES Template(id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT Template_Element_ibfk_2 FOREIGN KEY (element) REFERENCES Element(name) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Token (
	token CHAR(128) NOT NULL,
	user INT(11) NOT NULL,
	issue_time DATETIME NOT NULL,
	expire_time DATETIME NOT NULL,
	expired TINYINT(1) NOT NULL DEFAULT '0',
	ip_address VARCHAR(39) NOT NULL,
	PRIMARY KEY (token),
	UNIQUE KEY token (token),
	KEY user (user),
	CONSTRAINT Token_ibfk_1 FOREIGN KEY (user) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE User (
	id INT(11) NOT NULL AUTO_INCREMENT,
	username VARCHAR(64) NOT NULL,
	first_name VARCHAR(30) NOT NULL,
	last_name VARCHAR(30) NOT NULL,
	password CHAR(128) NOT NULL,
	role INT(11) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY username (username),
	KEY role (role),
	CONSTRAINT User_ibfk_1 FOREIGN KEY (role) REFERENCES Role(id) ON UPDATE CASCADE
);

-- INSERT PRE-POPULATED DATA --

INSERT INTO Permission VALUES ('bcae', 'Content Authoring/Editing', 'Author and edit content.'), ('cpan', 'Use Control Panel', 'Change site settings and manage users'), ('fmgr', 'Manage/Upload Files', 'Upload, delete, and otherwise manage files.');

INSERT INTO Role VALUES (1, 'System Operator'), (2, 'Author');
ALTER TABLE Role AUTO_INCREMENT = 3;

INSERT INTO Template(id, name, file_name, description) VALUES (1, 'Home', 'home', 'Home Page'), (2, 'Standard', 'standard', 'A standard page'), (3, 'Error', 'error', 'A page for showing error messages'), (4, 'Search', 'search', 'Search page'), (5, 'About', 'about', 'About Page'), (6, 'Projects Page', 'projects', 'Page to list all projects');
ALTER TABLE Template AUTO_INCREMENT = 8;

INSERT INTO Element VALUES ('banner', 'The image for a single-image banner'), ('banner-1', 'First image in a multi-image banner'), ('banner-2', 'Second image in a multi-image banner'), ('banner-3', 'Third image in a multi-image banner'), ('column-1', 'First column from left'), ('column-2', 'Second column from left'), ('left-sidebar', 'The left-hand sidebar'), ('main', 'The main content area'), ('site-tagline', 'The site tagline appearing on home'), ('site-title', 'The site title appearing on home');

INSERT INTO Template_Element VALUES (1, 'banner-1'), (1, 'banner-2'), (1, 'banner-3'), (1, 'site-title'), (1, 'site-tagline'), (1, 'column-1'), (1, 'column-2'), (2, 'left-sidebar'), (2, 'main'), (4, 'main');

INSERT INTO Page VALUES (1, 'Home', NULL, '', 1, 0, 1), (2, 'Not Found', NULL, 'notfound/', 0, 0, 3), (3, 'No Permission', NULL, 'noperm/', 0, 0, 3), (4, 'Search', NULL, 'search/', 0, 0, 4);
ALTER TABLE Page AUTO_INCREMENT = 5;