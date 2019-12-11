/* Worked on by
40150463	Rajat Jaiswal r_jais@encs.concordia.ca
*/

CREATE DATABASE IF NOT EXISTS krc353_2;
use krc353_2;

DROP TABLE IF EXISTS users_info;
CREATE TABLE users_info (
    userid VARCHAR(30) PRIMARY KEY NOT NULL,
    username VARCHAR(255),
    pass VARCHAR(16) NOT NULL,
    email VARCHAR(30) NOT NULL,
    UNIQUE(email)
);
INSERT INTO users_info(userid,username,pass,email)
VALUES 
('sysadmn', 'System Administrator', '12345', 'sysadmn@scc.ac.in'),
('controller', 'Controller', '12345', 'controller@scc.ac.in');

DROP TABLE IF EXISTS acc_details;
CREATE TABLE acc_details (
    accno INTEGER(16) NOT NULL,
    bankname VARCHAR(30) NOT NULL,
    accname VARCHAR(30),
    address VARCHAR(255),
    PRIMARY KEY(accno, accname)
);

DROP TABLE IF EXISTS charge_slab;
CREATE TABLE charge_slab (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    charge INTEGER(6) NOT NULL,
    num_days INTEGER(10) NOT NULL,
    num_posts INTEGER(10) NOT NULL,
    charge_post INTEGER NOT NULL,
    charge_day INTEGER NOT NULL
);
INSERT INTO charge_slab(charge, num_days, num_posts, charge_post, charge_day) VALUES(550, 5, 100, 80, 910);
INSERT INTO charge_slab(charge, num_days, num_posts, charge_post, charge_day) VALUES(400, 5, 8, 50, 85);
INSERT INTO charge_slab(charge, num_days, num_posts, charge_post, charge_day) VALUES(0, 0, 0, 0, 0);


DROP TABLE IF EXISTS events_info;
CREATE TABLE events_info (
    eventid INTEGER AUTO_INCREMENT PRIMARY KEY,
    eventname VARCHAR(255) NOT NULL,
    eventstatus TINYINT(1) NOT NULL,
    startdate DATE NOT NULL,
    enddate DATE NOT NULL,
    orgnType VARCHAR(30) NOT NULL,
    eventmanager VARCHAR(30),
    FOREIGN KEY(eventmanager) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE SET NULL,
    debitaccno INTEGER(16),
    debitbankname VARCHAR(30),
    FOREIGN KEY(debitaccno, debitbankname) REFERENCES acc_details(accno, bankname) ON UPDATE CASCADE ON DELETE SET NULL,
	time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fee INTEGER,
    charge_slab INTEGER,
    FOREIGN KEY(charge_slab) REFERENCES charge_slab(id) ON UPDATE CASCADE ON DELETE SET NULL,
    descr text
);

DROP TABLE IF EXISTS participants;
CREATE TABLE participants (
    user VARCHAR(30) NOT NULL,
    event INTEGER NOT NULL,
    PRIMARY KEY(user, event),
    FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(event) REFERENCES events_info(eventid) ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS event_join_req;
CREATE TABLE event_join_req (
    user VARCHAR(30) NOT NULL,
    event INTEGER NOT NULL,
    PRIMARY KEY(user, event),
    FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(event) REFERENCES events_info(eventid) ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
	sender VARCHAR(30) NOT NULL,
	receiver VARCHAR(30) NOT NULL,
    time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	text text NOT NULL,
	FOREIGN KEY(sender) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(receiver) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS system_emails;
CREATE TABLE system_emails (
	user VARCHAR(30),
    email VARCHAR(30) NOT NULL,
	txt text,
    time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS groups_info;
CREATE TABLE groups_info (
    groupid INTEGER AUTO_INCREMENT PRIMARY KEY,
    groupname VARCHAR(255) NOT NULL,
    description text,
    event INTEGER NOT NULL,
    groupmanager VARCHAR(30),
    FOREIGN KEY(groupmanager) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(event) REFERENCES events_info(eventid) ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS group_join_req;
CREATE TABLE group_join_req(
    user VARCHAR(30) NOT NULL,
    event INTEGER NOT NULL,
    groupid INTEGER NOT NULL,
    PRIMARY KEY(user, groupid),
    FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(event, groupid) REFERENCES groups_info(event, groupid) ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS group_participants;
CREATE TABLE group_participants(
    user VARCHAR(30) NOT NULL,
    event INTEGER NOT NULL,
    groupid INTEGER NOT NULL,
    PRIMARY KEY(user, groupid),
    FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(event, groupid) REFERENCES groups_info(event, groupid) ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS event_posts;
CREATE TABLE event_posts(
	postid INTEGER AUTO_INCREMENT PRIMARY KEY,
	content text,
	img text,
	user VARCHAR(30) NOT NULL,
	event INTEGER NOT NULL,
	time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(event) REFERENCES events_info(eventid) ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS user_posts;
CREATE TABLE user_posts(
	postid INTEGER AUTO_INCREMENT PRIMARY KEY,
	content text,
	img text,
	user VARCHAR(30) NOT NULL,
	time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS group_posts;
CREATE TABLE group_posts(
	postid INTEGER AUTO_INCREMENT PRIMARY KEY,
	content text,
	img text,
	user VARCHAR(30) NOT NULL,
	groupid INTEGER NOT NULL,
	time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(groupid) REFERENCES groups_info(groupid) ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS shared_posts;
CREATE TABLE shared_posts(
	post_type ENUM('event', 'user', 'group') NOT NULL,
	user VARCHAR(30) NOT NULL,
	    FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
	event_postid INTEGER NULL,
    	FOREIGN KEY(event_postid) REFERENCES event_posts(postid) ON UPDATE CASCADE ON DELETE CASCADE,
	user_postid INTEGER NULL,
	    FOREIGN KEY(user_postid) REFERENCES user_posts(postid) ON UPDATE CASCADE ON DELETE CASCADE,
	group_postid INTEGER NULL,
    	FOREIGN KEY(group_postid) REFERENCES group_posts(postid) ON UPDATE CASCADE ON DELETE CASCADE,
    time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    CHECK(
		(post_type = 'event' AND event_postid IS NOT NULL) OR
		(post_type = 'user' AND user_postid IS NOT NULL) OR
		(post_type = 'group' AND group_postid IS NOT NULL)	
	),
	CHECK (
      		(event_postid IS NOT NULL AND group_postid IS NULL AND user_postid IS NULL) OR
      		(event_postid IS NULL AND group_postid IS NOT NULL AND user_postid IS NULL) OR
      		(event_postid IS NULL AND group_postid IS NULL AND user_postid IS NOT NULL)
    	)
);

DROP TABLE IF EXISTS comments;
CREATE TABLE comments(
	commentid INTEGER AUTO_INCREMENT PRIMARY KEY,
	content text,
	user VARCHAR(30) NOT NULL,
	post_type ENUM('event', 'user', 'group') NOT NULL,
	event_postid INTEGER NULL,
    FOREIGN KEY(event_postid) REFERENCES event_posts(postid) ON UPDATE CASCADE ON DELETE CASCADE,
	user_postid INTEGER NULL,
    FOREIGN KEY(user_postid) REFERENCES user_posts(postid) ON UPDATE CASCADE ON DELETE CASCADE,
	group_postid INTEGER NULL,
    FOREIGN KEY(group_postid) REFERENCES group_posts(postid) ON UPDATE CASCADE ON DELETE CASCADE,
	time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
	CHECK(
		(post_type = 'event' AND event_postid IS NOT NULL) OR
		(post_type = 'user' AND user_postid IS NOT NULL) OR
		(post_type = 'group' AND group_postid IS NOT NULL)	
	),
	CHECK (
      		(event_postid IS NOT NULL AND group_postid IS NULL AND user_postid IS NULL) OR
      		(event_postid IS NULL AND group_postid IS NOT NULL AND user_postid IS NULL) OR
      		(event_postid IS NULL AND group_postid IS NULL AND user_postid IS NOT NULL)
    	)
);
