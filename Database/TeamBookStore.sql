/* Team Book Store tables */
PRAGMA foreign_keys = ON;

CREATE TABLE publisher (
	id integer PRIMARY KEY AUTOINCREMENT,
	name varchar,
	info text
);

CREATE TABLE book (
	isbn integer PRIMARY KEY,
	title varchar,
	published_by integer,
	price real,
	available integer,
	count integer,
	FOREIGN KEY (published_by) REFERENCES publisher(id)
);


CREATE TABLE author (
	id integer PRIMARY KEY AUTOINCREMENT,
	first_name varchar,
	last_name varchar
);

CREATE TABLE book_author (
	book_isbn integer,
	author_id integer,
	PRIMARY KEY (book_isbn, author_id),
	FOREIGN KEY (book_isbn) REFERENCES book(isbn),
	FOREIGN KEY (author_id) REFERENCES author(id)
);


CREATE TABLE book_preview (
	id integer PRIMARY KEY AUTOINCREMENT,
	preview text,
	rating integer,
	book_isbn integer,
	user_id integer,
	FOREIGN KEY (book_isbn) REFERENCES book(id)
);

CREATE TABLE book_order (
	id integer PRIMARY KEY AUTOINCREMENT,
	order_datetime datetime default current_timestamp,
	status integer,
	subtotal real,
	user_id integer,
	FOREIGN KEY (status) REFERENCES order_status(id)
);

CREATE TABLE order_status (
	id integer PRIMARY KEY AUTOINCREMENT,
	status varchar
);

CREATE TABLE order_item (
	order_id integer,
	book_isbn integer,
	PRIMARY KEY (order_id, book_isbn),
	FOREIGN KEY (book_isbn) REFERENCES book(isbn),
	FOREIGN KEY (order_id) REFERENCES book_order(id)
);

CREATE TABLE course_book (
	course_id integer,
	book_isbn integer,
	FOREIGN KEY (book_isbn) REFERENCES book(id)
);
