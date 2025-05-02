CREATE DATABASE IF NOT EXISTS MEDICAL_EASE;
USE MEDICAL_EASE;

-- Table Hôpital
CREATE TABLE hopital (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(80) NOT NULL
);

-- Table Vaccin
CREATE TABLE vaccin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(30) NOT NULL
);

-- Table Médecin
CREATE TABLE medecin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(30) NOT NULL UNIQUE,
    nom VARCHAR(30) NOT NULL,
    prenom VARCHAR(30) NOT NULL,
    password VARCHAR(30) NOT NULL,
    id_hopital INT NOT NULL,
    FOREIGN KEY (id_hopital) REFERENCES hopital(id)
);

-- Table Patient
CREATE TABLE patient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(30) NOT NULL UNIQUE,
    nom VARCHAR(30) NOT NULL,
    prenom VARCHAR(30) NOT NULL,
    password VARCHAR(30) NOT NULL,
    date_de_naissance DATE NOT NULL
);

-- Table Vaccination
CREATE TABLE vaccination (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_patient INT NOT NULL,
    id_vaccin INT NOT NULL,
    date DATE NOT NULL,
    statut VARCHAR(30) NOT NULL,
    ordonnance text ,
    FOREIGN KEY (id_patient) REFERENCES patient(id),
    FOREIGN KEY (id_vaccin) REFERENCES vaccin(id)
);

-- Table Rendez-vous
CREATE TABLE rendezvous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_patient INT NOT NULL,
    id_vaccin INT NOT NULL,
    date_rdv DATE NOT NULL,
    time_rdv TIME NOT NULL,
    id_hopital INT NOT NULL,
    id_medecin INT NOT NULL,
    FOREIGN KEY (id_patient) REFERENCES patient(id),
    FOREIGN KEY (id_vaccin) REFERENCES vaccin(id),
    FOREIGN KEY (id_hopital) REFERENCES hopital(id),
    FOREIGN KEY (id_medecin) REFERENCES medecin(id)
);
