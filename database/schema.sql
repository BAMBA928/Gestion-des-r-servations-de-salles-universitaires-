-- Active: 1786725086452@@127.0.0.1@3306
CREATE TABLE salles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    batiment VARCHAR(100) NOT NULL,
    capacite INT NOT NULL,
    type ENUM('cours','informatique','laboratoire','amphitheatre','reunion') NOT NULL,
    active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    salle_id INT NOT NULL,
    responsable VARCHAR(120) NOT NULL,
    email VARCHAR(255) NOT NULL,
    motif VARCHAR(255) NOT NULL,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    statut ENUM('confirmée','annulée') NOT NULL DEFAULT 'confirmée',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (salle_id) REFERENCES salles(id)
);