-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le: Dim 27 Janvier 2013 à 22:59
-- Version du serveur: 5.5.27-log
-- Version de PHP: 5.4.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `myfriends`
--

-- --------------------------------------------------------

--
-- Structure de la table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id_user` varchar(128) COLLATE utf8_bin NOT NULL,
  `id_user_f` varchar(128) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id_user`,`id_user_f`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `friends`
--

INSERT INTO `friends` (`id_user`, `id_user_f`) VALUES
('7c0c42b72e21968b3ce31f70ef54a6b1', '3a19281e0b7bceaa37f5e7c09b762ddd81e3a212'),
('7c0c42b72e21968b3ce31f70ef54a6b1', 'ebd8dfd348408fb357abbae99ceda5806ede0243');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `token` varchar(128) COLLATE utf8_bin NOT NULL,
  `publictoken` varchar(128) COLLATE utf8_bin NOT NULL,
  `pseudo` varchar(50) COLLATE utf8_bin NOT NULL,
  `firstname` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `lastname` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_bin NOT NULL,
  `age` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `city` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `imagelink` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `number` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`token`, `publictoken`, `pseudo`, `firstname`, `lastname`, `password`, `age`, `city`, `imagelink`, `number`) VALUES
('7c0c42b72e21968b3ce31f70ef54a6b1', '56b190e89225138b35fd4494d5bc606aef02495e', 'blazo123', 'blazo', 'NASTOV', 'nastov123', '15', 'montpellier', '/images/rabah.jpeg', NULL),
('81b1c9ac8ffe1af4a7cf998fae0cad89', 'ebd8dfd348408fb357abbae99ceda5806ede0243', 'betahouse', 'betahouse', 'betahouse', 'betahouse', NULL, NULL, NULL, NULL),
('b2501bb029faaea57f5c9585dfaa6f4b', '3a19281e0b7bceaa37f5e7c09b762ddd81e3a212', 'bibouh123', '', '', 'rabah123', '24', 'Alger', 'http://192.168.17.10/MyFriendWebService/MyFriend/images/rabah.jpg', '0651238329'),
('e378966c5b1cb3e2119774107c178149', 'e72aea39d0a16e28fdebad0274acf5d193dbad52', 'blazo123@live.fr', '', '', 'nastov123', '', '', '', ''),
('f3647954fb5d3f815c244e1e1210564c', '55be515e0ee8fdff8c2a4310f43500b998f92f03', 'bibouh123@live.fr', '', '', 'rabah123', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `usergeo`
--

CREATE TABLE IF NOT EXISTS `usergeo` (
  `token_user` varchar(128) COLLATE utf8_bin NOT NULL,
  `log` double NOT NULL,
  `lat` double NOT NULL,
  `time` varchar(100) COLLATE utf8_bin NOT NULL,
  `visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`token_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `usergeo`
--

INSERT INTO `usergeo` (`token_user`, `log`, `lat`, `time`, `visible`) VALUES
('7c0c42b72e21968b3ce31f70ef54a6b1', -122.084095, 37.422005, 'Sunday 13-January-2013 (10:51:06) -Europe/Paris-', 1),
('81b1c9ac8ffe1af4a7cf998fae0cad89', 0, 0, 'Thursday 27-December-2012 (23:07:55) -Europe/Paris-', 1),
('b2501bb029faaea57f5c9585dfaa6f4b', 50, 5, 'Sunday 27-January-2013 (22:52:40) -Europe/Paris-', 0),
('e378966c5b1cb3e2119774107c178149', 0, 0, 'Sunday 27-January-2013 (22:56:27) -Europe/Paris-', 1),
('f3647954fb5d3f815c244e1e1210564c', 0, 0, 'Wednesday 09-January-2013 (10:44:09) -Europe/Paris-', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
