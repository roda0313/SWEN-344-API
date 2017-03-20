/* General tables used by all groups */
/* ID field must be and INTEGER and not an INT to increment correctly */

/* A User is anyone with a login to the system */
/* Students should have IS_PROF = 0, IS_ADMIN = 0 */
CREATE TABLE User(
	ID INTEGER PRIMARY KEY,
	USERNAME TEXT NOT NULL,
	PASSWORD TEXT NOT NULL,
	FIRSTNAME TEXT NOT NULL,
	LASTNAME TEXT NOT NULL,
	EMAIL TEXT NOT NULL,
	IS_PROF BIT DEFAULT 0, /* If not provided, assume they are not a professor */
	IS_ADMIN BIT DEFAULT 0 /* If not provided, assume they are not an admin */
);

/* Course Table. Add fields as needed. */
/* Professor references the User ID of the Prof teaching the course. */
CREATE TABLE Course(
	ID INTEGER PRIMARY KEY,
	NAME TEXT NOT NULL,
	PROFESSOR_ID INTEGER NOT NULL,
	FOREIGN KEY(PROFESSOR_ID) REFERENCES User(ID)
);

/* Join table. Student to Course relation */
CREATE TABLE Course_User(
	COURSE_ID INTEGER NOT NULL,
	USER_ID INTEGER NOT NULL,
	FOREIGN KEY(COURSE_ID) REFERENCES Course(ID),
	FOREIGN KEY(USER_ID) REFERENCES User(ID)
);
