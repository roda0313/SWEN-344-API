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
