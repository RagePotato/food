CREATE DATABASE food;
USE food;

CREATE TABLE groceries(
	name_id	INT UNSIGNED		NOT NULL	AUTO_INCREMENT,
	price_per_ounce	FLOAT(4,4)	NOT NULL	DEFAULT	0,
	price_per_pound	FLOAT(4,4)	NOT NULL	DEFAULT	0,
	PRIMARY KEY	(name_id)
) ENGINE=InnoDB;

CREATE TABLE names(
	name	VARCHAR(100)		NOT NULL,
	common	BOOL/*TINYINT(1)*/	NOT NULL	DEFAULT=0,
	name_id	INT UNSIGNED		NOT NULL,
	FOREIGN KEY	(name_id) REFERENCES groceries(name_id)
) ENGINE=InnoDB;

CREATE TABLE type(
	type_id INT UNSIGNED		NOT NULL	AUTO_INCREMENT,
	type_name	VARCHAR(100)	NOT NULL,
	PRIMARY KEY	(type_id)
) ENGINE=InnoDB;

CREATE TABLE food_type_bridge(
	food_id	INT UNSIGNED		NOT NULL,
	type_id	INT UNSIGNED		NOT NULL,
	FOREIGN KEY	(food_id) REFERENCES groceries(name_id),
	FOREIGN KEY (type_id) REFERENCES type(type_id)
) ENGINE=InnoDB;

CREATE TABLE meal(
	name	VARCHAR(100)		NOT NULL,
	description	VARCHAR(255),
	instructions	MEDIUMTEXT	NOT NULL,
	id	INT UNSIGNED			NOT NULL	AUTO_INCREMENT,
	PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE pics(
	filename	VARCHAR(52)		NOT NULL,
	meal_id		INT UNSIGNED	NOT NULL,
	FOREIGN KEY(meal_id) REFERENCES meal(id)
) ENGINE=InnoDB;

CREATE TABLE ingredients_bridge(
	pounds	FLOAT(4,4),
	ounces	FLOAT(4,4),
	ingredient	INT UNSIGNED	NOT NULL,
	FOREIGN KEY (ingredient) REFERENCES groceries(name_id),
	FOREIGN KEY (meal) REFERENCES meal(id)
) ENGINE+InnoDB;