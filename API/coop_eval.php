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
					return getCompanies($_GET['StudentID']);
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
	return "TODO";
}

function updateStudentEvaluation($array_params)
{
	return "TODO";
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
		return "TODO";
}

function updateCompany($studentID, $name, $address)
{
	return "TODO";
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
	return "TODO";
}

function addEmployer($companyID, $fname, $lname, $email)
{
	return "TODO";
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

?>