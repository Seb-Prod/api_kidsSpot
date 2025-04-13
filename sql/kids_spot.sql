-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : sam. 12 avr. 2025 à 17:16
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
(1, 3, '2025-04-15', '2025-04-30'),
(2, 6, '2025-04-20', '2025-04-25'),
(3, 9, '2025-05-01', '2025-05-15'),
(4, 2, '2025-05-10', '2025-05-12'),
(5, 8, '2025-06-01', '2025-06-30');

-- --------------------------------------------------------

--
-- Structure de la table `lieux`
--

CREATE TABLE `lieux` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `adresse` varchar(100) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `code_postal` varchar(5) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(10,8) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `site_web` varchar(255) NOT NULL,
  `date_creation` date NOT NULL,
  `date_modification` date NOT NULL,
  `id_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lieux`
--

INSERT INTO `lieux` (`id`, `nom`, `description`, `adresse`, `ville`, `code_postal`, `latitude`, `longitude`, `telephone`, `site_web`, `date_creation`, `date_modification`, `id_type`) VALUES
(1, 'La Cabane des Petits Gourmets', 'Restaurant familial avec espace de jeux intérieur et menu adapté aux enfants.', '15 rue des Lilas', 'Paris', '75011', 48.85861234, 2.35916789, '0145789632', 'https://cabanepetitsgourmets.fr', '2025-01-15', '2025-03-20', 1),
(2, 'Le Jardin d\'Acclimatation', 'Parc d\'attractions et jardin d\'enfants avec manèges, aires de jeux et activités pour toute la famille.', '45 avenue du Bois de Boulogne', 'Paris', '75016', 48.87752416, 2.26536789, '0144967800', 'https://jardindacclimatation.fr', '2024-11-10', '2025-02-12', 2),
(3, 'Cité des Sciences et de l\'Industrie', 'Musée scientifique avec expositions interactives et espace dédié aux enfants (Cité des Enfants).', '30 avenue Corentin-Cariou', 'Paris', '75019', 48.89567823, 2.38797456, '0140057500', 'https://cite-sciences.fr', '2024-12-05', '2025-04-01', 3),
(4, 'Le Petit Café des Enfants', 'Café-restaurant pensé pour les familles avec aire de jeux et ateliers pour enfants.', '25 rue de la Gaité', 'Paris', '75014', 48.83987654, 2.32456789, '0143352614', 'https://petitcafedesenfants.fr', '2025-02-18', '2025-04-02', 1),
(5, 'Accrobranche de Saint-Germain', 'Parcours aventure en forêt avec tyroliennes et ponts suspendus adaptés à tous les âges.', '123 route Forestière', 'Saint-Germain-en-Laye', '78100', 48.89763245, 2.07894512, '0130613785', 'https://accrobranche-saintgermain.fr', '2024-10-25', '2025-03-15', 2),
(6, 'Médiathèque Françoise Sagan', 'Bibliothèque moderne avec espace jeunesse, animations lecture et ateliers créatifs.', '8 rue Léon Schwartzenberg', 'Paris', '75010', 48.87642531, 2.35489761, '0153246970', 'https://mediatheque-sagan.paris.fr', '2025-01-08', '2025-04-03', 3),
(7, 'Les Saveurs de l\'Île', 'Restaurant avec terrasse et espace jeux, situé au cœur du bois de Vincennes.', '56 avenue des Minimes', 'Vincennes', '94300', 48.84123675, 2.43679514, '0143283491', 'https://saveurs-ile.fr', '2025-02-01', '2025-04-01', 1),
(8, 'Koezio Île-de-France', 'Parc d\'aventure indoor proposant des missions ludiques en équipe pour les enfants et adolescents.', '16 avenue Ampère', 'Cergy', '95000', 49.03489712, 2.06712543, '0134356789', 'https://koezio-idf.fr', '2024-12-12', '2025-03-10', 2),
(9, 'Centre Pompidou - Atelier des Enfants', 'Centre culturel proposant des ateliers artistiques et expositions adaptées aux enfants.', '19 rue Beaubourg', 'Paris', '75004', 48.86071234, 2.35214567, '0144784840', 'https://centrepompidou.fr', '2025-01-25', '2025-03-28', 3),
(10, 'Pizzeria Famiglia', 'Pizzeria familiale avec coin enfants, menus bambino et animations le mercredi et week-end.', '67 avenue de Versailles', 'Paris', '75016', 48.84521479, 2.26987453, '0146472539', 'https://pizzeria-famiglia.fr', '2025-02-10', '2025-04-05', 1),
(11, 'nom', 'description', 'adress', 'ville', '89100', 12.00000000, 12.00000000, '0666224498', 'www.test.com', '2025-04-11', '2025-04-11', 1),
(12, 'nom', 'description', 'adress', 'ville', 'ff', 12.00000000, 12.00000000, '0666224498', 'www.test.com', '2025-04-11', '2025-04-11', 1),
(13, 'nom', 'description', 'adress', 'ville', 'ff', 12.00000000, 12.00000000, '0666224498', 'www.test.com', '2025-04-11', '2025-04-11', 1);

-- --------------------------------------------------------

--
-- Structure de la table `lieux_equipement`
--

CREATE TABLE `lieux_equipement` (
  `id` int(11) NOT NULL,
  `id_lieux` int(11) NOT NULL,
  `id_equipement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lieux_equipement`
--

INSERT INTO `lieux_equipement` (`id`, `id_lieux`, `id_equipement`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 4),
(4, 1, 5),
(5, 2, 1),
(6, 2, 2),
(7, 2, 5),
(8, 3, 1),
(9, 3, 3),
(10, 3, 5),
(11, 4, 1),
(12, 4, 2),
(13, 4, 4),
(14, 4, 5),
(15, 5, 1),
(16, 5, 2),
(17, 6, 1),
(18, 6, 3),
(19, 6, 5),
(20, 7, 1),
(21, 7, 2),
(22, 7, 3),
(23, 7, 4),
(24, 7, 5),
(25, 8, 1),
(26, 8, 2),
(27, 8, 5),
(28, 9, 1),
(29, 9, 3),
(30, 9, 5),
(31, 10, 1),
(32, 10, 2),
(33, 10, 4),
(34, 10, 5);

-- --------------------------------------------------------

--
-- Structure de la table `tranche_age`
--

CREATE TABLE `tranche_age` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Index pour les tables déchargées
--

--
-- Index pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD KEY `id_lieux` (`id_lieux`);

--
-- Index pour la table `lieux`
--
ALTER TABLE `lieux`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type` (`id_type`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD CONSTRAINT `evenements_ibfk_1` FOREIGN KEY (`id_lieux`) REFERENCES `lieux` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lieux`
--
ALTER TABLE `lieux`
  ADD CONSTRAINT `lieux_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `types_lieux` (`id`) ON DELETE CASCADE;

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
