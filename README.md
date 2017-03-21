# API For SWEN-344 Course Project

This is the common API all features will make requests against.

More details available [on the course website](http://www.se.rit.edu/~swen-344/projects/projectdescription.html).

## Contributing

This is a simple API running on PHP, backed by SQLITE3. As such, you should be developing on a machine with SQLITE3 installed, and you should have the ability to serve PHP files. Each team has their own `*.sql` file in the [`Database`](https://github.com/roda0313/SWEN-344-API/blob/master/Database/) directory. Add the custom tables specific to your team in your associated `.sql` file. Common tables, such as Student, should be placed in the [`GeneralTables.sql`](https://github.com/roda0313/SWEN-344-API/blob/master/Database/GeneralTables.sql) file. 

Include any seed data you would like included on database creation in a `DummyTABLEData.sql` file. Be sure to to append it to the list in the associated script files that create/populate the database. More info in the next section. 

### Git Workflow

To make sure we can all have consensus on new additions to the project we can use what's referred to as the fork-and-pull strategy. With Github, this works by each team member creating their own "fork" of the repo by clicking the fork button at the top of the repo's page. After you fork, you'll have an exact duplicate of the repo under your own account name. This personal copy is the one you should clone from, and it will be known as the "origin" remote on your work machine. In order to retrieve updates that others have pushed to the main repository, you need to add that as an additional remote, this is usually called "upstream". For us, the upstream can be added in the command line using `git remote add upstream https://github.com/roda0313/SWEN-344-API.git`

Now, you have remotes called origin and upstream. Origin is the one that you'll push to whenever you're ready to merge your working code into the main project. Pushing to origin will land your commits on your personal fork. After that, you can go to Github and click "open pull request", which will allow you to give a description of the additions you've made and will become a forum for us to comment on the code before merging it in. In order to pull the latest work contributed by others, use the upstream remote. Ideally, use the command `git pull -r upstream master`, which will merge the latest code via a rebase, meaning that the new commits will be pulled to your local machine, but it won't create a new commit that acknowledges the merge. This helps keep things clean.

### Git Setup

Setting up for the first time

#### Clone repo for the first time and configure remotes

```
cd location-you-want-the-repo
git clone https://github.com/your-github-user/repository-name
cd SWEN-341-API
git remote add upstream https://github.com/roda0313/SWEN-344-API.git
```

#### Set up git to commit with your name and email

```
git config --global user.name "Your name"
git config --global user.email "your@email.com"
git status
git add <files>
git commit -m "message"
git pull -r upstream master // Downloads latest updates from shared repo.
git push origin master // Pushes your local commits to your fork.
```

## Creating the database

Two scripts exist to create the database. The first, `createDB.bat` is for running on Windows. The second, `createDB.bash` is for running on Linux. Simply run these from within the `Database` directory to create your database structure.