-- Base de données Railway (pour jay)
-- USE railway;
CREATE DATABASE Covoiturage;
USE Covoiturage;;

-- --------------------------------------------------------
CREATE TABLE `password_resets` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    INDEX `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Utilisateurs (
    IdUtilisateur INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(100) NOT NULL,
    Prenom VARCHAR(100) NOT NULL,
    Courriel VARCHAR(150) UNIQUE NOT NULL,
    Solde Decimal(10,2) DEFAULT 1000,
    Role VARCHAR(50) NOT NULL,
    MotDePasse VARCHAR(255) NOT NULL,
    CHECK (Role IN ('Professeur','Eleve','Admin', 'Conducteur','Passager'))
);

CREATE TABLE Trajets (
    IdTrajet INT AUTO_INCREMENT PRIMARY KEY,
    IdConducteur INT NOT NULL,
    NomConducteur VARCHAR(50) NOT NULL,
    Distance NUMERIC,
    Depart VARCHAR(150) NOT NULL,
    Destination VARCHAR(150) NOT NULL,
    DateTrajet DATE NOT NULL,
    HeureTrajet TIME NOT NULL,
    PlacesDisponibles INT NOT NULL,
    Prix DECIMAL(6,2) NOT NULL,
    AnimauxAcceptes TINYINT(1) DEFAULT 0,
    TypeConversation VARCHAR(20) NOT NULL,
    Musique TINYINT(1) DEFAULT 0,
    Fumeur TINYINT(1) default 0,
    FOREIGN KEY (IdConducteur) REFERENCES Utilisateurs(IdUtilisateur),
    CHECK (TypeConversation IN ('Silencieux', 'Normal', 'Bavard'))
);

CREATE TABLE Reservations (
    IdReservation INT AUTO_INCREMENT PRIMARY KEY,
    IdTrajet INT NOT NULL,
    IdPassager INT NOT NULL,
    Distance numeric,
    DateReservation DATETIME DEFAULT CURRENT_TIMESTAMP,
    PlacesReservees INT NOT NULL,
    FOREIGN KEY (IdTrajet) REFERENCES Trajets(IdTrajet),
    FOREIGN KEY (IdPassager) REFERENCES Utilisateurs(IdUtilisateur)
);

CREATE TABLE Vehicules (
    IdVehicule INT AUTO_INCREMENT PRIMARY KEY,
    IdConducteur INT NOT NULL,
    Marque VARCHAR(50),
    Modele VARCHAR(50),
    Annee INT,
    PlacesTotal INT,
    FOREIGN KEY (IdConducteur) REFERENCES Utilisateurs(IdUtilisateur)
);

CREATE TABLE IF NOT EXISTS Activites (
    IdActivite INT AUTO_INCREMENT PRIMARY KEY,
    Titre VARCHAR(200) NOT NULL,
    Description TEXT,
    Lieu VARCHAR(200),
    DateActivite DATETIME NOT NULL,
    IdUtilisateur INT NOT NULL,
    FOREIGN KEY (IdUtilisateur) REFERENCES Utilisateurs(IdUtilisateur)
);

CREATE TABLE IF NOT EXISTS Commentaires (
    IdCommentaire INT AUTO_INCREMENT PRIMARY KEY,
    IdUtilisateur INT NOT NULL,
    Commentaire TEXT NOT NULL,
    IdTrajet INT NOT NULL,
    DateCommentaire DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdUtilisateur) REFERENCES Utilisateurs(IdUtilisateur),
    FOREIGN KEY (IdTrajet) REFERENCES Trajets(IdTrajet)
);

CREATE TABLE IF NOT EXISTS Evaluation (
    IdEvaluation INT AUTO_INCREMENT PRIMARY KEY,
    IdUtilisateur INT NOT NULL,
    Note DECIMAL(2,1) NOT NULL CHECK (Note BETWEEN 0 AND 5),
    IdTrajet INT NOT NULL,
    DateCommentaire DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdUtilisateur) REFERENCES Utilisateurs(IdUtilisateur),
    FOREIGN KEY (IdTrajet) REFERENCES Trajets(IdTrajet)
);

CREATE TABLE Paiements (
    IdPaiement INT AUTO_INCREMENT PRIMARY KEY,
    IdUtilisateur INT NOT NULL,
    IdTrajet INT NOT NULL,
    NombrePlaces INT NOT NULL DEFAULT 1,
    Montant DECIMAL(6,2) NOT NULL,
    Statut VARCHAR(50) DEFAULT 'En attente',
    MethodePaiement VARCHAR(50) DEFAULT 'Carte Crédit',
    DateCreation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdUtilisateur) REFERENCES Utilisateurs(IdUtilisateur),
    FOREIGN KEY (IdTrajet) REFERENCES Trajets(IdTrajet)
);

CREATE TABLE RecurrenceTrajet (
    IdRecurrence INT AUTO_INCREMENT PRIMARY KEY,
    IdTrajet INT NOT NULL,
    JourSemaine INT NOT NULL CHECK (JourSemaine BETWEEN 1 AND 7),
    Heure TIME NOT NULL,
    FOREIGN KEY (IdTrajet) REFERENCES Trajets(IdTrajet)
);

CREATE TABLE LesMessages (
    IdMessage INT AUTO_INCREMENT PRIMARY KEY,
    IdExpediteur INT NOT NULL,
    IdDestinataire INT NOT NULL,
    LeMessage TEXT NOT NULL,
    DateEnvoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    MessageLu TINYINT(1) DEFAULT 0,
    FOREIGN KEY (IdExpediteur) REFERENCES Utilisateurs(IdUtilisateur),
    FOREIGN KEY (IdDestinataire) REFERENCES Utilisateurs(IdUtilisateur)
);

create table Favoris (
    IdFavori INT AUTO_INCREMENT primary key,  
    IdUtilisateur int not null,
    IdTrajet int not null,
    TypeFavori varchar(20) not null,
    DateAjout DATETIME DEFAULT CURRENT_TIMESTAMP,    
    constraint fk_favoris_utilisateur foreign key (IdUtilisateur) references Utilisateurs(IdUtilisateur),
    constraint fk_favoris_trajet foreign key (IdTrajet) references Trajets(IdTrajet),
        CHECK (TypeFavori IN ('Rechercher', 'reserver'))
);

CREATE TABLE trajet_favoris (
    IdFavori INT AUTO_INCREMENT PRIMARY KEY,
    IdUtilisateur INT NOT NULL,
    Depart VARCHAR(150) NOT NULL,
    Destination VARCHAR(150) NOT NULL,
    DateDernierePublication DATE,
    HeureTrajet TIME,
    PlacesDisponibles INT,
    Prix DECIMAL(10, 2),
    AnimauxAcceptes BOOLEAN,
    TypeConversation VARCHAR(20),
    Musique BOOLEAN,
    Fumeur BOOLEAN,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (IdUtilisateur) REFERENCES Utilisateurs(IdUtilisateur) ON DELETE CASCADE
);

CREATE TABLE HistoriqueTransactions (
    IdHistorique INT PRIMARY KEY AUTO_INCREMENT,
    IdUtilisateur INT NOT NULL,
    IdTrajet INT NOT NULL,
    IdConducteur INT NOT NULL,
    IdPaiement INT NULL,
    IdReservation INT NULL,
    NombrePlaces INT NOT NULL,
    Montant DECIMAL(10, 2) NOT NULL,
    Statut VARCHAR(50) NOT NULL DEFAULT 'Payé',
    MethodePaiement VARCHAR(50) NOT NULL,
    Depart VARCHAR(255) NOT NULL,
    Destination VARCHAR(255) NOT NULL,
    DateTrajet DATE NOT NULL,
    HeureTrajet TIME NOT NULL,
    PrixUnitaire DECIMAL(10, 2) NOT NULL,
    NomConducteur VARCHAR(255) NOT NULL,
    PrenomConducteur VARCHAR(255) NOT NULL,
    DateTransaction DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CreatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdUtilisateur) REFERENCES Utilisateurs(IdUtilisateur) ON DELETE CASCADE,
    FOREIGN KEY (IdTrajet) REFERENCES Trajets(IdTrajet) ON DELETE CASCADE
) ;

CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `ip_address` VARCHAR(45) NULL DEFAULT NULL,
    `user_agent` TEXT NULL DEFAULT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `sessions_user_id_index` (`user_id`),
    INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL DEFAULT NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL DEFAULT NULL,
    `cancelled_at` INT NULL DEFAULT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


RENAME TABLE Trajets TO trajets;
RENAME TABLE Utilisateurs TO utilisateurs;
RENAME TABLE Reservations TO reservations;
RENAME TABLE Paiements TO paiements;
RENAME TABLE Commentaires TO commentaires;
RENAME TABLE Evaluation TO evaluation;
RENAME TABLE LesMessages TO lesmessages;
RENAME TABLE Favoris TO favoris;
RENAME TABLE HistoriqueTransactions TO historiquetransactions;
RENAME TABLE RecurrenceTrajet TO recurrencetrajet;
RENAME TABLE Vehicules TO vehicules;
RENAME TABLE Activites TO activites;


ALTER TABLE trajets 
ADD COLUMN RappelEmail TINYINT(1) DEFAULT 0 AFTER Fumeur,
ADD COLUMN RappelEnvoye TINYINT(1) DEFAULT 0 AFTER RappelEmail;

DROP PROCEDURE IF EXISTS ModifierNombrePlaces;
DROP PROCEDURE IF EXISTS GetConducteurByPaiement;
DROP PROCEDURE IF EXISTS PayerPanier;
DROP PROCEDURE IF EXISTS AjouterTrajet;
DROP PROCEDURE IF EXISTS AjouterFavori;


-- =========================================
-- 1. ModifierNombrePlaces
-- =========================================
DELIMITER $$

CREATE PROCEDURE ModifierNombrePlaces(
    IN p_IdPaiement INT,
    IN p_NouvellesPlaces INT
)
BEGIN
    DECLARE v_IdTrajet INT;
    DECLARE v_AnciennesPlaces INT;
    DECLARE v_Difference INT;
    DECLARE v_PrixUnitaire DECIMAL(10,2);
    DECLARE v_NouveauMontant DECIMAL(10,2);
    DECLARE v_PlacesDisponibles INT;

    SELECT IdTrajet, NombrePlaces INTO v_IdTrajet, v_AnciennesPlaces
    FROM paiements
    WHERE IdPaiement = p_IdPaiement;

    SELECT Prix, PlacesDisponibles INTO v_PrixUnitaire, v_PlacesDisponibles
    FROM trajets
    WHERE IdTrajet = v_IdTrajet;

    SET v_Difference = p_NouvellesPlaces - v_AnciennesPlaces;

    IF v_Difference > v_PlacesDisponibles THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pas assez de places disponibles';
    END IF;

    SET v_NouveauMontant = v_PrixUnitaire * p_NouvellesPlaces;

    UPDATE paiements
    SET NombrePlaces = p_NouvellesPlaces,
        Montant = v_NouveauMontant
    WHERE IdPaiement = p_IdPaiement;

    UPDATE trajets
    SET PlacesDisponibles = PlacesDisponibles - v_Difference
    WHERE IdTrajet = v_IdTrajet;
END$$

DELIMITER ;

-- =========================================
-- 2. GetConducteurByPaiement
-- =========================================
DELIMITER $$

CREATE PROCEDURE GetConducteurByPaiement(
    IN p_IdPaiement INT,
    OUT p_IdConducteur INT
)
BEGIN
    SELECT t.IdConducteur INTO p_IdConducteur
    FROM paiements p
    INNER JOIN trajets t ON p.IdTrajet = t.IdTrajet
    WHERE p.IdPaiement = p_IdPaiement;
END$$

DELIMITER ;

-- =========================================
-- 3. PayerPanier
-- =========================================
DELIMITER $$

CREATE PROCEDURE PayerPanier(
    IN p_conducteurId INT,
    IN p_idUtilisateur INT,
    IN p_idPaiement INT,
    IN p_typePaiement VARCHAR(50)
)
BEGIN
    DECLARE v_Montant DECIMAL(10,2);
    DECLARE v_SoldePassager DECIMAL(10,2);
    DECLARE v_IdTrajet INT;
    DECLARE v_PrixTrajet DECIMAL(10,2);
    DECLARE v_Places INT;
    DECLARE v_Distance NUMERIC;
    DECLARE v_PlacesDisponibles INT;
    
    SELECT p.IdTrajet, p.Montant, p.NombrePlaces INTO v_IdTrajet, v_Montant, v_Places
    FROM paiements p 
    WHERE p.IdPaiement = p_idPaiement AND p.IdUtilisateur = p_idUtilisateur;
    
    SELECT t.Distance, t.Prix, t.PlacesDisponibles INTO v_Distance, v_PrixTrajet, v_PlacesDisponibles
    FROM trajets t 
    WHERE t.IdTrajet = v_IdTrajet;
    
    SET v_Montant = v_PrixTrajet * v_Places;
    
    IF v_Places > v_PlacesDisponibles THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pas assez de places disponibles dans le trajet';
    END IF;
    
    SELECT Solde INTO v_SoldePassager 
    FROM utilisateurs 
    WHERE IdUtilisateur = p_idUtilisateur;
    
    START TRANSACTION;
    
    IF p_typePaiement = 'paypal' THEN
        UPDATE utilisateurs 
        SET Solde = Solde + v_Montant 
        WHERE IdUtilisateur = p_conducteurId;
        
    ELSE
        IF v_Montant > 0 AND v_Montant <= v_SoldePassager THEN
            -- Débiter le passager
            UPDATE utilisateurs 
            SET Solde = Solde - v_Montant 
            WHERE IdUtilisateur = p_idUtilisateur;
            
            UPDATE utilisateurs 
            SET Solde = Solde + v_Montant 
            WHERE IdUtilisateur = p_conducteurId;
        ELSE
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Fonds insuffisants';
        END IF;
    END IF;
    
    INSERT INTO reservations (IdTrajet, IdPassager, Distance, PlacesReservees, DateReservation)
    VALUES (v_IdTrajet, p_idUtilisateur, v_Distance, v_Places, NOW());
    
    UPDATE trajets 
    SET PlacesDisponibles = PlacesDisponibles - v_Places
    WHERE IdTrajet = v_IdTrajet;
    
    DELETE FROM paiements WHERE IdPaiement = p_idPaiement;
    
    COMMIT;
END$$

DELIMITER ;

-- =========================================
-- 4. AjouterTrajet
-- =========================================
DELIMITER $$

CREATE PROCEDURE AjouterTrajet(
    IN p_IdConducteur INT,
    IN p_NomConducteur VARCHAR(50),
    IN p_Distance NUMERIC,
    IN p_Depart VARCHAR(150),
    IN p_Destination VARCHAR(150),
    IN p_DateTrajet DATE,
    IN p_HeureTrajet TIME,
    IN p_PlacesDisponibles INT,
    IN p_Prix DECIMAL(6,2),
    IN p_AnimauxAcceptes TINYINT(1),
    IN p_TypeConversation VARCHAR(20),
    IN p_Musique TINYINT(1),
    IN p_Fumeur TINYINT(1)
)
BEGIN
    START TRANSACTION;

    INSERT INTO trajets (
        IdConducteur, 
        NomConducteur, 
        Distance, 
        Depart, 
        Destination, 
        DateTrajet, 
        HeureTrajet, 
        PlacesDisponibles, 
        Prix, 
        AnimauxAcceptes, 
        TypeConversation, 
        Musique, 
        Fumeur
    ) VALUES (
        p_IdConducteur, 
        p_NomConducteur, 
        p_Distance, 
        p_Depart, 
        p_Destination, 
        p_DateTrajet, 
        p_HeureTrajet, 
        p_PlacesDisponibles, 
        p_Prix, 
        p_AnimauxAcceptes, 
        p_TypeConversation, 
        p_Musique, 
        p_Fumeur
    );

    COMMIT;
END$$

DELIMITER ;

-- =========================================
-- 5. AjouterFavori
-- =========================================
DELIMITER $$

CREATE PROCEDURE AjouterFavori(
    IN p_IdUtilisateur int,
    IN p_IdTrajet int,
    IN p_TypeFavori varchar(20)
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM favoris
        WHERE IdUtilisateur = p_IdUtilisateur
          AND IdTrajet = p_IdTrajet
          AND TypeFavori = p_TypeFavori
    ) THEN
        INSERT INTO favoris (IdUtilisateur, IdTrajet, TypeFavori)
        VALUES (p_IdUtilisateur, p_IdTrajet, p_TypeFavori);
    END IF;
END$$

DELIMITER ;