<?php

///////////////////
//Human Resources//
///////////////////

// Switchboard to Human Resources Functions
function human_resources_switch($getFunctions)
{
	// Define the possible Human Resources function URLs which the page can be accessed from
	$possible_function_url = array("test", "updatePerson", "updateProf", "updateName", "updatePassword", "createProf", "getPersonalInfo", "getProfInfo", "getEmployees", "terminate", "removeEmployee");

	if ($getFunctions)
	{
		return $possible_function_url;
	}

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
            case "test":
                return testThis();

            case "updateProf":
    			if ((isset($_POST["id"]) && $_POST["id"] != null)
					&& (isset($_POST["salary"]) && $_POST["salary"] != null)
					&& (isset($_POST["title"]) && $_POST["title"] != null)
				){
                	return updateProfInfo($_POST["id"], $_POST["salary"], $_POST["title"]);
                }
                else
                {
                	return "Missing a parameter";
                }
                
            case "updatePerson":
            	if ((isset($_POST["username"]) && $_POST["username"] != null)
					&& (isset($_POST["fname"]) && $_POST["fname"] != null)
					&& (isset($_POST["lname"]) && $_POST["lname"] != null)
					&& (isset($_POST["email"]) && $_POST["email"] != null)
					&& (isset($_POST["address"]) && $_POST["address"] != null)
					&& (isset($_POST["phone"]) && $_POST["phone"] != null)
				){
            		return updatePersonalInfo($_POST["username"], $_POST["fname"], $_POST["lname"], $_POST["email"], $_POST["address"], $_POST["phone"]);
				}
				else
                {
                	return "Missing a parameter";
                }
               
            case "updatePassword":
            	if ((isset($_POST["username"]) && $_POST["username"] != null)
					&& (isset($_POST["password"]) && $_POST["password"] != null)
				){
            		return updatePassword($_POST["username"], $_POST["password"]);
				}
				else
                {
                	return "Missing a parameter";
                }
                
            case "updateName":
            	if ((isset($_POST["username"]) && $_POST["username"] != null)
					&& (isset($_POST["fname"]) && $_POST["fname"] != null)
					&& (isset($_POST["lname"]) && $_POST["lname"] != null)
				){
                	return updateFullName($_POST["username"], $_POST["fname"], $_POST["lname"]);
                }
                else
                {
                	return "Missing a parameter";
                }
			case "createProf":
				if ((isset($_POST["username"]) && $_POST["username"] != null)
					&& (isset($_POST["password"]) && $_POST["password"] != null)
					&& (isset($_POST["fname"]) && $_POST["fname"] != null)
					&& (isset($_POST["lname"]) && $_POST["lname"] != null)
					&& (isset($_POST["email"]) && $_POST["email"] != null)
					&& (isset($_POST["role"]) && $_POST["role"] != null)
					&& (isset($_POST["managerID"]) && $_POST["managerID"] != null)
					&& (isset($_POST["title"]) && $_POST["title"] != null)
					&& (isset($_POST["address"]) && $_POST["address"] != null)
					&& (isset($_POST["salary"]) && $_POST["salary"] != null)
					&& (isset($_POST["phone"]) && $_POST["phone"] != null)
				){
					return createProf($_POST["username"],
						$_POST["password"],
						$_POST["fname"],
						$_POST["lname"],
						$_POST["email"],
						$_POST["role"],
						$_POST["managerID"],
						$_POST["title"],
						$_POST["address"],
						$_POST["salary"],
						$_POST["phone"]
						);
						
                }
                else
                {
                    return "Missing a parameter";
                }
			case "getPersonalInfo":
				if ((isset($_POST["username"]) && $_POST["username"] != null)
				){
					return getPersonalInfo($_POST["username"]);
				}
				else
				{
					return "Missing a parameter";
				}
			case "getProfInfo":
				if ((isset($_POST["id"]) && $_POST["id"] != null)
				){
						return getProfessionalInfo($_POST["id"]);
                }
                else
                {
                    return "Missing a parameter";
                }
            case "getEmployees":
				if ((isset($_POST["id"]) && $_POST["id"] != null)
				){
						return getEmployees($_POST["id"]);
                }
                else
                {
                    return "Missing a parameter";
                }
            case "terminate":
				if ((isset($_POST["id"]) && $_POST["id"] != null)
				){
						return terminate($_POST["id"]);
                }
                else
                {
                    return "Missing a parameter";
                }
            case "removeEmployee":
				if ((isset($_POST["id"]) && $_POST["id"] != null)
				){
						return removeEmployee($_POST["id"]);
                }
                else
                {
                    return "Missing a parameter";
                }
		}
	}
	else
	{
		return "Function does not exist.";
	}
}

//Define Functions Here

// Test connection for Human Resource
function testThis()
{
    return "MOO";
}

// Update First and Last name with username
// Input Parameters:
//  First name, Last name
// Main Input Parameter:
//  Username
function updateFullName($username, $fname, $lname)
{
    $success = false;
    try
    {
        // Open a connection to database
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);
        // Prevent SQL Injection
        $query = $sqlite->prepare("UPDATE User SET FIRSTNAME=:fname, LASTNAME=:lname WHERE USERNAME=:username");
        // Set variables to query
        $query->bindParam(':username',$username);
        $query->bindParam(':fname',$fname);
        $query->bindParam(':lname',$lname);
        $query->execute();
        // Clear up the connection
        $sqlite->close();
        $success = true;
    }
    catch (Exception $exception)
    {
        if ($GLOBALS ["sqliteDebug"])
        {
			return $exception->getMessage();
		}
		logError($exception);
	}
	
	return $success;
}

// Update password with username
// Input parameter:
//  Password
// Main Input Parameter to update specific user:
//  Username
function updatePassword($username, $password)
{
    $success = false;

    try
    {
        // Open a connection to database
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);
        // Prevent SQL Injection
        $query = $sqlite->prepare("UPDATE User SET PASSWORD=:password WHERE USERNAME=:username");
        // Set variables to query
        $query->bindParam(':username',$username);
        $query->bindParam(':password',encrypt($password));
        $query->execute();
        // Clear up the connection
        $sqlite->close();
        $success = true;
    }
    catch (Exception $exception)
    {
        if ($GLOBALS ["sqliteDebug"])
        {
            return $exception->getMessage();
        }
		logError($exception);
    }
    return $success;
}

// Update personal information with username
// Input parameters:
//  First name, Last name, Email, Address Phone
// Main Input Parameter to update specific user:
//  Username
function updatePersonalInfo($username, $fname, $lname, $email, $address, $phone)
{
    $success = false;
	
    try
    {
		// Open a connection to database
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);
		// Prevent SQL Injection
        $query = $sqlite->prepare("UPDATE User SET FIRSTNAME=:fname, LASTNAME=:lname, EMAIL=:email WHERE USERNAME=:username");
		// Set variables to query
        $query->bindParam(':username', $username);
        $query->bindParam(':fname', $fname);
        $query->bindParam(':lname', $lname);
        $query->bindParam(':email', $email);
		
        $result = $query->execute();
        $result->finalize();

        // Prevent SQL Injection
        $query_id = $sqlite->prepare("SELECT ID FROM User WHERE USERNAME=:username");
        // Set variables to query
        $query_id->bindParam(":username", $username);
        $result = $query_id->execute();

        if($record = $result->fetchArray(SQLITE3_ASSOC))
        {
            $result->finalize();
        }
        else
        {
            return "Something went wrong";
        }
        $userId = $record['ID'];

        // Prevent SQL Injection
        $query = $sqlite->prepare("UPDATE UniversityEmployee SET ADDRESS=:address, PHONE=:phone WHERE USER_ID=:userId");
        // Set variables to query
        $query->bindParam(":address", $address);
        $query->bindParam(":phone", $phone);
        $query->bindParam(":userId", $userId);
        $query->execute();
        // Clear up the connection
		$sqlite->close();
		
        $success = true;
    }
    catch (Exception $exception)
    {
        if($GLOBAL ["sqliteDebug"])
        {
            return $exception->getMessage();
        }
		logError($exception);
    }
	
    return $success;
}

// Update professional information with username
// Input parameters:
//  Salary, Title
// Main Input Parameter to update specific user:
//  USER_ID
function updateProfInfo($id, $salary, $title)
{
    $success = false;

    try
    {
		// Open a connection to database
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);

		// Prevent SQL Injection
        $query = $sqlite->prepare("UPDATE UniversityEmployee SET SALARY=:salary, TITLE=:title WHERE USER_ID=:id");
		// Set variables to query
        $query->bindParam(':id', $id);
        $query->bindParam(':salary', floatval($salary));
        $query->bindParam(':title', $title);
        $query->execute();

		// Clear up the connection
        $sqlite->close();
        $success = true;
    } 
    catch(Exception $exception)
    {
        if ($GLOBALS ["sqliteDebug"])
        {
            return $exception->getMessage();
        }
		logError($exception);
    }
	
    return $success;
}

// Get personal information with username
// Input Parameters:
//  Username
function getPersonalInfo($username)
{
	$success = false;

	try
	{
		// Open a connection to database
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		// Prevent SQL Injection
		$query = $sqlite->prepare("SELECT * FROM User WHERE USERNAME=:username");
		// Set variables to query
		$query->bindParam(':username', $username);
		$result = $query->execute();
		
		if($record = $result->fetchArray(SQLITE3_ASSOC))
		{
			$result->finalize();
			$sqlite->close();
		
			return $record;
		}
		
	}
	catch(Exception $exception)
	{
		if($GLOBALS ["sqliteDebug"])
		 {
			return $exception->getMessage();
		 }
		 
		 logError($exception);
	}
	
	return $success;
}

// Get professional information (such as salary, title, etc) with USER_ID
// Input Parameters:
//  USER_ID
function getProfessionalInfo($id)
{
	$success = false;

	try
	{
		// Open a connection to database
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite-> enableExceptions(true);
		// Prevent SQL Injection
		$query = $sqlite->prepare("SELECT * FROM UniversityEmployee WHERE USER_ID=:id");
		// Set variables to query
		$query->bindParam(':id', $id);
		$result = $query->execute();
		
		if($record = $result->fetchArray(SQLITE3_ASSOC))
		{
			$result->finalize();
			$sqlite->close();
		
			return $record;
		}

	}
	catch(Exception $exception)
	{
		if($GLOBALS ["sqliteDebug"])
		{
			return $exception->getMessage();
		}

		logError($exception);
	}

	return $success;
}

// Creates a new professional user
// Input Parameters:
//  Username, Password, First name, Last name, Email, Role, ManagerID, Title, Address, Salary
function createProf($username, $password, $fname, $lname, $email, $role, $managerId, $title, $address,
	$salary, $phone)
{
	$success = false;

	try
	{
		// Open a connection to database
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);

		// Prevent SQL Injection
		// first check if the username already exists
		$query = $sqlite->prepare("SELECT * FROM User WHERE USERNAME=:username");
		// Set variables to query
		$query->bindParam(':username', $username);
		$result = $query->execute();

		if ($record = $result->fetchArray())
		{
			return "Username Already Exists";
		}

		// for varaible reuse
		$result->finalize();
		
		// Prevent SQL Injection
		$query1 = $sqlite->prepare("INSERT INTO User (USERNAME, PASSWORD, FIRSTNAME, LASTNAME, EMAIL,
			ROLE) VALUES (:username, :password, :fname, :lname, :email, :role)");
		// Set variables to query
		$query1->bindParam(':username', $username);
		$query1->bindParam(':password', encrypt($password));
		$query1->bindParam(':fname', $fname);
		$query1->bindParam(':lname', $lname);
		$query1->bindParam(':email', $email);
		$query1->bindParam(':role', $role);
        
		$result = $query1->execute();
        // Release variable
        $result->finalize();

        // Prevent SQL Injection
        $query_id = $sqlite->prepare("SELECT ID FROM User WHERE USERNAME=:username");
        // Set variables to query
        $query_id->bindParam(":username", $username);
        $result = $query_id->execute();

        if($record = $result->fetchArray(SQLITE3_ASSOC))
        {
            $result->finalize();
        }
        else
        {
            return "Something went wrong";
        }
        $userId = $record['ID'];

		// Prevent SQL Injection
		$query2 = $sqlite->prepare("INSERT INTO UniversityEmployee (USER_ID, MANAGER_ID, TITLE,
			ADDRESS, SALARY, PHONE) VALUES (:userId, :managerId, :title, :address, :salary, :phone)");
		// Set variables to query
		$query2->bindParam(':userId', $userId);
		$query2->bindParam(':managerId', $managerId);
		$query2->bindParam(':title', $title);
		$query2->bindParam(':address', $address);
		$query2->bindParam(':salary', floatval($salary));
		$query2->bindParam(':phone', $phone);

		$query2->execute();

		// clean up any objects
		$sqlite->close();

		// if it gets here without throwing an error, assume success = true;
		$success = true;
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"])
		{
			return $exception->getMessage();
		}
		logError($exception);
	}

	return $success;
}

// Get a list of employees where they are under managerID
// Input Parameters:
//  ID
function getEmployees($id) 
{
    try 
	{
		$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
		$sqlite->enableExceptions(true);
		
		//prepare query to protect from sql injection
		$query = $sqlite->prepare("SELECT * FROM UniversityEmployee WHERE MANAGER_ID=:id");	
		$query->bindParam(":id", $id);
		$result = $query->execute();
		
		$record = array();
		//$sqliteResult = $sqlite->query($queryString);
		while($emp=$result->fetchArray(SQLITE3_ASSOC))
		{
			array_push($record, $emp);
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
	return "ManagerID is not found";
}

// Mark a University Employee as terminated
// Input Parameters: 
//  ID
function terminate($id)
{
	$success = false;
	try
	{
		// Open a connection to database
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);

		// Prevent SQL Injection
		$terminated = 1;
        $query = $sqlite->prepare("UPDATE UniversityEmployee SET IS_TERMINATED=:terminated WHERE USER_ID=:id");
		// Set variables to query
        $query->bindParam(':id', $id);
        $query->bindParam(':terminated', $terminated);
        $query->execute();

		// Clear up the connection
        $sqlite->close();
        $success = true;
    } 
    catch(Exception $exception)
    {
        if ($GLOBALS ["sqliteDebug"])
        {
            return $exception->getMessage();
        }
		logError($exception);
    }
	
    return $success;
}

// Remove University Employee from the data base
// Input Parameters: 
//  ID
function removeEmployee($id)
{
	$success = false;
	try
	{
		// Open a connection to database
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);

        // Prevent SQL Injection
        $query = $sqlite->prepare("SELECT MANAGER_ID FROM UniversityEmployee WHERE USER_ID=:id");
        // Set variables to query
        $query->bindParam(':id', $id);
        $result = $query->execute();

        if($record = $result->fetchArray(SQLITE3_ASSOC))
        {
            $result->finalize();
        }
        $managerID = $record["MANAGER_ID"];

        // Prevent SQL Injection
        $query2 = $sqlite->prepare("UPDATE UniversityEmployee SET MANAGER_ID=:managerID WHERE MANAGER_ID=:id");
		// Set variables to query
		$query2->bindParam(':managerID', $managerID);
        $query2->bindParam(':id', $id);
        $query2->execute();

		// Prevent SQL Injection
        $query3 = $sqlite->prepare("DELETE FROM UniversityEmployee WHERE USER_ID=:id");
		// Set variables to query
        $query3->bindParam(':id', $id);
        $query3->execute();

        // Prevent SQL Injection
        $query4 = $sqlite->prepare("DELETE FROM User WHERE ID=:id");
        // Set variables to query
        $query4->bindParam(':id', $id);
        $query4->execute();

		// Clear up the connection
        $sqlite->close();
        $success = true;
    } 
    catch(Exception $exception)
    {
        if ($GLOBALS ["sqliteDebug"])
        {
            return $exception->getMessage();
        }
		logError($exception);
    }
	
    return $success;
}

?>
