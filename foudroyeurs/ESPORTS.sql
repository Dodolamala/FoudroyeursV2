-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 05 avr. 2025 à 16:43
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
-- Base de données : `esports`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresses`
--

CREATE TABLE `adresses` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `adresse_ligne1` varchar(255) NOT NULL,
  `adresse_ligne2` varchar(255) DEFAULT NULL,
  `ville` varchar(100) NOT NULL,
  `code_postal` varchar(20) NOT NULL,
  `pays` varchar(100) NOT NULL,
  `type` enum('facturation','livraison') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `slug`) VALUES
(1, 'Maillots', 'maillots'),
(2, 'V?tements', 'vetements'),
(3, 'Accessoires', 'accessoires'),
(4, '?quipement', 'equipement');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('en_attente','payee','expediee','livree','annulee') DEFAULT 'en_attente',
  `montant_total` decimal(10,2) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `elements_commande`
--

CREATE TABLE `elements_commande` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `equipes`
--

CREATE TABLE `equipes` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `jeu` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `equipes`
--

INSERT INTO `equipes` (`id`, `nom`, `jeu`, `description`) VALUES
(1, 'Les Foudroyeurs', 'League of Legends', 'L\'?quipe principale des Foudroyeurs, reconnue pour sa strat?gie foudroyante et son jeu agressif.');

-- --------------------------------------------------------

--
-- Structure de la table `membres_equipe`
--

CREATE TABLE `membres_equipe` (
  `id` int(11) NOT NULL,
  `equipe_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `pseudo` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT 'default-member.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `membres_equipe`
--

INSERT INTO `membres_equipe` (`id`, `equipe_id`, `nom`, `prenom`, `pseudo`, `role`, `bio`, `image_url`) VALUES
(1, 1, 'JANVIER', 'Matthieu', 'PoiujK', 'Top lane', 'V?ritable g?nie tactique, Matthieu anticipe les mouvements adverses et ?labore des strat?gies qui ont fait trembler les plus grandes ?quipes internationales.', 'poiujk.png'),
(2, 1, 'CHISTY', 'Nahian', 'Nahian22', 'Jungle', 'Malgr? le fait qu\'il trash ses mates, c\'est un joueur de jungle redoutable, avec une capacit? in?gal?e ? contr?ler la map en permettant gr?ce ? des ganks d?cisifs.', 'bao.png'),
(3, 1, 'BOUVET', 'Charlotte', 'Foudroyeur2tass', 'Mid lane', 'Joueuse de Mid lane comparable ? Faker, ma?trisant parfaitement sa lane et qui domine en teamfight gr?ce ? une m?canique irr?prochable et une vision de jeu d\'?lite.', 'default-member.png'),
(4, 1, 'LE', 'Bao-Long', 'bao v1', 'Bot lane', 'Un ADC d\'exception qui sait infliger un maximum de pression ? l\'ennemi gr?ce ? une gestion parfaite de la phase de lane et des objectifs.', 'default-member.png'),
(5, 1, 'GUILLOUX', 'Lo?s', 'PalpaMX', 'Support / CEO', 'Avec ses r?flexes foudroyants et son leadership naturel, Lo?s guide notre ?quipe vers la victoire depuis sa cr?ation en 2024.', 'default-member.png');

-- --------------------------------------------------------

--
-- Structure de la table `messages_contact`
--

CREATE TABLE `messages_contact` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_envoi` timestamp NOT NULL DEFAULT current_timestamp(),
  `est_lu` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages_contact`
--

INSERT INTO `messages_contact` (`id`, `nom`, `email`, `sujet`, `message`, `date_envoi`, `est_lu`) VALUES
(1, 'nuiieofghu', 'dorian.laceffe@hotmail.fr', 'recrutement', 'efeffef', '2025-04-05 13:27:10', 0);

-- --------------------------------------------------------

--
-- Structure de la table `options_produit`
--

CREATE TABLE `options_produit` (
  `id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `valeur` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `options_produit`
--

INSERT INTO `options_produit` (`id`, `produit_id`, `type`, `valeur`, `stock`) VALUES
(1, 1, 'taille', 'XS', 10),
(2, 1, 'taille', 'S', 15),
(3, 1, 'taille', 'M', 30),
(4, 1, 'taille', 'L', 25),
(5, 1, 'taille', 'XL', 15),
(6, 1, 'taille', 'XXL', 5),
(7, 3, 'taille', 'XS', 5),
(8, 3, 'taille', 'S', 10),
(9, 3, 'taille', 'M', 15),
(10, 3, 'taille', 'L', 10),
(11, 3, 'taille', 'XL', 7),
(12, 3, 'taille', 'XXL', 3),
(13, 6, 'modele', 'iPhone 14', 15),
(14, 6, 'modele', 'iPhone 13', 20),
(15, 6, 'modele', 'Samsung S22', 10),
(16, 6, 'modele', 'Google Pixel 7', 10);

-- --------------------------------------------------------

--
-- Structure de la table `palmares`
--

CREATE TABLE `palmares` (
  `id` int(11) NOT NULL,
  `equipe_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `date_obtention` date NOT NULL,
  `description` text DEFAULT NULL,
  `classement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `palmares`
--

INSERT INTO `palmares` (`id`, `equipe_id`, `titre`, `date_obtention`, `description`, `classement`) VALUES
(1, 1, 'Champions Nationaux 2024', '2024-05-15', 'Victoire éclatante face aux tenants du titre avec un score final de 3-0 qui a marqué l\'histoire du championnat.', 1),
(2, 1, 'Finalistes Européens', '2024-08-20', 'Une performance électrisante qui nous a menés jusqu\'en finale du plus grand tournoi européen, nous plaçant parmi l\'élite continentale.', 2),
(3, 1, 'Vainqueurs des Worlds 2024', '2024-02-10', 'Un parcours sans faute dans ce tournoi international avec une victoire écrasante en finale.', 1);

-- --------------------------------------------------------

--
-- Structure de la table `partenaires`
--

CREATE TABLE `partenaires` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT 'default-partner.png',
  `lien` varchar(255) DEFAULT NULL,
  `ordre` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `partenaires`
--

INSERT INTO `partenaires` (`id`, `nom`, `description`, `logo_url`, `lien`, `ordre`) VALUES
(1, 'Red Bull', 'Fournisseur officiel de boissons énergisantes des Foudroyeurs depuis 2024.', 'RB.png', 'https://redbull.com', 1),
(2, 'Logitech G', 'Equipementier officiel des Foudroyeurs pour les périphériques gaming.', 'logitech.png', 'https://logitechg.com', 2),
(3, 'Secret Lab', 'Fournisseur de chaises gaming ergonomiques pour nos champions.', 'secretlab.png', 'https://secretlab.eu', 3);

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `equipe_id` int(11) DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `quantite_stock` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT 'default-product.png',
  `est_nouveau` tinyint(1) DEFAULT 0,
  `est_populaire` tinyint(1) DEFAULT 0,
  `slug` varchar(255) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `equipe_id`, `categorie_id`, `nom`, `description`, `prix`, `quantite_stock`, `image_url`, `est_nouveau`, `est_populaire`, `slug`, `date_creation`) VALUES
(1, 1, 1, 'Maillot Officiel 2025', 'Le maillot officiel de l\'équipe Les Foudroyeurs pour la saison 2025.', 89.99, 100, 'maillotjaune.png', 1, 0, 'maillot-officiel-2025', '2025-04-03 20:19:08'),
(2, 1, 1, 'Maillot Alternatif 2025', 'La version alternative du maillot officiel avec un design unique.', 89.99, 75, 'maillot-alt.png', 0, 0, 'maillot-alternatif-2025', '2025-04-03 20:19:08'),
(3, 1, 2, 'Veste Rose', 'Veste aux couleurs des Foudroyeurs.', 74.99, 50, 'veste.png', 0, 1, 'veste-rose', '2025-04-03 20:19:08'),
(4, 1, 2, 'Hoodie', 'Sweat à capuche confortable avec le logo des Foudroyeurs.', 69.99, 60, 'hoodiefront.png', 0, 0, 'hoodie', '2025-04-03 20:19:08'),
(5, 1, 2, 'T-Shirt Logo', 'T-shirt simple avec le logo des Foudroyeurs.', 34.99, 120, 'tshirt.png', 0, 0, 't-shirt-logo', '2025-04-03 20:19:08'),
(6, 1, 3, 'Coque de téléphone', 'Protégez votre téléphone avec style grâce à cette coque.', 24.99, 80, 'coque.png', 0, 0, 'coque-telephone', '2025-04-03 20:19:08'),
(7, 1, 3, 'Gourde', 'Gourde écologique réutilisable avec le logo des Foudroyeurs.', 24.99, 70, 'gourde.png', 0, 0, 'gourde', '2025-04-03 20:19:08'),
(8, 1, 3, 'Casquette', 'Casquette ajustable avec logo brodé des Foudroyeurs.', 29.99, 60, 'casquette.png', 0, 0, 'casquette', '2025-04-03 20:19:08'),
(9, 1, 4, 'Clavier Gaming Pro', 'Clavier mécanique aux couleurs des Foudroyeurs.', 129.99, 30, 'clavier.png', 1, 1, 'clavier-gaming-pro', '2025-04-03 20:19:08'),
(10, 1, 4, 'Souris Gaming 8000 DPI', 'Souris gaming haute précision.', 89.99, 45, 'souris.png', 0, 0, 'souris-gaming', '2025-04-03 20:19:08'),
(11, 1, 4, 'Tapis de souris XL', 'Tapis de souris grand format pour une précision maximale.', 49.99, 55, 'tapisouris.png', 0, 0, 'tapis-souris-xl', '2025-04-03 20:19:08');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `role` enum('client','admin') DEFAULT 'client',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `email`, `mot_de_passe`, `prenom`, `nom`, `role`, `date_creation`) VALUES
(1, 'admin@foudroyeurs.fr', '$2y$10$8X1v1qUK7yHuDylxLpMOTOfpWHRNv6dh1z6q9jQB6hRvAaXy7ey5i', 'Admin', 'Foudroyeurs', 'admin', '2025-04-03 20:19:08');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `elements_commande`
--
ALTER TABLE `elements_commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_id` (`commande_id`),
  ADD KEY `produit_id` (`produit_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Index pour la table `equipes`
--
ALTER TABLE `equipes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `membres_equipe`
--
ALTER TABLE `membres_equipe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipe_id` (`equipe_id`);

--
-- Index pour la table `messages_contact`
--
ALTER TABLE `messages_contact`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `options_produit`
--
ALTER TABLE `options_produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Index pour la table `palmares`
--
ALTER TABLE `palmares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipe_id` (`equipe_id`);

--
-- Index pour la table `partenaires`
--
ALTER TABLE `partenaires`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `equipe_id` (`equipe_id`),
  ADD KEY `categorie_id` (`categorie_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresses`
--
ALTER TABLE `adresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `elements_commande`
--
ALTER TABLE `elements_commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `equipes`
--
ALTER TABLE `equipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `membres_equipe`
--
ALTER TABLE `membres_equipe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `messages_contact`
--
ALTER TABLE `messages_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `options_produit`
--
ALTER TABLE `options_produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `palmares`
--
ALTER TABLE `palmares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `partenaires`
--
ALTER TABLE `partenaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD CONSTRAINT `adresses_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `elements_commande`
--
ALTER TABLE `elements_commande`
  ADD CONSTRAINT `elements_commande_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `elements_commande_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`),
  ADD CONSTRAINT `elements_commande_ibfk_3` FOREIGN KEY (`option_id`) REFERENCES `options_produit` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `membres_equipe`
--
ALTER TABLE `membres_equipe`
  ADD CONSTRAINT `membres_equipe_ibfk_1` FOREIGN KEY (`equipe_id`) REFERENCES `equipes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `options_produit`
--
ALTER TABLE `options_produit`
  ADD CONSTRAINT `options_produit_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `palmares`
--
ALTER TABLE `palmares`
  ADD CONSTRAINT `palmares_ibfk_1` FOREIGN KEY (`equipe_id`) REFERENCES `equipes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`equipe_id`) REFERENCES `equipes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `produits_ibfk_2` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
