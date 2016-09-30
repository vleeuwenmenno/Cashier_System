# create script database comtoday

DROP DATABASE IF EXISTS comtoday;

CREATE DATABASE comtoday; 
 
USE comtoday;

CREATE TABLE bon 
(
  id	        INT NOT NULL AUTO_INCREMENT,
  klantid	INT,
  naam		CHAR(40),
  aanmaakdatum	DATE,
  aanmaaktijd	TIME,
  datum		DATE,
  tijd		TIME,
  status	CHAR(15), # betaald, wacht 
  betaalwijze	CHAR(15), # rekening, pin, kontant, pin+kontant 
  totaal	INT,
  pin		INT,
  kontant	INT,
  rekening	INT,
  userid	INT,
  moduleid	INT,
  PRIMARY KEY( id )
);

CREATE TABLE module
(
  id		INT NOT NULL AUTO_INCREMENT,
  type 		CHAR(10), # Beheer Kassa
  naam		CHAR(20),
  ipaddress	CHAR(16),
  printer       CHAR(30),
  PRIMARY KEY( id )
);

CREATE TABLE kassalog
(
  id		INT NOT NULL AUTO_INCREMENT,
  userid	INT,
  moduleid	INT,
  status	INT, # geopend, gesloten
  kasin		INT,
  kasuit	INT,
  kasgeld	INT,
  afromen	INT,
  pinbon	INT,
  oprekening	INT,
  kasverschil   INT,
  controle	INT, # ok, niet ok 1 - 0
  commentaar	CHAR(100),
  datum		DATE,
  tijd		TIME,
  PRIMARY KEY (id )
);

CREATE TABLE voorraadlog
(
  id		INT NOT NULL AUTO_INCREMENT,
  userid 	INT,
  moduleid	INT,
  artikelid	INT,
  inkoop	INT,
  prijs		INT,
  transactie	CHAR(20), # voorraad, demovoorraad, voorraaddemo, rma
  aantal 	INT,
  totaal	INT,
  datum		DATE,
  tijd		TIME,
  PRIMARY KEY (id )
);

CREATE TABLE klant
(
  id			INT NOT NULL AUTO_INCREMENT,
  voorletters		CHAR(10),
  tussenvoegsel		CHAR(10),
  achternaam		CHAR(30),
  bedrijfsnaam		CHAR(30), # zowel velden particulier als bedrijf opgenomen
  straat		CHAR(30),
  huisnr		CHAR(10),
  postcode		CHAR(10),
  woonplaats		CHAR(30),
  telefoon		CHAR(20),
  email			CHAR(20),
  debiteur		CHAR(4), # eigenlijk zou ik denken, klanten die soms debiteuren mogen zijn op rekening vandaar terminlogie
  eol			CHAR(4),
  PRIMARY KEY( id )
);

CREATE TABLE artikel
(
  id			INT NOT NULL AUTO_INCREMENT,
  categorie		CHAR(30),
  merk			CHAR(30),
  type			CHAR(50),
  inkoop		INT,
  marge			INT,
  prijs			INT,
  omschrijving		CHAR(100),
  voorraad		INT,
  demo			INT,
  rma			INT,
  retour		INT,
  eol			CHAR(4),
 # FULLTEXT KEY allfields (categorie,merk,type),
  PRIMARY KEY( id )
);

CREATE TABLE item # lijst van onderdelen op een bon
(
  id			INT NOT NULL AUTO_INCREMENT,
  bonid			INT,
  artikelid		INT,
  aantal		INT,
  prijs 		INT,
  categorie		CHAR(30),
  merk 			CHAR(30),	
  type			CHAR(50),
  omschrijving		CHAR(100),
  demo 			INT,
  transactie	        CHAR(15), # verkoop, retour, rma
  totaal		INT,
  PRIMARY KEY( id )

);

CREATE TABLE user
(
  id			INT NOT NULL AUTO_INCREMENT,
  naam			CHAR(20),
  password		CHAR(20),
  role 			CHAR(20), # 'Beheerder', 'Medewerker'
  PRIMARY KEY(id)
);

CREATE TABLE bonstatus # betaald, openstaand
(
  id			INT NOT NULL AUTO_INCREMENT,
  naam   		CHAR(20),
  PRIMARY KEY( id )
);
		
CREATE TABLE merk
(
  id			INT NOT NULL AUTO_INCREMENT,
  naam			CHAR(30),
  PRIMARY KEY( id )
);

CREATE TABLE betaalwijze # pin, rekening, kontant etc.
(
  id			INT NOT NULL AUTO_INCREMENT,
  naam  		CHAR(30),
  PRIMARY KEY(id)
);

CREATE TABLE userrole # beheerder, medewerker
(
  id		INT NOT NULL AUTO_INCREMENT,
  naam  	CHAR(20),
  PRIMARY KEY ( id )
);

CREATE TABLE kassastatus # geopend gesloten
(
  id		INT NOT NULL AUTO_INCREMENT,
  naam	 	CHAR(20),
  PRIMARY KEY ( id )
);

CREATE TABLE categorie # cd, moederbord,.......
(
  id		INT NOT NULL AUTO_INCREMENT,
  naam  	CHAR(20),
  PRIMARY KEY ( id )
);

CREATE TABLE transactie # rma, retour, verkoop
(
  id		INT NOT NULL AUTO_INCREMENT,
  naam  	CHAR(20),
  PRIMARY KEY ( id )
);

CREATE TABLE systeem 
(
  id		INT NOT NULL AUTO_INCREMENT,
  naam  	CHAR(30),
  totaal	INT,
  PRIMARY KEY ( id )
);

CREATE TABLE systeemitem
(
  id		INT NOT NULL AUTO_INCREMENT,
  systeemid	INT,
  artikelid	INT,
  categorie	CHAR(30),
  merk 		CHAR(30),	
  type		CHAR(50),
  omschrijving	CHAR(100),
  aantal	INT,
  totaal	INT,
  PRIMARY KEY ( id )
);
  
#end of script