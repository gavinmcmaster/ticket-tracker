/**
* Assumptions...only discovered after writing out all the indexes on foreign keys columns below
* Only the MySQL InnoDB engine supports foreign keys
* MySQL Innob indexes foreign key colums automatically 
* http://stackoverflow.com/questions/304317/does-mysql-index-foreign-key-columns-automatically
*/

SHOW DATABASES;
USE ticket_tracker;
SHOW TABLES;

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
	id INT NOT NULL,
  reported_by_id INT NOT NULL,
  status_type_id INT NOT NULL,
  due_date date,
  FOREIGN KEY (assigned_to_id) REFERENCES users (id) ON DELETE NO ACTION,
  FOREIGN KEY (reported_by_id) REFERENCES users (id) ON DELETE NO ACTION
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS user_types (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	type varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS user_permission_types (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	type varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ticket_resolution_types (
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

/*** rename ****/
RENAME TABLE ticket_types TO ticket_priority_types;

/***** all alter statements *****/
/****** Add foreign keys where columns didn't exist at time of table creation *****************/
ALTER TABLE users ADD CONSTRAINT fk_user_type_id FOREIGN KEY (user_type_id) references user_types (id) ON DELETE NO ACTION;
ALTER TABLE users ADD CONSTRAINT fk_permission_type_id FOREIGN KEY (permission_type_id) references user_permission_types (id) ON DELETE NO ACTION;

ALTER TABLE tickets ADD CONSTRAINT fk_ticket_type_id FOREIGN KEY (type_id) references ticket_types (id) ON DELETE NO ACTION;
ALTER TABLE tickets ADD CONSTRAINT fk_status_type_id FOREIGN KEY (status_type_id) references ticket_status_types (id) ON DELETE NO ACTION;

ALTER TABLE tickets DROP COLUMN due_date;
ALTER TABLE tickets DROP FOREIGN KEY fk_ticket_type_id;

ALTER TABLE tickets DROP COLUMN priority_type_id;
ALTER TABLE tickets ADD priority_type_id INT NOT NULL;
ALTER TABLE tickets ADD resolution_type_id INT UNSIGNED NOT NULL;
ALTER TABLE tickets MODIFY resolution_type_id INT NOT NULL;
ALTER TABLE tickets MODIFY assigned_to_id INT NULL;
ALTER TABLE tickets MODIFY resolution_type_id INT;

ALTER TABLE tickets ADD CONSTRAINT fk_priority_type_id FOREIGN KEY (priority_type_id) references ticket_priority_types (id) ON DELETE NO ACTION;
ALTER TABLE tickets ADD CONSTRAINT fk_resolution_type_id FOREIGN KEY (resolution_type_id) references ticket_resolution_types (id) ON DELETE NO ACTION;

ALTER TABLE tickets ALTER COLUMN status_type_id SET DEFAULT 1;
ALTER TABLE user_types ADD UNIQUE(type);
ALTER TABLE user_permission_types ADD UNIQUE(type);
ALTER TABLE users ALTER COLUMN permission_type_id SET DEFAULT 1;

ALTER TABLE tickets ADD created_date DATETIME NOT NULL;
ALTER TABLE tickets ADD resolved_date DATETIME;
ALTER TABLE tickets CHANGE created_date created_time DATETIME NOT NULL;
ALTER TABLE tickets CHANGE resolved_date resolved_time DATETIME;

ALTER TABLE comments CHANGE created_date created_time DATETIME;
ALTER TABLE comments CHANGE edited_date edited_time DATETIME;
ALTER TABLE comments ADD added_by_id INT NOT NULL;
ALTER TABLE comments ADD CONSTRAINT fk_added_by_id FOREIGN KEY (added_by_id) references users (id) ON DELETE NO ACTION;


/**** statements that don't work ******/
DELETE FROM user_types; /* won't work in safe mode without WHERE condition */
DROP TABLE user_types; /* Cannot delete: a foreign key constraint fails */

/**** update ****/
UPDATE users SET permission_type_id=3 WHERE name='gav';
UPDATE tickets SET created_time=NOW() WHERE created_time < (NOW() - INTERVAL 10 MINUTE);
UPDATE tickets SET created_time='2014-07-03 13:03:22' WHERE created_time < (NOW() - INTERVAL 10 MINUTE);
UPDATE tickets SET created_time=NOW() WHERE id=15;	

UPDATE tickets SET status_type_id = 2 WHERE assigned_to_id IS NOT NULL;

/**** all insert *****/
INSERT INTO users (name, email, password, user_type_id) VALUES ("test", "test@test.com", "password", 1);

INSERT INTO user_types (type) VALUES ('graphics');
INSERT INTO user_types (type) VALUES ('programmer');
INSERT INTO user_types (type) VALUES ('project manager');
INSERT INTO user_types (type) VALUES ('support');
INSERT INTO user_types (type) VALUES ('qa');

INSERT INTO user_permission_types (type) VALUES('view');
INSERT INTO user_permission_types (type) VALUES('update');
INSERT INTO user_permission_types (type) VALUES('crud');
INSERT INTO user_permission_types (type) VALUES('admin');

INSERT INTO ticket_priority_types (type) VALUES ('minor'), ('major'), ('critical');
INSERT INTO ticket_status_types (type) VALUES ('new'),('assigned'),('closed');
INSERT INTO ticket_types (type) VALUES ('task'),('enhancement'),('defect');
INSERT INTO ticket_resolution_types (type) VALUES ('fixed'),('invalid'),('wontfix'),('duplicate'),('worksforme');

INSERT INTO tickets (type_id, title, description, reported_by_id, priority_type_id, assigned_to_id) VALUES (1, "test title", "test description", 9, 1, 14);

/**** random delete *****/
DELETE FROM user_types WHERE id > 5;
DELETE FROM user_permission_types WHERE id > 4;
DELETE FROM users WHERE id >0;
DELETE FROM users WHERE name = '';

/*** empty table ****/
TRUNCATE users;

/***** random select ********/
SELECT * FROM users;
SELECT * FROM user_types;
SELECT * FROM user_permission_types;
SELECT * FROM tickets;
SELECT * FROM ticket_types;
SELECT * FROM ticket_priority_types;
SELECT * FROM ticket_status_types;
SELECT * from ticket_resolution_types;
SELECT * from comments;

SELECT * FROM users WHERE id=14; 
SELECT * FROM tickets WHERE created_time < (NOW() - INTERVAL 20 MINUTE); 

/*** query table structure ***/
SHOW CREATE TABLE tickets;
DESCRIBE tickets;

