 CREATE TABLE user(
    id INT AUTO_INCREMENT PRIMARY KEY,
	mail varchar(48) NOT NULL,
	name varchar(24) NOT NULL,
	surname varchar(24) NOT NULL,
	password varchar(64) NOT NULL,
    username varchar(24) NOT NULL,
    UNIQUE (mail),
    UNIQUE (username)
);

CREATE TABLE presepi(
    uId INT NOT NULL,
	mail varchar(48) NOT NULL,
	username varchar(24) NOT NULL,
	photoPath varchar(128) NOT NULL,
	presepeName varchar(48) NOT NULL,
	address varchar(64) NOT NULL,
	category ENUM('adulti','ragazzi') NOT NULL,
	description varchar(200),
	dateOfCreation date NOT NULL,
	PRIMARY KEY (uId, presepeName),
	FOREIGN KEY(uId) REFERENCES user(id)
);