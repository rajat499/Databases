CREATE TABLE IF NOT EXISTS users_info (
    userid VARCHAR(15) PRIMARY KEY NOT NULL,
    username VARCHAR(255),
    pass VARCHAR(16) NOT NULL,
    email VARCHAR(30),
    orgn VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS acc_details (
    accno INTEGER(16) PRIMARY KEY,
    bankname VARCHAR(30) NOT NULL,
    accname VARCHAR(30),
    address VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS events_info (
    eventid INTEGER AUTO_INCREMENT PRIMARY KEY,
    eventname VARCHAR(255) NOT NULL,
    eventstatus BOOLEAN NOT NULL,
    startdate DATE NOT NULL,
    enddate DATE NOT NULL,
    orgnType VARCHAR(30) NOT NULL,
    debitdetail INTEGER(16),
    eventmanager VARCHAR(15),
    FOREIGN KEY(eventmanager) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY(debitdetail) REFERENCES acc_details(accno) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS participants (
    user VARCHAR(15) NOT NULL,
    event INTEGER NOT NULL,
    PRIMARY KEY(user, event),
    FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(event) REFERENCES events_info(eventid) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS event_join_req (
    user VARCHAR(15) NOT NULL,
    event INTEGER NOT NULL,
    PRIMARY KEY(user, event),
    FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(event) REFERENCES events_info(eventid) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS messages (
	sender VARCHAR(30) NOT NULL,
	receiver VARCHAR(30) NOT NULL,
	text VARCHAR(255) NOT NULL,
	FOREIGN KEY(sender) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(receiver) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS event_posts(
	postid INTEGER AUTO_INCREMENT PRIMARY KEY,
	content text,
	img text,
	user VARCHAR(30) NOT NULL,
	event INTEGER NOT NULL,
	time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(event) REFERENCES events_info(eventid) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_posts(
	postid INTEGER AUTO_INCREMENT PRIMARY KEY,
	content text,
	img text,
	user VARCHAR(30) NOT NULL,
	time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS groups_info (
    groupid INTEGER AUTO_INCREMENT PRIMARY KEY,
    groupname VARCHAR(255) NOT NULL,
    description text,
    event INTEGER NOT NULL,
    groupmanager VARCHAR(30),
    FOREIGN KEY(groupmanager) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(event) REFERENCES events_info(eventid) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS group_posts(
	postid INTEGER AUTO_INCREMENT PRIMARY KEY,
	content text,
	img text,
	user VARCHAR(30) NOT NULL,
	groupid INTEGER NOT NULL,
	time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(groupid) REFERENCES groups_info(groupid) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS comments(
	commentid INTEGER AUTO_INCREMENT PRIMARY KEY,
	content text,
	user VARCHAR(30) NOT NULL,
	post_type ENUM('event', 'user', 'group') NOT NULL,
	event_postid INTEGER NULL,
	user_postid INTEGER NULL FOREIGN KEY REFERENCES user_posts(postid),
	group_postid INTEGER NULL FOREIGN KEY REFERENCES group_posts(postid),
	time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
	CHECK(
		(CASE WHEN post_type IS 'event' THEN event_postid IS NOT NULL END) AND
		(CASE WHEN post_type IS 'user' THEN user_postid IS NOT NULL END) AND
		(CASE WHEN post_type IS 'group' THEN group_postid IS NOT NULL END)	
	),
	CHECK (
      		(CASE WHEN event_postid IS NULL THEN 0 ELSE 1 END +
      		CASE WHEN user_postid IS NULL THEN 0 ELSE 1 END +
      		CASE WHEN group_postid  IS NULL THEN 0 ELSE 1 END) = 1
    	)
);

CREATE TABLE IF NOT EXISTS shared_posts(
	post_type ENUM('event', 'user', 'group') NOT NULL,
	user VARCHAR(30) NOT NULL,
	FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE,
	event_postid INTEGER NULL,
    	FOREIGN KEY(event_postid) REFERENCES event_posts(postid) ON UPDATE CASCADE ON DELETE CASCADE,
	user_postid INTEGER NULL,
	FOREIGN KEY(user_postid) REFERENCES user_posts(postid) ON UPDATE CASCADE ON DELETE CASCADE,
	group_postid INTEGER NULL,
    	FOREIGN KEY(group_postid) REFERENCES group_posts(postid) ON UPDATE CASCADE ON DELETE CASCADE
);
