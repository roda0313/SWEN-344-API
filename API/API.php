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
function general_switch()
{
	// Define the possible general function URLs which the page can be accessed from
	$possible_function_url = array("test", "login", "createUser", "getStudent", "postStudent", "getProfessor",
					"getAdmin", "getCourse", "postCourse");

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
				return getStudent($_GET["studentId"]);
				}
				else {
					return "Missing studentID parameter";
				}
			// returns: Newly created student object
			// params: yearLevel, gpa
			case "postStudent":
				if ((isset($_POST["yearLevel"]) && $_POST["yearLevel"] != null)
					&& (isset($_POST["gpa"]) && $_POST["gpa"] != null))
				{
					return postStudent($_POST["yearLevel"], $_POST["gpa"]);
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
			
			return $result;
			
			if ($record = $result->fetchArray(SQLITE3_ASSOC)) 
			{
				return $record;
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
	return "TODO";
}

function postStudent($yearLevel, $gpa)
{
	return "TODO";
}

function getProfessor($professorID)
{
	return "TODO";
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
function book_store_switch()
{
	// Define the possible Book Store function URLs which the page can be accessed from
	$possible_function_url = array("getBook", "getSectionBook", "postBook");

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
function human_resources_switch()
{
	// Define the possible Human Resources function URLs which the page can be accessed from
	$possible_function_url = array("getSalary","updateFname","updateLname","terminate");

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			// Calls function that gets an employee's salary
			// params: ID
			case 'getSalary':
				if (isset($_POST["ID"]) != null)
				{
					return getSalary($_POST["ID"]);
				}
				else
				{
					return "Missing ID";
				}
			
			// Calls function that updates a user's first name
			// params: 
			case 'updateFname':
				// if has params
				return update_lname();
				// else
				// return "Missing " . $_GET["param-name"]

			// Calls function that updates a user's last name
			// params: 
			case 'updateLname':
				// if has params
				return updateLname();
				// else
				// return "Missing " . $_GET["param-name"]

			// Calls function that removes an employee from the DB
			// params: 
			case 'terminate':
				// if has params
				return terminate();
				// else
				// return "Missing " . $_GET["param-name"]
		}
	}
}

//Define Functions Here

function getSalary($ID)
{
	return "TODO";
}

function updateLname()
{
	return "TODO";
}

function updateFname()
{
	return "TODO";
}

function terminate()
{
	return "TODO";
}



/////////////////////////
//Facilities Management//
/////////////////////////

// Switchboard to Facilities Management Functions
function facility_management_switch()
{
	// Define the possible Facilities Management function URLs which the page can be accessed from
	$possible_function_url = array("getRoom");

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
function student_enrollment_switch()
{
	// Define the possible Student Enrollment function URLs which the page can be accessed from
	$possible_function_url = array("getCourseList", "toggleSection", "getSection", "getCourseSections",
					"postSection", "deleteSection", "getStudentSections", "getProfessorSections",
					"getCurrentTerm", "getTerm", "postTerm", "enrollStudent",
					"waitlistStudent", "withdrawStudent");

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
				if (isset($_GET["courseCode"]) && $_GET["courseCode"] != null)
				{
					return getCourseSections($_GET["courseCode"]);
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
			case "getCurrentTerm":
				return getCurrentTerm();
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
			
		}
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
	return "TODO";
}

function getSection($sectionID)
{
	return "TODO";
}

function getCourseSections($courseCode)
{
	return "TODO";
}

function postSection($maxStudents, $professorID, $courseID, $termID, $classroomID)
{
	return "TODO";
}

function deleteSection($maxStudents, $professorID, $courseID, $termID, $classroomID)
{
	return "TODO";
}

function getStudentSections($studentID)
{
	return "TODO";
}

function getProfessorSections($professorID)
{
	return "TODO";
}

function getCurrentTerm()
{
	return "TODO";
}

function getTerm($termCode)
{
	return "TODO";
}

function postTerm($termCode, $startDate, $endDate)
{
	return "TODO";
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
function coop_eval_switch()
{
	// Define the possible Co-op Evaluation function URLs which the page can be accessed from
	$possible_function_url = array(
		"getStudentEvaluation", "addStudentEvaluation", "updateStudentEvaluation", 
		"getCompanies", "getEmployer", "updateEmployer", "addEmployer", "getEmployerEvaluation",
		"updateEmployerEvaluation", "addEmployerEvaluation", "getCoopAdvisor", "getCoopInfo"
	);

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			case "getStudentEvaluation":
				if (isset($_GET["id"]))
				{
					return getStudentEvaluation($_GET["id"]);
				}
				else
				{
					return NULL;
				}
			case "addStudentEvaluation":
				// if has params
				return addStudentEvaluation();
				// else
				// return "Missing " . $_GET["param-name"]
			case "updateStudentEvaluation":
				// if has params
				return updateStudentEvaluation();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getCompanies":
				// if has params
				return getCompanies();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getEmployer":
				// if has params
				return getEmployer();
				// else
				// return "Missing " . $_GET["param-name"]
			case "updateEmployer":
				// if has params
				return updateEmployer();
				// else
				// return "Missing " . $_GET["param-name"]
			case "addEmployer":
				// if has params
				return addEmployer();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getEmployerEvaluation":
				// if has params
				return getEmployerEvaluation();
				// else
				// return "Missing " . $_GET["param-name"]
			case "updateEmployerEvaluation":
				// if has params
				return updateEmployerEvaluation();
				// else
				// return "Missing " . $_GET["param-name"]
			case "addEmployerEvaluation":
				// if has params
				return addEmployerEvaluation();
				// else
				// return "Missing " . $_GET["param-name"]
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
}

//Define Functions Here
function getStudentEvaluation()
{
	return "TODO";
}

function addStudentEvaluation()
{
	return "TODO";
}

function updateStudentEvaluation()
{
	return "TODO";
}

function getCompanies()
{
	return "TODO";
}

function getEmployer()
{
	return "TODO";
}

function updateEmployer()
{
	return "TODO";
}

function addEmployer()
{
	return "TODO";
}

function getEmployerEvaluation()
{
	return "TODO";
}

function updateEmployerEvaluation()
{
	return "TODO";
}

function addEmployerEvaluation()
{
	return "TODO";
}

function getCoopAdvisor()
{
	return "TODO";
}

function getCoopInfo()
{
	return "TODO";
}


///////////
//Grading//
///////////

// Switchboard to Grading Functions
function grading_switch()
{
	// Define the possible Grading function URLs which the page can be accessed from
	$possible_function_url = array("getStudentGrades");

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
			$result = general_switch();
			break;
		case "book_store":
			$result = book_store_switch();
			break;
		case "human_resources":
			$result = human_resources_switch();
			break;
		case "facility_management":
			$result = facility_management_switch();
			break;
		case "student_enrollment":
			$result = student_enrollment_switch();
			break;
		case "coop_eval":
			$result = coop_eval_switch();
			break;
		case "grading":
			$result = grading_switch();
			break;
	}
}

//return JSON array
exit(json_encode($result));

?>