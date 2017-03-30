<?php
// Switchboard to Book Store Functions
function book_store_switch($getFunctions)
{
	// Define the possible Book Store function URLs which the page can be accessed from
	$possible_function_url = array("getBook", "getSectionBooks", "createBook", "findOrCreatePublisher", "toggleBook",
		"orderBook", "findOrCreateAuthor", "viewBookReviews", "updateBook", "searchBooks", "createReview", 
		"viewPurchaseHistory");

	if ($getFunctions)
	{
		return $possible_function_url;
	}
		
	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			case "createBook":
				if (isset($_POST["publisher_name"])){
					$pid = findOrCreatePublisher($_POST["publisher_name"], $_POST["address"], $_POST["website"]);
					$aid = findOrCreateAuthor($_POST["f_name"], $_POST["l_name"]);
				}
				else{
					logError("findOrCreatePublisher ~ Required parameters were not submited correctly.");
					return ("findOrCreatePublisher One or more parameters were not provided");
				}
				if (isset($_POST["isbn"]) &&
					isset($_POST["title"]) &&
					isset($_POST["price"]) &&
					isset($_POST["thumbnail_url"]) &&
					isset($_POST["available"]) &&
					isset($_POST["count"])
				)
				{
					if(getBook($_POST["isbn"])){
						return ("The book with the isbn already exists.");
					} else {
						//Book with the isbn does not yet exist so go
						//ahead and create it.
						return createBook(
							$_POST["isbn"],
							$_POST["title"],
							$pid,
							$_POST["price"],
							$_POST["thumbnail_url"],
							$_POST["available"],
							$_POST["count"]
							);
						}
					}
				else{
					logError("createBook ~ Required parameters were not submited correctly.");
					return ("createBook One or more parameters were not provided");
				}
			case "findOrCreatePublisher":
				logError("log or create pub case");
				if (isset($_POST["publisher_name"])){
					$pid = findOrCreatePublisher($_POST["publisher_name"], $_POST["address"], $_POST["website"]);
					return $pid;
				}
			case "findOrCreateAuthor":
				logError("log or create author case");
				if (isset($_POST["first_name"]) && isset($_POST["last_name"])){
					$aid = findorcreateAuthor($_POST["first_name"], $_POST["last_name"]);
					return $aid;
				}
				else{
					logError("findOrCreateAuthor ~ Required parameters were not submitted correctly.");
					return ("findOrCreateAuthor One or more parameters were not provided");
				}
			case "updateBook":
				if (isset($_POST["publisher_name"])){
					$pid = findOrCreatePublisher($_POST["publisher_name"], $_POST["address"], $_POST["website"]);
					$aid = findOrCreateAuthor($_POST["f_name"], $_POST["l_name"]);
				}
				else{
					logError("findOrCreatePublisher ~ Required parameters were not submited correctly.");
					return ("findOrCreatePublisher One or more parameters were not provided");
				}
				if (isset($_POST["isbn"]) &&
					isset($_POST["title"]) &&
					isset($_POST["price"]) &&
					isset($_POST["thumbnail_url"]) &&
					isset($_POST["available"]) &&
					isset($_POST["count"])
				)
					{
					return updateBook(
						$_POST["isbn"],
						$_POST["title"],
						$pid,
						$_POST["price"],
						$_POST["thumbnail_url"],
						$_POST["available"],
						$_POST["count"]
						);
					}
				else {
					logError("updateBook ~ Required parameters were not submited correctly.");
					return ("updateBook One or more parameters were not provided");
				}
			case "getBook":
				//if has params
				if (isset($_GET["isbn"])){
					return getBook($_GET["isbn"]);
				} else {
					logError("getBook ~ Required isbn parameter was not submitted correctly.");
					return ("getBook book isbn parameter was not submitted correctly.");
				}
				// return "Missing " . $_GET["param-name"]
			case "getSectionBooks":
				//if has params
				if (isset($_GET["section_id"])){
					return getSectionBooks($_GET["section_id"]);
				} else {
					logError("getBook ~ Required isbn parameter was not submitted correctly.");
					return ("getBook book isbn parameter was not submitted correctly.");
				}
	    case "toggleBook":
	        if (isset($_GET["isbn"]) && isset($_GET["available"]))
	        {
	            return toggleBook($_GET["isbn"], $_GET["available"]);
	        }
	        else{
	            logError("getBook ~ Required isbn and-or available parameter not submitted correctly.");
	            return ("toggleBook isbn and-or available parameter not submitted correctly.");
	        }
			case "createReview":
				if (isset($_POST["id"]) &&
					isset($_POST["review"]) &&
					isset($_POST["rating"]) &&
					isset($_POST["book_isbn"]) &&
					isset($_POST["user_id"]))
					{
					return createReview(
						$_POST["id"],
						$_POST["review"],
						$_POST["rating"],
						$_POST["book_isbn"],
						$_POST["user_id"]);
				} else {
					logError("createReview ~ Required parameters not submitted correctly.");
					return ("createReview parameters not submitted correctly.");
				}
			case "orderBook":
                if (isset($_GET["isbn"]) && isset($_GET["amount"]))
                {
                    return orderBook($_GET["isbn"], $_GET["amount"]);
                }
                else{
                    logError("orderBook ~ Required isbn and-or amount parameter not submitted correctly.");
                    return ("orderBook isbn and-or amount parameter not submitted correctly.");
                }
			case "viewBookReviews":
				if (isset($_GET["isbn"])){
					return viewBookReviews($_GET["isbn"]);
				}
				else{
					logError("viewBookReviews ~ Required isbn parameter not submitted correctly.");
                    return ("viewBookReviews isbn parameter not submitted correctly.");
				}

			case "searchBooks":
				if(isset($_GET["search_attribute"])){
					//ONLY PRINTING TO FIND OUT IF YOU WILL NEED TO CHECK IF THE FOLLOWING PARAM 
					//ISSET BEFORE CHECKING TO SEE IF EMPTY
					echo "The Search String Has been set:", "\n";
					echo isset($_GET["search_string"]), "\n";


					$search_string = (empty($_GET["search_string"]) ? "" : $_GET["search_string"]);
					switch ($_GET["search_attribute"]){
						case "isbn":
							return searchBookByAttribute("isbn", $search_string);
						case "title":
							return searchBookByAttribute("title", $search_string);
						case "price":
							return searchBookByAttribute("price", $search_string);
						case "thumbnail_url":
							return searchBookByAttribute("thumbnail_url", $search_string);
						case "available":
							return searchBookByAttribute("available", $search_string);
						//decided not to allow users to search for:
						// case "publisher_name":
						// case "author_name":
						// case "count":
					}
				} else {
					logError("searchBooks ~ Required search_attribute parameter not submitted correctly.");
                    return ("searchBooks search_attribute parameter not submitted correctly.");
				}

			case "viewPurchaseHistory":
				if (isset($_GET["user_id"])){
					return viewPurchaseHistory($_GET["user_id"]);
				}
				else{
					logError("viewPurchaseHistory ~ Required user_id parameter not submitted correctly.");
                    return ("viewPurchaseHistory user_id parameter not submitted correctly.");
				}
		}
	}
}


function searchBookByAttribute($attribute, $search_string){
	//TODO_SEARCH
	//GET THE SEARCH TERM IF ITS SET BUT IF ITS NOT THEN JUST SEARCH EVERY BOOK.
	echo "Search Books Was Called But Was Not Yet Implemented", "\n";
	echo "Try another function call please!", "\n";
}

//Define Functions Here
function createBook($isbn, $title, $publisher_id, $price, $thumbnail_url, $available, $count)
{
	logError("createBook ");
	try
		{
			//$sqlite = new SQLite3($GLOBALS ["databaseFile"]);

			$sqlite = new SQLite3($GLOBALS["databaseFile"]);

			$sqlite->enableExceptions(true);

			//prepare query to protect from sql injection
			$query = $sqlite->prepare("INSERT INTO Book (isbn, title, publisher_id,
						price, thumbnail_url, available, count) VALUES (:isbn, :title, :publisher_id,
							:price, :thumbnail_url, :available, :count)");

			$query->bindParam(':isbn', $isbn);
			$query->bindParam(':title', $title);
			$query->bindParam(':publisher_id', $publisher_id);
			$query->bindParam(':thumbnail_url', $thumbnail_url);
			$query->bindParam(':price', $price);
			$query->bindParam(':available', $available);
			$query->bindParam(':count', $count);
			$result = $query->execute();
			return $result;
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

function findOrCreatePublisher($name, $address, $website){
	logError("findorcreate ");
	try{
		$sqlite = new SQLite3($GLOBALS["databaseFile"]);

		$sqlite->enableExceptions(true);
		$pub_query = $sqlite->prepare("Select id from publisher where name=:name");
		$pub_query->bindParam(":name", $name);	//possible duplicate
		$publisher_id = $pub_query->execute();
		logError('outside of if statemet');
		$pub_id = $publisher_id->fetchArray();
		logError($pub_id[0]);
		if (empty($pub_id)){
			logError("inside if statement");
			$pub_query = $sqlite->prepare("INSERT INTO Publisher (name, address, website)
				VALUES (:name, :address, :website)");
			$pub_query->bindParam(':name', $name);
			$pub_query->bindParam(':address', $address);
			$pub_query->bindParam(':website', $website);
			$pub_query->execute();
			$pub_query = $sqlite->prepare("Select id from publisher where name=:name");
			$publisher_id = $pub_query->execute();
			$pub_id = $publisher_id->fetchArray();
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
	return $pub_id["ID"];
}

function findOrCreateAuthor($f_name, $l_name){
	logError("findorcreateAuthor ");
	try{
		$sqlite = new SQLite3($GLOBALS["databaseFile"]);

		$sqlite->enableExceptions(true);
		$author_query = $sqlite->prepare("Select id from author where first_name=:f_name and last_name=:l_name;");
		$author_query->bindParam(":f_name", $f_name);	//possible duplicate
		$author_query->bindParam(":l_name", $l_name);
		$author_id = $author_query->execute();
		$auth_id = $author_id->fetchArray();
		//logError($auth_id[0]);
		if (empty($auth_id)){
			$auth_query = $sqlite->prepare("INSERT INTO Author (first_name, last_name)
				VALUES (:f_name, :l_name);");
			$auth_query->bindParam(':f_name', $f_name);
			$auth_query->bindParam(':l_name', $l_name);
			$auth_query->execute();
			$auth_query = $sqlite->prepare("Select id from author where first_name=:f_name and last_name=:l_name;");
			$author_id = $auth_query->execute();
			$auth_id = $author_id->fetchArray();
			
			return $auth_id["ID"];
		}
		else
		{
			return NULL;
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

function updateBook($isbn, $title, $publisher_id, $price, $thumbnail_url, $available, $count)
{
	logError("updateBook ");
	try
		{
			$sqlite = new SQLite3($GLOBALS["databaseFile"]);
			$sqlite->enableExceptions(true);
			
			$update_book_query = $sqlite->prepare("UPDATE Book SET title=:title, publisher_id=:publisher_id, price=:price, thumbnail_url=:thumbnail_url, available=:available, count=:count WHERE isbn=:isbn");
			
			$update_book_query->bindParam(':isbn', $isbn);
			$update_book_query->bindParam(':title', $title);
			$update_book_query->bindParam(':publisher_id', $publisher_id);
			$update_book_query->bindParam(':thumbnail_url', $thumbnail_url);
			$update_book_query->bindParam(':price', $price);
			$update_book_query->bindParam(':available', $available);
			$update_book_query->bindParam(':count', $count);
			
			$result = $update_book_query->execute();
			//$book_result = $result->fetchArray();
			
			return $result; //keep in mind that this wont actually return any data since its an update query -Daniel Roberts
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

function getBook($isbn)
{
	logError("findorcreate ");
	try
	{
		$sqlite = new SQLite3($GLOBALS["databaseFile"]);

		$sqlite->enableExceptions(true);
		$book_query = $sqlite->prepare("Select * from book where isbn=:isbn;");
		$book_query->bindParam(':isbn', $isbn);
		//need to get everything out of the dict
		$result = $book_query->execute();
		$book_result = $result->fetchArray();
		
		return $book_result;
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

function getSectionBooks($section_id)
{
	return "TODO";
}

/* Change a book from available to customers to unavailable and vice versa */
function toggleBook($isbn, $isAvailable)
{
    // assert current state is 1 or 0
    $newState = ($isAvailable == 1 ? 0 : 1);
    try
    {
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);

        //prepare query to protect from sql injection
        $query = $sqlite->prepare("UPDATE Book SET available = :newState
                  WHERE isbn= :isbn");

        $query->bindParam(':isbn', $isbn);
        $query->bindParam(':newState', $newState);
        $result = $query->execute();

        return $result;
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
function createReview($id, $review, $rating, $book_isbn, $user_id)
{
	logError("createReview ");
	try{
		$sqlite = new SQLite3($GLOBALS["databaseFile"]);
		$sqlite->enableExceptions(true);

		//prepare query to protect from sql injection
    $query = $sqlite->prepare("INSERT INTO BookReview (id, review, rating,
					book_isbn, user_id) VALUES (:id, :review, :rating,
					:book_isbn, :user_id)");
		$query->bindParam(':id', $id);
		$query->bindParam(':review', $review);
		$query->bindParam(':rating', $rating);
		$query->bindParam(':book_isbn', $book_isbn);
		$query->bindParam(':user_id', $user_id);
		$result = $query->execute();

		return $result;
	}catch (Exception $exception){
		if ($GLOBALS ["sqliteDebug"]){
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function orderBook($isbn, $amount)
{
	logError("orderBook ");
	try
    {
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);

        //prepare query to protect from sql injection
		$count_query = $sqlite->prepare("Select count from Book where isbn=:isbn;");
		$count_query->bindParam(':isbn', $isbn);
		$count_result = $count_query->execute();
		$count = $count_result->fetchArray();
		$total = $count["COUNT"] + $amount;
        $query = $sqlite->prepare("UPDATE Book SET count=:total WHERE isbn=:isbn;");
		$query->bindParam(':isbn', $isbn);
		$query->bindParam(':total', $total);  //update statement not working for some reason
        $result = $query->execute();

        return $total;
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

function viewBookReviews($isbn){
		logError("viewBookReviews ");
	try
    {
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);

        //prepare query to protect from sql injection
		$query = $sqlite->prepare("Select * from BookReview where book_isbn=:isbn;");
		$query->bindParam(':isbn', $isbn);
		$result = $query->execute();
		$review = $result->fetchArray();


        return $review;
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

function viewPurchaseHistory($user_id)
{
	try
    {
        $sqlite = new SQLite3($GLOBALS ["databaseFile"]);
        $sqlite->enableExceptions(true);
		
		
		// select s.status, BookOrder.order_datetime From BookOrder 
		//	Join OrderItem as o on BookOrder.id = o.order_id 
		//	JOIN Book as b on o.book_isbn=b.isbn 
		//	Join OrderStatus as s on BookOrder.status_id = s.id 
		//	where BookOrder.user_id=1;
		
		

        //prepare query to protect from sql injection
		// !!TODO: join to get the author
		$query = $sqlite->prepare("SELECT BookOrder.subtotal, s.status, b.title, b.price 
			FROM BookOrder 
			JOIN OrderItem as o on BookOrder.id = o.order_id
			JOIN Book as b on o.book_isbn=b.isbn 
			JOIN OrderStatus as s on BookOrder.status_id = s.id 
			WHERE BookOrder.user_id=:user_id;");
		// inner join OrderItem as o on BookOrder.ID = o.order_id;
		$query->bindParam(':user_id', $user_id);
		$result = $query->execute();
		
		$bookOrders = array();
		
		// get all the rows until none are left to fetch
		while ( $row = $result->fetchArray() )
		{
			// Add sql row to our final result
			array_push($bookOrders, $row);
		}
		return $bookOrders;
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
