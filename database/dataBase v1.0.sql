CREATE TABLE user(
	mail varchar(48) NOT NULL PRIMARY KEY,
	nome varchar(24) NOT NULL,
	cognome varchar(24) NOT NULL,
	password varchar(64) NOT NULL);

CREATE TABLE presepi(
	mail varchar(48) NOT NULL,
	fotoPresepe varchar(128) NOT NULL,
	fotoNatalita varchar(128) NOT NULL,
	nomePresepe varchar(48) NOT NULL,
	indirizzo varchar(64) NOT NULL,
	categoria ENUM('adulti','ragazzi') NOT NULL,
	descrizione varchar(200),
	dataInserimento date NOT NULL,
	PRIMARY KEY (mail,nomePresepe),
	FOREIGN KEY(mail) REFERENCES user(mail)
);