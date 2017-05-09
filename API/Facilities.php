<?php

/////////////////////////
//Facilities Management//
/////////////////////////

function create_response_error($listParameters) {

	$error_message = "The function parameters missing are ";
	foreach ($listParameters as $parameterName => $parameterValue) {
		if(!$parameterValue) {
			$error_message = $error_message . $parameterName.",";
		}
	}
    $error_message = substr($error_message, 0, -1);
    $result = Array("error" => $error_message);
	return $result;
}

// Switchboard to Facilities Management Functions
function facility_management_switch($getFunctions)
{
	// Define the possible Facilities Management function URLs which the page can be accessed from
	$possible_function_url = array("getClassrooms", "addClassroom", "getClassroom", "getClassroomReservations", "updateClassroom", "deleteClassroom", "reserveClassroom", "searchClassrooms", "addDevice", "getDevices", "getDevice", "updateDevice", "deleteDevice");

	if ($getFunctions)
	{
		return $possible_function_url;
	}

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			case "getClassrooms":
				return getClassrooms();
			case "addClassroom":
				if (isset($_POST["building"]) && isset($_POST["room"]) && isset($_POST["capacity"]))
				{
					return addClassroom($_POST["building"], $_POST["room"], $_POST["capacity"]);
				}
				else
				{
                    $listParameters = array("building" => isset($_POST["building"]),
											"room"=> isset($_POST["room"]),
											"capacity"=> isset($_POST["capacity"])
						);
                    $result = create_response_error($listParameters);
                    logError($result["error"]);
                    return $result;
				}
			case "getClassroom":
				if (isset($_GET["id"]))
				{
					return getClassroom($_GET["id"]);
				}
				else
				{
                    $listParameters = array('id' => isset($_GET["id"]));
                    $result = create_response_error($listParameters);
                    logError($result["error"]);
                    return $result;
				}
                
            case "getClassroomReservations":
                if (isset($_GET["id"]))
				{
					return getClassroomReservations($_GET["id"]);
				}
				else
				{
                    $listParameters = array('id' => isset($_GET["id"]));
                    $result = create_response_error($listParameters);
                    logError($result["error"]);
                    return $result;
				}
                
			case "updateClassroom":
				if (isset($_POST["id"]) && isset($_POST["building"]) && isset($_POST["room"]) && isset($_POST["capacity"]))
				{
					return updateClassroom($_POST["id"], $_POST["capacity"], $_POST["room"], $_POST["building"]);
				}
				else
				{
                    $listParameters = array('id' => isset($_POST["id"]),
                    	"building" => isset($_POST["building"]),
                        "room"=> isset($_POST["room"]),
                        "capacity"=> isset($_POST["capacity"])
                    );
                    $result = create_response_error($listParameters);
                    logError($result["error"]);
                    return $result;
				}
			case "deleteClassroom":
				if (isset($_POST["id"]))
				{
					return deleteClassroom($_POST["id"]);
				}
				else
				{
                    $listParameters = array('id' => isset($_POST["id"]));
                    $result = create_response_error($listParameters);
                    logError($result["error"]);
                    return $result;
				}
			case "reserveClassroom":
				if (isset($_POST["id"]) && isset($_POST["day"]) && isset($_POST["section"]) && isset($_POST["timeslot"]) && isset($_POST["length"]))
				{
					return reserveClassroom($_POST["id"], $_POST["day"], $_POST["section"], $_POST["timeslot"], $_POST["length"]);
				}
				else
				{
                    $listParameters = array('id' => isset($_POST["id"]),
                        "building" => isset($_POST["building"]),
                        "room"=> isset($_POST["room"]),
                        "capacity"=> isset($_POST["capacity"])
                    );
                    $result = create_response_error($listParameters);
                    logError($result["error"]);
                    return $result;
				}
			case "searchClassrooms":
				if (isset($_GET["size"]) && isset($_GET["semester"]) && isset($_GET["day"]) && isset($_GET["length"]))
				{
					return searchClassrooms($_GET["size"], $_GET["semester"], $_GET["day"], $_GET["length"]);
				}
				else
				{
                    $listParameters = array('id' => isset($_GET["size"]),
                        "building" => isset($_GET["semester"]),
                        "room"=> isset($_GET["day"]),
                        "capacity"=> isset($_GET["length"])
                    );
                    $result = create_response_error($listParameters);
                    logError($result["error"]);
                    return $result;
				}
			case "addDevice":
				if (isset($_POST["name"]) && isset($_POST["condition"]))
				{
					return addDevice($_POST["name"], $_POST["condition"]);
				}
				else
				{
                    $listParameters = array(
                        "name" => isset($_POST["name"]),
                        "condition"=> isset($_POST["condition"])
                    );
                    $result = create_response_error($listParameters);
                    logError($result["error"]);
                    return $result;
				}
			case "getDevice":
				if (isset($_GET["id"]))
				{
					return getDevice($_GET["id"]);
				}
				else
				{
					$listParameters = array('id' => isset($_GET["id"]));
					$result = create_response_error($listParameters);
					logError($result["error"]);
					return $result;
				}
			case "getDevices":
				return getDevices();
			case "updateDevice":
				if (isset($_POST["id"]) && isset($_POST["condition"]) && isset($_POST["checkoutDate"]) && isset($_POST["checkedOut"]) && isset($_POST["name"]))
				{
                    return updateDevice($_POST["id"], $_POST["condition"], $_POST["checkoutDate"], $_POST["checkedOut"], $_POST["name"], $_POST["returnDate"], $_POST["userId"]);
				}
				else
				{
                    $listParameters = array('id' => isset($_POST["id"]),
                        "condition" => isset($_POST["condition"]),
                        "checkoutDate"=> isset($_POST["checkoutDate"]),
                        "checkedOut"=> isset($_POST["checkedOut"]),
                        "name"=> isset($_POST["name"]),
                        "returnDate"=> isset($_POST["returnDate"]),
                        "userId"=> isset($_POST["userId"])
                    );
                    $result = create_response_error($listParameters);
                    logError($result["error"]);
                    return $result;
				}
			case "deleteDevice":
				if (isset($_POST["uid"]))
				{
					return deleteDevice($_POST["uid"]);
				}
				else
				{
                    $listParameters = array('uid' => isset($_POST["uid"]));
                    $result = create_response_error($listParameters);
                    logError($result["error"]);
                    return $result;
				}
		}
	}
	else
	{
		return "Function does not exist.";
	}
}

//Define Functions Here

function getClassrooms(){
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);
	$query = $sqlite->prepare("SELECT * FROM Classroom");
	$result = $query->execute();

	$records = array();

	while($row = $result->fetchArray(SQLITE3_ASSOC)) {
		array_push($records, $row);
	}

	$result->finalize();
    $sqlite->close();

	return $records;
}

function addClassroom($building, $room, $capacity)
{
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);

	$query = $sqlite->prepare("INSERT INTO Classroom (BUILDING_ID, ROOM_NUM, CAPACITY) VALUES (:building, :room, :capacity)");
	$query->bindParam(':building', $building);
	$query->bindParam(':room', $room);
	$query->bindParam(':capacity', $capacity);

	$result = $query->execute();

	$result->finalize();
    $last_insert = $sqlite->lastInsertRowID();
	$sqlite->close();

	return $last_insert;
}

function getClassroom($id)
{
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);

	$query = $sqlite->prepare("SELECT * FROM Classroom WHERE ID=:id");
	$query->bindParam(':id', $id);
	$result = $query->execute();

	if ($record = $result->fetchArray(SQLITE3_ASSOC))
    {
        $result->finalize();
        $sqlite->close();
        return $record;
    }

	return $result;
}

function updateClassroom($id, $capacity, $rmNumber, $bid)
{
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);

	$query = $sqlite->prepare("UPDATE Classroom SET CAPACITY = :capacity, ROOM_NUM = :rmNumber, BUILDING_ID = :bid WHERE ID=:id");
	$query->bindParam(':id', $id);
	$query->bindParam(':capacity', $capacity);
	$query->bindParam(':rmNumber', $rmNumber);
	$query->bindParam(':bid', $bid);
	$result = $query->execute();

    if(!$result)
	{
	  $record = $sqlite->lastErrorMsg();
	}
	else
	{
	  $record = $sqlite->changes() . " record was updated.";
	}

	$result->finalize();
	$sqlite->close();

	return $record;
}

function deleteClassroom($id)
{
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);

	$query = $sqlite->prepare("DELETE FROM Classroom WHERE ID = :id");
	$query->bindParam(':id', $id);
	$result = $query->execute();

	$result->finalize();
	$sqlite->close();

	return $result;
}

function getClassroomReservations($id)
{
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);
	
	$query = $sqlite->prepare("SELECT * FROM Reservation WHERE CLASSROOM_ID=:id");
	$query->bindParam(':id', $id);		
	$result = $query->execute();
	
	$records = array();

	while($row = $result->fetchArray(SQLITE3_ASSOC)) {
		array_push($records, $row);
	}
    
    $result->finalize();
    $sqlite->close();

	return $records;
}

function reserveClassroom($id, $day, $section, $timeslot, $length)
{
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);

	$query = $sqlite->prepare("INSERT INTO Reservation (CLASSROOM_ID, SECTION_ID, DAY_OF_WEEK, TIME_SLOT_START, DURATION) VALUES (:id, :sectionId, :day, :timeslot, :length)");
	$query->bindParam(':id', $id);
	$query->bindParam(':sectionId', $section);
	$query->bindParam(':day', $day);
	$query->bindParam(':timeslot', $timeslot);
	$query->bindParam(':length', $length);
	$result = $query->execute();

	$result->finalize();

	// clean up any objects
	$sqlite->close();

	return $result;
}

function getValidClassroomTimes($classrooms, $reservations, $length)
{
	$classroomTimes = array();
	$classroomStartTimes = array();

	foreach ($classrooms as $room) {
		$roomId = $room["ID"];
		// Initially all timeslots are available
		$classroomTimes[$roomId] = range(1, 13);
		$classroomStartTimes[$roomId] = array();
	}

	foreach($reservations as $res) {
		$roomId = $res["RES_CLASSROOM_ID"];
		$start_time = $res["TIME_SLOT_START"];
		$duration = $res["DURATION"];

		for($i = $start_time; $i < $start_time + $duration; $i++){
			$iArray = array($i);
			$classroomTimes[$roomId] = array_diff($classroomTimes[$roomId], $iArray);
		}
	}

	foreach ($classroomTimes as $roomId => $times) {
		foreach($times as $timeslot){
			$valid = true;

			for($i = $timeslot + 1; $i <= $timeslot + $length - 1; $i++){
				if(!in_array($i, $times)){
					$valid = false;
				}
			}

			if($valid){
				array_push($classroomStartTimes[$roomId], $timeslot);
			}
		}
	}

	return $classroomStartTimes;
}

function searchClassrooms($capacity, $term, $day, $length)
{
	try
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		$query = $sqlite->prepare("SELECT * FROM Classroom WHERE CAPACITY >= :capacity");
		$query->bindParam(':capacity', $capacity);
		$result = $query->execute();
		$classrooms = array();

		while($row = $result->fetchArray(SQLITE3_ASSOC)) {
			array_push($classrooms, $row);
		}
		$result->finalize();

		$query2 = $sqlite->prepare("SELECT Reservation.CLASSROOM_ID as RES_CLASSROOM_ID, * FROM Reservation INNER JOIN Section WHERE Section.TERM_ID=:term AND Reservation.DAY_OF_WEEK=:day");
		$query2->bindParam(':term', $term);
		$query2->bindParam(':day', $day);
		$result2 = $query2->execute();
		$reservations = array();

		while($row = $result2->fetchArray(SQLITE3_ASSOC)) {
			array_push($reservations, $row);
		}

		$result2->finalize();
		$sqlite->close();

		return getValidClassroomTimes($classrooms, $reservations, $length);
	}
	catch (Exception $exception)
	{
		echo $exception;
		if ($GLOBALS ["sqliteDebug"])
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}


function addDevice($name, $condition)
{
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);

	$query = $sqlite->prepare("INSERT INTO Device (NAME, CONDITION) VALUES (:name, :condition)");
	$query->bindParam(':name', $name);
	$query->bindParam(':condition', $condition);

	$result = $query->execute();

	$result->finalize();
    $last_insert = $sqlite->lastInsertRowID();
	$sqlite->close();

	return $last_insert;
}

function getDevices()
{
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);
	$query = $sqlite->prepare("SELECT * FROM Device");
	$result = $query->execute();
	$records = array();

	while($row = $result->fetchArray(SQLITE3_ASSOC)) {
		array_push($records, $row);
	}

	$result->finalize();
        $sqlite->close();

	return $records;
}

function getDevice($id)
{
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);

	$query = $sqlite->prepare("SELECT * FROM Device WHERE ID=:id");
    	$query->bindParam(':id', $id);

	$result = $query->execute();

    if ($record = $result->fetchArray(SQLITE3_ASSOC))
    {
        $result->finalize();
        $sqlite->close();
        return $record;
    }
}

function updateDevice($id, $condition, $checkoutDate, $checkedOut, $name, $returnDate, $userId)
{
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);

	$query = $sqlite->prepare("UPDATE Device SET CONDITION = :condition, CHECK_OUT_DATE = :checkoutDate, NAME = :name, USER_ID = :userId, CHECKED_OUT = :checkedOut, RETURN_DATE = :returnDate WHERE ID = :id");
	$query->bindParam(':id', $id);
	$query->bindParam(':condition', $condition);
	$query->bindParam(':checkoutDate', $checkoutDate);
	$query->bindParam(':checkedOut', $checkedOut);
	$query->bindParam(':name', $name);
	$query->bindParam(':returnDate', $returnDate);
	$query->bindParam(':userId', $userId);
	$result = $query->execute();

	$result->finalize();
	$sqlite->close();

	return $result;
}

function deleteDevice($uid)
{
	$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
	$sqlite->enableExceptions(true);

	$query = $sqlite->prepare("DELETE FROM Device WHERE ID = :uid");
	$query->bindParam(':uid', $uid);
	$result = $query->execute();

	$result->finalize();
	$sqlite->close();

	return $result;
}

?>