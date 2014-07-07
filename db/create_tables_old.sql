/**
* Assumptions...only discovered after writing out all the indexes on foreign keys columns below
* Only the MySQL InnoDB engine supports foreign keys
* MySQL Innob indexes foreign key colums automatically 
* http://stackoverflow.com/questions/304317/does-mysql-index-foreign-key-columns-automatically
*/

SHOW DATABASES;
USE ticket_tracker;
SHOW TABLES;

SELECT * FROM users;

CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(32) NOT NULL,
  user_type_id INT NOT NULL,
  permission_type_id INT NOT NULL,
  UNIQUE(name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	

CREATE TABLE IF NOT EXISTS tickets (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  type_id INT NOT NULL,
  title varchar(255) NOT NULL,
  description mediumtext NOT NULL,
  assigned_to_id INT NOT NULL,
  reported_by_id INT NOT NULL,
  status_type_id INT NOT NULL,
  due_date date,
  FOREIGN KEY (assigned_to_id) REFERENCES users (id) ON DELETE NO ACTION,
  FOREIGN KEY (reported_by_id) REFERENCES users (id) ON DELETE NO ACTION
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SELECT * FROM tickets;

CREATE TABLE IF NOT EXISTS user_types (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	type varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS user_permission_types (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	type varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ticket_types (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	type varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ticket_status_types (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	type varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS comments (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	ticket_id INT NOT NULL,
	created_date DATETIME NOT NULL,
	edited_date datetime NOT NULL,
	comment TEXT,
	FOREIGN KEY (ticket_id) REFERENCES tickets (id) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS attachments (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	ticket_id INT NOT NULL,
	filepath VARCHAR(255) NOT NULL,
	FOREIGN KEY (ticket_id) REFERENCES tickets (id) ON DELETE NO ACTION
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


/****** Add foreign keys where columns didn't exist at time of table creation *****************/
ALTER TABLE users ADD CONSTRAINT fk_user_type_id FOREIGN KEY (user_type_id) references user_types (id) ON DELETE NO ACTION;
ALTER TABLE users ADD CONSTRAINT fk_permission_type_id FOREIGN KEY (permission_type_id) references user_permission_types (id) ON DELETE NO ACTION;

ALTER TABLE tickets ADD CONSTRAINT fk_ticket_type_id FOREIGN KEY (type_id) references ticket_types (id) ON DELETE NO ACTION;
ALTER TABLE tickets ADD CONSTRAINT fk_status_type_id FOREIGN KEY (status_type_id) references ticket_status_types (id) ON DELETE NO ACTION;
