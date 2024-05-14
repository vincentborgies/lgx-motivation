-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 07 mai 2024 à 10:21
-- Version du serveur : 10.11.7-MariaDB-cll-lve
-- Version de PHP : 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `u864174266_lgxmotivation`
--

-- --------------------------------------------------------

--
-- Structure de la table `exercices`
--

CREATE TABLE `exercices` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `exercices`
--

INSERT INTO `exercices` (`id`, `image`, `description`, `time`) VALUES
(2, '/uploads/661f91d6f2cd9-yoga.png', 'Manger bouger ', 'Yoga 15 min'),
(3, '/uploads/661f920fb54c8-run.jpg', 'manger 5 fruits et légumes par jour', 'Course à pied 30 min');

-- --------------------------------------------------------

--
-- Structure de la table `groupe_discussion`
--

CREATE TABLE `groupe_discussion` (
  `id` int(255) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `groupe_discussion`
--

INSERT INTO `groupe_discussion` (`id`, `nom`) VALUES
(1, 'CDA');

-- --------------------------------------------------------

--
-- Structure de la table `inspirations`
--

CREATE TABLE `inspirations` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `etiquette` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inspirations`
--

INSERT INTO `inspirations` (`id`, `image`, `etiquette`) VALUES
(1, '/uploads/661e386f1b31a-LOGO.png', 'Le SGT, source de bonheur et de repos, merci au collégien'),
(3, '/uploads/661e4e1aa6b42-fond.png', 'Le CSS rouge qui ouvre les yeux');

-- --------------------------------------------------------

--
-- Structure de la table `message_discussion`
--

CREATE TABLE `message_discussion` (
  `id` int(255) NOT NULL,
  `idemetteur` int(255) NOT NULL,
  `type` int(255) NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `idgroupe` int(255) NOT NULL,
  `date_creation` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `idgroupe` int(255) NOT NULL,
  `role` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `email`, `password`, `idgroupe`, `role`) VALUES
(1, 'Michel', 'a@a.fr', '$2y$10$SBguuRHARdkcL/zlRUda5ul4z9zjowLw2V2SMtp8jpyZESkxVwfwC', 0, NULL),
(2, 'heriol', 'heriol@gmail.com', '$2y$10$4nSJgeOHgDf7GBoidl5QteInZ7QAoE0yvVhLFnsE91PdRM78/MaDK', 1, NULL),
(3, 'valdo', 'valdo@gmail.com', '$2y$10$A.BvbqTgO1YbAzs.g9y9G.5qONMjcjKcTgA7wKghORQejUlZZb3gC', 0, NULL),
(4, 'vincent', 'vincent@gmail.com', '$2y$10$WOqhzq3tOlhKWQRXtjZGAe27Vfil7chpH8aseLK.S9dTz8SDHbm/i', 1, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `exercices`
--
ALTER TABLE `exercices`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `groupe_discussion`
--
ALTER TABLE `groupe_discussion`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `inspirations`
--
ALTER TABLE `inspirations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `message_discussion`
--
ALTER TABLE `message_discussion`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `exercices`
--
ALTER TABLE `exercices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `groupe_discussion`
--
ALTER TABLE `groupe_discussion`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `inspirations`
--
ALTER TABLE `inspirations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `message_discussion`
--
ALTER TABLE `message_discussion`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
