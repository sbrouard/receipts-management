-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 01 Décembre 2016 à 14:06
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
('Dessert', 1),
('Plat', 2),
('Plat', 3),
('Dessert', 4),
('Apéritif', 40);

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
('kcal', 100, 'artichaud', 'Calories'),
('kcal', 760, 'beurre', 'Calories'),
('kcal', 770, 'beurre salé', 'Calories'),
('%', 35, 'beurre salé', 'Magnésium'),
('kcal', 500, 'boeuf', 'Calories'),
('kcal', 610, 'cacahuettes', 'Calories'),
('kcal', 550, 'carotte', 'Calories'),
('kcal', 10, 'cerise', 'Calories'),
('kcal', 400, 'chocolat', 'Calories'),
('%', 1, 'chocolat', 'Teneur en fer'),
('kcal', 5, 'coriandre', 'Calories'),
('kcal', 50, 'dinde', 'Calories'),
('kcal', 80, 'fromage', 'Calories'),
('g/kg', 50, 'fromage', 'Matières grasses'),
('kcal', 30, 'gésier', 'Calories'),
('kcal', 30, 'haricot', 'Calories'),
('kcal', 120, 'miel', 'Calories'),
('%', 60, 'pâtes', 'Calcium'),
('kcal', 100, 'pâtes', 'Calories'),
('%', 40, 'pâtes', 'Matières grasses'),
('kcal', 50, 'poire', 'Calories'),
('kcal', 2, 'poivre', 'Calories'),
('kcal', 50, 'pomme', 'Calories'),
('kcal', 30, 'pomme de terre', 'Calories'),
('kcal', 90, 'poulet', 'Calories'),
('kcal', 70, 'purée', 'Calories'),
('kcal', 10, 'raisin', 'Calories'),
('kcal', 20, 'ratatouille', 'Calories'),
('kcal', 30, 'riz', 'Calories'),
('kcal', 15, 'salade', 'Calories'),
('kcal', 400, 'sauce tomate', 'Calories'),
('kcal', 42, 'sel', 'Calories'),
('kcal', 11, 'soja', 'Calories'),
('kcal', 230, 'steak', 'Calories'),
('kcal', 2, 'thym', 'Calories'),
('kcal', 86, 'tomate', 'Calories');

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
('Calcium'),
('Calories'),
('Magnésium'),
('Matières grasses'),
('Potassium'),
('Sodium'),
('Teneur en fer');

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
('Autre'),
('Dessert'),
('Entrée'),
('Plat');

-- --------------------------------------------------------

--
-- Structure de la table `Commenter`
--

CREATE TABLE IF NOT EXISTS `Commenter` (
  `date` datetime NOT NULL,
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
('2016-11-30 11:53:05', 'C''est fort en chocolat !! :p\r\nMouahah', 1, 1),
('2016-11-01 08:04:28', 'Moi j''aime bien', 1, 2),
('2016-11-27 17:52:04', 'Ce serait mieux avec de la bolognaise ! ;)', 1, 3),
('2016-11-27 17:52:51', 'C''est pas bon !', 1, 4),
('2016-11-12 02:11:23', 'Pas mal', 2, 3),
('2016-11-15 18:12:50', 'Merci pour la recette !', 3, 4),
('2016-11-30 18:28:40', 'La préparation manque de précision', 4, 2),
('2016-11-24 09:35:10', 'Bloup', 4, 3);

-- --------------------------------------------------------

--
-- Structure de la table `Contenir_ingredients`
--

CREATE TABLE IF NOT EXISTS `Contenir_ingredients` (
  `unite` varchar(255) NOT NULL,
  `valeur` int(11) NOT NULL,
  `id_recette` int(11) NOT NULL,
  `nom_ingrédient` varchar(255) NOT NULL,
  PRIMARY KEY (`id_recette`,`nom_ingrédient`),
  KEY `nom_ingrédient` (`nom_ingrédient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Contenir_ingredients`
--

INSERT INTO `Contenir_ingredients` (`unite`, `valeur`, `id_recette`, `nom_ingrédient`) VALUES
('g', 500, 1, 'chocolat'),
('g', 200, 2, 'fromage'),
('g', 50, 3, 'beurre'),
('kg', 1, 3, 'pâtes'),
('pincée', 1, 3, 'sel'),
('g', 200, 4, 'beurre salé'),
('truc', 0, 40, 'ratatouille');

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
(2, 1),
(3, 1),
(3, 2),
(2, 3),
(4, 3),
(4, 4),
(4, 40),
(1, 42);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Contenu de la table `Descriptions`
--

INSERT INTO `Descriptions` (`id_description`, `date_debut`, `date_fin`, `texte`, `id_recette`) VALUES
(1, '2016-11-24', '0000-00-00', 'C''est fort en chocolat !', 1),
(2, '2016-11-24', '0000-00-00', 'Miam!', 2),
(3, '2016-11-24', '0000-00-00', 'Ne pas oublier l''eau !', 3),
(4, '2016-11-24', '0000-00-00', 'Huummmmm :D', 4),
(23, '2016-11-10', '2016-11-24', 'Voici comment on prépare: jzdbfuoazbbpubg', 1),
(24, '2016-10-14', '2016-11-10', 'Première desciption: voila voila, tout mélanger', 1),
(26, '2016-11-30', '0000-00-00', 'fd', 40);

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
('artichaud'),
('beurre'),
('beurre salé'),
('boeuf'),
('cacahuettes'),
('carotte'),
('cerise'),
('chocolat'),
('coriandre'),
('dinde'),
('fromage'),
('gésier'),
('haricot'),
('miel'),
('pâtes'),
('poire'),
('poivre'),
('pomme'),
('pomme de terre'),
('poulet'),
('purée'),
('raisin'),
('ratatouille'),
('rhum'),
('riz'),
('salade'),
('sauce tomate'),
('sel'),
('soja'),
('steak'),
('thym'),
('tomate');

-- --------------------------------------------------------

--
-- Structure de la table `Internaute`
--

CREATE TABLE IF NOT EXISTS `Internaute` (
  `id_internaute` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  PRIMARY KEY (`id_internaute`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `Internaute`
--

INSERT INTO `Internaute` (`id_internaute`, `pseudo`, `mot_de_passe`) VALUES
(1, 'azertyuiop', 'qwerty'),
(2, 'maegrondin', 'maegrondin'),
(3, 'sbrouard', 'sbrouard'),
(4, 'phenry003', 'phenry003'),
(6, 'anonyme', 'anonyme'),
(15, 'testpassword', 'testpassword');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- Contenu de la table `Menu`
--

INSERT INTO `Menu` (`id_menu`, `nom_menu`, `id_internaute`) VALUES
(1, 'Menu cinq étoiles', 1),
(2, 'Menu de Noël', 2),
(3, 'Menu du RU', 3),
(4, 'J''ai pas d''idée de nom de menu', 4),
(40, 'ergazg', 1),
(41, 'Menu test', 1),
(42, '', 1),
(43, '', 1);

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
('1', 1, 4),
('2', 2, 1),
('2', 2, 2),
('3', 3, 1),
('3', 3, 3),
('1', 4, 1),
('3', 4, 4),
('3', 15, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Contenu de la table `Recettes_de_cuisine`
--

INSERT INTO `Recettes_de_cuisine` (`id_recette`, `nom_recette`, `date_ajout`, `nombre_personnes`, `temps_preparation`, `temps_cuisson`, `id_internaute`) VALUES
(1, 'chocapic', '2016-11-24', 1, '08:44:48', '00:00:00', 1),
(2, 'raclette', '2016-11-24', 10, '00:10:00', '00:30:00', 2),
(3, 'Pattes à l''eau', '2016-11-24', 2, '00:10:00', '00:10:00', 3),
(4, 'Caramel au beurre salé', '2016-11-24', 2000, '01:00:00', '00:00:00', 4),
(40, 'dg', '2016-11-30', 1, '00:00:00', '00:00:00', 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Appartenir_catégorie`
--
ALTER TABLE `Appartenir_catégorie`
  ADD CONSTRAINT `Appartenir_cat@0pgorie_ibfk_1` FOREIGN KEY (`id_recette`) REFERENCES `Recettes_de_cuisine` (`id_recette`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Appartenir_cat@0pgorie_ibfk_2` FOREIGN KEY (`nom_catégorie`) REFERENCES `Categories` (`nom_categorie`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Avoir_Caracteristiques`
--
ALTER TABLE `Avoir_Caracteristiques`
  ADD CONSTRAINT `Avoir_Caracteristiques_ibfk_1` FOREIGN KEY (`nom_ingredient`) REFERENCES `Ingredients` (`nom_ingredient`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Avoir_Caracteristiques_ibfk_2` FOREIGN KEY (`nom_caracteristique`) REFERENCES `Caracteristiques_nutritionnelles` (`nom_caracteristique`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `Commenter`
--
ALTER TABLE `Commenter`
  ADD CONSTRAINT `Commenter_ibfk_1` FOREIGN KEY (`id_internaute`) REFERENCES `Internaute` (`id_internaute`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Commenter_ibfk_2` FOREIGN KEY (`id_recette`) REFERENCES `Recettes_de_cuisine` (`id_recette`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Contenir_ingredients`
--
ALTER TABLE `Contenir_ingredients`
  ADD CONSTRAINT `Contenir_ingredients_ibfk_1` FOREIGN KEY (`id_recette`) REFERENCES `Recettes_de_cuisine` (`id_recette`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Contenir_ingredients_ibfk_2` FOREIGN KEY (`nom_ingrédient`) REFERENCES `Ingredients` (`nom_ingredient`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `Contenir_recette`
--
ALTER TABLE `Contenir_recette`
  ADD CONSTRAINT `Contenir_recette_ibfk_1` FOREIGN KEY (`id_recette`) REFERENCES `Recettes_de_cuisine` (`id_recette`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Contenir_recette_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `Menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `Noter_ibfk_1` FOREIGN KEY (`id_internaute`) REFERENCES `Internaute` (`id_internaute`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Noter_ibfk_2` FOREIGN KEY (`id_recette`) REFERENCES `Recettes_de_cuisine` (`id_recette`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Recettes_de_cuisine`
--
ALTER TABLE `Recettes_de_cuisine`
  ADD CONSTRAINT `Recettes_de_cuisine_ibfk_2` FOREIGN KEY (`id_internaute`) REFERENCES `Internaute` (`id_internaute`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
