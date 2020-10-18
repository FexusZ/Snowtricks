-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 18 oct. 2020 à 15:15
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `snowtricks`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activation_token` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `first_name`, `last_name`, `password`, `email`, `activation_token`, `image`, `reset_token`) VALUES
(9, 'Admin', 'Admin', '$argon2id$v=19$m=65536,t=4,p=1$MVNhNXBGMTNUZ3FRRmFpOQ$RpPZcOZnza05N+raqsYWwZe2zReWiTWd3JxrVoRA+Os', 'admin@admin.fr', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `figure_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `commentaire` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_67F068BC5C011B5` (`figure_id`),
  KEY `IDX_67F068BC19EB6921` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20201012185402', '2020-10-12 19:07:58', 366);

-- --------------------------------------------------------

--
-- Structure de la table `figures`
--

DROP TABLE IF EXISTS `figures`;
CREATE TABLE IF NOT EXISTS `figures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client_id` int(11) NOT NULL,
  `figure` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupe` int(11) NOT NULL,
  `featured_image` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ABF1009A99DED506` (`id_client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `figures`
--

INSERT INTO `figures` (`id`, `id_client_id`, `figure`, `description`, `groupe`, `featured_image`, `created_at`, `updated_at`) VALUES
(58, 9, '360', 'trois six pour un tour complet.', 0, 0, '2020-10-18 17:14:46', '2020-10-18 17:14:46'),
(59, 9, 'truck driver', 'saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)', 0, 0, '2020-10-18 17:14:46', '2020-10-18 17:14:46'),
(60, 9, 'seat belt', 'saisie du carre frontside à l\'arrière avec la main avant', 0, 0, '2020-10-18 17:14:46', '2020-10-18 17:14:46'),
(61, 9, 'japan air', 'saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.', 0, 0, '2020-10-18 17:14:46', '2020-10-18 17:14:46'),
(62, 9, 'stalefish ', 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière', 0, 0, '2020-10-18 17:14:46', '2020-10-18 17:14:46'),
(63, 9, 'bluntslide', 'Le bluntslide est un trick de skateboard. Il fait partie de la catégorie des slides et s\'effectue par conséquent sur un rail, un curb, ou un autre élément s\'y apparentant.', 1, 0, '2020-10-18 17:14:46', '2020-10-18 17:14:46'),
(64, 9, 'half-pipe', 'La rampe, ou half-pipe (ou halfpipe), est un des types de modules de skatepark que l\'on peut trouver dans les skateparks. C\'est également le nom d\'une discipline du skateboard, du roller et du BMX. On l\'appelle également la « big », la « vert » (venant de « verticale »), ou encore la « courbe ». C\'est également une épreuve olympique en surf des neiges et en ski.', 1, 0, '2020-10-18 17:14:46', '2020-10-18 17:14:46'),
(65, 9, 'boardercross', 'Le boardercross, bladercross ou snowboardcross est un parcours d\'obstacle chronométré sur piste comportant des bosses, des portes et des virages relevés.', 1, 0, '2020-10-18 17:14:46', '2020-10-18 17:14:46'),
(66, 9, '1080', 'ou big foot pour trois tours', 1, 0, '2020-10-18 17:14:46', '2020-10-18 17:14:46'),
(67, 9, 'nose grab', 'saisie de la partie avant de la planche, avec la main avant', 1, 0, '2020-10-18 17:14:46', '2020-10-18 17:14:46');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_figure_id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045F85F5AD92` (`id_figure_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `id_figure_id`, `image`) VALUES
(58, 58, 'abff895b7f0e8575a309c1068beaa201.jpg'),
(59, 59, 'ec68338c305ca181779dac195d614ac7.jpg'),
(60, 60, 'd4294d045c89b58c284d0a9a8481cfa5.jpg'),
(61, 61, '6377dd6c6ccdd3892dd42536d78bdd61.jpg'),
(62, 62, '01773071ff268749b0208ed3b70aa699.jpg'),
(63, 63, '451213309ac1611d48597c5faef38e58.jpg'),
(64, 64, 'cf9fa97fd65c1cffdb895bf88cd6fbd6.jpg'),
(65, 65, 'cab6dcc0a761af021ceea28e2d15f224.jpg'),
(66, 66, '6f941ddfddc18983088367729a653a48.jpg'),
(67, 67, 'c749cf981bc84818051fba0459daf1ab.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

DROP TABLE IF EXISTS `video`;
CREATE TABLE IF NOT EXISTS `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_figure_id` int(11) NOT NULL,
  `video` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7CC7DA2C85F5AD92` (`id_figure_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `id_figure_id`, `video`) VALUES
(58, 58, 'https://www.youtube.com/embed/JJy39dO_PPE'),
(59, 59, 'https://www.youtube.com/embed/6tgjY8baFT0'),
(60, 60, 'https://www.youtube.com/embed/4vGEOYNGi_c'),
(61, 61, 'https://www.youtube.com/embed/jH76540wSqU'),
(62, 62, 'https://www.youtube.com/embed/f9FjhCt_w2U'),
(63, 63, 'https://www.youtube.com/embed/Nkotow1RyaQ'),
(64, 64, 'https://www.youtube.com/embed/Kqz0z8opjfM'),
(65, 65, 'https://www.youtube.com/embed/lJhaUKC-0wg'),
(66, 66, 'https://www.youtube.com/embed/camHB0Rj4gA'),
(67, 67, 'https://www.youtube.com/embed/M-W7Pmo-YMY');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `FK_67F068BC19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_67F068BC5C011B5` FOREIGN KEY (`figure_id`) REFERENCES `figures` (`id`);

--
-- Contraintes pour la table `figures`
--
ALTER TABLE `figures`
  ADD CONSTRAINT `FK_ABF1009A99DED506` FOREIGN KEY (`id_client_id`) REFERENCES `client` (`id`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F85F5AD92` FOREIGN KEY (`id_figure_id`) REFERENCES `figures` (`id`);

--
-- Contraintes pour la table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `FK_7CC7DA2C85F5AD92` FOREIGN KEY (`id_figure_id`) REFERENCES `figures` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
