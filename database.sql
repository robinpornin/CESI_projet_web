-- DEUX CODES !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
--
-- 1 code écrit manuellement et 1 code généré par PHPMyAdmin.
--
--
-- Voici le CODE ECRIT MANUELLEMENT : 

DROP TABLE IF EXISTS Requerir;
DROP TABLE IF EXISTS Contenir;
DROP TABLE IF EXISTS Candidatures;
DROP TABLE IF EXISTS Offres;
DROP TABLE IF EXISTS Evaluations;
DROP TABLE IF EXISTS Competences;
DROP TABLE IF EXISTS Wishlists;
DROP TABLE IF EXISTS Entreprises;
DROP TABLE IF EXISTS Utilisateurs;

CREATE TABLE IF NOT EXISTS Utilisateurs(
   ID_Utilisateur INT AUTO_INCREMENT,
   Nom VARCHAR(255) NOT NULL,
   Prenom VARCHAR(255) NOT NULL,
   Email VARCHAR(255) NOT NULL UNIQUE,
   Mdp VARCHAR(255) NOT NULL,
   Role TINYINT NOT NULL,
   PRIMARY KEY(ID_Utilisateur)
);

CREATE TABLE IF NOT EXISTS Entreprises(
   ID_Entreprise INT AUTO_INCREMENT,
   Email_entreprise VARCHAR(255) NOT NULL UNIQUE,
   Nom_entreprise VARCHAR(255) NOT NULL,
   Secteur VARCHAR(255) NOT NULL,
   Type_ VARCHAR(255) NOT NULL,
   Nb_stagiaires INT NOT NULL,
   Description_entreprise VARCHAR(512) NOT NULL,
   Telephone VARCHAR(16) NOT NULL,
   ID_Utilisateur INT NOT NULL,
   PRIMARY KEY(ID_Entreprise),
   FOREIGN KEY(ID_Utilisateur) REFERENCES Utilisateurs(ID_Utilisateur)
);

CREATE TABLE IF NOT EXISTS Wishlists(
   ID_Wishlist INT AUTO_INCREMENT,
   ID_Utilisateur INT NOT NULL,
   PRIMARY KEY(ID_Wishlist),
   UNIQUE(ID_Utilisateur),
   FOREIGN KEY(ID_Utilisateur) REFERENCES Utilisateurs(ID_Utilisateur)
);

CREATE TABLE IF NOT EXISTS Competences(
   ID_Competence INT AUTO_INCREMENT,
   Nom_competence VARCHAR(255) NOT NULL,
   PRIMARY KEY(ID_Competence)
);

CREATE TABLE IF NOT EXISTS Evaluations(
   ID_Evaluation INT AUTO_INCREMENT,
   Note DECIMAL(2,1) NOT NULL CHECK (Note >= 1.0 AND Note <= 5.0),
   ID_Utilisateur INT NOT NULL,
   ID_Entreprise INT NOT NULL,
   PRIMARY KEY(ID_Evaluation),
   FOREIGN KEY(ID_Utilisateur) REFERENCES Utilisateurs(ID_Utilisateur),
   FOREIGN KEY(ID_Entreprise) REFERENCES Entreprises(ID_Entreprise)
);

CREATE TABLE IF NOT EXISTS Offres(
   ID_Offre INT AUTO_INCREMENT,
   Titre VARCHAR(128) NOT NULL,
   Remuneration DECIMAL(7,2) NOT NULL,
   Date_ DATE NOT NULL,
   Description VARCHAR(512) NOT NULL,
   Duree INT NOT NULL,
   Ville_CP VARCHAR(255) NOT NULL,
   ID_Entreprise INT NOT NULL,
   ID_Utilisateur INT NOT NULL,
   PRIMARY KEY(ID_Offre),
   FOREIGN KEY(ID_Entreprise) REFERENCES Entreprises(ID_Entreprise),
   FOREIGN KEY(ID_Utilisateur) REFERENCES Utilisateurs(ID_Utilisateur)
);

CREATE TABLE IF NOT EXISTS Candidatures(
   ID_Candidature INT AUTO_INCREMENT,
   Lettre_de_motivation VARCHAR(255) NOT NULL,
   CV VARCHAR(255) NOT NULL,
   ID_Offre INT NOT NULL,
   ID_Utilisateur INT NOT NULL,
   PRIMARY KEY(ID_Candidature),
   FOREIGN KEY(ID_Offre) REFERENCES Offres(ID_Offre),
   FOREIGN KEY(ID_Utilisateur) REFERENCES Utilisateurs(ID_Utilisateur)
);

CREATE TABLE IF NOT EXISTS Contenir(
   ID_Offre INT NOT NULL,
   ID_Wishlist INT NOT NULL,
   PRIMARY KEY(ID_Offre, ID_Wishlist),
   FOREIGN KEY(ID_Offre) REFERENCES Offres(ID_Offre),
   FOREIGN KEY(ID_Wishlist) REFERENCES Wishlists(ID_Wishlist)
);

CREATE TABLE IF NOT EXISTS Requerir(
   ID_Offre INT NOT NULL,
   ID_Competence INT NOT NULL,
   PRIMARY KEY(ID_Offre, ID_Competence),
   FOREIGN KEY(ID_Offre) REFERENCES Offres(ID_Offre),
   FOREIGN KEY(ID_Competence) REFERENCES Competences(ID_Competence)
);

-- ################################################################################################################################
-- ################################################################################################################################
-- ################################################################################################################################
-- ################################################################################################################################
-- ################################################################################################################################
-- ################################################################################################################################
























-- Voici le CODE GENERE PAR PHPMYADMIN : 


-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 19 mars 2026 à 17:55
-- Version du serveur : 8.0.45-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `CESI_projet_web`
--

-- --------------------------------------------------------

--
-- Structure de la table `Candidatures`
--

CREATE TABLE `Candidatures` (
  `ID_Candidature` int NOT NULL,
  `Lettre_de_motivation` varchar(255) NOT NULL,
  `CV` varchar(255) NOT NULL,
  `ID_Offre` int NOT NULL,
  `ID_Utilisateur` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Competences`
--

CREATE TABLE `Competences` (
  `ID_Competence` int NOT NULL,
  `Nom_competence` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Contenir`
--

CREATE TABLE `Contenir` (
  `ID_Offre` int NOT NULL,
  `ID_Wishlist` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Entreprises`
--

CREATE TABLE `Entreprises` (
  `ID_Entreprise` int NOT NULL,
  `Email_entreprise` varchar(255) NOT NULL,
  `Nom_entreprise` varchar(255) NOT NULL,
  `Secteur` varchar(255) NOT NULL,
  `Type_` varchar(255) NOT NULL,
  `Nb_stagiaires` int NOT NULL,
  `Description_entreprise` varchar(512) NOT NULL,
  `Telephone` varchar(16) NOT NULL,
  `ID_Utilisateur` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Evaluations`
--

CREATE TABLE `Evaluations` (
  `ID_Evaluation` int NOT NULL,
  `Note` decimal(2,1) NOT NULL,
  `ID_Utilisateur` int NOT NULL,
  `ID_Entreprise` int NOT NULL
) ;

-- --------------------------------------------------------

--
-- Structure de la table `Offres`
--

CREATE TABLE `Offres` (
  `ID_Offre` int NOT NULL,
  `Titre` varchar(128) NOT NULL,
  `Remuneration` decimal(7,2) NOT NULL,
  `Date_` date NOT NULL,
  `Description` varchar(512) NOT NULL,
  `Duree` int NOT NULL,
  `Ville_CP` varchar(255) NOT NULL,
  `ID_Entreprise` int NOT NULL,
  `ID_Utilisateur` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Requerir`
--

CREATE TABLE `Requerir` (
  `ID_Offre` int NOT NULL,
  `ID_Competence` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateurs`
--

CREATE TABLE `Utilisateurs` (
  `ID_Utilisateur` int NOT NULL,
  `Nom` varchar(255) NOT NULL,
  `Prenom` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Mdp` varchar(255) NOT NULL,
  `Role` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Wishlists`
--

CREATE TABLE `Wishlists` (
  `ID_Wishlist` int NOT NULL,
  `ID_Utilisateur` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Candidatures`
--
ALTER TABLE `Candidatures`
  ADD PRIMARY KEY (`ID_Candidature`),
  ADD KEY `ID_Offre` (`ID_Offre`),
  ADD KEY `ID_Utilisateur` (`ID_Utilisateur`);

--
-- Index pour la table `Competences`
--
ALTER TABLE `Competences`
  ADD PRIMARY KEY (`ID_Competence`);

--
-- Index pour la table `Contenir`
--
ALTER TABLE `Contenir`
  ADD PRIMARY KEY (`ID_Offre`,`ID_Wishlist`),
  ADD KEY `ID_Wishlist` (`ID_Wishlist`);

--
-- Index pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  ADD PRIMARY KEY (`ID_Entreprise`),
  ADD UNIQUE KEY `Email_entreprise` (`Email_entreprise`),
  ADD KEY `ID_Utilisateur` (`ID_Utilisateur`);

--
-- Index pour la table `Evaluations`
--
ALTER TABLE `Evaluations`
  ADD PRIMARY KEY (`ID_Evaluation`),
  ADD KEY `ID_Utilisateur` (`ID_Utilisateur`),
  ADD KEY `ID_Entreprise` (`ID_Entreprise`);

--
-- Index pour la table `Offres`
--
ALTER TABLE `Offres`
  ADD PRIMARY KEY (`ID_Offre`),
  ADD KEY `ID_Entreprise` (`ID_Entreprise`),
  ADD KEY `ID_Utilisateur` (`ID_Utilisateur`);

--
-- Index pour la table `Requerir`
--
ALTER TABLE `Requerir`
  ADD PRIMARY KEY (`ID_Offre`,`ID_Competence`),
  ADD KEY `ID_Competence` (`ID_Competence`);

--
-- Index pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD PRIMARY KEY (`ID_Utilisateur`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Index pour la table `Wishlists`
--
ALTER TABLE `Wishlists`
  ADD PRIMARY KEY (`ID_Wishlist`),
  ADD UNIQUE KEY `ID_Utilisateur` (`ID_Utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Candidatures`
--
ALTER TABLE `Candidatures`
  MODIFY `ID_Candidature` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Competences`
--
ALTER TABLE `Competences`
  MODIFY `ID_Competence` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  MODIFY `ID_Entreprise` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Evaluations`
--
ALTER TABLE `Evaluations`
  MODIFY `ID_Evaluation` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Offres`
--
ALTER TABLE `Offres`
  MODIFY `ID_Offre` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `ID_Utilisateur` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Wishlists`
--
ALTER TABLE `Wishlists`
  MODIFY `ID_Wishlist` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Candidatures`
--
ALTER TABLE `Candidatures`
  ADD CONSTRAINT `Candidatures_ibfk_1` FOREIGN KEY (`ID_Offre`) REFERENCES `Offres` (`ID_Offre`),
  ADD CONSTRAINT `Candidatures_ibfk_2` FOREIGN KEY (`ID_Utilisateur`) REFERENCES `Utilisateurs` (`ID_Utilisateur`);

--
-- Contraintes pour la table `Contenir`
--
ALTER TABLE `Contenir`
  ADD CONSTRAINT `Contenir_ibfk_1` FOREIGN KEY (`ID_Offre`) REFERENCES `Offres` (`ID_Offre`),
  ADD CONSTRAINT `Contenir_ibfk_2` FOREIGN KEY (`ID_Wishlist`) REFERENCES `Wishlists` (`ID_Wishlist`);

--
-- Contraintes pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  ADD CONSTRAINT `Entreprises_ibfk_1` FOREIGN KEY (`ID_Utilisateur`) REFERENCES `Utilisateurs` (`ID_Utilisateur`);

--
-- Contraintes pour la table `Evaluations`
--
ALTER TABLE `Evaluations`
  ADD CONSTRAINT `Evaluations_ibfk_1` FOREIGN KEY (`ID_Utilisateur`) REFERENCES `Utilisateurs` (`ID_Utilisateur`),
  ADD CONSTRAINT `Evaluations_ibfk_2` FOREIGN KEY (`ID_Entreprise`) REFERENCES `Entreprises` (`ID_Entreprise`);

--
-- Contraintes pour la table `Offres`
--
ALTER TABLE `Offres`
  ADD CONSTRAINT `Offres_ibfk_1` FOREIGN KEY (`ID_Entreprise`) REFERENCES `Entreprises` (`ID_Entreprise`),
  ADD CONSTRAINT `Offres_ibfk_2` FOREIGN KEY (`ID_Utilisateur`) REFERENCES `Utilisateurs` (`ID_Utilisateur`);

--
-- Contraintes pour la table `Requerir`
--
ALTER TABLE `Requerir`
  ADD CONSTRAINT `Requerir_ibfk_1` FOREIGN KEY (`ID_Offre`) REFERENCES `Offres` (`ID_Offre`),
  ADD CONSTRAINT `Requerir_ibfk_2` FOREIGN KEY (`ID_Competence`) REFERENCES `Competences` (`ID_Competence`);

--
-- Contraintes pour la table `Wishlists`
--
ALTER TABLE `Wishlists`
  ADD CONSTRAINT `Wishlists_ibfk_1` FOREIGN KEY (`ID_Utilisateur`) REFERENCES `Utilisateurs` (`ID_Utilisateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
