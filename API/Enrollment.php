<?php 

//////////////////////
//Student Enrollment//
//////////////////////

// Switchboard to Student Enrollment Functions
function student_enrollment_switch($getFunctions)
{
	// Define the possible Student Enrollment function URLs which the page can be accessed from
	$possible_function_url = array("getCourseList", "toggleSection", "getSection", "getCourseSections",
					"postSection", "toggleCourse", "getStudentSections", "getProfessorSections",
					"getTerms", "getTerm", "postTerm", "enrollStudent", "getPreReqs",
					"waitlistStudent", "withdrawStudent", "getSectionEnrolled", "getSectionWaitlist",
					"getStudentUser");
				
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
			// Calls function that toggles availability of section
			// returns: "Success" or Error Statement
			// params: sectionID
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
			// returns: "Success" or Error Statement
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
			// Calls function that toggles availability of course
			// returns: "Success" or Error Statement
			// params: courseID
			case "toggleCourse":
				if (isset($_POST["courseID"]) && $_POST["courseID"] != null)
				{
					return toggleCourse($_POST["courseID"]);
				}
				else
				{
					return "Missing courseID";
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
			// returns: "Success" or Error Statement
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
			// returns: "Success" or Error Statement
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
			// returns: "Success" or Error Statement
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
			// returns: "Success" or Error Statement
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
			// returns: both the user and student data of a Student User
			// params: userID
			case "getStudentUser":
				if (isset($_GET["userID"]) && $_GET["userID"] != null)
				{
					return getStudentUser($_GET["userID"]);
				}
				else
				{
					return "Missing userID parameter";
				}
			// returns: the prereqs of a course
			// params: courseID
			case "getPreReqs":
				if (isset($_GET["courseID"]) && $_GET["courseID"] != null)
				{
					return getPreReqs($_GET["courseID"]);
				}
				else
				{
					return "Missing courseID parameter";
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
				$queryInner = $sqlite->prepare("UPDATE Section SET AVAILABILITY = '1' WHERE ID =:sectionID");
				$queryInner->bindParam(':sectionID', $sectionID);		
				$resultInner = $queryInner->execute();
				
				$result = $resultInner;
			}
			else
			{
				//prepare query to protect from sql injection
				$queryInner = $sqlite->prepare("UPDATE Section SET AVAILABILITY = '0' WHERE ID =:sectionID");
				$queryInner->bindParam(':sectionID', $sectionID);		
				$resultInner = $queryInner->execute();
				
				$result = $resultInner;
			}
		}
	
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

function toggleCourse($courseID)
{
	try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Course WHERE ID=:courseID");
		$query->bindParam(':courseID', $courseID);		
		$result = $query->execute();
		
		

		if ($record = $result->fetchArray()) 
		{
			if ($record['AVAILABILITY'] == "0")
			{
				//prepare query to protect from sql injection
				$queryInner = $sqlite->prepare("UPDATE Course SET AVAILABILITY = '1' WHERE ID =:courseID");
				$queryInner->bindParam(':courseID', $courseID);		
				$resultInner = $queryInner->execute();
				
				$result = $resultInner;
			}
			else
			{
				//prepare query to protect from sql injection
				$queryInner = $sqlite->prepare("UPDATE Course SET AVAILABILITY = '0' WHERE ID =:courseID");
				$queryInner->bindParam(':courseID', $courseID);		
				$resultInner = $queryInner->execute();
				
				$result = $resultInner;
			}
		}
	
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

function getCourseSections($courseID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Section WHERE COURSE_ID=:courseID AND AVAILABILITY=1");
		$query->bindParam(':courseID', $courseID);
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

function postSection($maxStudents, $professorID, $courseID, $termID, $classroomID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("INSERT INTO Section (MAX_STUDENTS, PROFESSOR_ID, COURSE_ID, TERM_ID, CLASSROOM_ID) VALUES (:maxStudents, :professorID, :courseID, :termID, :classroomID)");
		$query->bindParam(':maxStudents', $maxStudents);
		$query->bindParam(':professorID', $professorID);
		$query->bindParam(':courseID', $courseID);
		$query->bindParam(':termID', $termID);
		$query->bindParam(':classroomID', $classroomID);
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

function getStudentSections($studentID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT SECTION_ID FROM Student_Section WHERE STUDENT_ID=:studentID");
		$query->bindParam(':studentID', $studentID);
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

function getTerms()
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Term");
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
		
		$query = $sqlite->prepare("INSERT INTO Term (CODE, START_DATE, END_DATE) VALUES (:code, :start_date, :end_date)");
		$query->bindParam(':code', $termCode);
		$query->bindParam(':start_date', $startDate);
		$query->bindParam(':end_date', $endDate);
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

function enrollStudent($studentID, $sectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("INSERT INTO Student_Section (STUDENT_ID, SECTION_ID) VALUES (:studentID, :sectionID)");
		$query->bindParam(':studentID', $studentID);
		$query->bindParam(':sectionID', $sectionID);
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

function waitlistStudent($studentID, $sectionID)
{
	try
	{
		date_default_timezone_set('America/New_York');
		$addedDate = date('m-d-Y');
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("INSERT INTO Waitlist (SECTION_ID, STUDENT_ID, ADDED_DATE) VALUES (:sectionID, :studentID, :addedDate)");
		$query->bindParam(':sectionID', $sectionID);
		$query->bindParam(':studentID', $studentID);
		$query->bindParam(':addedDate', $addedDate);
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

function withdrawStudent($studentID, $sectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Student_Section WHERE STUDENT_ID=:studentID AND SECTION_ID=:sectionID");
		$query->bindParam(':studentID', $studentID);
		$query->bindParam(':sectionID', $sectionID);
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

function getStudentUser($userID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT User.ID, User.FIRSTNAME, User.LASTNAME, User.EMAIL, Student.YEAR_LEVEL, Student.GPA FROM User JOIN Student ON Student.USER_ID = User.ID WHERE User.ID=:userID");
		$query->bindParam(':userID', $userID);
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

function getPreReqs($courseID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT PREREQ_COURSE_ID FROM Prerequisite WHERE COURSE_ID=:courseID");
		$query->bindParam(':courseID', $courseID);
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

?>