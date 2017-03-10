<?php

$salt = "SWEN344ISAWESOME";
$errorLogFile = "errors.txt";
$databaseFile = "../Database/SWEN344DB.db";

$sqliteDebug = true; //SET TO FALSE BEFORE OFFICIAL RELEASE

function logError($message)
{
	try 
	{
		$myfile = fopen($errorLogFile, "a");
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
	$hash = crypt($string, $salt);
	return $hash;
}

function queryDatabase($queryString)
{
	try 
	{
		$sqlite = SQLite3($databaseFile);
		$sqliteResult = $sqlite->query($queryString);
		
		if (!$sqliteResult and $sqliteDebug) 
		{
			// the query failed and debugging is enabled
			echo "<p>There was an error in query: $query</p>";
			echo $sqlite->lastErrorMsg();
		}
		
		if ($sqliteResult) 
		{
			if ($record = $sqliteResult->fetchArray()) 
			{
				//record was found, do stuff
			}
		}
		
		$sqliteResult->finalize();
		
		// clean up any objects
		$sqlite->close();
	}
	catch (Exception $exception)
	{
		if ($sqliteDebug) {
			echo $exception->getMessage();
		}
	}
}

function loginValid($username, $password)
{
	$valid = FALSE;
	
}


?>