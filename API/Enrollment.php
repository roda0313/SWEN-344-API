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
					"getTerms", "getTerm", "postTerm", "enrollStudent", "getPreReqs", "postPreReq",
					"waitlistStudent", "withdrawStudent", "getSectionEnrolled", "getSectionWaitlist",
					"getStudentUser", "getProfessorUser", "getSectionProfessor", "updateCourse",
					"updateSection", "getStudentWaitlist", "enrollFromWaitlist", "withdrawFromWaitlist",
          "deleteSchedule", "deletePreReq", "deleteTerm", "deleteSectionProfessor");
				
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
			// returns: ID of Section created
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
			// returns: ID of term created
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
      // returns: PreReq Item
			// params: courseID, preReqID
			case "postPreReq":
				if ((isset($_POST["courseID"]) && $_POST["courseID"] != null)
					&& (isset($_POST["preReqID"]) && $_POST["preReqID"] != null)
				){
					return postPreReq($_POST["courseID"],
							$_POST["preReqID"]);
				}
				else
				{
					return "Missing a parameter";
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
			// params: userID
			// return: User object
			case "getProfessorUser":
				if (isset($_GET["userID"]) && $_GET["userID"] != null)
				{
					return getProfessorUser($_GET["userID"]);
				}
				else 
				{
					return "Missing userID parameter";
				}
			// params: sectionID
			// returns: the professor of a section
			case "getSectionProfessor":
			if (isset($_GET["sectionID"]) && $_GET["sectionID"] != null)
			{

				return getSectionProfessor($_GET["sectionID"]);
			}
			else
			{
				return "Missing sectionID parameter";
			}
			// returns: "Success" or Error Statement
			// params: id, courseCode, courseName, credits, minGPA
			case "updateCourse":
				if ((isset($_POST["courseCode"]) && $_POST["courseCode"] != null)
					&& (isset($_POST["courseName"]) && $_POST["courseName"] != null)
					&& (isset($_POST["credits"]) && $_POST["credits"] != null)
					&& (isset($_POST["minGPA"]) && $_POST["minGPA"] != null)
					&& (isset($_POST["id"]) && $_POST["id"] != null)
				){
					return updateCourse($_POST["id"],
						$_POST["courseCode"],
						$_POST["courseName"],
						$_POST["credits"],
						$_POST["minGPA"]
						);
				}
				else {
					return "Missing parameter(s)";
				}
			// returns: "Success" or Error Statement
			// params: id, maxStudents, professorID, courseID, termID, classroomID
			case "updateSection":
				if ((isset($_POST["maxStudents"]) && $_POST["maxStudents"] != null)
					&& (isset($_POST["professorID"]) && $_POST["professorID"] != null)
					&& (isset($_POST["courseID"]) && $_POST["courseID"] != null)
					&& (isset($_POST["termID"]) && $_POST["termID"] != null)
					&& (isset($_POST["classroomID"]) && $_POST["classroomID"] != null)
					&& (isset($_POST["id"]) && $_POST["id"] != null)
				){
					return updateSection($_POST["id"],
							$_POST["maxStudents"],
							$_POST["professorID"],
							$_POST["courseID"],
							$_POST["termID"],
							$_POST["classroomID"]);
				}
				else
				{
					return "Missing a parameter";
				}
			// returns: sections that a student is waitlisted on
			// params: studentID
			case "getStudentWaitlist":
				if (isset($_GET["studentID"]) && $_GET["studentID"] != null)
				{
					return getStudentWaitlist($_GET["studentID"]);
				}
				else
				{
					return "Missing studentID parameter";
				}
			// returns: "Success" or Error Statement
			// params: sectionID
			case "enrollFromWaitlist":
				if (isset($_POST["sectionID"]) && $_POST["sectionID"] != null)
				{
					return enrollFromWaitlist($_POST["sectionID"]);
				}
				else
				{
					return "Missing sectionID parameter";
				}
			// returns: "Success" or Error Statement
			// params: studentID, sectionID
			case "withdrawFromWaitlist":
				if ((isset($_POST["studentID"]) && $_POST["studentID"] != null)
					&& (isset($_POST["sectionID"]) && $_POST["sectionID"] != null)
				){
					return withdrawFromWaitlist($_POST["studentID"], $_POST["sectionID"]);
				}
				else
				{
					return "Missing a parameter";
				}
      // returns: "Success" or error message
			// params: id
			case "deleteSchedule":
				if (isset($_POST["id"]) && $_POST["id"] != null)
				{
					return deleteSchedule($_POST["id"]);
				}
				else {
					return "Missing id";
				}
      // returns: "Success" or error message
			// params: id
			case "deleteTerm":
				if (isset($_POST["id"]) && $_POST["id"] != null)
				{
					return deleteTerm($_POST["id"]);
				}
				else {
					return "Missing id";
				}
      // returns: "Success" or error message
			// params: id
			case "deleteSectionProfessor":
				if (isset($_POST["id"]) && $_POST["id"] != null)
				{
					return deleteSectionProfessor($_POST["id"]);
				}
				else {
					return "Missing id";
				}
      // returns: "Success" or error message
			// params: courseID, preReqID
			case "deletePreReq":
				if ((isset($_POST["courseID"]) && $_POST["courseID"] != null)
          && (isset($_POST["preReqID"]) && $_POST["preReqID"] != null))
				{
					return deletePreReq($_POST["courseID"], $_POST["preReqID"]);
				}
				else {
					return "Missing parameter(s)";
				}
      
		}
	} 
	else {
		return "Function does not exist";

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
		
		//if it gets here without throwing an error, assume success = true;
    $query2 = $sqlite->prepare("SELECT ID FROM Section WHERE MAX_STUDENTS=:maxStudents AND PROFESSOR_ID=:professorID AND COURSE_ID=:courseID AND TERM_ID=:termID AND CLASSROOM_ID=classroomID");
		$query2->bindParam(':maxStudents', $maxStudents);
		$query2->bindParam(':professorID', $professorID);
		$query2->bindParam(':courseID', $courseID);
		$query2->bindParam(':termID', $termID);
		$query2->bindParam(':classroomID', $classroomID);
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
		
		//if it gets here without throwing an error, assume success = true;
    $query2 = $sqlite->prepare("SELECT ID FROM Term WHERE CODE=:code");
		$query2->bindParam(':code', $termCode);
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

function enrollStudent($studentID, $sectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$queryCount = $sqlite->prepare("SELECT STUDENT_ID FROM Student_Section WHERE SECTION_ID = :sectionID");
		$queryCount->bindParam(':sectionID', $sectionID);
		$resultCount = $queryCount->execute();
		$numOfStudents = count($resultCount);
		$resultCount->finalize();
		
		$queryMax = $sqlite->prepare("SELECT MAX_STUDENTS FROM Section WHERE ID = :sectionID");
		$queryMax->bindParam(':sectionID', $sectionID);
		$resultMax = $queryMax->execute();
		
		if ($recordMax = $resultMax->fetchArray(SQLITE3_ASSOC)) 
		{
			$resultMax->finalize();
			if (!isset($recordMax['MAX_STUDENTS']))
			{
				if ($numOfStudents < $recordMax['MAX_STUDENTS'])
				{
					$query = $sqlite->prepare("INSERT INTO Student_Section (STUDENT_ID, SECTION_ID) VALUES (:studentID, :sectionID)");
					$query->bindParam(':studentID', $studentID);
					$query->bindParam(':sectionID', $sectionID);
					$result = $query->execute();
					
					$result->finalize();
					// clean up any objects
					$sqlite->close();
					return "Success";
				} else
				{
					return "Section is full.";
				}
			}
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

function waitlistStudent($studentID, $sectionID)
{
	try
	{
		date_default_timezone_set('America/New_York');
		$addedDate = date('Y-m-d H:i:s.000');
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

function postPreReq($courseID, $preReqID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("INSERT INTO Prerequisite (COURSE_ID, PREREQ_COURSE_ID) VALUES (:courseID, :preReqID)");
		$query->bindParam(':courseID', $courseID);
		$query->bindParam(':preReqID', $preReqID);
		$result = $query->execute();
		
		//if it gets here without throwing an error, assume success = true;
    $query2 = $sqlite->prepare("SELECT * FROM Prerequisite WHERE COURSE_ID=:courseID AND PREREQ_COURSE_ID=:preReqID");
		$query2->bindParam(':courseID', $courseID);
		$query2->bindParam(':preReqID', $preReqID);
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

function getProfessorUser($userID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT User.ID, User.FIRSTNAME, User.LASTNAME, User.EMAIL, User.ROLE FROM User JOIN Professor ON Professor.USER_ID = User.ID WHERE User.ID=:userID");
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

function getSectionProfessor($sectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Section_Professor WHERE SECTION_ID=:sectionID");
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

function updateCourse($id, $courseCode, $courseName, $credits, $gpa)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		
		$query = $sqlite->prepare("UPDATE Course SET COURSE_CODE = :code, NAME = :name, CREDITS = :credits, MIN_GPA = :gpa WHERE ID = :id");
		$query->bindParam(':code', $courseCode);
		$query->bindParam(':name', $courseName);
		$query->bindParam(':credits', $credits);
		$query->bindParam(':gpa', $gpa);
		$query->bindParam(':id', $id);
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

function updateSection($id, $maxStudents, $professorID, $courseID, $termID, $classroomID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("UPDATE Section SET MAX_STUDENTS = :maxStudents, PROFESSOR_ID = :professorID, COURSE_ID = :courseID, TERM_ID = :termID, CLASSROOM_ID = :classroomID WHERE ID = :id");
		$query->bindParam(':maxStudents', $maxStudents);
		$query->bindParam(':professorID', $professorID);
		$query->bindParam(':courseID', $courseID);
		$query->bindParam(':termID', $termID);
		$query->bindParam(':classroomID', $classroomID);
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

function getStudentWaitlist($studentID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT SECTION_ID FROM Waitlist WHERE STUDENT_ID=:studentID");
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

function enrollFromWaitlist($sectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$queryID = $sqlite->prepare("SELECT STUDENT_ID FROM Waitlist WHERE SECTION_ID = :sectionID ORDER BY date(ADDED_DATE) ASC LIMIT 1");
		$queryID->bindParam(':sectionID', $sectionID);
		$resultID = $queryID->execute();
		
		if ($recordID = $resultID->fetchArray(SQLITE3_ASSOC))
		{
			$resultID->finalize();
			if (!isset($recordID['STUDENT_ID']))
			{
				$enrollment = enrollStudent($recordID['STUDENT_ID'], $sectionID);
				$waitlistRemoval = withdrawFromWaitlist($recordID['STUDENT_ID'], $sectionID);
				return "Success";
			}
			else
			{
				return "No Waitlisted Students";
			}
		}
		else
		{
			// This should only ever occur during debugging.
			return "Error Processing Waitlisted Dates";
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

function withdrawFromWaitlist($studentID, $sectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Waitlist WHERE STUDENT_ID=:studentID AND SECTION_ID=:sectionID");
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

//////////////////////////////////////////
//API Enrollment Tables Delete Functions//
//////////////////////////////////////////

function deleteSchedule($id)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Schedule WHERE SECTION_ID=:id");
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

function deleteTerm($id)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Term WHERE ID=:id");
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

function deleteSectionProfessor($id)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Section_Professor WHERE ID=:id");
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

function deletePreReq($courseID, $preReqID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("DELETE FROM Prerequisite WHERE COURSE_ID=:courseID AND PREREQ_COURSE_ID=:preReqID");
		$query->bindParam(':courseID', $courseID);
    $query->bindParam(':preReqID', $preReqID);
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

?>
