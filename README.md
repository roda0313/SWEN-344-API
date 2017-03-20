# API For SWEN-344 Course Project

This is the common API all features will make requests against.

More details available [on the course website](http://www.se.rit.edu/~swen-344/projects/projectdescription.html).

## Contributing

This is a simple API running on PHP, backed by SQLITE3. As such, you should be developing on a machine with SQLITE3 installed, and you should have the ability to serve PHP files. Each team has their own `*.sql` file in the [`Database`](https://github.com/roda0313/SWEN-344-API/blob/master/Database/) directory. Add the custom tables specific to your team in your associated `.sql` file. Common tables, such as Student, should be placed in the [`GeneralTables.sql`](https://github.com/roda0313/SWEN-344-API/blob/master/Database/GeneralTables.sql) file. 

Include any seed data you would like included on database creation in a `DummyTABLEData.sql` file. Be sure to to append it to the list in the associated script files that create/populate the database. More info in the next section. 

## Creating the database

Two scripts exist to create the database. The first, `createDB.bat` is for running on Windows. The second, `createDB.bash` is for running on Linux. Simply run these from within the `Database` directory to create your database structure.