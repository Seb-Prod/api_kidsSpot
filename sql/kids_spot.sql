-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 18, 2025 at 08:58 AM
-- Server version: 10.11.9-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kids_spot`
--

-- --------------------------------------------------------

--
-- Table structure for table `commentaires`
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
-- Dumping data for table `commentaires`
--

INSERT INTO `commentaires` (`id`, `id_lieu`, `commentaire`, `note`, `id_user`, `date_ajout`, `date_modification`) VALUES
(5, 1, 'Super endroit pour les enfants, très sécurisé.', 5, 5, '2025-04-14', '2025-04-14'),
(26, 1, 'Très bien, super pour les enfants', 5, 54, '2025-05-06', '2025-05-06'),
(29, 3, 'Reeeeeeeeeee', 3, 54, '2025-05-06', '2025-05-06'),
(31, 1, 'Bon lieu recommandable', 5, 53, '2025-05-07', '2025-05-07'),
(32, 168, 'Super endroit je recommande !', 5, 59, '2025-05-14', '2025-05-14'),
(33, 188, 'j’appelle m’oussa', 2, 53, '2025-05-15', '2025-05-15');

-- --------------------------------------------------------

--
-- Table structure for table `evenements`
--

CREATE TABLE `evenements` (
  `id` int(11) NOT NULL,
  `id_lieux` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evenements`
--

INSERT INTO `evenements` (`id`, `id_lieux`, `date_debut`, `date_fin`) VALUES
(1, 1, '2025-04-20', '2024-06-01'),
(8, 121, '2025-03-02', '2025-07-09'),
(9, 125, '2024-12-17', '2025-07-27'),
(11, 177, '2025-05-14', '2025-06-05'),
(12, 178, '2025-05-21', '2025-05-25'),
(13, 179, '2025-05-13', '2025-05-28'),
(14, 180, '2025-05-14', '2025-06-15'),
(15, 185, '2025-05-17', '2025-07-12'),
(16, 186, '2025-05-16', '2025-11-01'),
(18, 223, '2025-04-15', '2025-04-19');

-- --------------------------------------------------------

--
-- Table structure for table `favoris`
--

CREATE TABLE `favoris` (
  `id` int(11) NOT NULL,
  `id_lieu` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favoris`
--

INSERT INTO `favoris` (`id`, `id_lieu`, `id_user`) VALUES
(204, 128, 53),
(203, 130, 53),
(201, 131, 53),
(206, 135, 53),
(199, 136, 53),
(198, 138, 53),
(196, 139, 53),
(207, 168, 53),
(208, 168, 59),
(200, 173, 53),
(213, 190, 62),
(211, 196, 54);

-- --------------------------------------------------------

--
-- Table structure for table `lieux`
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
  `telephone` varchar(15) DEFAULT NULL,
  `site_web` varchar(255) DEFAULT NULL,
  `date_creation` date NOT NULL,
  `date_modification` date NOT NULL,
  `id_type` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lieux`
--

INSERT INTO `lieux` (`id`, `nom`, `description`, `horaires`, `adresse`, `ville`, `code_postal`, `latitude`, `longitude`, `telephone`, `site_web`, `date_creation`, `date_modification`, `id_type`, `id_user`) VALUES
(1, 'Café des enfants', 'Atelier contes\r\nEspaces ludiques\r\nEscape Game enfants', '10h45 - 18h', '1 rue Fleury', 'Paris', '75018', 48.88480156, 2.35385942, '01 53 09 99 59', 'https://homesweetmomes.paris/', '2025-05-26', '2025-05-26', 2, 5),
(3, 'Aire de Jeux Parc Monceau', 'Aire de jeux sécurisée avec toboggans.', '07:30-22:00', '35 Boulevard de Courcelles', 'Paris', '75008', 48.87900000, 2.30940000, '01 42 27 19 82', NULL, '2025-04-14', '2025-04-14', 2, 5),
(84, 'Tribu Paris', 'Café-poussette avec espace de jeux pour enfants de 0 à 6 ans, ateliers parent-enfant, studio yoga-pilates.', 'Consultez le site pour les horaires détaillés.', '183 Rue du Faubourg Poissonnière', 'Paris', '75009', 48.88255640, 2.34958660, '', 'https://www.tribuparis.com/', '2025-05-09', '2025-05-09', 1, 5),
(121, 'Ma petite cinémathèque', 'La Cinémathèque propose aux enfants, aux adolescents et à leurs parents de grands films classiques sur grand écran, pour mieux comprendre et apprendre l\'histoire du cinéma.\r\n', '11:00 - 20:00, fermé le mardi', '51 Rue de Bercy', 'Paris', '75012', 48.83720000, 2.37920000, '01 71 19 33 33', 'https://www.cinematheque.fr/cycle/ma-petite-cinematheque-987.html', '2025-05-12', '2025-05-12', 3, 5),
(122, 'Café des enfants par Home Sweet Momes', 'Atelier contes\r\nEspaces ludiques\r\nEscape Game enfants', '10:45 - 18:00', '1 rue Fleury', 'Paris', '75018', 48.88480156, 2.35385942, '01 53 09 99 59', 'https://homesweetmomes.paris/', '2025-05-12', '2025-05-12', 2, 5),
(123, 'Disneyland Paris', 'Aujourd\'hui, Disneyland Paris est une destination qui invite à un séjour prolongé avec deux parcs à thèmes, sept hôtels Disney et Village Nature® Paris, un golf de 27 trous, le centre de divertissements Disney Village et un des plus grands espaces dédiés aux congrès en Europe', '9h30 - 22h40', 'Avenue Paul Seramy', 'Coupvray', '77700', 48.86361720, 2.78835780, '09 69 32 60 66', 'https://www.disneylandparis.com/fr-fr/', '2025-05-12', '2025-05-12', 2, 5),
(124, 'Zoo de Paris', 'Le parc zoologique de Paris, anciennement parc zoologique du bois de Vincennes, familièrement appelé zoo de Vincennes, est un parc zoologique français de 14,5 hectares, faisant partie du Muséum national d&#039;histoire naturelle, situé dans l&#039;ouest du bois de Vincennes, attenant au 12ᵉ arrondissement de Paris. ', '9h30 - 18h00', 'Avenue Daumesnil', 'Paris', '75012', 48.84058060, 2.43453350, '01 44 75 20 00', 'https://www.parczoologiquedeparis.fr/fr', '2025-05-12', '2025-05-12', 3, 5),
(125, 'Exposition Flight - Musée de l\'air et de l\'Espace', 'Jusqu’au dimanche 27 juillet 2025, le musée de l’Air et de l’Espace révèle les secrets du vol avec Flight. L’exposition s’intéresse à la diversité des créatures volantes (avions, hélicoptères, drones, oiseaux, chauves-souris, insectes et même poissons !) sous le prisme de leur incroyable faculté à s’élever dans les airs.', '10h - 18h', 'Aéroport de Paris-Le Bourget', 'Le Bourget', '93350', 48.95876570, 2.43827720, NULL, 'https://www.museeairespace.fr/agenda/exposition-flight/', '2025-05-12', '2025-05-12', 3, 54),
(127, 'Cafézoïde', 'Café culturel associatif avec ateliers pour enfants, espace de jeux, plats bios à prix doux.', 'Mercredi-Dimanche 10h-18h', '92 bis Quai de la Loire', ' Paris', '75019', 48.88970000, 3.26184850, '2.3750', 'https://www.cafezoide.asso.fr/', '2025-05-12', '2025-05-12', 1, 5),
(128, 'La Recyclerie', 'Ancienne gare transformée en café avec ferme urbaine, ateliers pour enfants, menu enfant, chaises hautes.', '10h - 2h', '83 Boulevard Ornano ', 'Paris', '75018', 48.89720000, 2.34440000, '01 42 57 58 49', 'https://www.larecyclerie.com/', '2025-05-12', '2025-05-12', 1, 0),
(129, 'Les Niçois', 'Restaurant avec espace de jeux, chaises hautes, table à langer, menu enfant, brunch avec garderie.', 'brunch - Samedi & Dimanche 12h-15h', '7 Rue Lacharrière', 'Paris', '75011', 48.85780000, 2.37940000, '09 84 16 55 03', 'https://www.lesnicois.com/', '2025-05-12', '2025-05-12', 1, 0),
(130, 'Café Ernestine', 'Café avec espace de jeux, chaises hautes, table à langer, brunch enfant, ateliers pour enfants.', '10h-17h', '72 Rue Joseph de Maistre', 'Paris', '75018', 48.89110000, 2.33360000, '09 86 66 40 10', 'https://www.ernestinecafe.fr/', '2025-05-12', '2025-05-12', 1, 0),
(131, 'Mombini', 'Boutique déco et jouets avec coffee shop, espace de jeu, chaises hautes, ateliers pour enfants et femmes enceintes.', 'mardi au samedi : 10h30 - 19h', '22 Rue Gerbert', ' Paris', '75015', 48.84110000, 2.29940000, '01 73 70 62 31', 'https://bit.ly/mombini', '2025-05-12', '2025-05-12', 1, 0),
(132, 'Café jeux Le Petit Ney', 'Pôle jeux Ludiney pour adultes et enfants et café littéraire « Le Petit Ney ».', '11h-23h', '10 avenue de la Porte Montmartre', 'Paris', '75018', 48.89883373, 2.33739229, '01 42 62 00 00', 'https://lepetitney.fr/', '2025-05-12', '2025-05-12', 1, 0),
(133, 'Petit Nuage', 'Café-restaurant avec espace de jeux pour enfants', 'mardi au dimanche: 10h-18h', '13 Rue Simone Veil', 'Saint-Ouen', '93400', 48.90930000, 2.33840000, '01 40 12 04 53', 'https://www.lepetitnuage.fr/', '2025-05-12', '2025-05-12', 1, 0),
(134, 'Les Polinsons - La Garenne-Colombes', 'Lieu de vie familial avec aire de jeux, café, ateliers créatifs, club périscolaire pour enfants de 0 à 8 ans.', '9h30 - 12h30', '92 Avenue du Général de Gaulle  ', 'La Garenne-Colombes', '92250', 48.90741249, 2.25596389, '09 53 13 72 65', 'https://lespolinsons.fr/', '2025-05-12', '2025-05-12', 1, 0),
(135, 'Les Polinsons - Croissy-sur-Seine', 'Lieu de vie familial avec aire de jeux, café, ateliers créatifs, club périscolaire pour enfants de 0 à 8 ans.', 'voir le site web pour les horaires', '5 rue Ernest Gouin', 'Croissy-Sur-Seine', '78290', 48.87737870, 2.12681659, '09 53 13 72 65', 'https://lespolinsons.fr/croissy-sur-seine/', '2025-05-12', '2025-05-12', 1, 0),
(136, 'Petite Forêt', 'Café cosy avec salle de yoga, espace de jeux de 20m², ateliers créatifs pour parents et enfants.', '9h30 - 18h30', '10 Rue Laure Diebold', 'Paris', '75008', 48.87583826, 2.30596394, '09 75 56 63 15', 'https://www.petiteforet.com/', '2025-05-12', '2025-05-12', 1, 0),
(137, 'Môment’s Family Concept', 'Café-poussette avec grande aire de jeux, espace pour les plus petits, boissons artisanales et pâtisseries maison.', '9h30-18h (Mardi, Samedi, Dimanche)', '3 Rue de l’Abbé Rousseaux', 'Versailles', '78000', 48.79651349, 2.13636882, '01 30 21 07 16', 'https://moments-familyconcept.fr/', '2025-05-12', '2025-05-12', 1, 0),
(138, 'Otipi', 'Café avec espace de jeu, coin d’éveil, boutique de jouets, ateliers pour enfants de 0 à 12 ans.', '9h30 - 19h', '52 Avenue de Saxe', 'Paris', '75015', 48.84656922, 2.32744201, '06 38 45 38 33', 'https://otipi-boutique.com/', '2025-05-12', '2025-05-12', 1, 0),
(139, 'La tribu de bébé', 'Café-poussette & espace de jeux intérieur pour les enfants', '10h-18h', '1 ter, Av. de l\'Arbre À la Quenée', 'Méré', '78490', 49.03570000, 1.58010000, '01 30 42 61 27', 'http://www.latribudebebe.fr/', '2025-05-12', '2025-05-12', 1, 0),
(167, 'Palomano City Clichy', 'Parc ludo-éducatif indoor pour enfants de 0 à 10 ans, avec mini-ville immersive, espace 0-3 ans, animations sans écran, et Palo-Snack sur place.', '9h20 - 19h', '125 boulevard Jean Jaurès ', 'Clichy', '92110', 48.90564700, 2.30153300, NULL, 'https://www.palomano.com/', '2025-05-14', '2025-05-14', 2, 54),
(168, 'Palomano Story - Rueil Malmaison', 'Parc indoor basé sur le jeu d’imitation pour enfants de 0 à 10 ans, avec univers immersifs, animations ludiques, et espace de restauration.', '9h20 - 19h', '4 bis rue de l’arsenal', 'Rueil-Malmaison', '92500', 48.87567910, 2.20033280, NULL, 'https://www.palomano.com/', '2025-05-14', '2025-05-14', 2, 54),
(169, 'Boom Boom Villette ', 'Des animations où les enfants sont rois pendant que les parents chillent dans le food market.', '9h - 1h', '30 avenue Corentin Cariou', 'Paris ', '75019', 48.89642690, 2.38888530, NULL, 'https://boomboomvillette.com/fr', '2025-05-14', '2025-05-14', 2, 54),
(170, 'Merge Family ', 'Salon de coiffure et institut de beauté avec concept café poussette et garderie', 'Mardi au Samedi : 10h à 18h', '13 rue Estienne d’Orves', 'Créteil ', '94000', 48.79208800, 2.46331864, NULL, 'https://www.merge-family.com/', '2025-05-14', '2025-05-14', 2, 54),
(171, 'Musée de l’air de l’espace ', 'Musée avec espace Planète, Pilote pour les 6-12 ans, planétarium, espace pour les moins de 4 ans.', 'Consultez le site web pour les horaires ', 'Aéroport de Paris le Bourget', 'Le Bourget', '93350', 48.95876570, 2.43827720, NULL, 'https://www.museeairespace.fr/agenda/exposition-flight/', '2025-05-14', '2025-05-14', 3, 54),
(172, 'Cité des sciences ', 'Exposition, Mission spatiale, accessible dès 8 ans, maquettes, vidéos interactives.\nCité des enfants dès 2 ans.', '9h30 - 16h30', '30 avenue Corentin Cariou', 'Paris', '75019', 48.89642690, 2.38888530, NULL, 'https://www.cite-sciences.fr/fr/au-programme/expos-permanentes/mission-spatiale', '2025-05-14', '2025-05-14', 3, 54),
(173, 'Palais de la découverte ', 'Planétarium avec séances adaptées aux enfants dès 7 ans, ateliers scientifiques.', 'Consultez le site web pour les horaires ', '186 rue Saint Charles ', 'Paris ', '75015', 48.84021750, 2.27975030, NULL, 'https://www.palais-decouverte.fr/fr/accueil', '2025-05-14', '2025-05-14', 3, 54),
(174, 'Philarmonie des enfants ', 'Un espace de jeu dédié aux enfants de 4 à 10 ans,\npour jouer, explorer, écouter,  vivre et sentir la musique.', 'Consultez le site web pour les horaires ', '221 avenue Jean Jaurès ', 'Paris', '75019', 48.88989370, 2.39406990, NULL, 'https://philharmoniedeparis.fr/fr/philharmoniedesenfants', '2025-05-14', '2025-05-14', 3, 54),
(175, 'Le coffre à jouets ', 'La ludothèque municipale d’Argenteuil est un lieu de détente, de divertissement et d’échange autour du jeu et du jouet dédiée aux enfants de 0 à 10 ans. Sur inscription.', 'Consultez le site web pour les horaires ', '8 bis rue pierre joly', 'Argenteuil', '95100', 48.94591740, 2.25080190, NULL, 'https://argenteuil.bibenligne.fr/ludotheque-le-coffre-a-jouets', '2025-05-14', '2025-05-14', 3, 54),
(176, 'L’R de jeux ', 'L\'R de jeux, la plus grande ludothèque en plein air de France située place de la République (Paris centre) accueille chaque année plus de 100 000 joueurs, toute l\'année sur la place de la République. Enfants, adolescents, parents, grands-parents, touristes et professionnels peuvent s\'y retrouver pour jouer.', '13h30 - 20h selon les saisons ', 'Place de la république ', 'Paris ', '75011', 48.86754200, 2.36395830, NULL, 'http://www.aladressedujeu.fr/accueil/les-lieux-daccueil/rdejeux/', '2025-05-14', '2025-05-14', 2, 54),
(177, 'Festival Petits Tout Petits', 'Le Festival Petits Tout Petits revient avec une programmation itinérante qui s’adresse avant tout aux tout-petits. À travers 10 spectacles, petits et grands seront emportés dans des histoires douces et surprenantes, propices à l’éveil et à l’émerveillement. Les représentations se dérouleront dans divers lieux, répartis sur 11 communes de l’agglomération Roissy Pays de France.', 'voir le site web pour les horaires', 'Place de la mairie', 'Ecouen', '95205', 49.01860467, 2.37920825, NULL, 'https://www.roissypaysdefrance.fr/actualites/actualite/participez-au-festival-petits-tout-petits-de-lagglo-91', '2025-05-14', '2025-05-12', 2, 0),
(178, 'Festival Silence', 'Fin mai, cap sur Rosny-sous-Bois pour son festival Silence – dont la programmation s’adresse à un très large public puisque certaines séances sont destinées aux bébés dès 1 an. Durant quatre jours, les ciné-concerts s’enchaînent, proposant au public de découvrir différents films accompagnés par de véritables musiciens, sur la scène mais aussi sur le parvis du théâtre et cinéma Georges-Simenon.', ' Consultez le site web pour les horaires ', 'Place Carnot ', 'Rosny-sous-Bois', '93110', 48.88382990, 2.44075380, NULL, 'https://www.rosnysousbois.fr/simenon/festival-silence-2025/', '2025-05-14', '2025-05-14', 3, 54),
(179, 'BIAM 2025', 'Rendez-vous incontournable de la scène marionnettique actuelle, la Biennale internationale des arts de la marionnette (BIAM) s’étend sur deux semaines et dans tout le territoire francilien pour célébrer la vitalité créative, la diversité esthétique et thématique des arts de la marionnette.', 'Consultez le site web pour les horaires ', '73 rue Mouffetard', 'Paris ', '75005', 48.84221300, 2.34982010, NULL, 'https://lemouffetard.com/saison-2024-25/spectacles/biam-2025', '2025-05-14', '2025-05-14', 2, 54),
(180, 'Festival Un Neuf Trois Soleil', 'C’est l’un des plus beaux rendez-vous du printemps : cette année encore, Un neuf trois Soleil ! se déploie entre parcs, crèches et théâtres du département de la Seine-Saint-Denis avec une programmation pensée pour les plus petits (jusqu’à 5 ans), et pour les adultes qui les accompagnent. Dedans ou dehors, le programme déroule une belle variété de formes (cirque, danse, musique, théâtre…) à travers des spectacles, installations, espaces de découverte et animations pour une première expérience sensible.', 'Consultez le site web pour les horaires ', '14 rue Saint Just', 'Saint Denis ', '93200', 48.90842820, 2.36084660, NULL, 'https://unneuftroissoleil.fr/', '2025-05-14', '2025-05-14', 2, 54),
(181, 'Parc Saint Michel', 'Parc convivial avec jeux pour enfants, espace fitness et banc', 'Voir le site web', 'place Lucien boilleau', 'Morangis', '91420', 48.70617740, 2.33527740, NULL, 'https://www.morangis91.com/espaces-publics', '2025-05-14', '2025-05-14', 2, 53),
(182, 'Ludothèque Boulogne Billancourt ', 'Le Centre national du jeu est une ludothèque. Son but est d\'acheter des jeux et objets liés aux jeux afin d\'en assurer le prêt, d\'organiser des compétitions et évènements ludiques autour des jeux.', '9h30-18h, samedi : 14h - 20h30 ', '17 allée Robert Doisneau', 'Boulogne-Billancourt', '92100', 48.82766890, 2.23710000, NULL, 'https://centreludique-bb.fr/jouer/ludotheque/', '2025-05-14', '2025-05-14', 2, 54),
(183, 'Ludothèque Denise Garon ', 'A l\'origine de l\'association Cabane à jeux, des ludothécaires de la Ludothèque Denise Garon souhaitant organiser des actions autour du jeu pour différents publics. Les enfants se retrouvent dans le 13e arrondissement de Paris pour jouer et rencontrer.', '9h30 - 18h selon les jours ', '8 square dunois', 'Paris ', '75013', 48.83351720, 2.36639180, NULL, 'https://www.autempsdujeu.paris/#denisegaron', '2025-05-14', '2025-05-14', 2, 54),
(184, 'Ludothèque Nautilude ', 'Les espaces de jeu de notre vaisseau ludique, le Nautilude, accueille petits et grands pour vivre des moments de liberté, de rencontres et d’expérimentations ludiques.', '9h30 - 18h30', '2 rue Jules verne ', 'Paris ', '75011', 48.87007200, 2.37560010, NULL, 'https://www.autempsdujeu.paris/#nautilude', '2025-05-14', '2025-05-14', 2, 54),
(185, 'La rue est à nous ', 'Du 17 mai au 12 juillet 2025, La rue est à nous se déplace de samedi en samedi le long de l\'avenue Gabriel-Péri : profitez des stands de vos commerçants, de jeux et d\'activités sportives, de démonstrations sportives, de concerts et de DJ sets, de bars éphémères ainsi que d\'ateliers d\'activités manuelles.', '11h-19h', 'Avenue Gabriel Péri ', 'Argenteuil ', '95100', 48.94639120, 2.24880690, NULL, 'https://www.argenteuil.fr/fr/actualites/la-rue-est-nous-2025-des-samedis-de-folie', '2025-05-14', '2025-05-14', 2, 54),
(186, 'La traversière - 4ème édition ', 'Depuis quatre ans, la Ville et Paris Sud Aménagement investissent cette ancienne friche pour en faire un lieu vivant, préfigurant l’avenir du secteur Porte-Saint-Germain/Berges de Seine. \n\n\nSur la terrasse et dans le jardin de La Traversière, des animations pour tous sont proposées par des associations et entreprises. Au programme : ateliers créatifs (upcycling, chant, danse, écriture, photo…), activités sportives, jeux, rencontres entre jeunes entrepreneurs… Sur le thème de la nature et de l’environnement, cette nouvelle saison, orchestrée par l’association Full Art, vous invite à la détente et à la découverte dans un petit havre de paix.', '14h - 22h selon les jours ', '101 bis rue Henri Barbusse ', 'Argenteuil ', '95100', 48.90951500, 2.39075400, NULL, NULL, '2025-05-14', '2025-05-14', 2, 54),
(187, 'Parc de la Fête', 'Grande aire de jeux moderne de 6000 m² avec toboggans, trampolines, jeux d&#039;escalade et espace pour les tout-petits dans un parc \nverdoyant.', '8h-20h', 'Rue Guy de Maupassant ', 'Sartrouville ', '78500', 48.94756300, 2.17068720, NULL, NULL, '2025-05-14', '2025-05-14', 2, 54),
(188, 'TFOU Parc', 'Parc d\'attractions indoor inspiré des dessins animés de TF1 avec plus de 30 activités réparties sur 3800 m².', '10h-19h', '2 boulevard de l’Europe ', 'Evry ', '91000', 48.62852340, 2.42780600, NULL, NULL, '2025-05-14', '2025-05-14', 2, 54),
(189, 'Parc des Chanteraines ', 'Parc de 33 ha avec ferme pédagogique, aires de jeux, étangs et parc animalier. Parfait pour pique-niques en famille. Petit train', '7h-21h selon saison ', '46 avenue Georges Pompidou ', 'Gennevilliers ', '92230', 48.86247900, 2.51769200, NULL, NULL, '2025-05-14', '2025-05-14', 2, 54),
(190, 'Parkids Sartrouville ', 'Parc de jeux géant avec structures gonflables, toboggans et espaces thématiques (pirates, espace). Ateliers créatifs et espace café pour parents.', 'Mercredi et week-end  : 9h-20h', '41 rue Jean-Pierre Timbaud', 'Sartrouville ', '78500', 48.94942950, 2.19709330, '0950420136', NULL, '2025-05-14', '2025-05-16', 2, 54),
(191, 'Optiloup ', 'Parc de jeux géant avec structures gonflables, toboggans et espaces thématiques. Espace café pour parents.', 'Mardi - dimanche : 10h-18h30', '11 boulevard Clemenceau ', 'Cormeilles-en-Parisis', '95240', 48.97163290, 2.19187580, '01 34 50 12 34', 'https://optiloup.fr/evenement/', '2025-05-15', '2025-05-15', 2, 54),
(192, 'Terrakids', 'Parc 100% écolo avec :\r\n- Aire de jeux en bois recyclé\r\n- Parcours sensoriel (lumières, sons naturels)\r\n- Ateliers Petits Jardiniers', '10h - 19h weekend ', 'Rue du kiosque ', 'Mours', '95260', 50.36492060, 3.07555310, NULL, 'https://terrakids-parcdejeux.fr/', '2025-05-15', '2025-05-15', 2, 54),
(193, 'Little Villette', 'Espace culturel avec jeux libres et activités manuelles', 'Week-end : 14h30 - 18h30', '211 avenue Jean Jaurès ', 'Paris', '75019', 48.89224060, 2.38782260, NULL, 'https://www.lavillette.com/little-villette/', '2025-05-15', '2025-05-15', 2, 54),
(195, 'La tribu de bébé', 'Espace de jeux intérieur pour les enfants', '10h-18h', '1 ter avenue de l’arbre à la Quenée', 'Méré', '78490', 48.80136110, 1.82424530, NULL, 'https://www.latribudebebe.com/', '2025-05-15', '2025-05-15', 2, 54),
(196, 'Petits Artistes Paris 15', 'Atelier créatif et artistique pour enfants', '9h-19h', '6 place Jacques Marette', 'Paris ', '75015', 48.83248450, 2.30087200, NULL, NULL, '2025-05-15', '2025-05-15', 3, 54),
(223, 'Bibliothèque Louise Michel', 'Bibliothèque municipale avec espace jeunesse.', 'Mar-Sam 10:00-18:00', '29 Rue des Haies', 'Paris', '75020', 48.85300000, 2.40910000, '0143711940', 'https://bibliotheques.paris.fr', '2025-05-15', '2025-05-15', 3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `lieux_age`
--

CREATE TABLE `lieux_age` (
  `id` int(11) NOT NULL,
  `id_lieu` int(11) NOT NULL,
  `id_age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lieux_age`
--

INSERT INTO `lieux_age` (`id`, `id_lieu`, `id_age`) VALUES
(1, 1, 1),
(2, 1, 2),
(126, 84, 1),
(127, 84, 2),
(167, 123, 1),
(168, 123, 2),
(169, 123, 3),
(170, 124, 1),
(171, 124, 2),
(172, 124, 3),
(173, 122, 3),
(174, 122, 2),
(175, 125, 2),
(176, 125, 3),
(178, 127, 1),
(179, 128, 1),
(180, 128, 2),
(181, 128, 3),
(182, 127, 1),
(183, 127, 2),
(184, 127, 3),
(185, 129, 1),
(186, 129, 2),
(187, 129, 3),
(188, 130, 1),
(189, 130, 2),
(190, 130, 3),
(191, 131, 1),
(192, 131, 2),
(193, 131, 3),
(194, 132, 1),
(195, 132, 2),
(196, 132, 3),
(197, 138, 1),
(198, 138, 2),
(199, 138, 3),
(200, 133, 1),
(201, 133, 2),
(202, 133, 3),
(203, 135, 1),
(204, 135, 2),
(205, 135, 3),
(206, 134, 1),
(207, 134, 2),
(208, 134, 3),
(209, 136, 1),
(210, 136, 2),
(211, 136, 3),
(212, 137, 1),
(213, 137, 2),
(214, 84, 1),
(215, 84, 2),
(216, 139, 1),
(217, 139, 2),
(218, 139, 3),
(253, 167, 1),
(254, 167, 2),
(255, 167, 3),
(256, 168, 1),
(257, 168, 2),
(258, 168, 3),
(259, 169, 1),
(260, 169, 2),
(261, 169, 3),
(262, 170, 1),
(263, 170, 2),
(264, 170, 3),
(265, 171, 1),
(266, 171, 2),
(267, 171, 3),
(268, 172, 1),
(269, 172, 2),
(270, 172, 3),
(271, 173, 2),
(272, 173, 3),
(273, 174, 2),
(274, 174, 3),
(275, 175, 1),
(276, 175, 2),
(277, 175, 3),
(278, 176, 1),
(279, 176, 2),
(280, 176, 3),
(281, 177, 1),
(282, 177, 2),
(283, 177, 3),
(284, 178, 1),
(285, 178, 2),
(286, 178, 3),
(287, 179, 2),
(288, 179, 3),
(289, 180, 1),
(290, 180, 2),
(291, 180, 3),
(292, 181, 1),
(293, 181, 2),
(294, 181, 3),
(295, 182, 2),
(296, 182, 3),
(297, 183, 2),
(298, 183, 3),
(299, 184, 2),
(300, 184, 3),
(301, 185, 1),
(302, 185, 2),
(303, 185, 3),
(304, 186, 1),
(305, 186, 2),
(306, 186, 3),
(307, 187, 1),
(308, 187, 2),
(309, 187, 3),
(310, 188, 1),
(311, 188, 2),
(312, 188, 3),
(313, 189, 1),
(314, 189, 2),
(315, 189, 3),
(319, 191, 1),
(320, 191, 2),
(321, 191, 3),
(322, 192, 1),
(323, 192, 2),
(324, 192, 3),
(325, 193, 1),
(326, 193, 2),
(327, 193, 3),
(331, 195, 1),
(332, 195, 2),
(333, 195, 3),
(334, 196, 2),
(335, 196, 3),
(366, 223, 1),
(367, 223, 2),
(390, 190, 1),
(391, 190, 2),
(392, 190, 3);

-- --------------------------------------------------------

--
-- Table structure for table `lieux_equipement`
--

CREATE TABLE `lieux_equipement` (
  `id` int(11) NOT NULL,
  `id_lieux` int(11) NOT NULL,
  `id_equipement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lieux_equipement`
--

INSERT INTO `lieux_equipement` (`id`, `id_lieux`, `id_equipement`) VALUES
(35, 1, 1),
(36, 1, 2),
(184, 84, 1),
(185, 84, 2),
(186, 84, 3),
(187, 84, 4),
(188, 84, 5),
(226, 123, 1),
(227, 123, 2),
(228, 123, 3),
(229, 123, 4),
(230, 123, 5),
(231, 123, 6),
(232, 124, 1),
(233, 124, 6),
(234, 125, 1),
(235, 125, 2),
(236, 125, 3),
(237, 125, 4),
(238, 125, 5),
(239, 125, 6),
(241, 127, 1),
(242, 128, 1),
(243, 128, 2),
(244, 128, 4),
(245, 128, 3),
(246, 128, 5),
(247, 127, 2),
(248, 127, 5),
(249, 127, 3),
(250, 127, 4),
(251, 129, 1),
(252, 129, 5),
(253, 129, 3),
(254, 129, 4),
(255, 129, 2),
(256, 130, 1),
(257, 130, 5),
(258, 130, 3),
(259, 130, 4),
(260, 130, 2),
(261, 131, 1),
(262, 131, 5),
(263, 131, 3),
(264, 131, 4),
(265, 131, 2),
(266, 132, 1),
(267, 132, 4),
(268, 132, 2),
(269, 132, 3),
(270, 138, 1),
(271, 138, 5),
(272, 138, 3),
(273, 138, 4),
(274, 138, 5),
(275, 133, 1),
(276, 133, 2),
(277, 133, 4),
(278, 133, 3),
(279, 133, 5),
(280, 135, 1),
(281, 135, 5),
(282, 135, 3),
(283, 135, 2),
(284, 135, 6),
(285, 134, 1),
(286, 134, 2),
(287, 134, 3),
(288, 134, 5),
(289, 136, 1),
(290, 136, 5),
(291, 136, 3),
(292, 136, 2),
(293, 136, 4),
(294, 139, 1),
(295, 139, 3),
(296, 139, 6),
(297, 139, 5),
(323, 121, 1),
(330, 167, 1),
(331, 167, 2),
(332, 167, 3),
(333, 167, 4),
(334, 167, 5),
(335, 168, 1),
(336, 168, 2),
(337, 168, 3),
(338, 168, 4),
(339, 168, 5),
(340, 169, 1),
(341, 169, 2),
(342, 169, 3),
(343, 169, 4),
(344, 169, 5),
(345, 169, 6),
(346, 170, 1),
(347, 170, 2),
(348, 170, 3),
(349, 170, 4),
(350, 170, 5),
(351, 171, 1),
(352, 171, 2),
(353, 171, 5),
(354, 171, 6),
(355, 172, 1),
(356, 172, 2),
(357, 172, 3),
(358, 172, 5),
(359, 172, 6),
(360, 173, 1),
(361, 173, 2),
(362, 174, 1),
(363, 174, 2),
(364, 174, 5),
(365, 175, 1),
(366, 175, 2),
(367, 175, 5),
(368, 176, 1),
(369, 176, 2),
(370, 178, 1),
(371, 179, 1),
(372, 180, 1),
(373, 180, 2),
(374, 180, 3),
(375, 180, 4),
(376, 180, 5),
(377, 180, 6),
(378, 181, 1),
(379, 181, 2),
(380, 181, 6),
(381, 182, 2),
(382, 183, 2),
(383, 184, 1),
(384, 184, 2),
(385, 185, 1),
(386, 185, 2),
(387, 186, 1),
(388, 186, 2),
(389, 187, 1),
(390, 187, 2),
(391, 188, 1),
(392, 188, 2),
(393, 188, 3),
(394, 188, 4),
(395, 188, 5),
(396, 188, 6),
(397, 189, 1),
(398, 189, 2),
(399, 189, 6),
(406, 191, 1),
(407, 191, 2),
(408, 191, 3),
(409, 191, 4),
(410, 191, 5),
(411, 191, 6),
(412, 192, 1),
(413, 192, 2),
(414, 192, 3),
(415, 192, 4),
(416, 192, 5),
(417, 192, 6),
(418, 193, 1),
(419, 193, 2),
(420, 193, 5),
(421, 193, 6),
(426, 195, 1),
(427, 195, 2),
(428, 195, 3),
(429, 195, 4),
(430, 195, 5),
(431, 195, 6),
(432, 196, 1),
(433, 196, 2),
(456, 223, 1),
(457, 223, 2),
(476, 190, 1),
(477, 190, 2),
(478, 190, 3),
(479, 190, 4),
(480, 190, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tranche_age`
--

CREATE TABLE `tranche_age` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tranche_age`
--

INSERT INTO `tranche_age` (`id`, `nom`) VALUES
(1, '0 - 2 ans'),
(2, '3 - 6 ans'),
(3, '+ 7 ans');

-- --------------------------------------------------------

--
-- Table structure for table `types_equipement`
--

CREATE TABLE `types_equipement` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `types_equipement`
--

INSERT INTO `types_equipement` (`id`, `nom`) VALUES
(1, 'Accès poussette'),
(2, 'Aire de jeux'),
(3, 'Micro-ondes'),
(4, 'Chaise haute'),
(5, 'Table à langer'),
(6, 'Parking');

-- --------------------------------------------------------

--
-- Table structure for table `types_lieux`
--

CREATE TABLE `types_lieux` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `types_lieux`
--

INSERT INTO `types_lieux` (`id`, `nom`) VALUES
(1, 'Restaurant'),
(2, 'Loisirs'),
(3, 'Culture');

-- --------------------------------------------------------

--
-- Table structure for table `types_users`
--

CREATE TABLE `types_users` (
  `id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `types_users`
--

INSERT INTO `types_users` (`id`, `nom`) VALUES
(1, 'standart'),
(2, 'superUser'),
(3, 'spare'),
(4, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
  `date_expiration_token` datetime DEFAULT NULL,
  `opt_in_email` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indique si l''utilisateur souhaite recevoir des emails (1=oui, 0=non)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `mail`, `telephone`, `mot_de_passe`, `grade`, `date_creation`, `derniere_connexion`, `tentatives_connexion`, `compte_verrouille`, `date_verrouillage`, `token_reinitialisation`, `date_expiration_token`, `opt_in_email`) VALUES
(5, 'boby', 'seb.prod@gmail.com', '', '$2y$10$VFc5DxzXFm3IK0FqlMsgR.GgjRK5HaOAvixkkgEI.84w3vk2NNV.W', 4, '2025-04-13 16:50:42', '2025-05-16 16:24:29', 0, 0, '2025-05-05 08:28:54', NULL, NULL, 0),
(52, 'Bob', 'Sebastien.drillaud@gmail.com', '0666666666', '$2y$10$RJu5vuqFFaA5AhKDv5P4EOTMs87RgOjFpUacmPo4wlJtDba5HzvIq', 1, '2025-05-05 11:09:46', NULL, 0, 0, NULL, NULL, '2025-05-12 11:10:50', 0),
(53, 'Mouss', 'Kebemoussa19@yahoo.fr', '0666666666', '$2y$10$gO5XYUBTP9L7DnN9FkUl0efuWJ50C2dP0H4uCJdiFoPPAfWVfZL.W', 4, '2025-05-06 13:31:24', '2025-05-16 09:59:04', 0, 0, NULL, NULL, NULL, 0),
(54, 'jenna89', 'jenna.ramia@gmail.com', '0603714430', '$2y$10$JVHwxiM3XObwKzevSzZF/.AatwWpX7oxx6CedYCAjFMrarzsWCioW', 4, '2025-05-06 14:11:38', '2025-05-16 23:43:23', 0, 0, '2025-05-12 16:21:57', NULL, '2025-05-12 16:51:38', 0),
(58, 'Bobobob', 'Bobin.bob@gmail.com', '0666666666', '$2y$10$rD0XvmaQihAjn8lHeZ.05evDPetbONGN3wWA8tYXLfh3AryY7xksy', 1, '2025-05-11 10:35:26', NULL, 0, 0, NULL, NULL, NULL, 1),
(59, 'Claire ', 'Hilliou-claire@live.fr', '0699238566', '$2y$10$SR6luVJnm3vhMQbnGPwW7u8YPND/GJrnjnrNmQAW7VQU4aFYDteZK', 1, '2025-05-14 20:43:43', '2025-05-14 20:50:58', 0, 0, NULL, NULL, NULL, 0),
(60, 'Alex', 'Alexandrefourquin@hotmail.fr', '0677932655', '$2y$10$DI9E8c620biZOBjPdAHh.eZayBGHJJXGPzBGQ7jCJbTMtdZiSoQG.', 4, '2025-05-15 10:43:40', '2025-05-15 12:10:22', 0, 0, NULL, NULL, NULL, 0),
(61, 'Nico', 'Mk_77@hotmail.fr', '0666666666', '$2y$10$VrC8lInzagjTN0Int3IuqO5hY.Dj1/sdgH4/gHWa5RSpZ1rXtUyGO', 1, '2025-05-16 10:15:15', '2025-05-16 10:20:20', 0, 0, NULL, NULL, '2025-05-16 10:38:55', 1),
(62, 'Papa971', 'Benuffeyannis@gmail.com', '0633425977', '$2y$10$pXCwWWyQXHu.PHSF0DR8puCRVHVUstvVXcxPrRwQVICTss/Kj7kVq', 1, '2025-05-16 23:24:22', '2025-05-16 23:24:57', 0, 0, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_preference_age`
--

CREATE TABLE `user_preference_age` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_tranche_age` int(11) NOT NULL,
  `date_ajout` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_preference_age`
--

INSERT INTO `user_preference_age` (`id`, `id_user`, `id_tranche_age`, `date_ajout`) VALUES
(14, 5, 3, '2025-04-22 13:21:38'),
(15, 5, 2, '2025-04-22 13:21:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_preference_equipement`
--

CREATE TABLE `user_preference_equipement` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_equipement` int(11) NOT NULL,
  `date_ajout` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_preference_equipement`
--

INSERT INTO `user_preference_equipement` (`id`, `id_user`, `id_equipement`, `date_ajout`) VALUES
(15, 5, 1, '2025-04-22 13:21:38'),
(16, 5, 3, '2025-04-22 13:21:38');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_detail_lieu`
-- (See below for the actual view)
--
CREATE TABLE `vue_detail_lieu` (
`id_lieu` int(11)
,`nom_lieu` varchar(150)
,`description` text
,`latitude` decimal(10,8)
,`longitude` decimal(10,8)
,`adresse` varchar(100)
,`ville` varchar(50)
,`code_postal` varchar(5)
,`telephone` varchar(15)
,`site_web` varchar(255)
,`horaires` varchar(50)
,`type_lieu` varchar(139)
,`est_evenement` int(1)
,`equipements` mediumtext
,`tranches_age` mediumtext
,`commentaires` mediumtext
,`note_moyenne` decimal(12,1)
,`nombre_commentaires` bigint(21)
,`date_debut` date
,`date_fin` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_lieux_complete`
-- (See below for the actual view)
--
CREATE TABLE `vue_lieux_complete` (
`id_lieu` int(11)
,`nom_lieu` varchar(150)
,`description` text
,`horaires` varchar(50)
,`adresse` varchar(100)
,`ville` varchar(50)
,`code_postal` varchar(5)
,`latitude` decimal(10,8)
,`longitude` decimal(10,8)
,`telephone` varchar(15)
,`site_web` varchar(255)
,`type_lieu` varchar(139)
,`equipements` mediumtext
,`tranches_age` mediumtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_user_preference`
-- (See below for the actual view)
--
CREATE TABLE `vue_user_preference` (
`id_user` int(11)
,`pseudo` varchar(50)
,`mail` varchar(100)
,`telephone` varchar(20)
,`grade` int(11)
,`date_creation` datetime
,`derniere_connexion` datetime
,`opt_in_email` tinyint(1)
,`tranches_age` mediumtext
,`equipements` mediumtext
);

-- --------------------------------------------------------

--
-- Structure for view `vue_detail_lieu`
--
DROP TABLE IF EXISTS `vue_detail_lieu`;

CREATE ALGORITHM=UNDEFINED DEFINER=`seb-prod`@`%` SQL SECURITY DEFINER VIEW `vue_detail_lieu`  AS SELECT `l`.`id` AS `id_lieu`, `l`.`nom` AS `nom_lieu`, `l`.`description` AS `description`, `l`.`latitude` AS `latitude`, `l`.`longitude` AS `longitude`, `l`.`adresse` AS `adresse`, `l`.`ville` AS `ville`, `l`.`code_postal` AS `code_postal`, `l`.`telephone` AS `telephone`, `l`.`site_web` AS `site_web`, `l`.`horaires` AS `horaires`, json_object('id',`t`.`id`,'nom',`t`.`nom`) AS `type_lieu`, CASE WHEN `e`.`id` is not null THEN 1 ELSE 0 END AS `est_evenement`, group_concat(distinct json_object('id',`te`.`id`,'nom',`te`.`nom`) separator ',') AS `equipements`, group_concat(distinct json_object('id',`ta`.`id`,'nom',`ta`.`nom`) separator ',') AS `tranches_age`, group_concat(distinct json_object('pseudo',`u`.`pseudo`,'commentaire',`c`.`commentaire`,'note',`c`.`note`,'date_ajout',`c`.`date_ajout`) separator ',') AS `commentaires`, round(avg(`c`.`note`),1) AS `note_moyenne`, count(distinct `c`.`id`) AS `nombre_commentaires`, `e`.`date_debut` AS `date_debut`, `e`.`date_fin` AS `date_fin` FROM ((((((((`lieux` `l` join `types_lieux` `t` on(`l`.`id_type` = `t`.`id`)) left join `lieux_equipement` `le` on(`l`.`id` = `le`.`id_lieux`)) left join `types_equipement` `te` on(`le`.`id_equipement` = `te`.`id`)) left join `lieux_age` `la` on(`l`.`id` = `la`.`id_lieu`)) left join `tranche_age` `ta` on(`la`.`id_age` = `ta`.`id`)) left join `commentaires` `c` on(`l`.`id` = `c`.`id_lieu`)) left join `users` `u` on(`c`.`id_user` = `u`.`id`)) left join `evenements` `e` on(`l`.`id` = `e`.`id_lieux`)) GROUP BY `l`.`id`, `e`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_lieux_complete`
--
DROP TABLE IF EXISTS `vue_lieux_complete`;

CREATE ALGORITHM=UNDEFINED DEFINER=`seb-prod`@`%` SQL SECURITY DEFINER VIEW `vue_lieux_complete`  AS SELECT `l`.`id` AS `id_lieu`, `l`.`nom` AS `nom_lieu`, `l`.`description` AS `description`, `l`.`horaires` AS `horaires`, `l`.`adresse` AS `adresse`, `l`.`ville` AS `ville`, `l`.`code_postal` AS `code_postal`, `l`.`latitude` AS `latitude`, `l`.`longitude` AS `longitude`, `l`.`telephone` AS `telephone`, `l`.`site_web` AS `site_web`, json_object('id',`tl`.`id`,'nom',`tl`.`nom`) AS `type_lieu`, group_concat(distinct json_object('id',`te`.`id`,'nom',`te`.`nom`) separator ',') AS `equipements`, group_concat(distinct json_object('id',`ta`.`id`,'nom',`ta`.`nom`) separator ',') AS `tranches_age` FROM (((((`lieux` `l` left join `types_lieux` `tl` on(`l`.`id_type` = `tl`.`id`)) left join `lieux_equipement` `le` on(`l`.`id` = `le`.`id_lieux`)) left join `types_equipement` `te` on(`le`.`id_equipement` = `te`.`id`)) left join `lieux_age` `la` on(`l`.`id` = `la`.`id_lieu`)) left join `tranche_age` `ta` on(`la`.`id_age` = `ta`.`id`)) GROUP BY `l`.`id`, `l`.`nom`, `l`.`description`, `l`.`horaires`, `l`.`adresse`, `l`.`ville`, `l`.`code_postal`, `l`.`latitude`, `l`.`longitude`, `l`.`telephone`, `l`.`site_web`, `tl`.`id`, `tl`.`nom` ORDER BY `l`.`latitude` ASC, `l`.`longitude` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `vue_user_preference`
--
DROP TABLE IF EXISTS `vue_user_preference`;

CREATE ALGORITHM=UNDEFINED DEFINER=`seb-prod`@`%` SQL SECURITY DEFINER VIEW `vue_user_preference`  AS SELECT `u`.`id` AS `id_user`, `u`.`pseudo` AS `pseudo`, `u`.`mail` AS `mail`, `u`.`telephone` AS `telephone`, `u`.`grade` AS `grade`, `u`.`date_creation` AS `date_creation`, `u`.`derniere_connexion` AS `derniere_connexion`, `u`.`opt_in_email` AS `opt_in_email`, group_concat(distinct json_object('id',`ta`.`id`,'nom',`ta`.`nom`) separator ',') AS `tranches_age`, group_concat(distinct json_object('id',`te`.`id`,'nom',`te`.`nom`) separator ',') AS `equipements` FROM ((((`users` `u` left join `user_preference_age` `upa` on(`u`.`id` = `upa`.`id_user`)) left join `tranche_age` `ta` on(`upa`.`id_tranche_age` = `ta`.`id`)) left join `user_preference_equipement` `upe` on(`u`.`id` = `upe`.`id_user`)) left join `types_equipement` `te` on(`upe`.`id_equipement` = `te`.`id`)) GROUP BY `u`.`id`, `u`.`pseudo`, `u`.`mail`, `u`.`telephone`, `u`.`grade`, `u`.`date_creation`, `u`.`derniere_connexion`, `u`.`opt_in_email` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_lieu` (`id_lieu`,`id_user`),
  ADD KEY `commentaires_ibfk_2` (`id_user`);

--
-- Indexes for table `evenements`
--
ALTER TABLE `evenements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lieux` (`id_lieux`);

--
-- Indexes for table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_lieu` (`id_lieu`,`id_user`),
  ADD KEY `favoris_ibfk_2` (`id_user`);

--
-- Indexes for table `lieux`
--
ALTER TABLE `lieux`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type` (`id_type`);

--
-- Indexes for table `lieux_age`
--
ALTER TABLE `lieux_age`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_age` (`id_age`),
  ADD KEY `id_lieu` (`id_lieu`);

--
-- Indexes for table `lieux_equipement`
--
ALTER TABLE `lieux_equipement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_equipement` (`id_equipement`),
  ADD KEY `lieux_equipement_ibfk_2` (`id_lieux`);

--
-- Indexes for table `tranche_age`
--
ALTER TABLE `tranche_age`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types_equipement`
--
ALTER TABLE `types_equipement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types_lieux`
--
ALTER TABLE `types_lieux`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types_users`
--
ALTER TABLE `types_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`,`nom`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD KEY `grade` (`grade`);

--
-- Indexes for table `user_preference_age`
--
ALTER TABLE `user_preference_age`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_age` (`id_user`,`id_tranche_age`),
  ADD KEY `fk_user_preference_age_tranche` (`id_tranche_age`);

--
-- Indexes for table `user_preference_equipement`
--
ALTER TABLE `user_preference_equipement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_equipement` (`id_user`,`id_equipement`),
  ADD KEY `fk_user_preference_equip_type` (`id_equipement`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `evenements`
--
ALTER TABLE `evenements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `lieux`
--
ALTER TABLE `lieux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;

--
-- AUTO_INCREMENT for table `lieux_age`
--
ALTER TABLE `lieux_age`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=393;

--
-- AUTO_INCREMENT for table `lieux_equipement`
--
ALTER TABLE `lieux_equipement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=481;

--
-- AUTO_INCREMENT for table `tranche_age`
--
ALTER TABLE `tranche_age`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `types_equipement`
--
ALTER TABLE `types_equipement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `types_lieux`
--
ALTER TABLE `types_lieux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `types_users`
--
ALTER TABLE `types_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `user_preference_age`
--
ALTER TABLE `user_preference_age`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_preference_equipement`
--
ALTER TABLE `user_preference_equipement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`id_lieu`) REFERENCES `lieux` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evenements`
--
ALTER TABLE `evenements`
  ADD CONSTRAINT `evenements_ibfk_1` FOREIGN KEY (`id_lieux`) REFERENCES `lieux` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`id_lieu`) REFERENCES `lieux` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lieux`
--
ALTER TABLE `lieux`
  ADD CONSTRAINT `lieux_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `types_lieux` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lieux_age`
--
ALTER TABLE `lieux_age`
  ADD CONSTRAINT `lieux_age_ibfk_1` FOREIGN KEY (`id_age`) REFERENCES `tranche_age` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lieux_age_ibfk_2` FOREIGN KEY (`id_lieu`) REFERENCES `lieux` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lieux_equipement`
--
ALTER TABLE `lieux_equipement`
  ADD CONSTRAINT `lieux_equipement_ibfk_1` FOREIGN KEY (`id_equipement`) REFERENCES `types_equipement` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lieux_equipement_ibfk_2` FOREIGN KEY (`id_lieux`) REFERENCES `lieux` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`grade`) REFERENCES `types_users` (`id`);

--
-- Constraints for table `user_preference_age`
--
ALTER TABLE `user_preference_age`
  ADD CONSTRAINT `fk_user_preference_age_tranche` FOREIGN KEY (`id_tranche_age`) REFERENCES `tranche_age` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_preference_age_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_preference_equipement`
--
ALTER TABLE `user_preference_equipement`
  ADD CONSTRAINT `fk_user_preference_equip_type` FOREIGN KEY (`id_equipement`) REFERENCES `types_equipement` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_preference_equip_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
