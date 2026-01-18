-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 18 jan. 2026 à 13:26
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
-- Base de données : `ags_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(2, 'Ordinateurs & Portables', 'Laptops, PC Fixes et Workstations'),
(3, 'Périphériques', 'Claviers, Souris, Casques, Webcams'),
(4, 'Réseau & Serveurs', 'Routeurs, Switchs, Câbles RJ45'),
(5, 'Mobilier de Bureau', 'Chaises, Bureaux, Armoires'),
(6, 'Consommables', 'Papier, Encre, Stylos'),
(7, 'Stockage', 'Disques Durs, SSD, Clés USB');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20251230122203', '2025-12-30 13:22:26', 320),
('DoctrineMigrations\\Version20260111173210', NULL, NULL),
('DoctrineMigrations\\Version20260111192030', NULL, NULL),
('DoctrineMigrations\\Version20260112023519', NULL, NULL),
('DoctrineMigrations\\Version20260112225701', '2026-01-12 23:57:41', 475),
('DoctrineMigrations\\Version20260113091030', '2026-01-13 10:11:12', 50),
('DoctrineMigrations\\Version20260114091450', '2026-01-14 10:21:17', 150),
('DoctrineMigrations\\Version20260114092058', NULL, NULL),
('DoctrineMigrations\\Version20260114092839', '2026-01-14 10:28:53', 14),
('DoctrineMigrations\\Version20260114104922', '2026-01-14 11:49:40', 24),
('DoctrineMigrations\\Version20260114105727', '2026-01-14 11:57:42', 39);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `sales_price` decimal(10,2) DEFAULT NULL,
  `alert_threshold` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `name`, `quantity`, `supplier_id`, `reference`, `category_id`, `purchase_price`, `sales_price`, `alert_threshold`) VALUES
(13, 'HP EliteBook G9', 7467, NULL, 'PC-HP-001', 2, NULL, NULL, 5),
(15, 'MacBook Pro M2', 3, NULL, 'MAC-PRO-M2', 2, NULL, NULL, 5),
(16, 'Lenovo ThinkPad X1', 42, NULL, 'LEN-TP-X1', 2, NULL, NULL, 5),
(17, 'Ecran Samsung 24\"', 35, NULL, 'ECR-SAM-24', 3, NULL, NULL, 5),
(18, 'Ecran Dell UltraSharp', 59, NULL, 'ECR-DELL-27', 3, NULL, NULL, 5),
(19, 'Clavier Mécanique', 50, NULL, 'ACC-CLAV-01', 3, NULL, NULL, 5),
(20, 'Souris Logitech MX', 44, NULL, 'ACC-SOU-MX', 3, NULL, NULL, 5),
(21, 'Webcam HD Pro', 12, NULL, 'ACC-WEB-C920', 3, NULL, NULL, 5),
(22, 'Imprimante Canon MF', 655, NULL, 'RT-500', 3, NULL, NULL, 5),
(23, 'Papier A4 (Rame)', 228, NULL, NULL, 6, NULL, NULL, 5),
(24, 'Chaise Ergonomique', 11, NULL, NULL, 3, NULL, NULL, 5),
(25, 'Bureau Assis-Debout', 5, NULL, NULL, 5, NULL, NULL, 5),
(26, 'Disque SSD 1TB', 15, NULL, NULL, 7, NULL, NULL, 5),
(27, 'Câble HDMI 2m', 96, NULL, NULL, 4, NULL, NULL, 5),
(28, 'Switch 24 Ports', 969, NULL, NULL, 4, NULL, NULL, 5),
(29, 'Routeur Wifi 6', 110, NULL, NULL, 4, NULL, NULL, 5),
(33, 'tzgeb', 45, NULL, 'tèuikrf', 4, 54654.00, 85588.00, 5);

-- --------------------------------------------------------

--
-- Structure de la table `purchase_request`
--

CREATE TABLE `purchase_request` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `requested_by_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `justification` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `purchase_request`
--

INSERT INTO `purchase_request` (`id`, `product_id`, `requested_by_id`, `quantity`, `status`, `justification`, `created_at`) VALUES
(15, 18, 5, 49, 'Validée', 'Besoin urgent stock critique', '2026-01-10 19:55:14'),
(16, 27, 5, 18, 'Refusée', 'Besoin urgent stock critique', '2026-01-10 19:55:14'),
(17, 23, 5, 28, 'Validée', 'Besoin urgent stock critique', '2026-01-10 19:55:14'),
(18, 28, 5, 43, 'Validée', 'Besoin urgent stock critique', '2026-01-10 19:55:14'),
(19, 27, 5, 27, 'Validée', 'Besoin urgent stock critique', '2026-01-10 19:55:14'),
(20, 20, 5, 14, 'Validée', 'Besoin urgent stock critique', '2026-01-10 19:55:14'),
(21, 17, 5, 33, 'Validée', 'Besoin urgent stock critique', '2026-01-10 19:55:14'),
(22, 27, 5, 39, 'Validée', 'Besoin urgent stock critique', '2026-01-10 19:55:14'),
(23, 25, 5, 12, 'approved', 'Réassort mensuel standard', '2026-01-10 19:55:14'),
(24, 17, 5, 8, 'approved', 'Réassort mensuel standard', '2026-01-10 19:55:14'),
(25, 16, 5, 8, 'approved', 'Réassort mensuel standard', '2026-01-10 19:55:14'),
(26, 17, 5, 12, 'approved', 'Réassort mensuel standard', '2026-01-10 19:55:14'),
(27, 13, 5, 16, 'approved', 'Réassort mensuel standard', '2026-01-10 19:55:14'),
(28, 26, 5, 5, 'approved', 'Réassort mensuel standard', '2026-01-10 19:55:14'),
(29, 25, 5, 11, 'approved', 'Réassort mensuel standard', '2026-01-10 19:55:14'),
(30, 16, 5, 20, 'approved', 'Réassort mensuel standard', '2026-01-10 19:55:14'),
(32, 28, 4, 874, 'Validée', 'kijhvgugv', '2026-01-12 22:32:03'),
(33, 24, 4, 7, 'Validée', 'uoyfifiyiuhg', '2026-01-12 22:48:02'),
(34, 23, 4, 644, 'rejected', 'plkojihugyf', '2026-01-13 23:54:38'),
(35, 13, 4, 45, 'approved', 'YFLK', '2026-01-15 10:49:36'),
(36, 22, 4, 654, 'approved', '...', '2026-01-15 10:49:51'),
(37, 18, 4, 5465, 'pending', '.', '2026-01-15 11:03:30'),
(38, 16, 5, 12, 'approved', 'UPIU', '2026-01-15 11:12:08'),
(39, 28, 4, 45, 'pending', 'ttttttttttttttttttttttttt', '2026-01-15 12:01:13'),
(40, 16, 4, 22, 'approved', 'qqq', '2026-01-15 15:35:55');

-- --------------------------------------------------------

--
-- Structure de la table `stock_movement`
--

CREATE TABLE `stock_movement` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stock_movement`
--

INSERT INTO `stock_movement` (`id`, `product_id`, `quantity`, `type`, `created_at`, `comment`) VALUES
(5, 28, 23, 'IN', '2026-01-12 17:46:15', 'khifukfukyfuyfu'),
(6, 28, 23, 'IN', '2026-01-12 17:46:22', 'khifukfukyfuyfu'),
(7, 13, 679, 'IN', '2026-01-12 17:50:19', ';kjlhgfdgsfdfgkj'),
(8, 13, 6777, 'IN', '2026-01-12 17:51:00', 'kjohiugfydtsffghjkgdfshgjgkhf'),
(9, 13, 89, 'OUT', '2026-01-12 17:51:18', 'jhdsf'),
(10, 18, 49, 'IN', '2026-01-12 22:07:24', 'Approbation Demande #15'),
(11, 27, 39, 'IN', '2026-01-12 22:08:58', 'Approbation Demande #22'),
(12, 28, 43, 'IN', '2026-01-12 22:10:59', 'Approbation Demande #18'),
(13, 28, 874, 'IN', '2026-01-12 22:32:17', 'Approbation #32'),
(14, 17, 33, 'IN', '2026-01-13 15:43:48', 'Approbation #21'),
(15, 24, 7, 'IN', '2026-01-13 16:00:52', 'Approbation #33'),
(16, 23, 28, 'IN', '2026-01-13 19:26:54', 'Approbation #17'),
(17, 27, 27, 'IN', '2026-01-13 23:37:21', 'Approbation #19'),
(18, 20, 14, 'IN', '2026-01-13 23:54:26', 'Approbation #20'),
(19, 13, 50, 'IN', '2026-01-14 15:03:49', '...'),
(20, 29, 100, 'IN', '2026-01-14 15:04:26', '...'),
(21, 22, 654, 'ENTREE', '2026-01-15 11:02:24', NULL),
(22, 13, 45, 'ENTREE', '2026-01-15 11:02:30', NULL),
(23, 16, 12, 'ENTREE', '2026-01-15 12:00:37', NULL),
(24, 16, 22, 'ENTREE', '2026-01-15 20:12:53', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `email`, `phone`, `address`) VALUES
(6, 'Dell Maroc', 'dellmaroc@contact.c', '', NULL),
(7, 'Samsung Electronics', 'samsungelectronics@contact.com', '0522178970', NULL),
(8, 'Logitech Pro', 'logitechpro@contact.com', '0522983185', NULL),
(9, 'Bureau Vallée', 'bureauvallée@contact.com', '0522830861', NULL),
(10, 'TechMaroc Solutions', 'techmarocsolutions@contact.com', '0522756668', NULL),
(11, 'Cisco Systems', 'ciscosystems@contact.com', '0522236629', NULL),
(12, 'MegaComputer Casa', 'megacomputercasa@contact.com', '0522826548', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(4, 'admin@test.com', '[\"ROLE_ADMIN\",\"ROLE_USER\"]', '$2y$13$XOCf6IA4sX8o7WAZCaboO.Da0SIaT1pRTJ4l2gvzcIFg4bIGXR5jS'),
(5, 'manager@test.com', '[\"ROLE_MANAGER\",\"ROLE_USER\"]', '$2y$13$XOCf6IA4sX8o7WAZCaboO.Da0SIaT1pRTJ4l2gvzcIFg4bIGXR5jS'),
(6, 'magasinier@test.com', '[\"ROLE_MAGASINIER\",\"ROLE_USER\"]', '$2y$13$XOCf6IA4sX8o7WAZCaboO.Da0SIaT1pRTJ4l2gvzcIFg4bIGXR5jS');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD2ADD6D8C` (`supplier_id`),
  ADD KEY `IDX_D34A04AD12469DE2` (`category_id`);

--
-- Index pour la table `purchase_request`
--
ALTER TABLE `purchase_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_204D45E64584665A` (`product_id`),
  ADD KEY `IDX_204D45E64DA1E751` (`requested_by_id`);

--
-- Index pour la table `stock_movement`
--
ALTER TABLE `stock_movement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BB1BC1B54584665A` (`product_id`);

--
-- Index pour la table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `purchase_request`
--
ALTER TABLE `purchase_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `stock_movement`
--
ALTER TABLE `stock_movement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_D34A04AD2ADD6D8C` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`);

--
-- Contraintes pour la table `purchase_request`
--
ALTER TABLE `purchase_request`
  ADD CONSTRAINT `FK_204D45E64584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_204D45E64DA1E751` FOREIGN KEY (`requested_by_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `stock_movement`
--
ALTER TABLE `stock_movement`
  ADD CONSTRAINT `FK_BB1BC1B54584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
