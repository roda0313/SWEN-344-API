/* Team Co-op Evaluation tables */
/* GeneralTables.sql must be run before this so that foreign keys works correctly */

/* ID field must be and INTEGER and not an INT to increment correctly */
/* 
Employee object must be created BEFORE company object. Info will be supplied by the student
when creating a new company 
*/
CREATE TABLE Employee(
	ID INTEGER PRIMARY KEY,
	STUDENTID INT,
	FIRSTNAME TEXT,
	LASTNAME TEXT,
	EMAIL TEXT,
	DATECREATED DATE,
	LASTMODIFIED DATE,
	FOREIGN KEY(STUDENTID) REFERENCES STUDENT(ID)
);

CREATE TRIGGER employee_date_created AFTER INSERT ON Employee
BEGIN
	UPDATE Employee SET DATECREATED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

CREATE TRIGGER employee_date_modified AFTER UPDATE ON Employee
BEGIN
	UPDATE Employee SET LASTMODIFIED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

/* ID field must be and INTEGER and not an INT to increment correctly */
CREATE TABLE Company(
	ID INTEGER PRIMARY KEY,
	STUDENTID INT,
	EMPLOYEEID INT,
	NAME TEXT NOT NULL,
	ADDRESS TEXT,
	DATECREATED DATE,
	LASTMODIFIED DATE,
	FOREIGN KEY(STUDENTID) REFERENCES STUDENT(ID),
	FOREIGN KEY(EMPLOYEEID) REFERENCES Employee(ID)
);

CREATE TRIGGER company_date_created AFTER INSERT ON Company
BEGIN
	UPDATE Company SET DATECREATED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

CREATE TRIGGER company_date_modified AFTER UPDATE ON Company
BEGIN
	UPDATE Company SET LASTMODIFIED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

/* ID field must be and INTEGER and not an INT to increment correctly */
/* BLOB will need to be implemented in the API. Blobs simply store byte streams you put into them */
CREATE TABLE StudentEval(
	ID INTEGER PRIMARY KEY,
	STUDENTID INT,
	COMPANYID INT,
	DATA BLOB,
	DATECREATED DATE,
	LASTMODIFIED DATE,
	FOREIGN KEY(STUDENTID) REFERENCES STUDENT(ID),
	FOREIGN KEY(COMPANYID) REFERENCES Company(ID)
);

CREATE TRIGGER student_eval_date_created AFTER INSERT ON StudentEval
BEGIN
	UPDATE StudentEval SET DATECREATED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

CREATE TRIGGER student_eval_date_modified AFTER UPDATE ON StudentEval
BEGIN
	UPDATE StudentEval SET LASTMODIFIED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

/* ID field must be and INTEGER and not an INT to increment correctly */
/* BLOB will need to be implemented in the API. Blobs simply store byte streams you put into them */
CREATE TABLE EmployeeEval(
	ID INTEGER PRIMARY KEY,
	EMPLOYEEID INT,
	COMPANYID INT,
	DATA BLOB,
	DATECREATED DATE,
	LASTMODIFIED DATE,
	FOREIGN KEY(EMPLOYEEID) REFERENCES Employee(ID),
	FOREIGN KEY(COMPANYID) REFERENCES Company(ID)
);

CREATE TRIGGER employee_eval_date_created AFTER INSERT ON StudentEval
BEGIN
	UPDATE StudentEval SET DATECREATED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

CREATE TRIGGER employee_eval_date_modified AFTER UPDATE ON StudentEval
BEGIN
	UPDATE StudentEval SET LASTMODIFIED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;
