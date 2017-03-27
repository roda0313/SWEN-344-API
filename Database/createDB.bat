@echo off

set fileName="SWEN344DB.db"
ECHO This action will completely delete any existing database.
set /p selection=Are you sure you wish to continue? [y/n]?:
echo %selection%
IF "%selection%" == "y" (
	IF EXIST %fileName% (
		del %fileName% 
	)
	copy NUL %fileName%
	sqlite3 %fileName% < "GeneralTables.sql"
	sqlite3 %fileName% < "TeamCoopEval.sql"
	sqlite3 %fileName% < "TeamBookStore.sql"
	sqlite3 %fileName% < "TeamHumanResources.sql"
	sqlite3 %fileName% < "TeamEnrollment.sql"
	sqlite3 %fileName% < "TeamFacilityManagement.sql"
)
pause 