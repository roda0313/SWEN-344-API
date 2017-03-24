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
	$possible_function_url = array("test", "login", "createUser", "getStudent", "postStudent", "getInstructor",
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
			case "getStudent":
				// if has params
				return getStudent();
				// else
				// return "Missing " . $_GET["param-name"]
			case "postStudent":
				// if has params
				return postStudent();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getInstructor":
				// if has params
				return getInstructor();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getAdmin":
				// if has params
				return getAdmin();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getCourse":
				// if has params
				return getCourse();
				// else
				// return "Missing " . $_GET["param-name"]
			case "postCourse":
				// if has params
				return postCourse();
				// else
				// return "Missing " . $_GET["param-name"]
			case "createUser":
				if (isset($_POST["username"]) &&
					isset($_POST["password"]) &&
					isset($_POST["fname"]) &&
					isset($_POST["lname"]) &&
					isset($_POST["email"])
					)
					{
						return createUser($_POST["username"], 
							$_POST["password"], 
							$_POST["fname"],
							$_POST["lname"],
							$_POST["email"]
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

function getStudent()
{
	return "TODO";
}

function postStudent()
{
	return "TODO";
}

function getInstructor()
{
	return "TODO";
}

function getAdmin()
{
	return "TODO";
}

function getCourse()
{
	return "TODO";
}

function postCourse()
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
	$possible_function_url = array();

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			
		}
	}
}

//Define Functions Here



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
	$possible_function_url = array("getCourseList", "toggleCourse", "getSection", "getCourseSections",
					"postSection", "deleteSection", "getSectionList", "getStudentSections",
					"getInstructorSections", "getCurrentTerm", "getTerm", "postTerm", "enrollStudent",
					"waitlistStudent", "withdrawStudent");

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			// returns list of all courses in database
			// params: none
			case "getCourseList":
				return getCourseList();
				// else
				// return "Missing " . $_GET["param-name"]
			
			// Calls function that toggles availability of course
			// params: courseID
			case "toggleCourse":
				if (isset($_GET["courseID"]) && $_GET["courseID"] != null)
				{
					return toggleCourse();
				}
				else
				{
					return "Missing courseID";
				}
			// returns: information about desired course
			// params: sectionID
			case "getSection":
				if (isset($_GET["sectionID"]) && $_GET["sectionID"] != null)
				{
					return getSection();
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
					return getCourseSections();
				}
				else
				{
					return "Missing courseID param";
				}
			case "postSection":
				// if has params
				return postSection();
				// else
				// return "Missing " . $_GET["param-name"]
			case "deleteSection":
				// if has params
				return deleteSection();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getSectionList":
				// if has params
				return getSectionList();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getStudentSections":
				// if has params
				return getStudentSections();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getInstructorSections":
				// if has params
				return getInstructorSections();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getCurrentTerm":
				// if has params
				return getCurrentTerm();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getTerm":
				// if has params
				return getTerm();
				// else
				// return "Missing " . $_GET["param-name"]
			case "postTerm":
				// if has params
				return postTerm();
				// else
				// return "Missing " . $_GET["param-name"]
			case "enrollStudent":
				// if has params
				return enrollStudent();
				// else
				// return "Missing " . $_GET["param-name"]
			case "waitlistStudent":
				// if has params
				return waitlistStudent();
				// else
				// return "Missing " . $_GET["param-name"]
			case "withdrawStudent":
				// if has params
				return withdrawStudent();
				// else
				// return "Missing " . $_GET["param-name"]
			
		}
	}
}

//Student Enrollment Functions

function getCourseList()
{
	return "TODO";
}

function toggleCourse()
{
	return "TODO";
}

function getSection()
{
	return "TODO";
}

function getCourseSections()
{
	return "TODO";
}

function postSection()
{
	return "TODO";
}

function deleteSection()
{
	return "TODO";
}

function getSectionList()
{
	return "TODO";
}

function getStudentSections()
{
	return "TODO";
}

function getInstructorSections()
{
	return "TODO";
}

function getCurrentTerm()
{
	return "TODO";
}

function getTerm()
{
	return "TODO";
}

function postTerm()
{
	return "TODO";
}

function enrollStudent()
{
	return "TODO";
}

function waitlistStudent()
{
	return "TODO";
}

function withdrawStudent()
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