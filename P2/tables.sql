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
