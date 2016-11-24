-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 24 Novembre 2016 à 09:17
-- Version du serveur: 5.5.29-0ubuntu0.12.04.2
-- Version de PHP: 5.3.10-1ubuntu3.25

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `maegrondin`
--

-- --------------------------------------------------------

--
-- Structure de la table `Appartenir_catégorie`
--

CREATE TABLE IF NOT EXISTS `Appartenir_catégorie` (
  `nom_catégorie` varchar(255) NOT NULL,
  `id_recette` int(11) NOT NULL,
  PRIMARY KEY (`nom_catégorie`,`id_recette`),
  KEY `id_recette` (`id_recette`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Appartenir_catégorie`
--

INSERT INTO `Appartenir_catégorie` (`nom_catégorie`, `id_recette`) VALUES
('Déssert', 1),
('Plat', 2),
('Plat', 3),
('Déssert', 4);

-- --------------------------------------------------------

--
-- Structure de la table `Avoir_Caracteristiques`
--

CREATE TABLE IF NOT EXISTS `Avoir_Caracteristiques` (
  `unite` varchar(255) NOT NULL,
  `valeur` int(11) NOT NULL,
  `nom_ingredient` varchar(255) NOT NULL,
  `nom_caracteristique` varchar(255) NOT NULL,
  PRIMARY KEY (`nom_ingredient`,`nom_caracteristique`),
  KEY `nom_caracteristique` (`nom_caracteristique`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Avoir_Caracteristiques`
--

INSERT INTO `Avoir_Caracteristiques` (`unite`, `valeur`, `nom_ingredient`, `nom_caracteristique`) VALUES
('bzh', 35, 'Beurre salé', 'Dangereux pour les allergiques à la Bretagne'),
('kg', 1, 'Chocolat', 'C''est fort en chocolat !'),
('g', 200, 'Fromage', 'Gras'),
('kg', 1, 'Pâtes', 'Féculent');

-- --------------------------------------------------------

--
-- Structure de la table `Caracteristiques_nutritionnelles`
--

CREATE TABLE IF NOT EXISTS `Caracteristiques_nutritionnelles` (
  `nom_caracteristique` varchar(255) NOT NULL,
  PRIMARY KEY (`nom_caracteristique`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Caracteristiques_nutritionnelles`
--

INSERT INTO `Caracteristiques_nutritionnelles` (`nom_caracteristique`) VALUES
('C''est fort en chocolat !'),
('Dangereux pour les allergiques à la Bretagne'),
('Féculent'),
('Gras');

-- --------------------------------------------------------

--
-- Structure de la table `Categories`
--

CREATE TABLE IF NOT EXISTS `Categories` (
  `nom_categorie` varchar(255) NOT NULL,
  PRIMARY KEY (`nom_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Categories`
--

INSERT INTO `Categories` (`nom_categorie`) VALUES
('Apéritif'),
('Déssert'),
('Entrée'),
('Plat');

-- --------------------------------------------------------

--
-- Structure de la table `Commenter`
--

CREATE TABLE IF NOT EXISTS `Commenter` (
  `date` date NOT NULL,
  `texte` varchar(255) NOT NULL,
  `id_internaute` int(11) NOT NULL,
  `id_recette` int(11) NOT NULL,
  PRIMARY KEY (`id_internaute`,`id_recette`),
  KEY `id_recette` (`id_recette`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Commenter`
--

INSERT INTO `Commenter` (`date`, `texte`, `id_internaute`, `id_recette`) VALUES
('2016-11-24', 'Moi j''aime bien', 1, 2),
('2016-11-24', 'Pas mal', 2, 3),
('2016-11-24', 'Merci pour la recette !', 3, 4),
('2016-11-24', 'Bloup', 4, 3);

-- --------------------------------------------------------

--
-- Structure de la table `Contenir_ingredients`
--

CREATE TABLE IF NOT EXISTS `Contenir_ingredients` (
  `unité` varchar(255) NOT NULL,
  `valeur` int(11) NOT NULL,
  `id_recette` int(11) NOT NULL,
  `nom_ingrédient` varchar(255) NOT NULL,
  PRIMARY KEY (`id_recette`,`nom_ingrédient`),
  KEY `nom_ingrédient` (`nom_ingrédient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Contenir_ingredients`
--

INSERT INTO `Contenir_ingredients` (`unité`, `valeur`, `id_recette`, `nom_ingrédient`) VALUES
('g', 500, 1, 'Chocolat'),
('g', 200, 2, 'Fromage'),
('kg', 1, 3, 'Pâtes'),
('g', 200, 4, 'Beurre salé');

-- --------------------------------------------------------

--
-- Structure de la table `Contenir_recette`
--

CREATE TABLE IF NOT EXISTS `Contenir_recette` (
  `id_recette` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  PRIMARY KEY (`id_recette`,`id_menu`),
  KEY `id_menu` (`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Contenir_recette`
--

INSERT INTO `Contenir_recette` (`id_recette`, `id_menu`) VALUES
(1, 1),
(3, 2),
(2, 3),
(4, 3),
(4, 4);

-- --------------------------------------------------------

--
-- Structure de la table `Descriptions`
--

CREATE TABLE IF NOT EXISTS `Descriptions` (
  `id_description` int(11) NOT NULL AUTO_INCREMENT,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `texte` varchar(255) NOT NULL,
  `id_recette` int(11) NOT NULL,
  PRIMARY KEY (`id_description`),
  KEY `id_recette` (`id_recette`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `Descriptions`
--

INSERT INTO `Descriptions` (`id_description`, `date_debut`, `date_fin`, `texte`, `id_recette`) VALUES
(1, '2016-11-24', '0000-00-00', 'C''est fort en chocolat !', 1),
(2, '2016-11-24', '0000-00-00', 'Miam!', 2),
(3, '2016-11-24', '0000-00-00', 'Ne pas oublier l''eau !', 3),
(4, '2016-11-24', '0000-00-00', 'Huummmmm :D', 4);

-- --------------------------------------------------------

--
-- Structure de la table `Ingredients`
--

CREATE TABLE IF NOT EXISTS `Ingredients` (
  `nom_ingredient` varchar(255) NOT NULL,
  PRIMARY KEY (`nom_ingredient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Ingredients`
--

INSERT INTO `Ingredients` (`nom_ingredient`) VALUES
('Beurre salé'),
('Chocolat'),
('Fromage'),
('Pâtes');

-- --------------------------------------------------------

--
-- Structure de la table `Internaute`
--

CREATE TABLE IF NOT EXISTS `Internaute` (
  `id_internaute` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  PRIMARY KEY (`id_internaute`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `Internaute`
--

INSERT INTO `Internaute` (`id_internaute`, `pseudo`) VALUES
(1, 'azertyuiop'),
(2, 'maegrondin'),
(3, 'sbrouard'),
(4, 'phenry003');

-- --------------------------------------------------------

--
-- Structure de la table `Menu`
--

CREATE TABLE IF NOT EXISTS `Menu` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `nom_menu` varchar(255) NOT NULL,
  `id_internaute` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_menu`),
  KEY `id_internaute` (`id_internaute`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `Menu`
--

INSERT INTO `Menu` (`id_menu`, `nom_menu`, `id_internaute`) VALUES
(1, 'Menu cinq étoiles', 1),
(2, 'Menu de Noël', 2),
(3, 'Menu du RU', 3),
(4, 'J''ai pas d''idée de nom de menu', 4);

-- --------------------------------------------------------

--
-- Structure de la table `Noter`
--

CREATE TABLE IF NOT EXISTS `Noter` (
  `valeur` enum('1','2','3') NOT NULL,
  `id_internaute` int(11) NOT NULL,
  `id_recette` int(11) NOT NULL,
  PRIMARY KEY (`id_internaute`,`id_recette`),
  KEY `id_recette` (`id_recette`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Noter`
--

INSERT INTO `Noter` (`valeur`, `id_internaute`, `id_recette`) VALUES
('1', 1, 1),
('2', 2, 2),
('3', 3, 3),
('3', 4, 4);

-- --------------------------------------------------------

--
-- Structure de la table `Recettes_de_cuisine`
--

CREATE TABLE IF NOT EXISTS `Recettes_de_cuisine` (
  `id_recette` int(11) NOT NULL AUTO_INCREMENT,
  `nom_recette` varchar(255) NOT NULL,
  `date_ajout` date NOT NULL,
  `nombre_personnes` int(11) NOT NULL,
  `temps_preparation` time NOT NULL,
  `temps_cuisson` time NOT NULL,
  `id_internaute` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_recette`),
  KEY `id_internaute` (`id_internaute`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `Recettes_de_cuisine`
--

INSERT INTO `Recettes_de_cuisine` (`id_recette`, `nom_recette`, `date_ajout`, `nombre_personnes`, `temps_preparation`, `temps_cuisson`, `id_internaute`) VALUES
(1, 'chocapic', '2016-11-24', 1, '08:44:48', '00:00:00', 1),
(2, 'raclette', '2016-11-24', 10, '00:10:00', '00:30:00', 2),
(3, 'Pattes à l''eau', '2016-11-24', 2, '00:10:00', '00:10:00', 3),
(4, 'Caramel au beurre salé', '2016-11-24', 2000, '01:00:00', '00:00:00', 4);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Appartenir_catégorie`
--
ALTER TABLE `Appartenir_catégorie`
  ADD CONSTRAINT `Appartenir_cat@0pgorie_ibfk_2` FOREIGN KEY (`nom_catégorie`) REFERENCES `Categories` (`nom_categorie`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Appartenir_cat@0pgorie_ibfk_1` FOREIGN KEY (`id_recette`) REFERENCES `Recettes_de_cuisine` (`id_recette`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Avoir_Caracteristiques`
--
ALTER TABLE `Avoir_Caracteristiques`
  ADD CONSTRAINT `Avoir_Caracteristiques_ibfk_2` FOREIGN KEY (`nom_caracteristique`) REFERENCES `Caracteristiques_nutritionnelles` (`nom_caracteristique`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Avoir_Caracteristiques_ibfk_1` FOREIGN KEY (`nom_ingredient`) REFERENCES `Ingredients` (`nom_ingredient`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `Commenter`
--
ALTER TABLE `Commenter`
  ADD CONSTRAINT `Commenter_ibfk_2` FOREIGN KEY (`id_recette`) REFERENCES `Recettes_de_cuisine` (`id_recette`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Commenter_ibfk_1` FOREIGN KEY (`id_internaute`) REFERENCES `Internaute` (`id_internaute`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Contenir_ingredients`
--
ALTER TABLE `Contenir_ingredients`
  ADD CONSTRAINT `Contenir_ingredients_ibfk_2` FOREIGN KEY (`nom_ingrédient`) REFERENCES `Ingredients` (`nom_ingredient`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Contenir_ingredients_ibfk_1` FOREIGN KEY (`id_recette`) REFERENCES `Recettes_de_cuisine` (`id_recette`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Contenir_recette`
--
ALTER TABLE `Contenir_recette`
  ADD CONSTRAINT `Contenir_recette_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `Menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Contenir_recette_ibfk_1` FOREIGN KEY (`id_recette`) REFERENCES `Recettes_de_cuisine` (`id_recette`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Descriptions`
--
ALTER TABLE `Descriptions`
  ADD CONSTRAINT `Descriptions_ibfk_1` FOREIGN KEY (`id_recette`) REFERENCES `Recettes_de_cuisine` (`id_recette`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Menu`
--
ALTER TABLE `Menu`
  ADD CONSTRAINT `Menu_ibfk_1` FOREIGN KEY (`id_internaute`) REFERENCES `Internaute` (`id_internaute`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `Noter`
--
ALTER TABLE `Noter`
  ADD CONSTRAINT `Noter_ibfk_2` FOREIGN KEY (`id_recette`) REFERENCES `Recettes_de_cuisine` (`id_recette`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Noter_ibfk_1` FOREIGN KEY (`id_internaute`) REFERENCES `Internaute` (`id_internaute`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Recettes_de_cuisine`
--
ALTER TABLE `Recettes_de_cuisine`
  ADD CONSTRAINT `Recettes_de_cuisine_ibfk_2` FOREIGN KEY (`id_internaute`) REFERENCES `Internaute` (`id_internaute`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
