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
	$possible_function_url = array();

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			
		}
	}
}

//Define Functions Here



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