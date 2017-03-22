/* This script is intended only for testing purposes and will insert a bunch of dummy student data into 
the student table. For usersnames and passwords see the inserts below 
The password used for each user is 'password' with no quotes, encrypted using password_hash()
*/

INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL) VALUES ("Student1", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "John", "Doe", "JDoe@email.com" );
INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL) VALUES ("Student2", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "Jane", "Doe", "JaDoe@email.com" );
INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL) VALUES ("Student3", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "John", "Smith", "Smithe@email.com" );
INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL) VALUES ("Student4", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "Sammy", "Gray", "Gray@email.com" );
INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL) VALUES ("Student5", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "Superman", "Clark", "Clark@email.com" );
INSERT INTO User VALUES (null, "Student6", "$2y$10$OPdL0s8h6N61JJHQIpmhGOmy9yuzi38azcjcF/pojNsnBFn0tDcKm", "Bruce", "Willis", "BWilly@email.com", 0, 0);