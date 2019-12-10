CREATE TABLE IF NOT EXISTS comments(
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
	FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE
	);

,
	CHECK(
		(CASE WHEN post_type = 'event' THEN (event_postid IS NOT NULL) END) AND
		(CASE WHEN post_type = 'user' THEN (user_postid IS NOT NULL) END) AND
		(CASE WHEN post_type = 'group' THEN (group_postid IS NOT NULL) END)	
	),


CHECK((CASE WHEN event_postid IS NULL THEN 0 ELSE 1 END + CASE WHEN user_postid IS NULL THEN 0 ELSE 1 END + CASE WHEN group_postid  IS NULL THEN 0 ELSE 1 END) = 1)

CREATE TABLE IF NOT EXISTS group_participants(user VARCHAR(30) NOT NULL, groupid INTEGER NOT NULL, PRIMARY KEY(user, groupid), FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE, FOREIGN KEY(groupid) REFERENCES groups_info(groupid) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS group_join_req(user VARCHAR(30) NOT NULL, groupid INTEGER NOT NULL, PRIMARY KEY(user, groupid), FOREIGN KEY(user) REFERENCES users_info(userid) ON UPDATE CASCADE ON DELETE CASCADE, FOREIGN KEY(groupid) REFERENCES groups_info(groupid) ON UPDATE CASCADE ON DELETE CASCADE
);