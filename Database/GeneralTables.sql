/* General tables used by all groups */

/* ID field must be and INTEGER and not an INT to increment correctly */
CREATE TABLE STUDENT(
	ID INTEGER PRIMARY KEY,
	USERNAME TEXT NOT NULL,
	PASSWORD TEXT NOT NULL,
	FIRSTNAME TEXT,
	LASTNAME TEXT,
	EMAIL TEXT
);

