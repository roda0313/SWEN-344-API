<?php

///////////
//Grading//
///////////

// Switchboard to Grading Functions
function grading_switch($getFunctions)
{
	// Define the possible Grading function URLs which the page can be accessed from
	$possible_function_url = array(
		"getGradeForStudentSection",
		"getGradesForCourseSection",
		"getCommentsForStudentSection",
		"getStudentComments",
		"postLockGrade",
		"postUpdateGrade",
		"postGradeComment"
		);

	if ($getFunctions)
	{
		return $possible_function_url;
	}
	
	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			case "getGradeForStudentSection":
				if (isset($_GET["student_section_id"]))
				{
					return getGradeForStudentSection($_GET["student_section_id"]);
				}
				else
				{
					return "Missing required query param: 'student_section_id'";
				}
			case "getGradesForCourseSection":
				if (isset($_GET["section_id"]))
				{
					return getGradesForCourseSection($_GET["section_id"]);
				}
				else
				{
					return "Missing required query param: 'section_id'";
				}
			case "getCommentsForStudentSection":
				if (isset($_GET["student_section_id"]))
				{
					return getCommentsForStudentSection($_GET["student_section_id"]);
				}
				else
				{
					return "Missing required query param: 'student_section_id";
				}
			case "getStudentComments":
				if (isset($_GET["studentID"]))
				{
					return getStudentComments($_GET["studentID"]);
				}
				else
				{
					return "Missing required query param: 'studentID";
				}
			case "postLockGrade":
				if (isset($_POST["student_section_id"]))
				{
					return postLockGrade($_POST["student_section_id"]);
				}
				else
				{
					return "Missing required form data for key: 'student_section_id";
				}
			case "postUpdateGrade":
				if (isset($_POST["value"]) && isset($_POST["student_section_id"]))
				{
					return postUpdateGrade($_POST["value"], $_POST["student_section_id"]);
				}
				else
				{
					return "Missing required form data for key: 'student_section_id";
				}
			case "postGradeComment":
				if (isset($_POST["user_id"]) && isset($_POST["grade_id"]) && isset($_POST["content"]))
				{
					return postGradeComment($_POST["user_id"], $_POST["grade_id"], $_POST["content"]);
				}
				else
				{
					return "Missing required form data! Required: 'user_id', 'grade_id', 'content'";
				}
		}
	}
	else
	{
		return "Function does not exist.";
	}
}

function postGradeComment($userID, $gradeID, $content)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("INSERT INTO Comment (USER_ID, GRADE_ID, CONTENT) VALUES (:user_id, :grade_id, :content)");
		$query->bindParam(':user_id', $userID);
		$query->bindParam(':grade_id', $gradeID);
		$query->bindParam(':content', $content);
        $query->execute();
        $sqlite->close();

		return true;
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

function postUpdateGrade($value, $studentSectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);

		// first check to see if the Grade row exists or must be updated
		// $gradeExistsQuery = $sqlite->prepare("SELECT * FROM Grade WHERE STUDENT_SECTION_ID=:studentSectionID");
		// $gradeExistsQuery->bindParam(':studentSectionID', $studentSectionID);
		$gradeQueryResult = $sqlite->querySingle("SELECT * FROM Grade WHERE STUDENT_SECTION_ID=" . $studentSectionID);

		if ($gradeQueryResult) { 
			// have rows
			$query = $sqlite->prepare("UPDATE Grade SET VALUE=:value WHERE STUDENT_SECTION_ID=:studentSectionID");
			$query->bindParam(':studentSectionID', $studentSectionID);
			$query->bindParam(':value', $value);
			$result = $query->execute();
			$sqlite->close();

			return $result;
		} else { 
			// zero rows 
			$query = $sqlite->prepare("INSERT INTO Grade (VALUE, STUDENT_SECTION_ID) VALUES (:value, :studentSectionID)");
			$query->bindParam(':studentSectionID', $studentSectionID);
			$query->bindParam(':value', $value);
			$result = $query->execute();
			$sqlite->close();

			return $result;
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

function createNotification($studentSectionID, $message)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);

		// Retrieve course name based on studentsection data
		$courseName = '';
		$courseQuery = $sqlite->prepare("SELECT NAME FROM Course as c INNER JOIN Section as s ON c.ID = s.COURSE_ID INNER JOIN Student_Section as ss ON ss.SECTION_ID = s.ID WHERE ss.ID=:studentSectionID");
		$courseQuery->bindParam(':studentSectionID', $studentSectionID);
		$sqlite3Result = $courseQuery->execute();
		$result = $sqlite3Result->fetchArray(SQLITE3_ASSOC);
		// prepend course name to notification message
		$notificationMessage = $result['NAME'] . ': ' . $message;
		
		// create the notification
		$query = $sqlite->prepare("INSERT INTO Notification (MESSAGE, STUDENT_SECTION_ID) VALUES (:message, :studentSectionID)");
		$query->bindParam(':message', $notificationMessage);
		$query->bindParam(':studentSectionID', $studentSectionID);
        $query->execute();
        $sqlite->close();

		return;
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

function postLockGrade($studentSectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("UPDATE Grade SET IS_LOCKED=1 WHERE STUDENT_SECTION_ID=:studentSectionID");
		$query->bindParam(':studentSectionID', $studentSectionID);
        $query->execute();
        $sqlite->close();

		// create the notification
		return createNotification($studentSectionID, 'Your grade has been locked in');
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

/**
 *	Retrieves the row from the Grade table matching the student_section_id
 *	@param $studentSectionID - the ID matching the studentsection
 */
function getGradeForStudentSection($studentSectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Grade WHERE STUDENT_SECTION_ID=:studentSectionID");
		$query->bindParam(':studentSectionID', $studentSectionID);
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

/**
 *	Retrieves a collection of rows from the Grade table for a given section id
 *	@param $studentSectionID - the ID matching the studentsection
 */
function getGradesForCourseSection($sectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM Grade as g INNER JOIN Student_Section as ss ON g.STUDENT_SECTION_ID = ss.ID WHERE ss.SECTION_ID=:sectionID");
		$query->bindParam(':sectionID', $sectionID);
		$result = $query->execute();
		
		$record = array();
		//$sqliteResult = $sqlite->query($queryString);
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

function getCommentsForStudentSection($studentSectionID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("SELECT c.USER_ID, c.CONTENT, c.CREATED_TIME, u.FIRSTNAME, u.LASTNAME, u.ROLE FROM Comment as c INNER JOIN Grade as g ON c.GRADE_ID = g.ID INNER JOIN User as u ON c.USER_ID = u.ID WHERE g.STUDENT_SECTION_ID=:studentsection_ID");
		$query->bindParam(':studentsection_ID', $studentSectionID);
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

function getStudentComments($studentID)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		$query = $sqlite->prepare("SELECT c.*, g.STUDENT_SECTION_ID FROM Comment as c INNER JOIN Grade as g ON c.GRADE_ID = g.ID WHERE USER_ID=:user_id");
		$query->bindParam(':user_id', $studentID);
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