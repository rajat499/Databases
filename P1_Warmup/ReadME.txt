-------------------------------------------------------------
COMP353, Section F, Prof BC DESAI, Project-1
COMP353- Group 11
27771223	Soumayyah AHMED	so_ahmed@encs.concordia.ca
40012133	Florin POENARIU f_poenar@encs.concordia.ca
40024628	Avnish PATEL av_pate@encs.concordia.ca
40036565	Sadia Anowara SMITHA s_smitha@encs.concordia.ca
40150463	Rajat JAISWAL r_jais@encs.concordia.ca
-------------------------------------------------------------

This ReadME.txt contains information about all the three php files and example queries as well as other information related to this project.

Fire up https://krc353.encs.concordia.ca in your browser to see this project working. You probably need a user name and password to see it.
If you have read access to import_csv.php or tables.php then you can just look for username and password. It's the same that is used to connect to the database.

The Submission Contains 3 main files:
index.php - It is the Main page. It provides two main functionalities. One is to enter the name of the csv file with which you want to populate the database.
            Second is to view the informations stored in all the three tables i.e. Users Table, Events Table, Role of People in the Events Table.

import_csv.php - It takes a file name which should be a csv file, and creates three tables namely USERS, EVENTS, ROLES and 
                insert values in this table by reading from the CSV file. Obviously, the file has to be present on server for the parser to parse it.
                The file is read in the format specified in the assignment statement. If the formatting differs then it might display errors.
                The three tables are inter-connected via FOREIGN KEY Constraints so if you try to enter values that doesn't match the constraints then it will display error.

tables.php - A simple code to display all the data in a table. It takes the name of the table and displays all it content on the page.

Certain Examples or Sample Queries and their results:

mysql> select u.FirstName, u.LastName, e.EventName, e.EventID from USERS u, EVENTS e where
    ->         u.UserID = e.AdminUserID;
+-----------+-----------+-----------+---------+
| FirstName | LastName  | EventName | EventID |
+-----------+-----------+-----------+---------+
| Sandra    | Deamo     | C3S2E10   |       3 |
| R.        | Agrawal   | IDEAS11   |      16 |
| Shri      | Ojha      | C3S2E11   |      28 |
| Dominique | Laurent   | IDEAS12   |      33 |
| Ratvinder | Grewal    | IDEAS13   |      48 |
| Ozgur     | Ulusoy    | C3S2E12   |      50 |
| Rainer    | Unland    | C3S2E13   |      78 |
| Alfredo   | Cuzzocrea | C3S2E19   |     112 |
+-----------+-----------+-----------+---------+
8 rows in set (0.00 sec)

mysql> select u.FirstName, e.AdminUserID from USERS u, EVENTS e where 
    -> u.UserID = e.AdminUserID and
    -> e.EventID = '48';
+-----------+-------------+
| FirstName | AdminUserID |
+-----------+-------------+
| Ratvinder | 9818575     |
+-----------+-------------+
1 row in set (0.00 sec)

mysql> select e.EventName, e.EventID from ROLES r, EVENTS e where
    -> e.EventID = r.EventID and
    -> r.UserID = '2135714';
+-----------+---------+
| EventName | EventID |
+-----------+---------+
| C3S2E10   |       3 |
| IDEAS11   |      16 |
| C3S2E11   |      28 |
| IDEAS12   |      33 |
| IDEAS13   |      48 |
| C3S2E12   |      50 |
| C3S2E13   |      78 |
| C3S2E19   |     112 |
+-----------+---------+
8 rows in set (0.00 sec) 

mysql> select * from USERS;
+----------------+-------------+------------+---------+---------+
| LastName       | FirstName   | MiddleName | UserID  | Pass    |
+----------------+-------------+------------+---------+---------+
| Voigt          | Hannes      | NULL       | 1043082 | 4353857 |
| Lee            | Wookey      | NULL       | 1157581 | 2690840 |
| Deamo          | Sandra      | NULL       | 1751053 | 1643328 |
| Seguin         | Normand     | NULL       | 1940295 | 2589416 |
| Wang           | Di          | NULL       | 2135714 | 7900293 |
| Harder         | Theo        | NULL       | 3060862 | 9887425 |
| Laurent        | Dominique   | NULL       | 3143297 | 1849693 |
| Ojha           | Shri        | Kant       | 3715673 | 9364928 |
| Ulusoy         | Ozgur       | NULL       | 4433784 | 1288930 |
| Lucena         | Carlos      | Jose       | 4480757 | 8868711 |
| Lee            | Leong       | NULL       | 4569161 | 8168898 |
| Candrlic       | Sanja       | NULL       | 5526601 | 5704019 |
| Plaice         | John        | NULL       | 5528650 | 6695247 |
| Agrawal        | R.          | K.         | 5677623 | 293331  |
| Catal          | Cagatay     | NULL       | 6461563 | 6982667 |
| Leopold        | Jennifer    | L          | 6630784 | 4892302 |
| Jenkin         | Michael     | NULL       | 6797613 | 287185  |
| Kolins         | Jeevaratnam | NULL       | 6890285 | 4365687 |
| Nagano         | Kyoko       | NULL       | 7034113 | 3449164 |
| Shanker        | Udai        | NULL       | 7126196 | 7862734 |
| Savarybelanger | Olivier     | NULL       | 7137011 | 1178358 |
| Passi          | Kalpdrum    | NULL       | 781264  | 3071145 |
| Hackl          | Guenter     | NULL       | 7935081 | 6087206 |
| Unland         | Rainer      | NULL       | 8263266 | 684938  |
| Cuzzocrea      | Alfredo     | NULL       | 8634886 | 1119622 |
| Lehner         | Wolfgang    | NULL       | 8640039 | 138625  |
| Jakupovic      | Alen        | NULL       | 9547285 | 642996  |
| Collet         | Christine   | NULL       | 9693449 | 5108382 |
| Grewal         | Ratvinder   | S          | 9818575 | 3543799 |
| Espinola       | Roger       | Castillo   | 9981456 | 1834766 |
+----------------+-------------+------------+---------+---------+
30 rows in set (0.00 sec)

mysql> select * from EVENTS;
+-----------+---------+------------+------------+-------------+
| EventName | EventID | StartDate  | EndDate    | AdminUserID |
+-----------+---------+------------+------------+-------------+
| C3S2E10   |       3 | 2009-10-16 | 2010-05-21 | 1751053     |
| IDEAS11   |      16 | 2010-08-20 | 2011-09-30 | 5677623     |
| C3S2E11   |      28 | 2010-06-11 | 2011-05-18 | 3715673     |
| IDEAS12   |      33 | 2011-12-21 | 2012-08-10 | 3143297     |
| IDEAS13   |      48 | 2012-12-17 | 2013-10-12 | 9818575     |
| C3S2E12   |      50 | 2011-05-31 | 2012-06-27 | 4433784     |
| C3S2E13   |      78 | 2012-12-24 | 2013-07-12 | 8263266     |
| C3S2E19   |     112 | 2019-05-08 | NULL       | 8634886     |
+-----------+---------+------------+------------+-------------+
8 rows in set (0.00 sec)

mysql> select * from ROLES;
+---------+---------+
| UserID  | EventID |
+---------+---------+
| 2135714 |       3 |
| 3060862 |       3 |
| 7034113 |       3 |
| 781264  |       3 |
| 9547285 |       3 |
| 2135714 |      16 |
| 3060862 |      16 |
| 7034113 |      16 |
| 781264  |      16 |
| 9547285 |      16 |
| 2135714 |      28 |
| 3060862 |      28 |
| 7034113 |      28 |
| 781264  |      28 |
| 9547285 |      28 |
| 2135714 |      33 |
| 3060862 |      33 |
| 7034113 |      33 |
| 781264  |      33 |
| 9547285 |      33 |
| 2135714 |      48 |
| 3060862 |      48 |
| 7034113 |      48 |
| 781264  |      48 |
| 9547285 |      48 |
| 2135714 |      50 |
| 3060862 |      50 |
| 7034113 |      50 |
| 781264  |      50 |
| 9547285 |      50 |
| 2135714 |      78 |
| 3060862 |      78 |
| 7034113 |      78 |
| 781264  |      78 |
| 9547285 |      78 |
| 2135714 |     112 |
| 3060862 |     112 |
| 7034113 |     112 |
| 781264  |     112 |
| 9547285 |     112 |
+---------+---------+
40 rows in set (0.00 sec)

The schema of all the three tables are as shown below:

mysql> desc USERS;
+------------+-------------+------+-----+---------+-------+
| Field      | Type        | Null | Key | Default | Extra |
+------------+-------------+------+-----+---------+-------+
| LastName   | varchar(25) | YES  |     | NULL    |       |
| FirstName  | varchar(25) | YES  |     | NULL    |       |
| MiddleName | varchar(25) | YES  |     | NULL    |       |
| UserID     | varchar(15) | NO   | PRI | NULL    |       |
| Pass       | varchar(15) | NO   |     | NULL    |       |
+------------+-------------+------+-----+---------+-------+

mysql> desc EVENTS;
+-------------+-------------+------+-----+---------+-------+
| Field       | Type        | Null | Key | Default | Extra |
+-------------+-------------+------+-----+---------+-------+
| EventName   | varchar(25) | YES  |     | NULL    |       |
| EventID     | int(10)     | NO   | PRI | NULL    |       |
| StartDate   | date        | YES  |     | NULL    |       |
| EndDate     | date        | YES  |     | NULL    |       |
| AdminUserID | varchar(15) | YES  | MUL | NULL    |       |
+-------------+-------------+------+-----+---------+-------+


mysql> ROLES;
+---------+-------------+------+-----+---------+-------+
| Field   | Type        | Null | Key | Default | Extra |
+---------+-------------+------+-----+---------+-------+
| UserID  | varchar(15) | NO   | PRI | NULL    |       |
| EventID | int(10)     | NO   | PRI | NULL    |       |
+---------+-------------+------+-----+---------+-------+



In the input file given to us, the EVENTS table had two entries where AdminUserID wasn't present in USERS table,
therefore due to FOREIGN KEY Violation, these two entries were not entered in EVENTS table.

Error in query: INSERT INTO EVENTS VALUES ('IDEAS10', '4', '2009-10-16', '2010-08-18', '963482'). Cannot add or update a child row: a foreign key constraint fails
Error in query: INSERT INTO EVENTS VALUES ('C3S2E10P', '10', '2010-02-22', '2010-05-21', '546685'). Cannot add or update a child row: a foreign key constraint fails

Consequently due to these two entries not being present in EVENTS table, ROLES table entries had its own FOREIGN KEY violations while entering the values

Error in query: INSERT INTO ROLES VALUES ('2135714', '10') Cannot add or update a child row: a foreign key constraint fails
Error in query: INSERT INTO ROLES VALUES ('2135714', '4') Cannot add or update a child row: a foreign key constraint fails
Error in query: INSERT INTO ROLES VALUES ('3060862', '10') Cannot add or update a child row: a foreign key constraint fails
Error in query: INSERT INTO ROLES VALUES ('3060862', '4') Cannot add or update a child row: a foreign key constraint fails
Error in query: INSERT INTO ROLES VALUES ('7034113', '10') Cannot add or update a child row: a foreign key constraint fails
Error in query: INSERT INTO ROLES VALUES ('7034113', '4') Cannot add or update a child row: a foreign key constraint fails
Error in query: INSERT INTO ROLES VALUES ('781264', '10') Cannot add or update a child row: a foreign key constraint fails
Error in query: INSERT INTO ROLES VALUES ('781264', '4') Cannot add or update a child row: a foreign key constraint fails
Error in query: INSERT INTO ROLES VALUES ('9547285', '10') Cannot add or update a child row: a foreign key constraint fails
Error in query: INSERT INTO ROLES VALUES ('9547285', '4') Cannot add or update a child row: a foreign key constraint fails
