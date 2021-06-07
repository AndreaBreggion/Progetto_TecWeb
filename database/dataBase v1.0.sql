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
    id INT AUTO_INCREMENT PRIMARY KEY,
	photoPath varchar(128) NOT NULL,
	presepeName varchar(48) NOT NULL,
	category ENUM('adulti','ragazzi') NOT NULL,
	description TEXT NOT NULL,
	dateOfCreation date NOT NULL,
	FOREIGN KEY(uId) REFERENCES user(id)
);

CREATE TABLE likes(
    uId INT NOT NULL,
    pId INT NOT NULL,
    CONSTRAINT likeKey PRIMARY KEY (uId, pId),
    FOREIGN KEY (uId) REFERENCES user(id),
    FOREIGN KEY (pId) REFERENCES presepi(id)
);