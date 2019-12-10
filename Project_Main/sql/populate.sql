INSERT INTO users_info(userid,username,pass,email,orgn)
VALUES 
('sysadmn', 'System Administrator', 'admin', 'admn@gmaail.com', 'No Organization'),
('controller', 'Controller', 'control', 'control@gmail.com', 'No Organization');

INSERT INTO users_info(userid,username,pass,email,orgn)
VALUES 
('Userid1', 'Username1', 'Password1', 'user1@gmail.com' , 'Organization1'),
('Userid2', 'Username2', 'Password2', 'user2@gmail.com' , 'Organization2'),
('Userid3', 'Username3', 'Password3', 'user3@gmail.com' , 'Organization3'),
('Userid4', 'Username4', 'Password4', 'user4@gmail.com' , 'Organization4'),
('Userid5', 'Username5', 'Password5', 'user5@gmail.com' , 'Organization5'),
('Userid6', 'Username6', 'Password6', 'user6@gmail.com' , 'Organization6'),
('Userid7', 'Username7', 'Password7', 'user7@gmail.com' , 'Organization7'),
('Userid8', 'Username8', 'Password8', 'user8@gmail.com' , 'Organization8'),
('Userid9', 'Username9', 'Password9', 'user9@gmail.com' , 'Organization9'),
('Userid10', 'Username10', 'Password10', 'user10@gmail.com' , 'Organization10');


INSERT INTO acc_details (accno,bankname,accname,address)
VALUES
(1,'bankname1','accname1','address1'),
(2,'bankname2','accname2','address2'),
(3,'bankname3','accname3','address3'),
(4,'bankname4','accname4','address4'),
(5,'bankname5','accname5','address5'),
(6,'bankname6','accname6','address6');











INSERT INTO events_info(eventname,eventstatus,startdate,enddate,orgnType,debitdetail,eventmanager)
VALUES
('eventname1',1,'2020-01-01','2020-01-11','Organization1',1,'Userid1'),
('eventname2',1,'2020-01-02','2020-01-12','Organization2',2,'Userid2'),
('eventname3',1,'2020-01-03','2020-01-13','Organization3',4,'Userid3'),
('eventname4',1,'2020-01-04','2020-01-14','Organization4',5,'Userid4'),
('eventname5',1,'2020-01-05','2020-01-15','Organization5',1,'Userid5'),
('eventname6',1,'2020-01-06','2020-01-16','Organization6',4,'Userid6'),
('eventname7',1,'2020-01-07','2020-01-17','Organization7',6,'Userid7'),
('eventname8',1,'2020-01-08','2020-01-18','Organization8',1,'Userid8'),
('eventname9',1,'2020-01-09','2020-01-19','Organization9',2,'Userid8'),
('eventname10',1,'2020-01-10','2020-01-20','Organization9',6,'Userid8');

-- Not userid hosting-9,10
-- Organization not hosting-10


-- This one is long too many participants for all the events.


INSERT INTO participants
VALUES
('Userid2',1),
('Userid3',1),
('Userid4',1),
('Userid5',1),
('Userid6',1),	
('Userid7',1),
('Userid8',1),
('Userid9',1),
('Userid10',1),
('Userid1',2),
('Userid3',2),
('Userid4',2),
('Userid5',2),
('Userid6',2),
('Userid7',2),
('Userid8',2),
('Userid9',2),
('Userid10',2),
('Userid1',3),
('Userid2',3),
('Userid4',3),
('Userid5',3),
('Userid6',3),
('Userid7',3),
('Userid8',3),
('Userid9',3),
('Userid10',3),
('Userid1',4),
('Userid2',4),
('Userid3',4),
('Userid5',4),
('Userid6',4),
('Userid7',4),
('Userid8',4),
('Userid9',4),
('Userid10',4),
('Userid1',5),
('Userid2',5),
('Userid3',5),
('Userid4',5),
('Userid6',5),
('Userid7',5),
('Userid8',5),
('Userid9',5),
('Userid10',5),
('Userid1',6),
('Userid2',6),
('Userid3',6),
('Userid4',6),
('Userid5',6),
('Userid7',6),
('Userid8',6),
('Userid9',6),
('Userid10',6),
('Userid1',7),
('Userid2',7),
('Userid3',7),
('Userid4',7),
('Userid5',7),
('Userid6',7),
('Userid8',7),
('Userid9',7),
('Userid10',7),
('Userid1',8),
('Userid2',8),
('Userid3',8),
('Userid4',8),
('Userid5',8),
('Userid6',8),
('Userid7',8),
('Userid9',8),
('Userid10',8),
('Userid1',9),
('Userid2',9),
('Userid3',9),
('Userid4',9),
('Userid5',9),
('Userid6',9),
('Userid7',9),
('Userid9',9),
('Userid10',9),
('Userid1',10),
('Userid2',10),
('Userid3',10),
('Userid4',10),
('Userid5',10),
('Userid6',10),
('Userid7',10),
('Userid9',10),
('Userid10',10);

