<?php

// initial result of the api
$result = "An error has occurred";

// needed globals
$errorLogFile = "errors.txt";
$databaseFile = getcwd(). "/../Database/SWEN344DB.db";

// debug switch
$sqliteDebug = true; //SET TO FALSE BEFORE OFFICIAL RELEASE

//////////////////////
//General Functions///
//////////////////////

// Switchboard to General Functions
function general_switch($getFunctions)
{
	// Define the possible general function URLs which the page can be accessed from
	$possible_function_url = array("test", "login", "createUser", "getStudent", "postStudent", "getProfessor",
					"getAdmin", "getCourse", "postCourse");
				
	if ($getFunctions)
	{
		return $possible_function_url;
	}
	
	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			case "test":
				return APITest();
			case "login":
				if (isset($_POST["username"]) && isset($_POST["password"])) 
				{
					return login($_POST["username"], $_POST["password"]);
				}
				else 
				{
					logError("loginValid ~ Required parameters were not submit correctly.");
					return FALSE;
				}
			// returns: student object
			// params: studentID
			case "getStudent":
				if (isset($_GET["studentID"]) && $_GET["studentID"] != null)
				{
					return getStudent($_GET["studentID"]);
				}
				else 
				{
					return "Missing studentID parameter";
				}
			// returns: Newly created student object
			// params: userID, yearLevel, gpa
			case "postStudent":
				if ((isset($_POST["yearLevel"]) && $_POST["yearLevel"] != null)
					&& (isset($_POST["gpa"]) && $_POST["gpa"] != null)
					&& (isset($_POST["userID"]) && $_POST["userID"] != null))
				{
					return postStudent($_POST["userID"], $_POST["yearLevel"], $_POST["gpa"]);
				}
				else {
					return "Missing parameter(s)";
				}
			// returns: professor object
			// params: professorID
			case "getProfessor":
				if (isset($_GET["professorID"]) && $_GET["professorID"] != null)
				{
					return getProfessor($_GET["professorID"]);
				}
				else {
					return "Missing professorID";
				}
			// returns: admin object
			// params: adminID
			case "getAdmin":
				if (isset($_GET["adminID"]) && $_GET["adminID"] != null)
				{
					return getAdmin($_GET["adminID"]);
				}
				else {
					return "Missing adminID parameter";
				}
			// returns: course object
			// params: courseID
			case "getCourse":
				if (isset($_GET["courseID"]) && $_GET["courseID"] != null)
				{
					return getCourse($_GET["courseID"]);
				}
				else {
					return "Missing courseID parameter";
				}
			// returns: newly created course object
			// params: courseCode, courseName, credits, minGPA
			case "postCourse":
				if ((isset($_POST["courseCode"]) && $_POST["courseCode"] != null)
					&& (isset($_POST["courseName"]) && $_POST["courseName"] != null)
					&& (isset($_POST["credits"]) && $_POST["credits"] != null)
					&& (isset($_POST["minGPA"]) && $_POST["minGPA"] != null))
				{
					return postCourse($_POST["courseCode"],
						$_POST["courseName"],
						$_POST["credits"],
						$_POST["minGPA"]
						);
				}
				else {
					return "Missing parameter(s)";
				}
				
			case "createUser":
				if (isset($_POST["username"]) &&
					isset($_POST["password"]) &&
					isset($_POST["fname"]) &&
					isset($_POST["lname"]) &&
					isset($_POST["email"]) &&
					isset($_POST["role"])
					)
					{
						return createUser($_POST["username"], 
							$_POST["password"], 
							$_POST["fname"],
							$_POST["lname"],
							$_POST["email"],
							$_POST["role"]
							);
					}
					else 
					{
						logError("createUser ~ Required parameters were not submit correctly.");
						return ("One or more parameters were not provided");
					}
		}
	}
	else
	{
		return "Function does not exist.";
	}
}
	
function APITest()
{
	return "API Connection Success!";
}

function logError($message)
{
	try 
	{
		$myfile = fopen($GLOBALS ["errorLogFile"], "a");
		fwrite($myfile, ($message . "\n"));
		fclose($myfile);
	}
	catch (Exception $exception)
	{ 
		//what should happen if this fails???
	}
}

//to decrypt this hash you NEED to use password_verify($password, $hash)
function encrypt($string)
{
	return password_hash($string, PASSWORD_DEFAULT);
}

//to create prof or admin simply use this function with the correct flags
//This also checks if username is valid and encrypts the plain text password
//returns true if successful, else false
function createUser($username, $password, $fname, $lname, $email, $role)
{
	$success = FALSE;
	
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//first check if the username already exists
		$query = $sqlite->prepare("SELECT * FROM User WHERE USERNAME=:username");
		$query->bindParam(':username', $username);		
		$result = $query->execute();
		
		if ($record = $result->fetchArray()) 
		{
			return "Username Already Exists";
		}
		
		//for varaible reuse
		$result->finalize();
		
		$query1 = $sqlite->prepare("INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL, ROLE) VALUES (:username, :password, :fname, :lname, :email, :role)");
		
		$query1->bindParam(':username', $username);		
		$query1->bindParam(':password', encrypt($password));	
		$query1->bindParam(':fname', $fname);	
		$query1->bindParam(':lname', $lname);
		$query1->bindParam(':email', $email);
		$query1->bindParam(':role', $role);
		
		$query1->execute();	
		
		// clean up any objects
		$sqlite->close();
		
		//if it gets here without throwing an error, assume success = true;
		$success = TRUE;
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
	
	return $success;
}

function login($username, $password)
{
	if (loginValid($username, $password))
	{
		try 
		{
			$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
			$sqlite->enableExceptions(true);
			
			//prepare query to protect from sql injection
			$query = $sqlite->prepare("SELECT * FROM User WHERE USERNAME=:username");
			$query->bindParam(':username', $username);		
			$result = $query->execute();
			
			
			//$sqliteResult = $sqlite->query($queryString);
			
			if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
			{
				$result->finalize();
				$sqlite->close();
				
				return $record;
			}
		
		}
		catch (Exception $exception)
		{
			if ($GLOBALS ["sqliteDebug"]) 
			{
				return $exception->getMessage();
			}
			logError($exception);
		}
	}
	else 
	{
		return null;
	}
}

//username and PLAIN TEXT password
//must submit values via POST and not GET
function loginValid($username, $password)
{
	$valid = FALSE;
	//return $GLOBALS ["databaseFile"];
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM User WHERE USERNAME=:username");
		$query->bindParam(':username', $username);		
		$result = $query->execute();
		
		
		//$sqliteResult = $sqlite->query($queryString);

		if ($record = $result->fetchArray()) 
		{
			if (password_verify($password, $record['PASSWORD']))
			{
				$valid = TRUE;
			}
		}
	
		$result->finalize();
		
		// clean up any objects
		$sqlite->close();
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
	
	return $valid;
}

function getStudent($studentID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Student WHERE USER_ID=:user_ID");
		$query->bindParam(':user_ID', $studentID);
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			$sqlite->close();
			
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function postStudent($yearLevel, $gpa)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		
		$query = $sqlite->prepare("INSERT INTO Student (YEAR_LEVEL, GPA) VALUES (:yearLevel, :gpa)");
		$query->bindParam(':yearLevel', $yearLevel);
		$query->bindParam(':gpa', $gpa);
		$result = $query->execute();
		
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function getProfessor($professorID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("SELECT * FROM Professor WHERE USER_ID=:user_ID");
		$query->bindParam(':user_ID', $professorID);
		$result = $query->execute();
		
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			$sqlite->close();
			
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function getAdmin($adminID)
{
	return "TODO";
}

function getCourse($courseID)
{
	return "TODO";
}

function postCourse($courseCode, $courseName, $credits, $gpa)
{
	return "TODO";
}

////////////////////////
//Team Based Functions//
////////////////////////

//////////////
//Book Store//
//////////////

// Switchboard to Book Store Functions
function book_store_switch($getFunctions)
{
	// Define the possible Book Store function URLs which the page can be accessed from
	$possible_function_url = array("getBook", "getSectionBook", "postBook");

	if ($getFunctions)
	{
		return $possible_function_url;
	}
	
	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			case "getBook":
				// if has params
				return getBook();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getSectionBook":
				// if has params
				return getSectionBook();
				// else
				// return "Missing " . $_GET["param-name"]
			case "postBook":
				// if has params
				return postBook();
				// else
				// return "Missing " . $_GET["param-name"]
		}
	}
	else
	{
		return "Function does not exist.";
	}
}

//Define Functions Here

function getBook()
{
	return "TODO";
}

function getSectionBook()
{
	return "TODO";
}

function postBook()
{
	return "TODO";
}

///////////////////
//Human Resources//
///////////////////

// Switchboard to Human Resources Functions
function human_resources_switch($getFunctions)
{
	// Define the possible Human Resources function URLs which the page can be accessed from
	$possible_function_url = array("test","updatePerson","updateProf","updateName", "updatePassword");

	if ($getFunctions)
	{
		return $possible_function_url;
	}

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			
            case "test":
                return testThis();

            case "updateProf":
    			if ((isset($_POST["id"]) && $_POST["id"] != null)
					&& (isset($_POST["salary"]) && $_POST["salary"] != null)
					&& (isset($_POST["title"]) && $_POST["title"] != null)
				){
                	return updateProfInfo($_POST["id"], $_POST["salary"], $_POST["title"]);
                }
                else
                {
                	return "Missing a parameter";
                }
                
            case "updatePerson":
            	if ((isset($_POST["username"]) && $_POST["username"] != null)
					&& (isset($_POST["fname"]) && $_POST["fname"] != null)
					&& (isset($_POST["lname"]) && $_POST["lname"] != null)
					&& (isset($_POST["email"]) && $_POST["email"] != null)
					&& (isset($_POST["address"]) && $_POST["address"] != null)
				){
            		return updatePersonalInfo($_POST["username"], $_POST["fname"], $_POST["lname"], $_POST["email"], $_POST["address"]);
				}
				else
                {
                	return "Missing a parameter";
                }
               
            case "updatePassword":
            	if ((isset($_POST["username"]) && $_POST["username"] != null)
					&& (isset($_POST["password"]) && $_POST["password"] != null)
				){
            		return updatePassword($_POST["username"], $_POST["password"]);
				}
				else
                {
                	return "Missing a parameter";
                }
                
            case "updateName":
            	if ((isset($_POST["username"]) && $_POST["username"] != null)
					&& (isset($_POST["fname"]) && $_POST["fname"] != null)
					&& (isset($_POST["lname"]) && $_POST["lname"] != null)
				){
                	return updateFullName($_POST["username"], $_POST["fname"], $_POST["lname"]);
                }
                else
                {
                	return "Missing a parameter";
                }
		}
	}
	else
	{
		return "Function does not exist.";
	}
}

//Define Functions Here

// Test connection for Human Resource
function testThis()
{
    return "MOO";
}

// Update First and Last name with username
// Input Parameters:
//  First name, Last name
// Main Input Parameter:
//  Username
function updateFullName($username, $fname, $lname)
{
    $success = false;
    try
    {
        // Open a connection to database
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);
        // Prevent SQL Injection
        $query = $sqlite->prepare("UPDATE User SET FIRSTNAME=:fname, LASTNAME=:lname WHERE USERNAME=:username");
        // Set variables to query
        $query->bindParam(':username',$username);
        $query->bindParam(':fname',$fname);
        $query->bindParam(':lname',$lname);
        $query->execute();
        // Clear up the connection
        $sqlite->close();
        $success = true;
    }
    catch (Exception $exception)
    {
        if ($GLOBALS ["sqliteDebug"])
        {
			return $exception->getMessage();
		}
		logError($exception);
	}
	
	return $success;
}

// Update password with username
// Input parameter:
//  Password
// Main Input Parameter to update specific user:
//  Username
function updatePassword($username, $password)
{
    $success = false;

    try
    {
        // Open a connection to database
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);
        // Prevent SQL Injection
        $query = $sqlite->prepare("UPDATE User SET PASSWORD=:password WHERE USERNAME=:username");
        // Set variables to query
        $query->bindParam(':username',$username);
        $query->bindParam(':password',encrypt($password));
        $query->execute();
        // Clear up the connection
        $sqlite->close();
        $success = true;
    }
    catch (Exception $exception)
    {
        if ($GLOBALS ["sqliteDebug"])
        {
            return $exception->getMessage();
        }
		logError($exception);
    }
    return $success;
}

// Update personal information with username
// Input parameters:
//  First name, Last name, Email, Address Phone
// Main Input Parameter to update specific user:
//  Username
function updatePersonalInfo($username, $fname, $lname, $email, $address)
{
    $success = false;
	
    try
    {
		// Open a connection to database
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);
		// Prevent SQL Injection
        $query = $sqlite->prepare("UPDATE User SET FIRSTNAME=:fname LASTNAME=:lname EMAIL=:email ADDRESS=:address WHERE USERNAME=:username");
		// Set variables to query
        $query->bindParam(':username', $username);
        $query->bindParam(':fname', $fname);
        $query->bindParam(':lname', $lname);
        $query->bindParam(':email', $email);
        $query->bindParam(':address', $address);
		
        $query->execute();
        // Clear up the connection
		$sqlite->close();
		
        $success = true;
    }
    catch (Exception $exception)
    {
        if($GLOBAL ["sqliteDebug"])
        {
            return $exception->getMessage();
        }
		logError($exception);
    }
	
    return $success;
}

// Update professional information with username
// Input parameters:
//  Salary, Title
// Main Input Parameter to update specific user:
//  ID
function updateProfInfo($id, $salary, $title)
{
    $success = false;

    try
    {
		// Open a connection to database
        $sqlite = new SQLITE($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);
		// Prevent SQL Injection
        $query = $sqlite->prepare("UPDATE UniversityEmployee SET SALARY=:salary TITLE=:title WHERE ID=:id");
		// Set variables to query
        $query->bindParam(':id', $id);
        $query->bindParam(':salary', $salary);
        $query->bindParam(':title', $title);
        $query->execute();
		// Clear up the connection
        $sqlite->close();
        $success = true;
    } 
    catch(Exception $exception)
    {
        if ($GLOBALS ["sqliteDebug"])
        {
            return $exception->getMessage();
        }
		logError($exception);
    }
	
    return $success;
}




/////////////////////////
//Facilities Management//
/////////////////////////

// Switchboard to Facilities Management Functions
function facility_management_switch($getFunctions)
{
	// Define the possible Facilities Management function URLs which the page can be accessed from
	$possible_function_url = array("getRoom");

	if ($getFunctions)
	{
		return $possible_function_url;
	}
	
	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			case "getFreeRoom":
				// if has params
				return getRoom();
				// else
				// return "Missing " . $_GET["param-name"]
		}
	}
	else
	{
		return "Function does not exist.";
	}
}

//Define Functions Here

function getFreeRoom()
{
	return "TODO";
}

//////////////////////
//Student Enrollment//
//////////////////////

// Switchboard to Student Enrollment Functions
function student_enrollment_switch($getFunctions)
{
	// Define the possible Student Enrollment function URLs which the page can be accessed from
	$possible_function_url = array("getCourseList", "toggleSection", "getSection", "getCourseSections",
					"postSection", "deleteSection", "getStudentSections", "getProfessorSections",
					"getTerms", "getTerm", "postTerm", "enrollStudent",
					"waitlistStudent", "withdrawStudent", "getSectionEnrolled", "getSectionWaitlist");
				
	if ($getFunctions)
	{
		return $possible_function_url;
	}
	
	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			// returns list of all courses in database
			// params: none
			case "getCourseList":
				return getCourseList();
			// Calls function that toggles availability of course
			// params: courseID
			case "toggleSection":
				if (isset($_POST["sectionID"]) && $_POST["sectionID"] != null)
				{
					return toggleSection($_POST["sectionID"]);
				}
				else
				{
					return "Missing sectionID";
				}
			// returns: information about desired course
			// params: sectionID
			case "getSection":
				if (isset($_GET["sectionID"]) && $_GET["sectionID"] != null)
				{
					return getSection($_GET["sectionID"]);
				}
				else
				{
					return "Missing sectionID parameter";
				}
			// returns: list of all sections of a course
			// params: courseID
			case "getCourseSections":
				if (isset($_GET["courseID"]) && $_GET["courseID"] != null)
				{
					return getCourseSections($_GET["courseID"]);
				}
				else
				{
					return "Missing courseID param";
				}
			// returns: created section object
			// params: maxStudents, professorID, courseID, termID, classroomID
			case "postSection":
				if ((isset($_POST["maxStudents"]) && $_POST["maxStudents"] != null)
					&& (isset($_POST["professorID"]) && $_POST["professorID"] != null)
					&& (isset($_POST["courseID"]) && $_POST["courseID"] != null)
					&& (isset($_POST["termID"]) && $_POST["termID"] != null)
					&& (isset($_POST["classroomID"]) && $_POST["classroomID"] != null)
				){
					return postSection($_POST["maxStudents"],
							$_POST["professorID"],
							$_POST["courseID"],
							$_POST["termID"],
							$_POST["classroomID"]);
				}
				else
				{
					return "Missing a parameter";
				}	
			// returns: deleted section object
			// params: maxStudents, professorID, courseID, termID, classroomID
			case "deleteSection":
				if ((isset($_POST["maxStudents"]) && $_POST["maxStudents"] != null)
					&& (isset($_POST["professorID"]) && $_POST["professorID"] != null)
					&& (isset($_POST["courseID"]) && $_POST["courseID"] != null)
					&& (isset($_POST["termID"]) && $_POST["termID"] != null)
					&& (isset($_POST["classroomID"]) && $_POST["classroomID"] != null)
				){
					return deleteSection($_POST["maxStudents"],
							$_POST["professorID"],
							$_POST["courseID"],
							$_POST["termID"],
							$_POST["classroomID"]);
				}
				else
				{
					return "Missing a parameter";
				}
			// returns: object array of a student's enrolled and waitlisted sections
			// params: studentID
			case "getStudentSections":
				if (isset($_GET["studentID"]) && $_GET["studentID"] != null)
				{
					return getStudentSections($_GET["studentID"]);
				}
				else
				{
					return "Missing studentID param";
				}
			// returns: object array of a professor's sections
			// params: professorID
			case "getProfessorSections":
				if (isset($_GET["professorID"]) && $_GET["professorID"] != null)
				{
					return getProfessorSections($_GET["professorID"]);
				}
				else
				{
					return "Missing professorID param";
				}
			// returns: current term
			// params: none
			case "getTerms":
				return getTerms();
			// returns: term object
			// params: termCode
			case "getTerm":
				if (isset($_GET["termCode"]) && $_GET["termCode"] != null)
				{
					return getTerm($_GET["termCode"]);
				}
				else
				{
					return "Missing termCode param";
				}
			// returns: created term object
			// params: termCode, startDate, endDate
			case "postTerm":
				if ((isset($_POST["termCode"]) && $_POST["termCode"] != null)
					&& (isset($_POST["startDate"]) && $_POST["startDate"] != null)
					&& (isset($_POST["endDate"]) && $_POST["endDate"] != null)
				){
					return postTerm($_POST["termCode"],
							$_POST["startDate"],
							$_POST["endDate"]);
				}
				else
				{
					return "Missing a parameter";
				}
			// returns: student object enrolled in section
			// params: studentID, sectionID
			case "enrollStudent":
				if ((isset($_POST["studentID"]) && $_POST["studentID"] != null)
					&& (isset($_POST["sectionID"]) && $_POST["sectionID"] != null)
				){
					return enrollStudent($_POST["studentID"], $_POST["sectionID"]);
				}
				else
				{
					return "Missing a parameter";
				}
			// returns: student object waitlisted for section
			// params: studentID, sectionID
			case "waitlistStudent":
				if ((isset($_POST["studentID"]) && $_POST["studentID"] != null)
					&& (isset($_POST["sectionID"]) && $_POST["sectionID"] != null)
				){
					return waitlistStudent($_POST["studentID"], $_POST["sectionID"]);
				}
				else
				{
					return "Missing a parameter";
				}
			// returns: student object withdrawn from section
			// params: studentID, sectionID
			case "withdrawStudent":
				if ((isset($_POST["studentID"]) && $_POST["studentID"] != null)
					&& (isset($_POST["sectionID"]) && $_POST["sectionID"] != null)
				){
					return withdrawStudent($_POST["studentID"], $_POST["sectionID"]);
				}
				else
				{
					return "Missing a parameter";
				}
			// returns: enrolled student_ids of a section
			// params: sectionID
			case "getSectionEnrolled":
				if (isset($_GET["sectionID"]) && $_GET["sectionID"] != null)
				{
					return getSectionEnrolled($_GET["sectionID"]);
				}
				else
				{
					return "Missing sectionID parameter";
				}
			// returns: waitlisted student_ids of a section
			// params: sectionID
			case "getSectionWaitlist":
				if (isset($_GET["sectionID"]) && $_GET["sectionID"] != null)
				{
					return getSectionWaitlist($_GET["sectionID"]);
				}
				else
				{
					return "Missing sectionID parameter";
				}
			
		}
	}
	else
	{
		return "Function does not exist.";
	}
}

//Student Enrollment Functions

function getCourseList()
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Course");		
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function toggleSection($sectionID)
{
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Section WHERE ID=:sectionID");
		$query->bindParam(':sectionID', $sectionID);		
		$result = $query->execute();
		
		

		if ($record = $result->fetchArray()) 
		{
			if ($record['AVAILABILITY'] == "0")
			{
				//prepare query to protect from sql injection
				$query_inner = $sqlite->prepare("UPDATE Section SET AVAILABILITY = '1' WHERE ID = sectionID;");
				$query_inner->bindParam(':sectionID', $sectionID);		
				$result_inner = $query->execute();
			}
			else
			{
				//prepare query to protect from sql injection
				$query_inner = $sqlite->prepare("UPDATE Section SET AVAILABILITY = '0' WHERE ID = sectionID;");
				$query_inner->bindParam(':sectionID', $sectionID);		
				$result_inner = $query->execute();
			}
		}
	
		$result->finalize();
		
		// clean up any objects
		$sqlite->close();
		return $record;
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function getSection($sectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Section WHERE ID=:sectionID");
		$query->bindParam(':sectionID', $sectionID);
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function getSectionEnrolled($sectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT STUDENT_ID FROM Student_Section WHERE SECTION_ID=:sectionID");
		$query->bindParam(':sectionID', $sectionID);
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function getSectionWaitlist($sectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT STUDENT_ID FROM Waitlist WHERE SECTION_ID=:sectionID");
		$query->bindParam(':sectionID', $sectionID);
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function getCourseSections($courseID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Section WHERE COURSE_ID=:courseID AND AVAILABILITY=:avail");
		$query->bindParam(':courseID', $courseID);
		$query->bindParam(':avail', 1);
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function postSection($maxStudents, $professorID, $courseID, $termID, $classroomID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//first check if the username already exists
		$query = $sqlite->prepare("INSERT INTO Section (MAX_STUDENTS, PROFESSOR_ID, COURSE_ID, TERM_ID, CLASSROOM_ID) VALUES (:max_students, :professor_id, :course_id, :term_id, :classroomID)");
		$query->bindParam(':max_students', $maxStudents);
		$query->bindParam(':professor_id', $professorID);
		$query->bindParam(':course_id', $courseID);
		$query->bindParam(':term_id', $termID);
		$query->bindParam(':classroom_id', $classroomID);
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function getStudentSections($studentID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT STUDENT_ID FROM Student_Section WHERE STUDENT_ID=:studentID");
		$query->bindParam(':studentID', $studentID);
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function getProfessorSections($professorID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Section WHERE PROFESSOR_ID=:professorID");
		$query->bindParam(':professorID', $professorID);
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function getTerms()
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Term");
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function getTerm($termCode)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Term WHERE CODE=:code");
		$query->bindParam(':code', $termCode);
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function postTerm($termCode, $startDate, $endDate)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//first check if the username already exists
		$query = $sqlite->prepare("INSERT INTO Term (CODE, START_DATE, END_DATE) VALUES (:code, :start_date, :end_date)");
		$query->bindParam(':code', $termCode);
		$query->bindParam(':start_date', $startDate);
		$query->bindParam(':end_date', $endDate);
		$result = $query->execute();
		
		//$sqliteResult = $sqlite->query($queryString);
		if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
		{
			$result->finalize();
			// clean up any objects
			$sqlite->close();
			return $record;
		}
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function enrollStudent($studentID, $sectionID)
{
	return "TODO";
}

function waitlistStudent($studentID, $sectionID)
{
	return "TODO";
}

function withdrawStudent($studentID, $sectionID)
{
	return "TODO";
}



////////////////////
//Co-op Evaluation//
////////////////////

// Switchboard to Co-op Evaluation Functions
function coop_eval_switch($getFunctions)
{
	// Define the possible Co-op Evaluation function URLs which the page can be accessed from
	$possible_function_url = array(
		"getStudentEvaluation", "addStudentEvaluation", "updateStudentEvaluation", "addCompany", "updateCompany",
		"getCompanies", "getEmployers", "updateEmployer", "addEmployer", "getEmployerEvaluation",
		"updateEmployerEvaluation", "addEmployerEvaluation", "getCoopAdvisor", "getCoopInfo"
	);

	if ($getFunctions)
	{
		return $possible_function_url;
	}
	
	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			case "getStudentEvaluation":
				if (isset($_GET['STUDENTID']) && isset($_GET['COMPANYID']))
				{
					return getStudentEvaluation($_GET["STUDENTID"], $_GET["COMPANYID"]);
				}
				else
				{
					return NULL;
				}
			case "addStudentEvaluation":
				if (isset($_POST['STUDENTID']) && isset($_POST['COMPANYID']))
				{
					return addStudentEvaluation(array(
						'studentID'=>$_POST['StudentID'],
						'companyID'=>$_POST['CompanyID'],
						'name'=>$_POST['name'],
						'email'=>$_POST['email'],
						'ename'=>$_POST['ename'],
						'eemail'=>$_POST['eemail'],
						'position'=>$_POST['position'],
						'q1'=>$_POST['q1'],
						'q2'=>$_POST['q2'],
						'q3'=>$_POST['q3'],
						'q4'=>$_POST['q4'],
						'q5'=>$_POST['q5']			
					));
				}
				else 
				{
					return NULL;
				}
			case "updateStudentEvaluation":
				if (isset($_POST['STUDENTID']) && isset($_POST['COMPANYID']))
				{
					return updateStudentEvaluation(array(
						'studentID'=>$_POST['StudentID'],
						'companyID'=>$_POST['CompanyID'],
						'name'=>$_POST['name'],
						'email'=>$_POST['email'],
						'eemail'=>$_POST['eemail'],
						'position'=>$_POST['position'],
						'q1'=>$_POST['q1'],
						'q2'=>$_POST['q2'],
						'q3'=>$_POST['q3'],
						'q4'=>$_POST['q4'],
						'q5'=>$_POST['q5']
					));
				}
				else 
				{
					return NULL;
				}
			case "getCompanies":
				if ($_GET['StudentID'])
				{
					return getCompanies();
				}
				else
				{
					return NULL;
				}
			case "addCompany":
				if ($_POST['StudentID'] && $_POST['name'])
				{
					return addCompany($_POST['StudentID'], $_POST['name'], $_POST['address']);
				}
				else 
				{
					return NULL;
				}
				
			case "updateCompany":
				if (isset($_POST['StudentID']) && isset($_POST['name']))
				{
					return updateCompany($_POST['StudentID'], $_POST['name'], $_POST['address']);
				}
				else 
				{
					return NULL;
				}
				
			case "getEmployers":
				if (isset($_GET['CompanyID']))
				{
					return getEmployer($_GET['CompanyID']);
				}
				else
				{
					return NULL;
				}
			case "updateEmployer":
				if (isset($_POST['CompanyID']) && isset($_POST['ID']))
				{
					return updateEmployer(
					$_POST['ID'], 
					$_POST['CompanyID'],
					$_POST['fname'],
					$_POST['lname'],
					$_POST['email']
					);
				}
				else
				{
					return NULL;
				}
				// return "Missing " . $_GET["param-name"]
			case "addEmployer":
				if (isset($_POST['CompanyID']))
				{
					return addEmployer(
						$_POST['CompanyID'], 
						$_POST['fname'], 
						$_POST['lname'], 
						$_POST['email']
					);
				}
				else
				{
					return NULL;
				}
				// return "Missing " . $_GET["param-name"]
			case "getEmployerEvaluation":
				if (isset($_GET['EMPLOYEEID']) && isset($_GET['COMPANYID']))
				{
					return getEmployerEvaluation($_GET["EMPLOYEEID"], $_GET["COMPANYID"]);
				}
				else
				{
					return NULL;
				}
			case "updateEmployerEvaluation":
				if (isset($_POST['EMPLOYEEID']) && isset($_POST['COMPANYID']))
				{
					return updateEmployerEvaluation(array(
						'employeeID'=>$_POST['EmployeeID'],
						'companyID'=>$_POST['CompanyID'],
						'name'=>$_POST['name'],
						'email'=>$_POST['email'],
						'sname'=>$_POST['sname'],
						'semail'=>$_POST['semail'],
						'position'=>$_POST['position'],
						'q1'=>$_POST['q1'],
						'q2'=>$_POST['q2'],
						'q3'=>$_POST['q3'],
						'q4'=>$_POST['q4'],
						'q5'=>$_POST['q5']			
					));
				}
				else 
				{
					return NULL;
				}
			case "addEmployerEvaluation":
				if (isset($_POST['EMPLOYEEID']) && isset($_POST['COMPANYID']))
				{
					return updateEmployerEvaluation(array(
						'employeeID'=>$_POST['EmployeeID'],
						'companyID'=>$_POST['CompanyID'],
						'name'=>$_POST['name'],
						'email'=>$_POST['email'],
						'sname'=>$_POST['sname'],
						'semail'=>$_POST['semail'],
						'position'=>$_POST['position'],
						'q1'=>$_POST['q1'],
						'q2'=>$_POST['q2'],
						'q3'=>$_POST['q3'],
						'q4'=>$_POST['q4'],
						'q5'=>$_POST['q5']			
					));
				}
				else 
				{
					return NULL;
				}
			case "getCoopAdvisor":
				// if has params
				return getCoopAdvisor();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getCoopInfo":
				// if has params
				return getCoopInfo();
				// else
				// return "Missing " . $_GET["param-name"]
		}
	}
	else
	{
		return "Function does not exist.";
	}
}

//Define Functions Here
function getStudentEvaluation($studentID, $comapanyID)
{
	return "TODO";
}

function addStudentEvaluation($array_params)
{
	return "TODO";
}

function updateStudentEvaluation($array_params)
{
	return "TODO";
}

function getCompanies($studentID)
{
	return "TODO";
}

function addCompany($studentID, $name, $address)
{
		return "TODO";
}

function updateCompany($studentID, $name, $address)
{
	return "TODO";
}

function getEmployers($companyID)
{
	return "TODO";
}

//need ID here because its the only unique identifier
//Maybe this will have to change later
function updateEmployer($ID, $companyID, $fname, $lname, $email)
{
	return "TODO";
}

function addEmployer($companyID, $fname, $lname, $email)
{
	return "TODO";
}

function getEmployerEvaluation($employeeID, $companyID)
{
	return "TODO";
}

function updateEmployerEvaluation($array_params)
{
	return "TODO";
}

function addEmployerEvaluation($array_params)
{
	return "TODO";
}

/* 
Currently these are not used
function getCoopAdvisor()
{
	return "TODO";
}

function getCoopInfo()
{
	return "TODO";
}
*/


///////////
//Grading//
///////////

// Switchboard to Grading Functions
function grading_switch($getFunctions)
{
	// Define the possible Grading function URLs which the page can be accessed from
	$possible_function_url = array("getStudentGrades");

	if ($getFunctions)
	{
		return $possible_function_url;
	}
	
	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			case "getStudentGrades":
				// if has params
				return getStudentGrades();
				// else
				// return "Missing " . $_GET["param-name"]
		}
	}
	else
	{
		return "Function does not exist.";
	}
}

//Define Functions Here

function getStudentGrades()
{
	return "TODO";
}

/////////////////////
//API Master Switch//
/////////////////////

// Define the possible team URLs which the page can be accessed from
$possible_url = array("general", "book_store", "human_resources", "facility_management", "student_enrollment", "coop_eval", "grading");

if (isset($_GET["team"]) && in_array($_GET["team"], $possible_url))
{
	switch ($_GET["team"])
	{
		case "general":
			$result = general_switch(false);
			break;
		case "book_store":
			$result = book_store_switch(false);
			break;
		case "human_resources":
			$result = human_resources_switch(false);
			break;
		case "facility_management":
			$result = facility_management_switch(false);
			break;
		case "student_enrollment":
			$result = student_enrollment_switch(false);
			break;
		case "coop_eval":
			$result = coop_eval_switch(false);
			break;
		case "grading":
			$result = grading_switch(false);
			break;
	}
}


//A utility function to get a list of all availiable API functions
if (isset($_GET["getAllFunctions"]))
{
	$result = array(
		"Teams" => $possible_url,
		"General" => general_switch(true),
		"book_store" => book_store_switch(true),
		"human_resources" => human_resources_switch(true),
		"facility_management" => facility_management_switch(true),
		"student_enrollment" => student_enrollment_switch(true),
		"coop_eval" => coop_eval_switch(true),
		"grading" => grading_switch(true)
	);
}

//return JSON array
exit(json_encode($result));

?>
