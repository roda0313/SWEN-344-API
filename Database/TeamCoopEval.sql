/* Team Co-op Evaluation tables */
/* GeneralTables.sql must be run before this so that foreign keys works correctly */

/* ID field must be and INTEGER and not an INT to increment correctly */
/* 
Employee object must be created BEFORE company object. Info will be supplied by the student
when creating a new company 
*/
CREATE TABLE CoopEmployee(
	ID INTEGER PRIMARY KEY,
	COMPANYID INT NOT NULL,
	FIRSTNAME TEXT,
	LASTNAME TEXT,
	EMAIL TEXT,
	DATECREATED DATE,
	LASTMODIFIED DATE,
	FOREIGN KEY(COMPANYID) REFERENCES CoopCompany(ID)
);

CREATE TRIGGER employee_date_created AFTER INSERT ON CoopEmployee
BEGIN
	UPDATE CoopEmployee SET DATECREATED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

CREATE TRIGGER employee_date_modified AFTER UPDATE ON CoopEmployee
BEGIN
	UPDATE CoopEmployee SET LASTMODIFIED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

/* ID field must be and INTEGER and not an INT to increment correctly */
CREATE TABLE CoopCompany(
	ID INTEGER PRIMARY KEY,
	STUDENTID INT NOT NULL,
	NAME TEXT NOT NULL,
	ADDRESS TEXT,
	DATECREATED DATE,
	LASTMODIFIED DATE,
	FOREIGN KEY(STUDENTID) REFERENCES User(ID)
);

CREATE TRIGGER company_date_created AFTER INSERT ON CoopCompany
BEGIN
	UPDATE CoopCompany SET DATECREATED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

CREATE TRIGGER company_date_modified AFTER UPDATE ON CoopCompany
BEGIN
	UPDATE CoopCompany SET LASTMODIFIED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

/* ID field must be and INTEGER and not an INT to increment correctly */
/* BLOB will need to be implemented in the API. Blobs simply store byte streams you put into them */
CREATE TABLE StudentEval(
	ID INTEGER PRIMARY KEY,
	STUDENTID INT NOT NULL,
	COMPANYID INT NOT NULL,
	NAME TEXT,
	EMAIL TEXT,
	ENAME TEXT,
	EEMAIL TEXT,
	POSITION TEXT,
	QUESTION1 TEXT,
	QUESTION2 TEXT,
	QUESTION3 INT,
	QUESTION4 INT,
	QUESTION5 BOOL,
	COMPLETE BOOL,
	DATECREATED DATE,
	LASTMODIFIED DATE,
	FOREIGN KEY(STUDENTID) REFERENCES User(ID),
	FOREIGN KEY(COMPANYID) REFERENCES CoopCompany(ID)
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
	EMPLOYEEID INT NOT NULL,
	COMPANYID INT NOT NULL,
	NAME TEXT,
	EMAIL TEXT,
	SNAME TEXT,
	SEMAIL TEXT,
	POSITION TEXT,
	QUESTION1 TEXT,
	QUESTION2 TEXT,
	QUESTION3 INT,
	QUESTION4 INT,
	QUESTION5 BOOL,
	COMPLETE BOOL NOT NULL,
	DATECREATED DATE,
	LASTMODIFIED DATE,
	FOREIGN KEY(EMPLOYEEID) REFERENCES Employee(ID),
	FOREIGN KEY(COMPANYID) REFERENCES CoopCompany(ID)
);

CREATE TRIGGER employee_eval_date_created AFTER INSERT ON EmployeeEval
BEGIN
	UPDATE EmployeeEval SET DATECREATED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;

CREATE TRIGGER employee_eval_date_modified AFTER UPDATE ON EmployeeEval
BEGIN
	UPDATE EmployeeEval SET LASTMODIFIED = Datetime('now') WHERE ROWID = NEW.ROWID;
END;
