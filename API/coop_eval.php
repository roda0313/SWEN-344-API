<?php

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
				if (isset($_GET['studentID']) && isset($_GET['companyID']))
				{
					return getStudentEvaluation($_GET["studentID"], $_GET["companyID"]);
				}
				else
				{
					return NULL;
				}
			case "addStudentEvaluation":
				if (isset($_POST['studentID']) && isset($_POST['companyID']))
				{
					return addStudentEvaluation(array(
						'studentID'=>$_POST['studentID'],
						'companyID'=>$_POST['companyID'],
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
				if (isset($_POST['studentID']) && isset($_POST['companyID']))
				{
					return updateStudentEvaluation(array(
						'studentID'=>$_POST['studentID'],
						'companyID'=>$_POST['companyID'],
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
				if ($_GET['studentID'])
				{
					return getCompanies($_GET['studentID']);
				}
				else
				{
					return NULL;
				}
			case "addCompany":
				if ($_POST['studentID'] && $_POST['name'])
				{
					return addCompany($_POST['studentID'], $_POST['name'], $_POST['address']);
				}
				else 
				{
					return NULL;
				}
				
			case "updateCompany":
				if (isset($_POST['studentID']) && isset($_POST['name']))
				{
					return updateCompany($_POST['studentID'], $_POST['name'], $_POST['address']);
				}
				else 
				{
					return NULL;
				}
				
			case "getEmployers":
				if (isset($_GET['companyID']))
				{
					return getEmployer($_GET['companyID']);
				}
				else
				{
					return NULL;
				}
			case "updateEmployer":
				if (isset($_POST['companyID']) && isset($_POST['ID']))
				{
					return updateEmployer(
					$_POST['ID'], 
					$_POST['companyID'],
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
				if (isset($_POST['companyID']))
				{
					return addEmployer(
						$_POST['companyID'], 
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
				if (isset($_GET['employeeID']) && isset($_GET['companyID']))
				{
					return getEmployerEvaluation($_GET["employeeID"], $_GET["companyID"]);
				}
				else
				{
					return NULL;
				}
			case "updateEmployerEvaluation":
				if (isset($_POST['employeeID']) && isset($_POST['companyID']))
				{
					return updateEmployerEvaluation(array(
						'employeeID'=>$_POST['employeeID'],
						'companyID'=>$_POST['companyID'],
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
				if (isset($_POST['employeeID']) && isset($_POST['companyID']))
				{
					return updateEmployerEvaluation(array(
						'employeeID'=>$_POST['employeeID'],
						'companyID'=>$_POST['companyID'],
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
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM StudentEval WHERE STUDENTID = :studentID AND COMPANYID = :companyID");
		$query->bindParam(':studentID', $studentID);
		$query->bindParam(':companyID', $companyID);		
		$result = $query->execute();
		
		$record = array();
		
		while ($arr = $result->fetchArray(SQLITE3_ASSOC)) 
		{			
			array_push($record, $arr);
		}
		
		$result->finalize();
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

function addStudentEvaluation($array_params)
{
	//array(
	//'studentID'=>$_POST['StudentID'],
	//'companyID'=>$_POST['CompanyID'],
	//'name'=>$_POST['name'],
	//'email'=>$_POST['email'],
	//'ename'=>$_POST['ename'],
	//'eemail'=>$_POST['eemail'],
	//'position'=>$_POST['position'],
	//'q1'=>$_POST['q1'],
	//'q2'=>$_POST['q2'],
	//'q3'=>$_POST['q3'],
	//'q4'=>$_POST['q4'],
	//'q5'=>$_POST['q5']			
	//)
	
	$complete = true;
	
	foreach ($array_params as $value)
	{
		if ($value == null)
		{
			$complete = false;
		}
	}
	
	$queryString = "INSERT INTO StudentEval
	(STUDENTID, COMPANYID, NAME, EMAIL, ENAME, EEMAIL, POSITION, QUESTION1,
	QUESTION2, QUESTION3, QUESTION4, QUESTION5, COMPLETE)
	VALUES (:studentID, :companyID, :name, :email, :ename, :eemail, :position, 
	:q1, :q2, :q3, :q4, :q5, :complete)";
	
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare($queryString);
		$query->bindParam(':studentID', $array_params['studentID']);
		$query->bindParam(':companyID', $array_params['companyID']);
		$query->bindParam(':name', $array_params['name']);	
		$query->bindParam(':email', $array_params['email']);
		$query->bindParam(':ename', $array_params['ename']);
		$query->bindParam(':eemail', $array_params['eemail']);
		$query->bindParam(':position', $array_params['position']);
		$query->bindParam(':q1', $array_params['q1']);
		$query->bindParam(':q2', $array_params['q2']);
		$query->bindParam(':q3', $array_params['q3']);
		$query->bindParam(':q4', $array_params['q4']);
		$query->bindParam(':q5', $array_params['q5']);
		$query->bindParam(':complete', $complete);
		
		$result = $query->execute();		
		
		$result->finalize();
		$sqlite->close();
		
		return $result;
	
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

function updateStudentEvaluation($array_params)
{
	//array(
	//'studentID'=>$_POST['StudentID'],
	//'companyID'=>$_POST['CompanyID'],
	//'name'=>$_POST['name'],
	//'email'=>$_POST['email'],
	//'ename'=>$_POST['ename'],
	//'eemail'=>$_POST['eemail'],
	//'position'=>$_POST['position'],
	//'q1'=>$_POST['q1'],
	//'q2'=>$_POST['q2'],
	//'q3'=>$_POST['q3'],
	//'q4'=>$_POST['q4'],
	//'q5'=>$_POST['q5']			
	//)
	
	$complete = true;
	
	foreach ($array_params as $value)
	{
		if ($value == null)
		{
			$complete = false;
		}
	}
	
	$queryString = "UPDATE StudentEval SET
	(STUDENTID = :studentID, COMPANYID = :companyID, NAME = :name, 
	EMAIL = :email, ENAME = :ename, EEMAIL = :eemail, POSITION = :position, 
	QUESTION1 = :q1, QUESTION2 = :q2, QUESTION3 = :q3, QUESTION4 = :q4, QUESTION5 = :q5, COMPLETE = :complete)
	WHERE STUDENTID = :studentID AND COMPANYID = :companyID";
	
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare($queryString);
		$query->bindParam(':studentID', $array_params['studentID']);
		$query->bindParam(':companyID', $array_params['companyID']);
		$query->bindParam(':name', $array_params['name']);	
		$query->bindParam(':email', $array_params['email']);
		$query->bindParam(':ename', $array_params['ename']);
		$query->bindParam(':eemail', $array_params['eemail']);
		$query->bindParam(':position', $array_params['position']);
		$query->bindParam(':q1', $array_params['q1']);
		$query->bindParam(':q2', $array_params['q2']);
		$query->bindParam(':q3', $array_params['q3']);
		$query->bindParam(':q4', $array_params['q4']);
		$query->bindParam(':q5', $array_params['q5']);
		$query->bindParam(':complete', $complete);
		
		$result = $query->execute();
		
		$result->finalize();
		$sqlite->close();
		
		return $result;
	
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

//Gets all company objects and their asscoiated evaluations
function getCompanies($studentID)
{	
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT User.ID, User.USERNAME, CoopCompany.* FROM User JOIN CoopCompany ON CoopCompany.STUDENTID = User.ID WHERE User.ID = :studentID");
		$query->bindParam(':studentID', $studentID);		
		$result = $query->execute();
		
		$record = array();
		
		while ($arr = $result->fetchArray(SQLITE3_ASSOC)) 
		{			
			array_push($record, $arr);
		}
		
		$result->finalize();
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

function addCompany($studentID, $name, $address)
{
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("INSERT INTO CoopCompany (STUDENTID, NAME, ADDRESS) VALUES (:studentID, :name, :address)");
		$query->bindParam(':studentID', $studentID);	
		$query->bindParam(':name', $name);
		$query->bindParam(':address', $address);
		$result = $query->execute();
		
		$result->finalize();
		$sqlite->close();
		
		return $result;
	
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

function updateCompany($studentID, $name, $address)
{
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("UPDATE CoopCompany SET (STUDENTID = :studentID, NAME = :name, ADDRESS = :address)");
		$query->bindParam(':studentID', $studentID);	
		$query->bindParam(':name', $name);
		$query->bindParam(':address', $address);
		$result = $query->execute();
		
		$result->finalize();
		$sqlite->close();
		
		return $result;
	
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

function getEmployers($companyID)
{
	try 
		{
			$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
			$sqlite->enableExceptions(true);
			
			//prepare query to protect from sql injection
			$query = $sqlite->prepare("SELECT CoopCompany.*, CoopEmployee.* FROM CoopCompany JOIN CoopEmployee ON CoopCompany.ID = CoopEmployee.COMPANYID WHERE CoopCompany.ID = :companyID");
			$query->bindParam(':companyID', $companyID);		
			$result = $query->execute();
			
			$record = array();
			
			while ($arr = $result->fetchArray(SQLITE3_ASSOC)) 
			{			
				array_push($record, $arr);
			}
			
			$result->finalize();
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

//need ID here because its the only unique identifier
//Maybe this will have to change later
function updateEmployer($ID, $companyID, $fname, $lname, $email)
{
	try 
		{
			$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
			$sqlite->enableExceptions(true);
			
			//prepare query to protect from sql injection
			$query = $sqlite->prepare("UPDATE CoopEmployee SET (COMPANYID = :companyID, FIRSTNAME = :fname, LASTNAME = :lname, EMAIL = :email) WHERE ID = :id");
			$query->bindParam(':companyID', $companyID);
			$query->bindParam(':fname', $fname);
			$query->bindParam(':lname', $lname);
			$query->bindParam(':email', $email);
			$query->bindParam(':id', $ID);
			$result = $query->execute();
			
			$result->finalize();
			$sqlite->close();
			
			return true; //change this eventually to actually display if it was successful or not
		
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

function addEmployer($companyID, $fname, $lname, $email)
{
	try 
		{
			$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
			$sqlite->enableExceptions(true);
			
			//prepare query to protect from sql injection
			$query = $sqlite->prepare("INSERT INTO CoopEmployee (COMPANYID, FIRSTNAME, LASTNAME, EMAIL) VALUES (:companyID, :fname, :lname, :email");
			$query->bindParam(':companyID', $companyID);
			$query->bindParam(':fname', $fname);
			$query->bindParam(':lname', $lname);
			$query->bindParam(':email', $email);
			$result = $query->execute();
			
			$result->finalize();
			$sqlite->close();
			
			return true; //change this eventually to actually display if it was successful or not
		
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

function getEmployerEvaluation($employeeID, $companyID)
{
	try 
		{
			$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
			$sqlite->enableExceptions(true);
			
			//prepare query to protect from sql injection
			$query = $sqlite->prepare("SELECT * FROM EmployeeEval WHERE EMPLOYEEID = :employeeID AND COMPANYID = :companyID");
			$query->bindParam(':employeeID', $employeeID);
			$query->bindParam(':companyID', $companyID);		
			$result = $query->execute();
			
			$record = array();
			
			while ($arr = $result->fetchArray(SQLITE3_ASSOC)) 
			{			
				array_push($record, $arr);
			}
			
			$result->finalize();
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

function updateEmployerEvaluation($array_params)
{
	$complete = true;
	
	foreach ($array_params as $value)
	{
		if ($value == null)
		{
			$complete = false;
		}
	}
	
	$queryString = "UPDATE EmployeeEval SET
	(EMPLOYEEID = :employeeID, COMPANYID = :companyID, NAME = :name, 
	EMAIL = :email, SNAME = :sname, SEMAIL = :semail, POSITION = :position, 
	QUESTION1 = :q1, QUESTION2 = :q2, QUESTION3 = :q3, QUESTION4 = :q4, QUESTION5 = :q5, COMPLETE = :complete)
	WHERE EMPLOYEEID = :employeeID AND COMPANYID = :companyID";
	
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare($queryString);
		$query->bindParam(':employeeID', $array_params['employeeID']);
		$query->bindParam(':companyID', $array_params['companyID']);
		$query->bindParam(':name', $array_params['name']);	
		$query->bindParam(':email', $array_params['email']);
		$query->bindParam(':sname', $array_params['sname']);
		$query->bindParam(':semail', $array_params['semail']);
		$query->bindParam(':position', $array_params['position']);
		$query->bindParam(':q1', $array_params['q1']);
		$query->bindParam(':q2', $array_params['q2']);
		$query->bindParam(':q3', $array_params['q3']);
		$query->bindParam(':q4', $array_params['q4']);
		$query->bindParam(':q5', $array_params['q5']);
		$query->bindParam(':complete', $complete);
		
		$result = $query->execute();
		
		$result->finalize();
		$sqlite->close();
		
		return $result;
	
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

function addEmployerEvaluation($array_params)
{
	$complete = true;
	
	foreach ($array_params as $value)
	{
		if ($value == null)
		{
			$complete = false;
		}
	}
	
	$queryString = "INSERT INTO EmployeeEval
	(EMPLOYEEID, COMPANYID, NAME, EMAIL, SNAME, SEMAIL, POSITION, QUESTION1,
	QUESTION2, QUESTION3, QUESTION4, QUESTION5, COMPLETE)
	VALUES (:employeeID, :companyID, :name, :email, :sname, :semail, :position, 
	:q1, :q2, :q3, :q4, :q5, :complete)";
	
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare($queryString);
		$query->bindParam(':employeeID', $array_params['employeeID']);
		$query->bindParam(':companyID', $array_params['companyID']);
		$query->bindParam(':name', $array_params['name']);	
		$query->bindParam(':email', $array_params['email']);
		$query->bindParam(':sname', $array_params['sname']);
		$query->bindParam(':semail', $array_params['semail']);
		$query->bindParam(':position', $array_params['position']);
		$query->bindParam(':q1', $array_params['q1']);
		$query->bindParam(':q2', $array_params['q2']);
		$query->bindParam(':q3', $array_params['q3']);
		$query->bindParam(':q4', $array_params['q4']);
		$query->bindParam(':q5', $array_params['q5']);
		$query->bindParam(':complete', $complete);
		
		$result = $query->execute();		
		
		$result->finalize();
		$sqlite->close();
		
		return $result;
	
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

?>