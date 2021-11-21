DROP TABLE IF EXISTS officer;
DROP TABLE IF EXISTS department;
DROP TABLE IF EXISTS eb_member;
DROP TABLE IF EXISTS link;
DROP TABLE IF EXISTS band_role;
DROP TABLE IF EXISTS band_member;
DROP TABLE IF EXISTS contact;
DROP TABLE IF EXISTS artist;
DROP TABLE IF EXISTS home;
DROP TABLE IF EXISTS about;
DROP TABLE IF EXISTS menu;

CREATE TABLE department (
	department_id INTEGER NOT NULL PRIMARY KEY,
	is_active INTEGER NOT NULL DEFAULT 1,
	order_key INTEGER,
	name TEXT NOT NULL,
	description TEXT NULL DEFAULT NULL,
	image_url TEXT NULL DEFAULT NULL
);

CREATE TABLE officer (
	officer_id INTEGER NOT NULL PRIMARY KEY,
	is_active INTEGER DEFAULT 1,
	order_key INTEGER,
	name TEXT NOT NULL,
	department_id INTEGER NOT NULL,
	position TEXT NULL DEFAULT NULL,
	FOREIGN KEY (department_id)
		REFERENCES department(department_id)
		ON DELETE RESTRICT
);

CREATE TABLE eb_member (
	eb_id INTEGER NOT NULL PRIMARY KEY,
	is_active INTEGER NOT NULL DEFAULT 1,
	order_key INTEGER,
	position_name TEXT NOT NULL,
	description TEXT NULL DEFAULT NULL,
	member_name TEXT NOT NULL,
	member_year INTEGER NOT NULL,
	member_course TEXT NOT NULL,
	image_url TEXT NULL DEFAULT NULL
);

CREATE TABLE artist (
	artist_id INTEGER NOT NULL PRIMARY KEY,
	is_active INTEGER NOT NULL DEFAULT 1,
	order_key INTEGER,
	artist_name TEXT NOT NULL,
	description TEXT NULL DEFAULT NULL,
	image_url TEXT NULL DEFAULT NULL,
	image_strip_url TEXT NULL DEFAULT NULL,
	is_band INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE band_member (
	member_id INTEGER NOT NULL PRIMARY KEY,
	is_active INTEGER NOT NULL DEFAULT 1,
	order_key INTEGER,
	member_name TEXT NOT NULL,
	band_id INTEGER NOT NULL,
	FOREIGN KEY (band_id)
		REFERENCES artist(artist_id)
		ON DELETE CASCADE
);

CREATE TABLE band_role (
	role_id INTEGER NOT NULL PRIMARY KEY,
	order_key INTEGER,
	role_name TEXT NOT NULL,
	member_id INTEGER NOT NULL,
	FOREIGN KEY (member_id)
		REFERENCES band_member(member_id)
		ON DELETE CASCADE
);

CREATE TABLE link (
	link_id INTEGER NOT NULL PRIMARY KEY,
	order_key INTEGER,
	link_url TEXT NOT NULL,
	artist_id INTEGER NOT NULL,
	FOREIGN KEY (artist_id)
		REFERENCES artist(artist_id)
		ON DELETE CASCADE
);

CREATE TABLE contact (
	contact_id INTEGER PRIMARY KEY NOT NULL,
	order_key INTEGER,
	contact_info TEXT NOT NULL,
	artist_id INTEGER NOT NULL,
	FOREIGN KEY (artist_id)
		REFERENCES artist(artist_id)
		ON DELETE CASCADE
);

CREATE TABLE home (
	slide_id INTEGER PRIMARY KEY NOT NULL,
	is_active INTEGER NOT NULL DEFAULT 1,
	order_key INTEGER,
	quote_content TEXT,
	quote_song_title TEXT,
	quote_artist TEXT,
	image_url TEXT
);

CREATE TABLE about (
	about_id INTEGER PRIMARY KEY NOT NULL,
	is_active INTEGER NOT NULL DEFAULT 1,
	header TEXT NOT NULL,
	description TEXT
);

CREATE TABLE menu (
	menu_id INTEGER PRIMARY KEY NOT NULL,
	is_active INTEGER NOT NULL DEFAULT 1,
	order_key INTEGER,
	label TEXT,
	href TEXT,
	parent_id INTEGER,
	FOREIGN KEY (parent_id)
		REFERENCES menu(menu_id)
);