/* This script is intended only for testing purposes and will insert a bunch of dummy data into 
the tables. For usersnames and passwords see the inserts below 
The password used for each user is 'password' with no quotes, encrypted using password_hash()
*/

/* 
User data
*/

INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL, ROLE) VALUES ("Student1", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "John", "Doe", "JDoe@email.com", "Student");
INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL, ROLE) VALUES ("Student2", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "Jane", "Doe", "JaDoe@email.com", "Student");
INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL, ROLE) VALUES ("Student3", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "John", "Smith", "Smithe@email.com", "Student");
INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL, ROLE) VALUES ("Admin1", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "Sammy", "Gray", "Gray@email.com", "Admin");
INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL, ROLE) VALUES ("Professor1", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "Superman", "Clark", "Clark@email.com", "Professor");
INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL, ROLE) VALUES ("Professor2", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "Bruce", "Willis", "BWilly@email.com", "Professor");

INSERT INTO Student (USER_ID, YEAR_LEVEL, GPA) VALUES (1, 3, 3.4);
INSERT INTO Student (USER_ID, YEAR_LEVEL, GPA) VALUES (2, 1, 1.9);
/*
Co-op Eval Data
*/

INSERT INTO CoopCompany (STUDENTID, NAME, ADDRESS) VALUES (1, "Company", "Address");
INSERT INTO CoopCompany (STUDENTID, NAME, ADDRESS) VALUES (2, "Company1", "Address1");
INSERT INTO CoopCompany (STUDENTID, NAME, ADDRESS) VALUES (3, "Company2", "Address2");

INSERT INTO CoopEmployee (COMPANYID, FIRSTNAME, LASTNAME, EMAIL) VALUES (1, "Bob", "TheMan", "BobTheMan@bob.com");
INSERT INTO CoopEmployee (COMPANYID, FIRSTNAME, LASTNAME, EMAIL) VALUES (2, "Bill", "Dodo", "Dodo@bob.com");
INSERT INTO CoopEmployee (COMPANYID, FIRSTNAME, LASTNAME, EMAIL) VALUES (3, "Jill", "jill", "Jill@jill.com");

INSERT INTO StudentEval (STUDENTID, COMPANYID) VALUES (1, 1);
INSERT INTO EmployeeEval (EMPLOYEEID, COMPANYID) VALUES (1, 1);

/*
Enrollment Data
*/
INSERT INTO Course (COURSE_CODE, NAME, CREDITS, MIN_GPA, AVAILABILITY) VALUES ("SWEN-344", "Web Engineering", 3, 3, 1);
INSERT INTO Course (COURSE_CODE, NAME, CREDITS, MIN_GPA, AVAILABILITY) VALUES ("SWEN-444", "Human Requirements and Design", 3, 3, 0);
INSERT INTO Course (COURSE_CODE, NAME, CREDITS, MIN_GPA, AVAILABILITY) VALUES ("BEER-101", "Beers of the World", 1, 2, 1);
INSERT INTO Course (COURSE_CODE, NAME, CREDITS, MIN_GPA, AVAILABILITY) VALUES ("WINE-101", "Wines of the World", 1 , 2, 1);
INSERT INTO Section (MAX_STUDENTS, PROFESSOR_ID, COURSE_ID, TERM_ID, CLASSROOM_ID, AVAILABILITY) VALUES (20, 1, 1, 1, 1, 1);
INSERT INTO Section (MAX_STUDENTS, PROFESSOR_ID, COURSE_ID, TERM_ID, CLASSROOM_ID, AVAILABILITY) VALUES (15, 1, 2, 1, 3, 1);

INSERT INTO Student_Section (STUDENT_ID, SECTION_ID) VALUES (1, 1);
INSERT INTO Student_Section (STUDENT_ID, SECTION_ID) VALUES (2, 1);

INSERT INTO Waitlist (SECTION_ID, STUDENT_ID, ADDED_DATE) VALUES (1, 1, 09/29/2016);
INSERT INTO Schedule (SECTION_ID, DAY_OF_WEEK, START_TIME, END_TIME) VALUES (1, "Monday", "18:00", "19:00");

/*
Book store Data
*/

/*
Facility Management Data
*/

/*
HR Data
*/

/*
Grading Data
*/
