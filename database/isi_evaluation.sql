-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 08 fév. 2025 à 01:25
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `isi_evaluation`
--

-- --------------------------------------------------------

--
-- Structure de la table `criteres`
--

CREATE TABLE `criteres` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `criteres`
--

INSERT INTO `criteres` (`id`, `nom`) VALUES
(1, 'Pédagogie'),
(2, 'Ponctualité'),
(3, 'Interaction'),
(4, 'Organisation');

-- --------------------------------------------------------

--
-- Structure de la table `enseignants`
--

CREATE TABLE `enseignants` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `matiere` varchar(100) NOT NULL,
  `biographie` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enseignants`
--

INSERT INTO `enseignants` (`id`, `nom`, `matiere`, `biographie`, `photo`) VALUES
(1, 'Chamsidine Aidara', 'PHP', 'Je suis le gentil prof de PHP de ces gosses là, Oui je suis vraiment gentille haha', 'photo1.jpg'),
(2, 'Mar Diop', 'Python', '6 ans dexperiences dans mon domaine je suis imbattable ', 'photo2.jpg'),
(3, 'Atoisse', 'Algo', 'Not a genius but not far from genius', 'photo3.jpg'),
(4, 'Ibrahima Lo', 'C++', 'Prof cool', 'photo4.jpg'),
(6, 'Robert', 'Javascripts', 'Biographie de Professeur', 'photo6.jpg'),
(13, 'TestDepuisiphone ', 'Niggawhaaat', 'Vous n\'irez jamais en rattrapage avec moi', 'IMG_0209.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `enseignant_id` int(11) NOT NULL,
  `pedagogie` int(11) NOT NULL,
  `ponctualité` int(11) NOT NULL,
  `interaction` int(11) NOT NULL,
  `organisation` int(11) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `date_evaluation` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id`, `user_id`, `enseignant_id`, `pedagogie`, `ponctualité`, `interaction`, `organisation`, `commentaire`, `date_evaluation`) VALUES
(1, 1, 1, 5, 4, 5, 5, 'Excellent professeur, très pédagogue.', '2025-02-05'),
(2, 2, 1, 5, 5, 5, 5, 'Incroyable, il maîtrise parfaitement son sujet.', '2025-01-21'),
(4, 3, 2, 3, 4, 3, 4, 'Un peu strict, mais compétent.', '2025-01-23'),
(5, 2, 3, 4, 4, 4, 4, 'Très clair dans ses explications, mais parfois un peu rapide.', '2025-01-24'),
(6, 4, 3, 5, 5, 5, 5, 'Le meilleur en algo, rien à dire.', '2025-01-25'),
(7, 3, 4, 4, 4, 4, 3, 'Bon professeur, mais peut encore mieux gérer le temps.', '2025-01-26'),
(11, 1, 6, 3, 3, 3, 3, 'Des efforts à faire pour rendre les cours plus interactifs.', '2025-01-26'),
(12, 3, 6, 4, 4, 4, 4, 'C’est bien, mais peut mieux faire.', '2025-01-27'),
(17, 1, 13, 3, 5, 4, 4, 'Making sure that all work perfectly', '2025-02-07');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `email`, `nom`, `message`, `date_creation`) VALUES
(1, 'message@gmail.com', 'Teste message', 'Je vérifie si le message est bien récupéré puis affiché au niveau de Message dans le dashboard de l\\\'admin', '2025-02-01 02:37:10'),
(2, 'BeSureItWorkPerfect@gmail.com', 'Re-testing', 'Yeah nigga, grown up your rank !', '2025-02-07 12:03:30'),
(3, 'new@gmail.com', 'Crocodile ', 'testeeeee', '2025-02-07 23:57:01');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `matricule` varchar(50) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'etudiant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `matricule`, `nom`, `email`, `password`, `role`) VALUES
(1, '123456', 'Madieye Anne', 'madieyeanne@mail.com', '123', 'etudiant'),
(2, '234567', 'Elhadj Diallo', 'elhadjdiallo@mail.com', '123', 'etudiant'),
(3, '345678', 'Bilal Diop', 'bilaldiop@mail.com', '123', 'etudiant'),
(4, '456789', 'Birahim Fall', 'birahimfall@mail.com', '123', 'etudiant'),
(5, 'admin', 'Admin User', 'admin@mail.com', 'admin123', 'admin'),
(8, '000000', 'newnew', 'newneeew@gmail.com', '$2y$10$dssvs8WPuh2yMq3FAz1x4OwkxnH0sh99KXap45I0E/sfpUYHp2wrC', 'etudiant');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `criteres`
--
ALTER TABLE `criteres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `enseignants`
--
ALTER TABLE `enseignants`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `enseignant_id` (`enseignant_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `criteres`
--
ALTER TABLE `criteres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `enseignants`
--
ALTER TABLE `enseignants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
