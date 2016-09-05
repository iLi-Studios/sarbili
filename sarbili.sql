-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 03 Septembre 2016 à 16:09
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `sarbili`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `idClient` int(10) NOT NULL AUTO_INCREMENT,
  `NameClient` varchar(250) NOT NULL,
  `PhoneClient` varchar(250) DEFAULT NULL,
  `EmailClient` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idClient`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `client`
--

INSERT INTO `client` (`idClient`, `NameClient`, `PhoneClient`, `EmailClient`) VALUES
(1, 'Passager', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `logsystem`
--

CREATE TABLE IF NOT EXISTS `logsystem` (
  `idLog` int(10) NOT NULL AUTO_INCREMENT,
  `idUser` int(10) NOT NULL,
  `Timestamp` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`idLog`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `idOrder` int(10) NOT NULL AUTO_INCREMENT,
  `idUser` int(10) NOT NULL,
  `idPayement` int(10) NOT NULL,
  PRIMARY KEY (`idOrder`),
  KEY `idUser` (`idUser`,`idPayement`),
  KEY `idPayement` (`idPayement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `orderprodect`
--

CREATE TABLE IF NOT EXISTS `orderprodect` (
  `idProduct` int(10) NOT NULL,
  `idOrder` int(10) NOT NULL,
  `idClient` int(10) NOT NULL,
  `Table` int(10) NOT NULL,
  KEY `idProduct` (`idProduct`,`idOrder`,`idClient`),
  KEY `idOrder` (`idOrder`),
  KEY `idClient` (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `idPayment` int(10) NOT NULL AUTO_INCREMENT,
  `idUser` int(10) NOT NULL,
  `RecevedByAdmin` tinyint(1) DEFAULT NULL,
  `timestamp` varchar(255) NOT NULL,
  PRIMARY KEY (`idPayment`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `idProduct` int(10) NOT NULL AUTO_INCREMENT,
  `idProductSubFamily` int(10) NOT NULL,
  `NameProduct` varchar(250) NOT NULL,
  `PriceProduct` varchar(250) NOT NULL,
  PRIMARY KEY (`idProduct`),
  KEY `idProductSubFamily` (`idProductSubFamily`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `product`
--

INSERT INTO `product` (`idProduct`, `idProductSubFamily`, `NameProduct`, `PriceProduct`) VALUES
(1, 1, 'Expresse', '1.500'),
(2, 1, 'Capuccin', '1.700'),
(3, 1, 'Direct', '2.500'),
(4, 1, 'Cappoccino', '3.000'),
(5, 1, 'Café Turk', '2.000'),
(6, 2, 'Thé Normal', '1.200'),
(7, 2, 'Thé Ammonde', '2.000'),
(8, 3, 'Coca Cola', '2.000'),
(9, 3, 'Pepsi', '2.000'),
(10, 3, 'Fanta', '2.000'),
(11, 3, 'Apla', '2.000'),
(12, 3, 'Garci', '1.500'),
(13, 3, 'Safia', '1.200'),
(14, 2, 'Fraise', '3.000'),
(15, 2, 'Lait de poule', '2.500'),
(16, 6, '2 Boules', '2.500'),
(17, 6, '3 Boules ', '3.000');

-- --------------------------------------------------------

--
-- Structure de la table `productfamily`
--

CREATE TABLE IF NOT EXISTS `productfamily` (
  `idProductFamily` int(10) NOT NULL AUTO_INCREMENT,
  `NameProductFamily` varchar(250) NOT NULL,
  PRIMARY KEY (`idProductFamily`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `productfamily`
--

INSERT INTO `productfamily` (`idProductFamily`, `NameProductFamily`) VALUES
(1, 'Chaud'),
(2, 'Froid');

-- --------------------------------------------------------

--
-- Structure de la table `productsubfamily`
--

CREATE TABLE IF NOT EXISTS `productsubfamily` (
  `idProductSubFamily` int(10) NOT NULL AUTO_INCREMENT,
  `idProductFamily` int(10) NOT NULL,
  `NameProductSubFamily` varchar(250) NOT NULL,
  PRIMARY KEY (`idProductSubFamily`),
  KEY `idProductFamily` (`idProductFamily`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `productsubfamily`
--

INSERT INTO `productsubfamily` (`idProductSubFamily`, `idProductFamily`, `NameProductSubFamily`) VALUES
(1, 1, 'Café'),
(2, 1, 'Thé'),
(3, 2, 'Soda'),
(4, 2, 'Eau'),
(5, 2, 'Jus'),
(6, 2, 'Glace');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int(10) NOT NULL AUTO_INCREMENT,
  `loginUser` varchar(250) NOT NULL,
  `passUser` varchar(250) NOT NULL,
  `rankUser` varchar(250) NOT NULL,
  `token` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `loginUser` (`loginUser`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`idUser`, `loginUser`, `passUser`, `rankUser`, `token`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrateur', NULL),
(2, 'user', '242e46207ba47ad7c47415fa039457d5', 'Utilisateur', NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `logsystem`
--
ALTER TABLE `logsystem`
  ADD CONSTRAINT `logsystem_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`idPayement`) REFERENCES `payment` (`idPayment`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `orderprodect`
--
ALTER TABLE `orderprodect`
  ADD CONSTRAINT `orderprodect_ibfk_2` FOREIGN KEY (`idClient`) REFERENCES `client` (`idClient`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderprodect_ibfk_1` FOREIGN KEY (`idOrder`) REFERENCES `order` (`idOrder`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`idProductSubFamily`) REFERENCES `productsubfamily` (`idProductSubFamily`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `productsubfamily`
--
ALTER TABLE `productsubfamily`
  ADD CONSTRAINT `productsubfamily_ibfk_1` FOREIGN KEY (`idProductFamily`) REFERENCES `productfamily` (`idProductFamily`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
