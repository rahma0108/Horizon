-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 02:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greenmove`
--

-- --------------------------------------------------------

--
-- Table structure for table `actualites`
--

CREATE TABLE `actualites` (
  `id_actualite` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `date_publication` date DEFAULT NULL,
  `id_categorie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorie_actualite`
--

CREATE TABLE `categorie_actualite` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commentaire`
--

CREATE TABLE `commentaire` (
  `id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date` datetime DEFAULT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `sport_type` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `createdby` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date` datetime DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `quantite` int(11) NOT NULL,
  `id_categorie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` char(50) DEFAULT NULL,
  `prenom` char(50) DEFAULT NULL,
  `email` char(100) DEFAULT NULL,
  `adresse` char(255) DEFAULT NULL,
  `password` char(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `role` char(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `adresse`, `password`, `date`, `role`) VALUES
(45612374, 'mamaa', 'ahashuim', 'mimaoia@gamil.com', 'hezcrheo', '$2y$10$XNjTpKZb1i/YKsU.A/NTRe4AWMiEuFkvDvjJQ8qk5ubWEmENHNx1W', '2025-04-24', 'user'),
(45612375, 'mama', 'ahashuim', 'mimaaa@gamil.com', 'hezcrheo', '$2y$10$9oRx9jYdf1inTVHAhJgaIuDrDL7hmeQRoxQtGLjxgdXN7ALEg.Hie', '2025-04-08', 'user'),
(45612376, 'mama', 'ahashuim', 'mimaapaa@gamil.com', 'hezcrheo', '$2y$10$ghHiW82zpxsPWYzX8ZhdguWsg/SxvYjIO7PwSLl.anbjMVxithXeS', '2025-04-17', 'user'),
(45612378, 'mama', 'ahashuim', 'mimaa@gamil.com', 'hezcrheo', '$2y$10$K6g/N9NqzCbSQFzTj8T9FeRaz/f3lduPEUdUj4Uv6y4tl352vKK5G', '2025-04-16', 'user'),
(45612389, 'mama', 'ahashuim', 'mimakja@gamil.com', 'hezcrheo', '$2y$10$.2UkzGCVNPKxHzNeius6e.XIyJp570vUH.ilPRC72uVP8rEkJTiHu', '2025-04-21', 'admin'),
(45645694, 'zheuiezmuh', 'ehzuihoieza', 'uezgeh@gmail.com', 'uyugZI', '$2y$10$3QG3PZr3fannOR7frophv.mnER5m9etn4njdtFDjDC.1XmLxWKKyO', '2025-04-23', 'admin'),
(52552255, 'test', 'test', 'test@test.test', 'test', '$2y$10$5oD47vKQreqk4aiIQoWfS.U889KnjbmGkONpS/d7ExNt5vfP8bGrm', '2002-08-26', 'admin'),
(55487523, 'dali', 'zjbe', 'daddzsliedy@gmail.com', 'mourouj', '$2y$10$wcWug0FepS7fS6EMBk25uOIUHewjElCzGwGLQbcyU6uEVKrA37s16', '2025-04-15', 'admin'),
(55555129, 'molka', 'ajengui', 'molkaaapaajenguii@gmail.com', 'soukra', '$2y$10$NBoILMtbwIUwR.EMVqvzGOZ07qmaZr0JSrzT9RMkiGfeBmRmtaUsS', '2025-04-22', 'user'),
(55564789, 'dali', 'zjbe', 'dadeedsliedy@gmail.com', 'mourouj', '$2y$10$iXzdq.o5H5sskpkkR6U/u.uDgHD4tCUKFJ6S0/nRxrnBpfLBs8vEy', '2025-04-08', 'user'),
(75481234, 'molka', 'tyhbdtuygtryugrhutrgyu', 'trrrrrrrr@gmail.com', 'tryytytytyyt', '$2y$10$FX0abBvoSJDEvkuh/R/q5.wMAaSkMY/dN8J/u.rfefvibmRijA36K', '2025-04-16', 'client'),
(75555145, 'molka', 'ajengui', 'molkaappjenguii@gmail.com', 'soukra', '$2y$10$MAO9bC9HKwCdmv8HnSQpNeouJWeKfvFUve/zxtYgsnq/hHlwV4qr.', '2025-04-22', 'user'),
(77777414, '77777414', 'mehdi', 'abaoub', 'mehdiabaoubb@gmail.com', '$2y$10$a.iac54Zychjfe1vyGh7reeKQb6YvxhI8AEtH9up43owtZli9yCvG', '0000-00-00', '2025-04-22'),
(77777417, 'mehdi', 'abaoub', 'mehdiabaouubb@gmail.com', 'menzah', '$2y$10$P3JM6SCl9ajB6Z1kBqmk5.4aqKUGaW/155VDHv2vnpVpQUh/uOKwi', '2025-04-16', 'admin'),
(77777418, 'mehdi', 'abaoub', 'mehdiabaoub@gmail.com', 'menzah', '$2y$10$OGcP0K4zLETEbPYQI0jJWOmI4j9bjNjFYJbZhxq/ftwLspQJ7HqU2', '2004-05-07', 'user'),
(77777588, '77777588', 'molka', 'ajengui', 'molkaajjjjengui@gmail.com', '$2y$10$cbjztJ6t5ZNeOUyQXmYdCOamHfeeLDsMgD98K2nVFL0k5k4m71nJS', '0000-00-00', '2025-04-29'),
(77777775, 'ùiezfhùiz', 'makni', 'benhamzajihane8@gmail.com', 'ezgugyul', '$2y$10$ZMERoIHBQRTGOwXaoOzg5es3C5rw.Ve84Q7uqAPuu/lMZt5zrYWOS', '2025-04-08', 'admin'),
(77789457, 'dali', 'zjbe', 'daddsliedy@gmail.com', 'mourouj', '$2y$10$TKbXadPId6rcYuipIVndf.JDMewwXssRq1PErcofcbu7RloCJ8Otq', '2025-04-15', 'admin'),
(78459621, 'hjcveyulzeyc', 'sdcncuis', 'jsdcubus@gmail.com', 'qsygdyz', '$2y$10$rLeWEUr9KGkTTAgoCDKXSOMc4u48EVwmV9jXKb7SHHU3w65RdgUEK', '2024-07-10', 'user'),
(78945888, 'salma', 'ekezni', 'mouhibdark2001@mail.com', 'ehehzy', '$2y$10$r7rBEdOUfB4wsDCStM1zt.1iFJntNGbpWoxciNMBslJdf.VplkYYq', '2025-04-03', 'admin'),
(88888874, '88888874', 'molka', 'ajengui', 'molkaajjjengui@gmail.com', '$2y$10$VH5CIZbDrkMBFRyFN8.jd.QZoqUGA2VwuMORGtUrEmPhoHWnKh0ue', '0000-00-00', '2025-04-18'),
(88888882, 'rahma', 'makni', 'molka.ajengui@esprit.tn', 'soukra', '4447p', '2025-04-09', 'admin'),
(88888883, 'dali', 'zjbe', 'daliedy@gmail.com', 'mourouj', '$2y$10$CcAcX7YYZaHLwQ6qKo/mZe9//3sRC/h1xTEEpSZPnJ9oBdsQERS32', '2025-04-09', 'user'),
(99999475, 'dali', 'zjbe', 'daliedyi@gmail.com', 'mourouj', '$2y$10$0k.I1tHY8k/LIfUKqGA7gecoXorwOkJMW75VqfIbJktjtHV22smGO', '2025-04-09', 'admin'),
(99999994, 'Molka Ajengui', 'zauxyaz', 'molkaajengui@gmail.com', 'sqbxyusc', '$2y$10$j3ayzPL73SZFk2SDWI40Z.YjcoAzbM4hZlGILnax1Qi1m5/Z3hP/6', '2025-04-08', 'admin'),
(99999995, 'molka', 'zauxyaz', 'sjxqhyu@gmail.com', 'sqbxyusc', '$2y$10$7ET9VGdpcSRpSK/ln8eH7OB2JdgZxPOhSeNlVK.sutr08eokZmaDS', '2025-04-08', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actualites`
--
ALTER TABLE `actualites`
  ADD PRIMARY KEY (`id_actualite`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorie_actualite`
--
ALTER TABLE `categorie_actualite`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Indexes for table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_post` (`id_post`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actualites`
--
ALTER TABLE `actualites`
  MODIFY `id_actualite` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categorie_actualite`
--
ALTER TABLE `categorie_actualite`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99999996;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actualites`
--
ALTER TABLE `actualites`
  ADD CONSTRAINT `actualites_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie_actualite` (`id_categorie`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `utilisateurs` (`id`);

--
-- Constraints for table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
