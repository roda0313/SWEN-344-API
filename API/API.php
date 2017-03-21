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
	$possible_function_url = array("test", "loginValid");

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			case "test":
				return APITest();
			case "loginValid":
				return loginValid($_GET["username"], $_GET["password"]);
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
function encrypt($string)
{
	return password_hash($string, PASSWORD_DEFAULT);
}

//username and PLAIN TEXT password
function loginValid($username, $password)
{
	$valid = FALSE;
	//return $GLOBALS ["databaseFile"];
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM STUDENTS WHERE USERNAME=:username");
		$query->bindParam(':username', $username);		
		$query->execute();
		
		
		//$sqliteResult = $sqlite->query($queryString);

		if ($record = $query->fetchArray()) 
		{
			if ($record['USERNAME'] == $username && password_verify(encrypt($password), $record['PASSWORD']))
			{
				$valid = TRUE;
			}
		}
	
		$sqliteResult->finalize();
		
		// clean up any objects
		$sqlite->close();
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
	}
	
	return $valid;
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
	$possible_function_url = array();

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			
		}
	}
}

//Define Functions Here



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
	$possible_function_url = array();

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			
		}
	}
}

//Define Functions Here



//////////////////////
//Student Enrollment//
//////////////////////

// Switchboard to Student Enrollment Functions
function student_enrollment_switch()
{
	// Define the possible Student Enrollment function URLs which the page can be accessed from
	$possible_function_url = array("getStudent", "postStudent", "getInstructor", "getAdmin", "getCourse",
					"postCourse", "getCourseList", "toggleCourse", "getSection", "getCourseSections",
					"postSection", "deleteSection", "getSectionList", "getStudentGrades", "getStudentSections",
					"getInstructorSections", "getBook", "getSectionBook", "getRoom", "getCurrentTerm", "getTerm",
					"postTerm", "enrollStudent", "waitlistStudent", "postBook", "withdrawStudent");

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
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
			case "getCourseList":
				// if has params
				return getCourseList();
				// else
				// return "Missing " . $_GET["param-name"]
			case "toggleCourse":
				// if has params
				return toggleCourse();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getSection":
				// if has params
				return getSection();
				// else
				// return "Missing " . $_GET["param-name"]
			case "getCourseSections":
				// if has params
				return getCourseSections();
				// else
				// return "Missing " . $_GET["param-name"]
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
			case "getStudentGrades":
				// if has params
				return getStudentGrades();
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
			case "getRoom":
				// if has params
				return getRoom();
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
			case "postBook":
				// if has params
				return postBook();
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

function getStudentGrades()
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

function getBook()
{
	return "TODO";
}

function getSectionBook()
{
	return "TODO";
}

function getRoom()
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

function postBook()
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
function coop_eval_switch_switch()
{
	// Define the possible Co-op Evaluation function URLs which the page can be accessed from
	$possible_function_url = array();

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			
		}
	}
}

//Define Functions Here



///////////
//Grading//
///////////

// Switchboard to Grading Functions
function grading_switch()
{
	// Define the possible Grading function URLs which the page can be accessed from
	$possible_function_url = array();

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			
		}
	}
}

//Define Functions Here



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