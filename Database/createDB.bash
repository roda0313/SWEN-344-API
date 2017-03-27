#!/bin/bash
# WARNING THIS SCRIPT WILL DELETE ANY EXISTING DB
fileName="SWEN344DB.db";

read -p "This action will completely delete any existing database. Are you sure you wish to continue? [y/n]" selection; 
if [ $selection == "y" ] 
then 
	if [ -f $fileName ]; then
		rm $fileName;
	fi
	touch $fileName;
	sqlite3 $fileName < "GeneralTables.sql";
	sqlite3 $fileName < "TeamCoopEval.sql";
	sqlite3 $fileName < "TeamBookStore.sql";
	sqlite3 $fileName < "TeamHumanResources.sql";
	sqlite3 $fileName < "TeamEnrollment.sql";
	sqlite3 $fileName < "TeamFacilityManagement.sql";
fi

chmod a+rwx $fileName;