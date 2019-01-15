/* TODO: create tables */
CREATE TABLE `accounts` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`username`	TEXT NOT NULL UNIQUE,
	`password`	TEXT NOT NULL,
	`session`	TEXT UNIQUE
);]

CREATE TABLE `images` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`image_title`	TEXT NOT NULL,
	`image_ext`	TEXT NOT NULL,
	`uploader`	TEXT NOT NULL
);

CREATE TABLE `tags` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`tag_title`	TEXT NOT NULL UNIQUE
);

CREATE TABLE `images_tags` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`tags_id`	INTEGER,
	`images_id`	INTEGER NOT NULL
);


/* TODO: initial seed data */
INSERT INTO accounts (username, password) VALUES ('user01', '$2y$10$33p2gVgF.2ofQXizv92DoOGiWNK5Np67rTOBCvJPaXGDcqCDc0.1m');
INSERT INTO accounts (username, password) VALUES ('user02', '$2y$10$kfzD1lHOlUBC0Prt6bWD9exTLAMipcpy.sd735zPvTC/.6O4.BZ8G');

INSERT INTO images (image_title, image_ext, uploader) VALUES ('boy.jpg', 'jpg', 'user01');
INSERT INTO images (image_title, image_ext, uploader) VALUES ('black_swordsman.jpg', 'jpg', 'user01');
INSERT INTO images (image_title, image_ext, uploader) VALUES ('grunbeld.jpg', 'jpg', 'user01');
INSERT INTO images (image_title, image_ext, uploader) VALUES ('zodd.jpg', 'jpg', 'user01');
INSERT INTO images (image_title, image_ext, uploader) VALUES ('mark.jpg', 'jpg', 'user01');
INSERT INTO images (image_title, image_ext, uploader) VALUES ('band_of_hawk.jpg', 'jpg', 'user02');
INSERT INTO images (image_title, image_ext, uploader) VALUES ('dark_knight.jpg', 'jpg', 'user02');
INSERT INTO images (image_title, image_ext, uploader) VALUES ('war.jpg', 'jpg', 'user02');
INSERT INTO images (image_title, image_ext, uploader) VALUES ('berserker_armor.jpg', 'jpg', 'user02');
INSERT INTO images (image_title, image_ext, uploader) VALUES ('beast_of_darkness.jpg', 'jpg', 'user02');

INSERT INTO tags (tag_title) VALUES ('moonlit boy');
INSERT INTO tags (tag_title) VALUES ('berserk');
INSERT INTO tags (tag_title) VALUES ('beach');
INSERT INTO tags (tag_title) VALUES ('zodd');
INSERT INTO tags (tag_title) VALUES ('mark');

INSERT INTO images_tags (tags_id, images_id) VALUES (1, 1);
INSERT INTO images_tags (tags_id, images_id) VALUES (2, 1);
INSERT INTO images_tags (tags_id, images_id) VALUES (3, 1);
INSERT INTO images_tags (tags_id, images_id) VALUES (2, 2);
INSERT INTO images_tags (tags_id, images_id) VALUES (2, 3);
INSERT INTO images_tags (tags_id, images_id) VALUES (2, 4);
INSERT INTO images_tags (tags_id, images_id) VALUES (4, 4);
INSERT INTO images_tags (tags_id, images_id) VALUES (2, 5);
INSERT INTO images_tags (tags_id, images_id) VALUES (5, 5);
INSERT INTO images_tags (tags_id, images_id) VALUES (2, 6);
INSERT INTO images_tags (tags_id, images_id) VALUES (2, 7);
INSERT INTO images_tags (tags_id, images_id) VALUES (2, 8);
