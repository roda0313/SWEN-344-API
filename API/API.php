<?php
require_once("Bookstore.php");
require_once("coop_eval.php");
require_once("Enrollment.php");
require_once("Facilities.php");
require_once("Grading.php");
require_once("HR.php");

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
	$possible_function_url = array("test", "login", "createUser", "getUsers", "getUser", "getStudent", "postStudent", "getProfessor",
					"getAdmin", "getCourse", "postCourse", "deleteUser", "deleteAdmin", "deleteProfessor", "deleteStudent",
          "deleteSection", "deleteCourse", "deleteStudentSection");
				
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
			// params: userID
			// returns: User information
			case "getUser":
				if (isset($_GET["userID"]) && $_GET["userID"] != null)
				{
					return getUser($_GET["userID"]);
				}
				else
				{
					return "Missing userID parameter";
				}
			//returns: array of all users in database
			case "getUsers":
				return getUsers();
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
						return ("createUser One or more parameters were not provided");
					}
      // returns: "Success" or error message
			// params: id
			case "deleteUser":
				if (isset($_POST["id"]) && $_POST["id"] != null)
				{
					return deleteUser($_POST["id"]);
				}
				else {
					return "Missing id";
				}
      // returns: "Success" or error message
			// params: id
			case "deleteAdmin":
				if (isset($_POST["id"]) && $_POST["id"] != null)
				{
					return deleteAdmin($_POST["id"]);
				}
				else {
					return "Missing id";
				}
      // returns: "Success" or error message
			// params: id
			case "deleteProfessor":
				if (isset($_POST["id"]) && $_POST["id"] != null)
				{
					return deleteProfessor($_POST["id"]);
				}
				else {
					return "Missing id";
				}
      // returns: "Success" or error message
			// params: id
			case "deleteStudent":
				if (isset($_POST["id"]) && $_POST["id"] != null)
				{
					return deleteStudent($_POST["id"]);
				}
				else {
					return "Missing id";
				}
      // returns: "Success" or error message
			// params: id
			case "deleteSection":
				if (isset($_POST["id"]) && $_POST["id"] != null)
				{
					return deleteSection($_POST["id"]);
				}
				else {
					return "Missing id";
				}
      // returns: "Success" or error message
			// params: id
			case "deleteCourse":
				if (isset($_POST["id"]) && $_POST["id"] != null)
				{
					return deleteCourse($_POST["id"]);
				}
				else {
					return "Missing id";
				}
      // returns: "Success" or error message
			// params: id
			case "deleteStudentSection":
				if (isset($_POST["id"]) && $_POST["id"] != null)
				{
					return deleteStudentSection($_POST["id"]);
				}
				else {
					return "Missing id";
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

		//if it gets here without throwing an error, assume success = true;
    $query2 = $sqlite->prepare("SELECT ID FROM User WHERE USERNAME=:username");
		$query2->bindParam(':username', $username);
		$result2 = $query2->execute();

		if ($record2 = $result2->fetchArray(SQLITE3_ASSOC)) 
		{
			$result2->finalize();
			// clean up any objects
			$sqlite->close();
			return $record2;
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

function getUsers()
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT ID, USERNAME, FIRSTNAME, LASTNAME, EMAIL, ROLE FROM User");		
		$result = $query->execute();
		
		$record = array();
		
		while($arr=$result->fetchArray(SQLITE3_ASSOC))
		{
			array_push($record, $arr);
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
function getUser($userID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT ID, FIRSTNAME, LASTNAME, EMAIL, ROLE FROM User WHERE ID=:userID");
    $query->bindParam(':userID', $userID);
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

function postStudent($userID, $yearLevel, $gpa)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		
		$query = $sqlite->prepare("INSERT INTO Student (USER_ID, YEAR_LEVEL, GPA) VALUES (:userID, :yearLevel, :gpa)");
		$query->bindParam(':userID', $userID);
    $query->bindParam(':yearLevel', $yearLevel);
		$query->bindParam(':gpa', $gpa);
		$result = $query->execute();
		
		//if it gets here without throwing an error, assume success = true;
    $query2 = $sqlite->prepare("SELECT ID FROM User WHERE ID=:userID");
		$query2->bindParam(':userID', $userID);
		$result2 = $query2->execute();

		if ($record2 = $result2->fetchArray(SQLITE3_ASSOC)) 
		{
			$result2->finalize();
			// clean up any objects
			$sqlite->close();
			return $record2;
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
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("SELECT * FROM Admin WHERE USER_ID=:user_ID");
		$query->bindParam(':user_ID', $adminID);
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

function getCourse($courseID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("SELECT * FROM Course WHERE ID=:ID");
		$query->bindParam(':ID', $courseID);
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

function postCourse($courseCode, $courseName, $credits, $gpa)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		
		$query = $sqlite->prepare("INSERT INTO Course (COURSE_CODE, NAME, CREDITS, MIN_GPA) VALUES (:code, :name, :credits, :gpa)");
		$query->bindParam(':code', $courseCode);
		$query->bindParam(':name', $courseName);
		$query->bindParam(':credits', $credits);
		$query->bindParam(':gpa', $gpa);
		$result = $query->execute();
    
    //if it gets here without throwing an error, assume success = true;
    $query2 = $sqlite->prepare("SELECT ID FROM Course WHERE COURSE_CODE=:courseCode");
		$query2->bindParam(':courseCode', $courseCode);
		$result2 = $query2->execute();

		if ($record2 = $result2->fetchArray(SQLITE3_ASSOC)) 
		{
			$result2->finalize();
			// clean up any objects
			$sqlite->close();
			return $record2;
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

///////////////////////////////////////
//API General Tables Delete Functions//
///////////////////////////////////////

function deleteUser($id)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM User WHERE ID=:id");
		$query->bindParam(':id', $id);
		$result = $query->execute();
    
    $result->finalize();
		// clean up any objects
		$sqlite->close();
		return "Success";
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

function deleteAdmin($id)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Admin WHERE USER_ID=:id");
		$query->bindParam(':id', $id);
		$result = $query->execute();
    
    $result->finalize();
		// clean up any objects
		$sqlite->close();
		return "Success";
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

function deleteProfessor($id)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Professor WHERE USER_ID=:id");
		$query->bindParam(':id', $id);
		$result = $query->execute();
    
    $result->finalize();
		// clean up any objects
		$sqlite->close();
		return "Success";
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

function deleteStudent($id)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Student WHERE USER_ID=:id");
		$query->bindParam(':id', $id);
		$result = $query->execute();
    
    $result->finalize();
		// clean up any objects
		$sqlite->close();
		return "Success";
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

function deleteSection($id)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Section WHERE ID=:id");
		$query->bindParam(':id', $id);
		$result = $query->execute();
    
    $result->finalize();
		// clean up any objects
		$sqlite->close();
		return "Success";
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

function deleteCourse($id)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Course WHERE ID=:id");
		$query->bindParam(':id', $id);
		$result = $query->execute();
    
    $result->finalize();
		// clean up any objects
		$sqlite->close();
		return "Success";
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

function deleteStudentSection($id)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Student_Section WHERE ID=:id");
		$query->bindParam(':id', $id);
		$result = $query->execute();
    
    $result->finalize();
		// clean up any objects
		$sqlite->close();
		return "Success";
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

//return JSON
header('Content-type:application/json;charset=utf-8');
echo json_encode($result);

?>
