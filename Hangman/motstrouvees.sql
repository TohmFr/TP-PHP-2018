-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  jeu. 11 oct. 2018 à 15:33
-- Version du serveur :  5.7.17
-- Version de PHP :  5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `tp`
--

-- --------------------------------------------------------

--
-- Structure de la table `motstrouvees`
--

CREATE TABLE `motstrouvees` (
  `mot` varchar(1000) CHARACTER SET utf8 COLLATE utf8_roman_ci NOT NULL,
  `trouve` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `motstrouvees`
--

INSERT INTO `motstrouvees` (`mot`, `trouve`) VALUES
('agioteras', 0),
('ardoiseront', 1),
('bourgeonnasse', 1),
('cabrasses', 0),
('chaumait', 0),
('clisserai', 0),
('commettant', 0),
('consolidasse', 1),
('constitutionnalisasses', 1),
('désadapté', 0),
('détonneraient', 0),
('durcissions', 0),
('esthétisante', 0),
('familiariserai', 1),
('frisassiez', 0),
('garnît', 0),
('glairasse', 0),
('grognant', 0),
('hongroierions', 0),
('immortalisent', 0),
('laïcisasses', 0),
('longeai', 0),
('marginalismes', 0),
('modérations', 0),
('peignerions', 0),
('persuadaient', 1),
('planchéiai', 1),
('radiotélévisés', 1),
('surclassez', 0),
('torréfierai', 0),
('transbahutions', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `motstrouvees`
--
ALTER TABLE `motstrouvees`
  ADD UNIQUE KEY `mot_idx` (`mot`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
