<?php

$errorLogFile = "errors.txt";
$databaseFile = getcwd(). "/../Database/SWEN344DB.db";

$sqliteDebug = true; //SET TO FALSE BEFORE OFFICIAL RELEASE

//////////////////////
//General Functions///
//////////////////////

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


////////////////
//team based functions
///////////////

?>