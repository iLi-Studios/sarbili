-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 02 Septembre 2016 à 14:20
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
(2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'Utilisateur', NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `logsystem`
--
ALTER TABLE `logsystem`
  ADD CONSTRAINT `logsystem_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
