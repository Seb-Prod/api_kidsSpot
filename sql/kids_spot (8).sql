-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 14 avr. 2025 à 10:43
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `kids_spot`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `id_lieu` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_modification` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `id_lieu`, `commentaire`, `note`, `id_user`, `date_ajout`, `date_modification`) VALUES
(5, 1, 'Super endroit pour les enfants, très sécurisé.', 5, 5, '2025-04-14', '2025-04-14'),
(6, 2, 'Musée intéressant mais un peu cher pour une famille nombreuse.', 3, 6, '2025-04-14', '2025-04-14'),
(7, 3, 'Aire de jeux propre et bien entretenue.', 4, 7, '2025-04-14', '2025-04-14'),
(8, 4, 'Ambiance chaleureuse, personnel sympa !', 5, 8, '2025-04-14', '2025-04-14'),
(9, 5, 'Pas adapté aux tout-petits, dommage.', 2, 9, '2025-04-14', '2025-04-14'),
(10, 1, 'Le jardin est magnifique au printemps.', 4, 10, '2025-04-14', '2025-04-14'),
(11, 2, 'Expositions originales, mes enfants ont adoré.', 5, 5, '2025-04-14', '2025-04-14'),
(12, 3, 'Il y avait trop de monde, difficile de profiter.', 2, 6, '2025-04-14', '2025-04-14'),
(13, 4, 'Parfait pour une pause goûter avec des enfants.', 5, 7, '2025-04-14', '2025-04-14'),
(14, 5, 'Endroit calme, idéal pour lire avec les enfants.', 4, 8, '2025-04-14', '2025-04-14');

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

CREATE TABLE `evenements` (
  `id` int(11) NOT NULL,
  `id_lieux` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `evenements`
--

INSERT INTO `evenements` (`id`, `id_lieux`, `date_debut`, `date_fin`) VALUES
(1, 1, '2025-04-20', '2025-04-20'),
(2, 2, '2025-04-22', '2025-04-22');

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

CREATE TABLE `favoris` (
  `id` int(11) NOT NULL,
  `id_lieu` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lieux`
--

CREATE TABLE `lieux` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `horaires` varchar(50) NOT NULL,
  `adresse` varchar(100) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `code_postal` varchar(5) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(10,8) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `site_web` varchar(255) DEFAULT NULL,
  `date_creation` date NOT NULL,
  `date_modification` date NOT NULL,
  `id_type` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lieux`
--

INSERT INTO `lieux` (`id`, `nom`, `description`, `horaires`, `adresse`, `ville`, `code_postal`, `latitude`, `longitude`, `telephone`, `site_web`, `date_creation`, `date_modification`, `id_type`, `id_user`) VALUES
(1, 'Jardin des Plantes', 'Grand jardin botanique en plein cœur de Paris.', '08:00-20:00', '57 Rue Cuvier', 'Paris', '75005', 48.84300000, 2.35800000, '01 40 79 56 01', 'https://www.jardindesplantes.fr', '2025-04-14', '2025-04-14', 2, 3),
(2, 'Musée en Herbe', 'Musée ludique adapté pour les enfants.', '10:00-19:00', '23 Rue de l\'Arbre Sec', 'Paris', '75001', 48.86150000, 2.34400000, '01 40 67 97 66', 'https://www.museeenherbe.com', '2025-04-14', '2025-04-14', 3, 4),
(3, 'Aire de Jeux Parc Monceau', 'Aire de jeux sécurisée avec toboggans.', '07:30-22:00', '35 Boulevard de Courcelles', 'Paris', '75008', 48.87900000, 2.30940000, '01 42 27 19 82', NULL, '2025-04-14', '2025-04-14', 1, 2),
(4, 'Le P\'tit Café Familial', 'Café cosy avec coin enfants et ateliers créatifs.', '09:00-18:00', '12 Rue de Belleville', 'Paris', '75020', 48.87220000, 2.38950000, '01 43 64 12 77', 'http://www.ptitcafeparis.fr', '2025-04-14', '2025-04-14', 1, 5),
(5, 'Bibliothèque Louise Michel', 'Bibliothèque municipale avec espace jeunesse.', 'Mar-Sam 10:00-18:00', '29 Rue des Haies', 'Paris', '75020', 48.85300000, 2.40910000, '01 43 71 19 40', 'https://bibliotheques.paris.fr', '2025-04-14', '2025-04-14', 3, 6);

-- --------------------------------------------------------

--
-- Structure de la table `lieux_age`
--

CREATE TABLE `lieux_age` (
  `id` int(11) NOT NULL,
  `id_lieu` int(11) NOT NULL,
  `id_age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lieux_equipement`
--

CREATE TABLE `lieux_equipement` (
  `id` int(11) NOT NULL,
  `id_lieux` int(11) NOT NULL,
  `id_equipement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tranche_age`
--

CREATE TABLE `tranche_age` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tranche_age`
--

INSERT INTO `tranche_age` (`id`, `nom`) VALUES
(1, '0 - 3 ans'),
(2, '3 - 6 ans'),
(3, '6 ans et +');

-- --------------------------------------------------------

--
-- Structure de la table `types_equipement`
--

CREATE TABLE `types_equipement` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `types_equipement`
--

INSERT INTO `types_equipement` (`id`, `nom`) VALUES
(1, 'Accès poussette'),
(2, 'Aire de jeux'),
(3, 'Micro-ondes'),
(4, 'Chaise haute'),
(5, 'Table à langer');

-- --------------------------------------------------------

--
-- Structure de la table `types_lieux`
--

CREATE TABLE `types_lieux` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `types_lieux`
--

INSERT INTO `types_lieux` (`id`, `nom`) VALUES
(1, 'Restaurant'),
(2, 'Loisir'),
(3, 'Culture');

-- --------------------------------------------------------

--
-- Structure de la table `types_users`
--

CREATE TABLE `types_users` (
  `id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `types_users`
--

INSERT INTO `types_users` (`id`, `nom`) VALUES
(1, 'standart'),
(2, 'superUser'),
(3, 'spare'),
(4, 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `grade` int(11) NOT NULL DEFAULT 1,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `derniere_connexion` datetime DEFAULT NULL,
  `tentatives_connexion` tinyint(1) NOT NULL DEFAULT 0,
  `compte_verrouille` tinyint(1) NOT NULL DEFAULT 0,
  `date_verrouillage` datetime DEFAULT NULL,
  `token_reinitialisation` varchar(255) DEFAULT NULL,
  `date_expiration_token` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `mail`, `telephone`, `mot_de_passe`, `grade`, `date_creation`, `derniere_connexion`, `tentatives_connexion`, `compte_verrouille`, `date_verrouillage`, `token_reinitialisation`, `date_expiration_token`) VALUES
(5, 'Seb-Prod2', 'seb.prod@gmail.com', '', '$2y$10$VFc5DxzXFm3IK0FqlMsgR.GgjRK5HaOAvixkkgEI.84w3vk2NNV.W', 4, '2025-04-13 16:50:42', '2025-04-13 21:54:53', 0, 0, NULL, NULL, NULL),
(6, 'User1', 'user1@gmail.com', '', '$2y$10$skcvMlbpDgjW2gM80aTTwO2xNkfZVe5EV31XiTmw7inBQ9hDtjdNS', 1, '2025-04-13 16:51:48', NULL, 0, 0, NULL, NULL, NULL),
(7, 'User2', 'user2@gmail.com', '', '$2y$10$HaeW28Uiv1V4BatCU7NP7ObkBZu/reVEwwhzcB6FZtHHKZMnnV1bu', 1, '2025-04-13 16:52:09', NULL, 0, 0, NULL, NULL, NULL),
(8, 'User3', 'user3@gmail.com', '', '$2y$10$GsOs4GJUjCs6JmfG8ZryhehrcXL1v4G9lyPkugNImHvD.SpkH0h9O', 1, '2025-04-13 16:52:18', NULL, 0, 0, NULL, NULL, NULL),
(9, 'User4', 'user4@gmail.com', '', '$2y$10$ss/0Jd6gRjYUddYSZ4.91OaPgtygSzw5jBpxo8vNh/ypBhNspalty', 1, '2025-04-13 16:52:36', NULL, 0, 0, NULL, NULL, NULL),
(10, 'User5', 'user5@gmail.com', '', '$2y$10$4JdmBcMQDef82UrTDQMIwOPe5/ouURIShj6ZhmOX106rX7VocFxq6', 1, '2025-04-13 16:52:43', NULL, 0, 0, NULL, NULL, NULL),
(12, 'User6', 'user6@gmail.com', '', '$2y$10$X70NzqyTAO4Vg0AtFwAMJe9ad8vy.OcqSFohK8yjz2tpNGrW2pqJC', 1, '2025-04-13 16:54:04', '2025-04-14 09:35:24', 0, 0, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_lieu` (`id_lieu`,`id_user`),
  ADD KEY `commentaires_ibfk_2` (`id_user`);

--
-- Index pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lieux` (`id_lieux`);

--
-- Index pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_lieu` (`id_lieu`,`id_user`),
  ADD KEY `favoris_ibfk_2` (`id_user`);

--
-- Index pour la table `lieux`
--
ALTER TABLE `lieux`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type` (`id_type`);

--
-- Index pour la table `lieux_age`
--
ALTER TABLE `lieux_age`
  ADD KEY `id_age` (`id_age`),
  ADD KEY `id_lieu` (`id_lieu`);

--
-- Index pour la table `lieux_equipement`
--
ALTER TABLE `lieux_equipement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_equipement` (`id_equipement`),
  ADD KEY `lieux_equipement_ibfk_2` (`id_lieux`);

--
-- Index pour la table `tranche_age`
--
ALTER TABLE `tranche_age`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `types_equipement`
--
ALTER TABLE `types_equipement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `types_lieux`
--
ALTER TABLE `types_lieux`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `types_users`
--
ALTER TABLE `types_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`,`nom`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD KEY `grade` (`grade`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `evenements`
--
ALTER TABLE `evenements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `lieux`
--
ALTER TABLE `lieux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `lieux_equipement`
--
ALTER TABLE `lieux_equipement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `tranche_age`
--
ALTER TABLE `tranche_age`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `types_equipement`
--
ALTER TABLE `types_equipement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `types_lieux`
--
ALTER TABLE `types_lieux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `types_users`
--
ALTER TABLE `types_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`id_lieu`) REFERENCES `lieux` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD CONSTRAINT `evenements_ibfk_1` FOREIGN KEY (`id_lieux`) REFERENCES `lieux` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`id_lieu`) REFERENCES `lieux` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lieux`
--
ALTER TABLE `lieux`
  ADD CONSTRAINT `lieux_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `types_lieux` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lieux_age`
--
ALTER TABLE `lieux_age`
  ADD CONSTRAINT `lieux_age_ibfk_1` FOREIGN KEY (`id_age`) REFERENCES `tranche_age` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lieux_age_ibfk_2` FOREIGN KEY (`id_lieu`) REFERENCES `lieux` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lieux_equipement`
--
ALTER TABLE `lieux_equipement`
  ADD CONSTRAINT `lieux_equipement_ibfk_1` FOREIGN KEY (`id_equipement`) REFERENCES `types_equipement` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lieux_equipement_ibfk_2` FOREIGN KEY (`id_lieux`) REFERENCES `lieux` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`grade`) REFERENCES `types_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
