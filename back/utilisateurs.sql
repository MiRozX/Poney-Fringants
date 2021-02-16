DROP DATABASE IF EXISTS Poneyfringants; 
CREATE DATABASE Poneyfringants; 
USE Poneyfringants; 

CREATE USER IF NOT EXISTS 'enzo'@'localhost' IDENTIFIED BY 'EnzoAdmin';

GRANT ALL ON Poneyfringants.* TO 'enzo'@'localhost'; 

-- Création des tables
CREATE TABLE utilisateurs (
    adherentID int PRIMARY KEY AUTO_INCREMENT,
    pseudo varchar(20) UNIQUE NOT NULL,
    email varchar(128) UNIQUE NOT NULL,
    `password` varchar(255) NOT NULL,
    
    
    INDEX (pseudo) -- Permet d'optimiser la recherche d'un adhérent par son nom
);

CREATE TABLE interets (
    interetID int PRIMARY KEY AUTO_INCREMENT,
);

CREATE TABLE profils (
    profilID int PRIMARY KEY AUTO_INCREMENT,
    titre varchar(50) NOT NULL,
    photo varchar(50),
    `description` text NOT NULL,
    adherentID int NOT NULL,

    CONSTRAINT adherentID_FK FOREIGN KEY (adherentID) REFERENCES adherents (adherentID) 
        ON DELETE CASCADE -- Si l'adhérent référencé par la clé est supprimé, le profil le sera aussi 
);

CREATE TABLE interetAdherent (
    centreInteretID int NOT NULL,
    adherentID int NOT NULL,

    PRIMARY KEY (centreInteretID, adherentID), -- La clé primaire de la table interetAdherent est "composée" par un couple centreInteretID et adherentID
    CONSTRAINT interet_FK FOREIGN KEY (centreInteretID) REFERENCES interets (interetID),
    CONSTRAINT adherentID_interet_FK FOREIGN KEY (adherentID) REFERENCES adherents (adherentID)
);