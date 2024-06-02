-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 29 mai 2024 à 11:55
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projets2`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `UpdatePseudos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdatePseudos` ()   BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE u_id INT;
    DECLARE u_type_de_compte VARCHAR(50);
    DECLARE cur CURSOR FOR SELECT identifiant, type_de_compte FROM utilisateurs;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO u_id, u_type_de_compte;
        IF done THEN
            LEAVE read_loop;
        END IF;

        SET @counter = 1;
        SET @pseudo = CONCAT(u_type_de_compte, '_', u_id);
        UPDATE utilisateurs SET pseudo = @pseudo WHERE identifiant = u_id;

    END LOOP;

    CLOSE cur;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `acheteur`
--

DROP TABLE IF EXISTS `acheteur`;
CREATE TABLE IF NOT EXISTS `acheteur` (
  `acheteur_id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `numero_panier` int NOT NULL,
  `nom_prenom` varchar(255) NOT NULL,
  `adresse_ligne1` varchar(255) NOT NULL,
  `adresse_ligne2` varchar(255) DEFAULT NULL,
  `ville` varchar(100) NOT NULL,
  `code_postal` varchar(20) NOT NULL,
  `pays` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  PRIMARY KEY (`acheteur_id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `article_id` int NOT NULL AUTO_INCREMENT,
  `vendeur_id` int NOT NULL,
  `nom_article` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `type_de_vente` varchar(50) NOT NULL,
  `rarete` varchar(50) NOT NULL DEFAULT 'regulier',
  PRIMARY KEY (`article_id`),
  KEY `vendeur_id` (`vendeur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`article_id`, `vendeur_id`, `nom_article`, `image`, `prix`, `type_de_vente`, `rarete`) VALUES
(2, 1, 'Article 1', 'image1.jpg', 19.99, 'enchere', 'regulier'),
(3, 1, 'Article 2', 'image2.jpg', 29.99, 'immediat', 'regulier'),
(4, 1, 'Article 3', 'image3.jpg', 39.99, 'enchere', 'regulier'),
(5, 2, 'Article 4', 'image4.jpg', 49.99, 'immediat', 'regulier'),
(6, 2, 'Article 5', 'image5.jpg', 59.99, 'enchere', 'regulier');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `identifiant` int NOT NULL AUTO_INCREMENT,
  `mot_de_passe` varchar(255) NOT NULL,
  `pseudo` varchar(255) DEFAULT NULL,
  `type_de_compte` varchar(50) NOT NULL,
  PRIMARY KEY (`identifiant`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`identifiant`, `mot_de_passe`, `pseudo`, `type_de_compte`) VALUES
(1, 'admin', 'administrateur_1', 'administrateur'),
(2, 'admin', 'administrateur_2', 'administrateur'),
(3, 'vendeur', 'vendeur_1', 'vendeur'),
(4, 'vendeur', 'vendeur_2', 'vendeur'),
(5, 'acheteur', 'acheteur_1', 'acheteur'),
(6, 'acheteur', 'acheteur_2', 'acheteur');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
