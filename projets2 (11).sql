-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 30 mai 2024 à 13:55
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

-- --------------------------------------------------------

--
-- Structure de la table `acheteur`
--

DROP TABLE IF EXISTS `acheteur`;
CREATE TABLE IF NOT EXISTS `acheteur` (
  `id_acheteur` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_panier` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `adresse1` varchar(255) NOT NULL,
  `adresse2` varchar(255) DEFAULT NULL,
  `code_postal` varchar(20) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `pays` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  PRIMARY KEY (`id_acheteur`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_panier` (`id_panier`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `type_vente` varchar(50) NOT NULL,
  `type_produit` varchar(50) NOT NULL DEFAULT 'regulier',
  `rarete` varchar(50) NOT NULL DEFAULT 'regulier',
  `description` text,
  `date_depot` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  PRIMARY KEY (`id_article`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `id_utilisateur`, `nom`, `image`, `prix`, `type_vente`, `type_produit`, `rarete`, `description`, `date_depot`, `date_fin`) VALUES
(2, 1, 'Article 1', 'image1.jpg', 19.99, 'enchere', 'regulier', '', NULL, NULL, NULL),
(3, 1, 'Article 2', 'image2.jpg', 29.99, 'immediat', 'regulier', '', NULL, NULL, NULL),
(4, 1, 'Article 3', 'image3.jpg', 39.99, 'enchere', 'regulier', '', NULL, NULL, NULL),
(5, 2, 'Article 4', 'image4.jpg', 49.99, 'immediat', 'regulier', '', NULL, NULL, NULL),
(6, 2, 'Article 5', 'image5.jpg', 59.99, 'enchere', 'regulier', '', NULL, NULL, NULL),
(12, 3, 'pull7', 'image1.jpg', 56.00, 'enchere', 'regulier', '', NULL, NULL, '2024-05-31'),
(13, 3, 'pull9', 'image1.jpg', 56.00, 'enchere', 'regulier', '', NULL, '2024-05-29', '2024-05-31'),
(14, 3, 'pull9', 'image1.jpg', 56.00, 'enchere', 'regulier', '', '', '2024-05-29', '2024-05-31'),
(15, 3, 'pull9', 'image1.jpg', 56.00, 'enchere', 'regulier', '', '', '2024-05-29', '2024-05-31'),
(16, 3, 'pull9', 'image1.jpg', 35.00, 'enchere', 'regulier', '', 'NJZCOERNCV ZPIR', '2024-05-29', '2024-05-24'),
(20, 3, 'ifkcnzep', 'jcozn.jpd', 345.00, 'immediat', 'regulier', '', 'HFZI', '2024-05-29', NULL),
(18, 0, '', '', 0.00, 'immediat', 'rare', 'rare', '', '2024-05-29', NULL),
(19, 0, '', '', 0.00, 'vendeur_client', 'rare', 'rare', '', '2024-05-29', NULL),
(21, 3, 'ifkcnzep', 'jcozn.jpd', 289.00, 'immediat', 'regulier', '', 'EBNCOJ', '2024-05-30', NULL),
(22, 4, 'ifkcnzep', 'jcozn.jpd', 8340.00, 'immediat', 'regulier', '', 'cjek', '2024-05-30', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `enchere`
--

DROP TABLE IF EXISTS `enchere`;
CREATE TABLE IF NOT EXISTS `enchere` (
  `id_enchere` int NOT NULL AUTO_INCREMENT,
  `id_article` int NOT NULL,
  `id_acheteur` int NOT NULL,
  `enchere_max` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_enchere`),
  KEY `id_article` (`id_article`),
  KEY `id_acheteur` (`id_acheteur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `id_notif` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  `rarete` varchar(50) NOT NULL,
  PRIMARY KEY (`id_notif`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `id_paiement` int NOT NULL AUTO_INCREMENT,
  `id_acheteur` int NOT NULL,
  `nom_sur_carte` varchar(100) NOT NULL,
  `type_carte` varchar(20) NOT NULL,
  `date_expiration` date NOT NULL,
  `numero_carte` varchar(20) NOT NULL,
  `cvc` varchar(10) NOT NULL,
  PRIMARY KEY (`id_paiement`),
  KEY `id_acheteur` (`id_acheteur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id_panier` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_article` int NOT NULL,
  PRIMARY KEY (`id_panier`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_article` (`id_article`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) DEFAULT NULL,
  `pseudo` varchar(255) DEFAULT NULL,
  `type_compte` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `mail`, `pseudo`, `type_compte`, `mot_de_passe`) VALUES
(6, 'acheteur2@agora.com', 'acheteur2', 'acheteur', 'acheteur'),
(5, 'acheteur1@agora.com', 'acheteur1', 'acheteur', 'acheteur'),
(4, 'vendeur2@agora.com', 'vendeur2', 'vendeur', 'vendeur'),
(3, 'vendeur1@agora.com', 'vendeur1', 'vendeur', 'vendeur'),
(2, 'admin2@agora.com', 'admin2', 'administrateur', 'admin'),
(1, 'admin1@agora.com', 'admin1', 'administrateur', 'admin'),
(22, NULL, NULL, 'administrateur', 'vendeur'),
(23, NULL, NULL, 'vendeur', 'vendeur'),
(24, NULL, NULL, 'acheteur', 'maxence'),
(25, '', '', 'acheteur', 'maxence'),
(26, '', '', 'administrateur', 'maxxmax'),
(27, 'max@gmail.com', 'max1', 'vendeur', 'maxxmax'),
(28, 'vendeur1@agora.com', 'nczoejk', 'administrateur', 'vendeur'),
(29, 'vendeur1@agora.com', 'nczoejk', 'administrateur', 'vendeur'),
(30, 'user@gmail.com', 'JNOVZ', 'administrateur', 'password');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
