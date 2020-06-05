CREATE DATABASE Electronics;

USE Electronics;

CREATE TABLE Prodotto (
    ID SERIAL PRIMARY KEY,
    nome VARCHAR(30),
    categoria VARCHAR(30),
    prezzo INTEGER,
    immagine VARCHAR(80)
);

CREATE TABLE Cliente (
    ID SERIAL PRIMARY KEY,
    username VARCHAR(30),
    email VARCHAR(45),
    nome VARCHAR(30),
    cognome VARCHAR(30),
    provincia VARCHAR(30),
    citta VARCHAR(30),
    via VARCHAR(30),
    numero INTEGER,
    cap INTEGER,
    pwd VARCHAR(513)
);

CREATE TABLE PuntoVendita (
    ID SERIAL PRIMARY KEY,
    cellulare VARCHAR(30),
    regione VARCHAR(30),
    citta VARCHAR(30),
    via VARCHAR(30),
    numero INTEGER,
    cap INTEGER
);

CREATE TABLE Ordine (
    ID SERIAL PRIMARY KEY,
    dataOrdine DATE,
    IDCliente BIGINT,
    RitiroInNegozio BOOLEAN,
    IDPuntoVendita BIGINT
);

CREATE TABLE DettagliOrdine (
    IDProdotto BIGINT,
    IDOrdine BIGINT,
    quantita INTEGER,
    PRIMARY KEY(IDProdotto, IDOrdine)
);

CREATE TABLE Magazzino (
    IDProdotto BIGINT,
    IDPuntoVendita BIGINT,
    quantita INTEGER,
    PRIMARY KEY(IDProdotto, IDPuntoVendita)
);

CREATE TABLE AdminProfile (
    ID SERIAL PRIMARY KEY,
    IDPuntoVendita BIGINT,
    username VARCHAR(30),
    pwd VARCHAR(513)
);


INSERT INTO Prodotto VALUES 
(0, "Arduino", "Hardware", 30, "https://images-na.ssl-images-amazon.com/images/I/51rmayrbsPL._AC_.jpg"),
(0, "Breadboard", "Hardware", 10, "https://images-na.ssl-images-amazon.com/images/I/61gIK-1ze6L._SL1412_.jpg"),
(0, "Condensatore", "Hardware", 3, "https://images-na.ssl-images-amazon.com/images/I/71fRYostwSL._SL1500_.jpg"),
(0, "Torcia", "Accessori", 5, "https://images-na.ssl-images-amazon.com/images/I/61Yvyr%2BJJXL._AC_SL1001_.jpg"),
;

INSERT INTO PuntoVendita VALUES 
(0, "3335664587", 'Lombardia' , 'Milano', 'viale Adua', 56, 78436),
(0, "3353335546", 'Toscana' , 'Montecatini', 'via Baccelli', 12, 51016),
(0, "3353543576", 'Lazio' , 'Roma', 'via Roma', 26, 10245)
;


INSERT INTO Magazzino VALUES 
(1, 1, 1000),
(2, 1, 1000),
(3, 1, 600),
(4, 1, 500),
(1, 2, 200),
(2, 2, 200),
(3, 2, 200),
(4, 2, 200)
;


CREATE USER 'Cliente'@'localhost' IDENTIFIED BY 'Cliente';
SET PASSWORD for 'Cliente'@'localhost' = 'Cliente';
GRANT SELECT ON Electronics.Prodotto TO 'Cliente'@'localhost';
GRANT SELECT ON Electronics.PuntoVendita TO 'Cliente'@'localhost';
GRANT SELECT, INSERT ON Electronics.Cliente TO 'Cliente'@'localhost';
GRANT SELECT, INSERT ON Electronics.DettagliOrdine TO 'Cliente'@'localhost';
GRANT SELECT, INSERT ON Electronics.Ordine TO 'Cliente'@'localhost';
GRANT UPDATE (quantita), SELECT ON Electronics.Magazzino TO 'Cliente'@'localhost';
GRANT LOCK TABLES ON Electronics.* TO 'Cliente'@'localhost';
ALTER USER 'Cliente'@'localhost' IDENTIFIED WITH mysql_native_password
BY 'Cliente'; 


CREATE USER 'Admin'@'localhost' IDENTIFIED BY 'Admin';
SET PASSWORD for 'Admin'@'localhost' = 'Admin';
GRANT SELECT ON Electronics.* TO 'Admin'@'localhost';
GRANT UPDATE, INSERT, DELETE ON Electronics.Magazzino TO 'Admin'@'localhost';
GRANT LOCK TABLES ON Electronics.* TO 'Admin'@'localhost';
ALTER USER 'Admin'@'localhost' IDENTIFIED WITH mysql_native_password
BY 'Admin'; 