<?php

///////////
//Grading//
///////////

// Switchboard to Grading Functions
function grading_switch($getFunctions)
{
	// Define the possible Grading function URLs which the page can be accessed from
	$possible_function_url = array(
		"getGradeForStudentSection"
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
		}
	}
	else
	{
		return "Function does not exist.";
	}
}

/**
 *	Retrives the row from the Grade table matching the student_section_id
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

?>