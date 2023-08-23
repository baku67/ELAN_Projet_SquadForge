-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage de la structure de table squadforge. candidature
CREATE TABLE IF NOT EXISTS `candidature` (
  `id` int NOT NULL AUTO_INCREMENT,
  `groupe_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci,
  `creation_date` datetime NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E33BD3B87A45358C` (`groupe_id`),
  KEY `IDX_E33BD3B8A76ED395` (`user_id`),
  CONSTRAINT `FK_E33BD3B87A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `group` (`id`),
  CONSTRAINT `FK_E33BD3B8A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.candidature : ~0 rows (environ)
DELETE FROM `candidature`;

-- Listage de la structure de table squadforge. censure
CREATE TABLE IF NOT EXISTS `censure` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `word` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5268F5A9A76ED395` (`user_id`),
  CONSTRAINT `FK_5268F5A9A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.censure : ~16 rows (environ)
DELETE FROM `censure`;
INSERT INTO `censure` (`id`, `user_id`, `creation_date`, `word`) VALUES
	(1, 3, '2023-05-17 18:04:44', 'pute'),
	(2, 3, '2023-05-17 18:13:29', 'merde'),
	(4, 3, '2023-05-17 18:13:44', 'quoicoubeh'),
	(7, 3, '2023-05-17 22:24:18', 'putain'),
	(9, 3, '2023-05-17 22:40:07', 'nique'),
	(10, 3, '2023-05-18 00:23:11', 'salot'),
	(11, 3, '2023-05-18 01:06:46', 'niquer'),
	(12, 3, '2023-05-18 01:06:55', 'batard'),
	(13, 3, '2023-05-18 01:07:18', 'conard'),
	(14, 3, '2023-05-18 01:07:24', 'connard'),
	(15, 3, '2023-05-18 01:07:30', 'connasse'),
	(16, 3, '2023-05-18 01:07:35', 'conasse'),
	(17, 3, '2023-05-18 01:07:49', 'enculé'),
	(22, 3, '2023-05-18 01:09:25', 'enfoiré'),
	(23, 3, '2023-05-18 01:09:32', 'bitch'),
	(25, 3, '2023-07-03 23:42:12', 'dfvdfv');

-- Listage de la structure de table squadforge. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table squadforge.doctrine_migration_versions : ~0 rows (environ)
DELETE FROM `doctrine_migration_versions`;
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230430131901', '2023-05-01 19:46:44', 43),
	('DoctrineMigrations\\Version20230430154643', '2023-05-01 19:46:44', 8),
	('DoctrineMigrations\\Version20230527014530', '2023-05-27 03:46:00', 94);

-- Listage de la structure de table squadforge. favoris
CREATE TABLE IF NOT EXISTS `favoris` (
  `user_id` int NOT NULL,
  `game_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`game_id`),
  KEY `IDX_8933C432A76ED395` (`user_id`),
  KEY `IDX_8933C432E48FD905` (`game_id`),
  CONSTRAINT `FK_8933C432A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_8933C432E48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.favoris : ~6 rows (environ)
DELETE FROM `favoris`;
INSERT INTO `favoris` (`user_id`, `game_id`) VALUES
	(3, 2),
	(3, 4),
	(3, 5),
	(3, 6),
	(7, 2),
	(8, 4);

-- Listage de la structure de table squadforge. game
CREATE TABLE IF NOT EXISTS `game` (
  `id` int NOT NULL AUTO_INCREMENT,
  `genre_id` int DEFAULT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `editor` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish_date` datetime NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `font_color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tiny_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nbr_places` smallint NOT NULL,
  `site_logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'logoSquadForge_v3.png',
  PRIMARY KEY (`id`),
  KEY `IDX_232B318C4296D31F` (`genre_id`),
  CONSTRAINT `FK_232B318C4296D31F` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.game : ~9 rows (environ)
DELETE FROM `game`;
INSERT INTO `game` (`id`, `genre_id`, `title`, `editor`, `publish_date`, `description`, `color`, `logo`, `banner`, `font_color`, `tiny_logo`, `sub_banner`, `nbr_places`, `site_logo`) VALUES
	(1, 1, 'Overwatch', 'Blizzard', '2017-05-01 21:16:23', 'FPS dynamique et efficace', '#ff9b00', 'overwatchLogo.png', 'overwatchBanner.jpg', 'white', 'overwatch1.png', 'overwatchHeaderBg.jpg', 0, 'logoSquadForge_overwatch.png'),
	(2, 1, 'Call of Duty', 'Activision', '2002-05-01 21:16:54', 'FPS connu ', '#74c814', 'callOfDutyLogo.png', 'codBanner.jpg', 'white', 'cod1.png', 'callofdutyHeaderBg.jpg', 0, 'logoSquadForge_cod.png'),
	(3, 2, 'Isaac', 'Edmund McMillen', '2003-05-01 23:59:12', 'Oulah', '#e24f37', 'isaacLogo.jpg', 'isaacBanner.jpg', 'white', 'isaac1.png', 'overwatchHeaderBg.jpg', 0, 'logoSquadForge_White_Rogned.png'),
	(4, 1, 'Valorant', 'Riot Games', '2021-05-02 01:41:56', 'Fps mélange entre Overwatch et Counter Strike', '#ff4655', 'valorantLogo.png', 'valorantBanner.jpg', 'white', 'valorant1.png', 'valorantHeaderBg.jpg', 0, 'logoSquadForge_valorant.png'),
	(5, 3, 'PUBG', 'Krafton', '2016-05-02 02:52:53', 'Battle royal pas ouf', '#ffd632', 'pubgLogo.jpeg', 'pubgBanner.jpg', 'white', 'pubg1.png', 'pubgHeaderBg.jpg', 0, 'logoSquadForge_pubg.png'),
	(6, 4, 'League of Legend', 'Riot Games', '2023-07-04 00:15:46', 'Bah c\'est lol', '#d4af61', 'lolLogo.jpg', 'lolBanner.jpg', 'white', 'lol1.png', 'lolHeaderBg.jpg', 0, 'logoSquadForge_lol.png'),
	(7, 4, 'League of Legend', 'Riot Games', '2023-07-04 00:15:46', 'Bah c\'est lol', '#d4af61', 'lolLogo.jpg', 'lolBanner.jpg', 'white', 'lol1.png', 'lolHeaderBg.jpg', 0, 'logoSquadForge_lol.png'),
	(8, 4, 'League of Legend', 'Riot Games', '2023-07-04 00:15:46', 'Bah c\'est lol', '#d4af61', 'lolLogo.jpg', 'lolBanner.jpg', 'white', 'lol1.png', 'lolHeaderBg.jpg', 0, 'logoSquadForge_lol.png'),
	(9, 4, 'League of Legend', 'Riot Games', '2023-07-04 00:15:46', 'Bah c\'est lol', '#d4af61', 'lolLogo.jpg', 'lolBanner.jpg', 'white', 'lol1.png', 'lolHeaderBg.jpg', 0, 'logoSquadForge_lol.png');

-- Listage de la structure de table squadforge. genre
CREATE TABLE IF NOT EXISTS `genre` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.genre : ~2 rows (environ)
DELETE FROM `genre`;
INSERT INTO `genre` (`id`, `name`) VALUES
	(1, 'FPS'),
	(2, 'indie'),
	(3, 'Battle Royal'),
	(4, 'MOBA');

-- Listage de la structure de table squadforge. group
CREATE TABLE IF NOT EXISTS `group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leader_id` int NOT NULL,
  `game_id` int NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `nbr_places` smallint NOT NULL,
  `creation_date` datetime NOT NULL,
  `restriction_18` tinyint(1) DEFAULT NULL,
  `restriction_lang` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restriction_mic` tinyint(1) DEFAULT NULL,
  `candidature_description` longtext COLLATE utf8mb4_unicode_ci,
  `restriction_img_proof` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6DC044C573154ED4` (`leader_id`),
  KEY `IDX_6DC044C5E48FD905` (`game_id`),
  CONSTRAINT `FK_6DC044C573154ED4` FOREIGN KEY (`leader_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_6DC044C5E48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.group : ~5 rows (environ)
DELETE FROM `group`;
INSERT INTO `group` (`id`, `leader_id`, `game_id`, `title`, `description`, `nbr_places`, `creation_date`, `restriction_18`, `restriction_lang`, `status`, `img_url`, `restriction_mic`, `candidature_description`, `restriction_img_proof`) VALUES
	(36, 7, 1, 'La team overwatch', 'fzefzef', 2, '2023-06-21 21:39:31', 0, 'fr', 'hidden', NULL, 0, NULL, 0),
	(38, 3, 5, 'Team PUBG du dimanche', 'On joue le dimanche aprèm pour le fun', 3, '2023-06-30 01:14:35', 0, 'fr', 'public', NULL, 0, NULL, 1),
	(39, 3, 6, 'Team lol', 'C\'est la team mdr lol', 5, '2023-07-04 01:03:57', 1, 'en', 'public', NULL, 1, NULL, 1),
	(47, 7, 4, 'Valo team numéro One', 'Ola', 6, '2023-07-08 06:14:54', 1, NULL, 'public', NULL, 0, NULL, 1),
	(49, 3, 2, 'fzefzefzef', 'zefzefzef', 2, '2023-08-22 14:57:39', 1, NULL, 'public', NULL, 1, NULL, 1);

-- Listage de la structure de table squadforge. group_answer
CREATE TABLE IF NOT EXISTS `group_answer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_question_id` int DEFAULT NULL,
  `candidature_id` int DEFAULT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_95B92F53B9FE97E` (`group_question_id`),
  KEY `IDX_95B92F5B6121583` (`candidature_id`),
  CONSTRAINT `FK_95B92F53B9FE97E` FOREIGN KEY (`group_question_id`) REFERENCES `group_question` (`id`),
  CONSTRAINT `FK_95B92F5B6121583` FOREIGN KEY (`candidature_id`) REFERENCES `candidature` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=321 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.group_answer : ~0 rows (environ)
DELETE FROM `group_answer`;

-- Listage de la structure de table squadforge. group_blacklist
CREATE TABLE IF NOT EXISTS `group_blacklist` (
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`),
  KEY `IDX_FBA40F64FE54D947` (`group_id`),
  KEY `IDX_FBA40F64A76ED395` (`user_id`),
  CONSTRAINT `FK_FBA40F64A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_FBA40F64FE54D947` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.group_blacklist : ~1 rows (environ)
DELETE FROM `group_blacklist`;

-- Listage de la structure de table squadforge. group_question
CREATE TABLE IF NOT EXISTS `group_question` (
  `id` int NOT NULL AUTO_INCREMENT,
  `groupe_id` int DEFAULT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BFC705317A45358C` (`groupe_id`),
  CONSTRAINT `FK_BFC705317A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.group_question : ~4 rows (environ)
DELETE FROM `group_question`;
INSERT INTO `group_question` (`id`, `groupe_id`, `text`, `required`) VALUES
	(30, 36, 'Test 1', 1),
	(36, 38, 'Test', 1),
	(38, 39, 'Question 1', 1);

-- Listage de la structure de table squadforge. group_session
CREATE TABLE IF NOT EXISTS `group_session` (
  `id` int NOT NULL AUTO_INCREMENT,
  `team_id` int NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comfirm_needed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_564481D8296CD8AE` (`team_id`),
  CONSTRAINT `FK_564481D8296CD8AE` FOREIGN KEY (`team_id`) REFERENCES `group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.group_session : ~7 rows (environ)
DELETE FROM `group_session`;
INSERT INTO `group_session` (`id`, `team_id`, `date_start`, `date_end`, `title`, `comfirm_needed`) VALUES
	(4, 47, '2023-07-26 20:25:00', '2023-07-26 21:25:00', 'hrhrtyh', 0),
	(5, 47, '2023-07-27 09:10:00', '2023-07-28 09:10:00', 'ergheg', 1),
	(8, 39, '2023-07-27 13:33:00', '2023-07-27 13:53:00', 'bfgbfgbf', 0),
	(9, 39, '2023-07-28 13:44:00', '2023-07-28 17:34:00', 'cbvcvbcvb', NULL),
	(10, 39, '2023-07-29 13:41:00', '2023-07-29 17:41:00', 'test ajout session', NULL),
	(11, 47, '2023-07-31 16:26:00', '2023-07-31 20:26:00', 'olaaa', NULL),
	(13, 39, '2023-08-22 10:00:00', '2023-08-30 10:00:00', 'rzeqffeqf', NULL),
	(14, 49, '2023-08-23 15:10:00', '2023-08-23 17:10:00', 'fesfsefs', NULL);

-- Listage de la structure de table squadforge. group_session_dispo
CREATE TABLE IF NOT EXISTS `group_session_dispo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` int NOT NULL,
  `member_id` int NOT NULL,
  `disponibility` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CCAF3BBB613FECDF` (`session_id`),
  KEY `IDX_CCAF3BBB7597D3FE` (`member_id`),
  CONSTRAINT `FK_CCAF3BBB613FECDF` FOREIGN KEY (`session_id`) REFERENCES `group_session` (`id`),
  CONSTRAINT `FK_CCAF3BBB7597D3FE` FOREIGN KEY (`member_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.group_session_dispo : ~3 rows (environ)
DELETE FROM `group_session_dispo`;
INSERT INTO `group_session_dispo` (`id`, `session_id`, `member_id`, `disponibility`) VALUES
	(3, 4, 3, 'perhaps'),
	(4, 4, 8, 'dispo'),
	(6, 5, 3, 'notdispo');

-- Listage de la structure de table squadforge. media
CREATE TABLE IF NOT EXISTS `media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish_date` datetime NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validated` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `game_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6A2CA10CA76ED395` (`user_id`),
  KEY `IDX_6A2CA10CE48FD905` (`game_id`),
  CONSTRAINT `FK_6A2CA10CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_6A2CA10CE48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.media : ~25 rows (environ)
DELETE FROM `media`;
INSERT INTO `media` (`id`, `title`, `publish_date`, `url`, `status`, `validated`, `user_id`, `game_id`) VALUES
	(1, 'Regardez donc ce 360° no scope, il doit etre dégouterrrr', '2023-05-09 08:59:47', 'valorant.gif', 'open', 'validated', 3, 4),
	(2, 'test topic test topic  test topic', '2023-05-09 11:08:54', 'valorant2.gif', 'open', 'validated', 3, 4),
	(3, 'test ajout media sans img', '2023-05-09 11:11:23', 'valorant3.gif', 'open', 'validated', 3, 4),
	(13, 'Test upload Gif Overwatch Test', '2023-05-10 01:54:11', 'overwatch2.gif', 'open', 'validated', 3, 1),
	(14, 'Test upload gif2 Overwatch Pog', '2023-05-10 01:56:42', 'overwatch.gif', 'open', 'validated', 3, 1),
	(15, 'test upload Gif Call of Duty', '2023-05-10 02:00:56', 'cod2.gif', 'open', 'validated', 3, 2),
	(16, 'Test upload meme Call of', '2023-05-10 02:02:12', 'cod.gif', 'open', 'validated', 3, 2),
	(17, 'test topic test topic  test topic', '2023-05-10 02:11:54', 'valorant2.gif', 'open', 'validated', 3, 4),
	(20, 'Test upload im toast Isaac.', '2023-05-10 03:08:54', 'isaac.jpg', 'open', 'validated', 3, 3),
	(21, 'Upload PUBG clip from mobile', '2023-05-11 02:47:04', '645c3b0808a20_1683766024.gif', 'open', 'validated', 3, 5),
	(22, 'Test pubg upload media again', '2023-05-11 02:50:14', '645c3bc60ed31_1683766214.gif', 'open', 'validated', 3, 5),
	(23, 'Re test upload PUBG media', '2023-05-11 02:52:30', '645c3c4e24ea3_1683766350.gif', 'open', 'validated', 3, 5),
	(24, 'Upload n4 gif pubg test', '2023-05-11 02:55:47', '645c3d1339ee3_1683766547.gif', 'open', 'validated', 3, 5),
	(25, 'Upload 5gif pubg gif os', '2023-05-11 02:57:03', '645c3d5f0e346_1683766623.gif', 'open', 'validated', 3, 5),
	(26, 'Test upoad N2 pibg uolo', '2023-05-11 02:58:00', '645c3d98a16c5_1683766680.gif', 'open', 'validated', 3, 5),
	(27, 'PUBG uoplad IMG media N1', '2023-05-11 02:58:50', '645c3dca01159_1683766730.gif', 'open', 'validated', 3, 5),
	(28, 'PUBG uoplad media original n0', '2023-05-11 03:00:18', '645c3e227000b_1683766818.gif', 'open', 'validated', 3, 5),
	(29, 'Test upload media overwatcj dndnd d', '2023-05-14 23:10:03', '64614e2b395a0_1684098603.gif', 'open', 'validated', 3, 1),
	(30, 'Isaac gif media upload kdkd', '2023-05-14 23:13:01', '64614edd486d2_1684098781.gif', 'open', 'validated', 3, 3),
	(31, 'Test upload Valolo gif fig', '2023-05-15 23:15:17', '6462a0e5a1fb4_1684185317.gif', 'open', 'validated', 3, 4),
	(38, 'non validé par modo snirf', '2023-05-18 04:51:43', '646592bf97980_1684378303.jpg', 'open', 'refused', 3, 4),
	(39, 'test upload media sa as a', '2023-05-18 05:02:26', '646595425dd82_1684378946.png', 'open', 'validated', 3, 3),
	(40, 'Test upload media en attente', '2023-05-18 05:35:57', '64659d1d9551c_1684380957.png', 'open', 'refused', 3, 2),
	(41, 'Test upload media gif 2Mo', '2023-05-26 01:24:50', '64659f26349ad_1684381478.gif', 'open', 'validated', 3, 2),
	(43, 'Retest gif upload overwatch gif', '2023-05-26 01:05:36', '646fe9944d7af_1685055892.gif', 'closed', 'validated', 3, 1),
	(74, 'test topic test topic  test topic', '2023-07-19 22:35:14', '64b8490234ab6_1689798914.jpg', 'open', 'waiting', 3, 4);

-- Listage de la structure de table squadforge. media_post
CREATE TABLE IF NOT EXISTS `media_post` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `media_id` int DEFAULT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_99CDB35EA76ED395` (`user_id`),
  KEY `IDX_99CDB35EEA9FDD75` (`media_id`),
  CONSTRAINT `FK_99CDB35EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_99CDB35EEA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.media_post : ~19 rows (environ)
DELETE FROM `media_post`;
INSERT INTO `media_post` (`id`, `user_id`, `media_id`, `text`, `publish_date`) VALUES
	(1, 3, 17, 'Test publiccation de media Post', '2023-05-10 09:17:16'),
	(2, 3, 3, 'test post media', '2023-05-10 09:26:29'),
	(3, 3, 20, 'test post biscotte', '2023-05-10 11:13:21'),
	(4, 3, 16, 'Test post', '2023-05-11 02:35:51'),
	(5, 3, 28, 'test post media', '2023-05-11 04:03:47'),
	(6, 3, 26, 'Pog', '2023-05-11 05:19:39'),
	(7, 3, 26, 'First', '2023-05-11 05:20:01'),
	(8, 3, 26, 'Nullllll', '2023-05-12 23:59:20'),
	(9, 3, 31, 'Test reponse', '2023-05-16 01:10:22'),
	(10, 3, 31, 'Réponse 2', '2023-05-16 01:10:34'),
	(11, 3, 15, 'Le commentaire a été supprimé', '2023-05-17 22:03:36'),
	(12, 3, 30, 'Y\'a bois isaac', '2023-06-07 01:50:21'),
	(13, 3, 30, 'Y\'a boy isaac*', '2023-06-07 01:50:47'),
	(14, 3, 30, 'Jdjd', '2023-06-07 01:50:52'),
	(15, 3, 30, '*****', '2023-06-07 01:51:06'),
	(16, 3, 30, 'Test', '2023-06-12 23:57:07'),
	(17, 3, 30, 'Hhhh', '2023-06-12 23:57:15'),
	(18, 3, 41, 'wvdsvqsdv', '2023-06-15 21:27:00'),
	(22, 7, 30, 'test', '2023-07-04 01:24:41');

-- Listage de la structure de table squadforge. media_post_like
CREATE TABLE IF NOT EXISTS `media_post_like` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `media_post_id` int DEFAULT NULL,
  `state` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_40569436A76ED395` (`user_id`),
  KEY `IDX_405694362A5E8565` (`media_post_id`),
  CONSTRAINT `FK_405694362A5E8565` FOREIGN KEY (`media_post_id`) REFERENCES `media_post` (`id`),
  CONSTRAINT `FK_40569436A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Upvote ou Downvote d''un Post de Media par un User';

-- Listage des données de la table squadforge.media_post_like : ~1 rows (environ)
DELETE FROM `media_post_like`;
INSERT INTO `media_post_like` (`id`, `user_id`, `media_post_id`, `state`) VALUES
	(52, 7, 18, 'upvote');

-- Listage de la structure de table squadforge. media_upvotes
CREATE TABLE IF NOT EXISTS `media_upvotes` (
  `media_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`media_id`,`user_id`),
  KEY `IDX_DE6026A8EA9FDD75` (`media_id`),
  KEY `IDX_DE6026A8A76ED395` (`user_id`),
  CONSTRAINT `FK_DE6026A8A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_DE6026A8EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.media_upvotes : ~18 rows (environ)
DELETE FROM `media_upvotes`;
INSERT INTO `media_upvotes` (`media_id`, `user_id`) VALUES
	(3, 3),
	(14, 3),
	(15, 3),
	(24, 3),
	(25, 3),
	(29, 3),
	(29, 7),
	(30, 3),
	(30, 7),
	(30, 8),
	(31, 3),
	(31, 8),
	(39, 3),
	(41, 3),
	(41, 7),
	(41, 8),
	(43, 3),
	(43, 7);

-- Listage de la structure de table squadforge. membre_group
CREATE TABLE IF NOT EXISTS `membre_group` (
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`),
  KEY `IDX_DBE64B44FE54D947` (`group_id`),
  KEY `IDX_DBE64B44A76ED395` (`user_id`),
  CONSTRAINT `FK_DBE64B44A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_DBE64B44FE54D947` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.membre_group : ~9 rows (environ)
DELETE FROM `membre_group`;
INSERT INTO `membre_group` (`group_id`, `user_id`) VALUES
	(36, 7),
	(38, 3),
	(38, 7),
	(39, 3),
	(47, 3),
	(47, 7),
	(47, 8),
	(49, 3);

-- Listage de la structure de table squadforge. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.messenger_messages : ~0 rows (environ)
DELETE FROM `messenger_messages`;

-- Listage de la structure de table squadforge. notation
CREATE TABLE IF NOT EXISTS `notation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `game_id` int DEFAULT NULL,
  `note` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_268BC95A76ED395` (`user_id`),
  KEY `IDX_268BC95E48FD905` (`game_id`),
  CONSTRAINT `FK_268BC95A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_268BC95E48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.notation : ~9 rows (environ)
DELETE FROM `notation`;
INSERT INTO `notation` (`id`, `user_id`, `game_id`, `note`) VALUES
	(9, 8, 4, 3),
	(41, 3, 5, 4),
	(43, 3, 1, 2),
	(44, 3, 3, 3),
	(45, 3, 2, 3),
	(46, 3, 6, 4),
	(47, 7, 4, 4),
	(48, 7, 2, 4),
	(53, 3, 4, 4);

-- Listage de la structure de table squadforge. notification
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` datetime NOT NULL,
  `seen` tinyint(1) DEFAULT '0',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clicked` tinyint(1) DEFAULT '0',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_id` int DEFAULT NULL,
  `type_nbr` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BF5476CAA76ED395` (`user_id`),
  CONSTRAINT `FK_BF5476CAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=519 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.notification : ~85 rows (environ)
DELETE FROM `notification`;
INSERT INTO `notification` (`id`, `user_id`, `text`, `date_creation`, `seen`, `link`, `clicked`, `type`, `type_id`, `type_nbr`) VALUES
	(408, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est dispo pour la session "hrhrtyh" qui a lieu le ', '2023-07-26 21:03:23', 1, NULL, 0, NULL, NULL, NULL),
	(409, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est dispo pour la session "hrhrtyh" qui a lieu le ', '2023-07-26 21:03:42', 1, 'http://127.0.0.1:8000/groupDetails/47/409', 0, NULL, NULL, NULL),
	(410, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le ', '2023-07-26 21:07:45', 1, 'http://127.0.0.1:8000/groupDetails/47/410', 0, NULL, NULL, NULL),
	(411, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 2023/07/26', '2023-07-26 21:08:45', 1, 'http://127.0.0.1:8000/groupDetails/47/411', 0, NULL, NULL, NULL),
	(412, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 08:07', '2023-07-26 21:09:24', 1, 'http://127.0.0.1:8000/groupDetails/47/412', 0, NULL, NULL, NULL),
	(413, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 21:09:38', 1, 'http://127.0.0.1:8000/groupDetails/47/413', 0, NULL, NULL, NULL),
	(414, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:40:38', 1, 'http://127.0.0.1:8000/groupDetails/47/414', 0, NULL, NULL, NULL),
	(415, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:40:45', 1, 'http://127.0.0.1:8000/groupDetails/47/415', 0, NULL, NULL, NULL),
	(416, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:40:51', 1, 'http://127.0.0.1:8000/groupDetails/47/416', 0, NULL, NULL, NULL),
	(417, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:48:30', 1, 'http://127.0.0.1:8000/groupDetails/47/417', 0, NULL, NULL, NULL),
	(418, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:52:50', 1, 'http://127.0.0.1:8000/groupDetails/47/418', 0, NULL, NULL, NULL),
	(419, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:52:53', 1, 'http://127.0.0.1:8000/groupDetails/47/419', 0, NULL, NULL, NULL),
	(420, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:53:55', 1, 'http://127.0.0.1:8000/groupDetails/47/420', 0, NULL, NULL, NULL),
	(421, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:57:46', 1, 'http://127.0.0.1:8000/groupDetails/47/421', 0, NULL, NULL, NULL),
	(422, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 23:01:02', 1, 'http://127.0.0.1:8000/groupDetails/47/422', 0, NULL, NULL, NULL),
	(423, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 23:01:05', 1, 'http://127.0.0.1:8000/groupDetails/47/423', 1, NULL, NULL, NULL),
	(424, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 23:33:16', 1, 'http://127.0.0.1:8000/groupDetails/47/424', 0, NULL, NULL, NULL),
	(425, 7, 'Nouvelle candidature de "basile3" pour votre team "Valo team numéro One"', '2023-07-26 23:36:52', 1, 'http://127.0.0.1:8000/candidatureDetails/83/425', 1, NULL, NULL, NULL),
	(426, 8, 'Votre candidature pour rejoindre la team "Valo team numéro One" a été acceptée.', '2023-07-26 23:37:14', 1, 'http://127.0.0.1:8000/groupDetails/47/426', 1, NULL, NULL, NULL),
	(428, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile3" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 00:46:24', 1, 'http://127.0.0.1:8000/groupDetails/47/428', 0, NULL, NULL, NULL),
	(429, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 01:06:42', 1, 'http://127.0.0.1:8000/groupDetails/47/429', 0, NULL, NULL, NULL),
	(430, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 01:06:54', 1, 'http://127.0.0.1:8000/groupDetails/47/430', 0, NULL, NULL, NULL),
	(431, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 01:07:25', 1, 'http://127.0.0.1:8000/groupDetails/47/431', 0, NULL, NULL, NULL),
	(432, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:00:21', 0, 'http://127.0.0.1:8000/groupDetails/47/432', 0, NULL, NULL, NULL),
	(433, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:00:37', 0, 'http://127.0.0.1:8000/groupDetails/47/433', 0, NULL, NULL, NULL),
	(435, 8, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Une session a été planifié "ergheg". Confirmation demandée par le leader de la team.', '2023-07-27 09:10:14', 0, 'http://127.0.0.1:8000/groupDetails/47/435', 0, NULL, NULL, NULL),
	(437, 8, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Une session a été planifié "sdvsdvdsd". ', '2023-07-27 09:10:30', 0, 'http://127.0.0.1:8000/groupDetails/47/437', 0, NULL, NULL, NULL),
	(438, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:10:38', 0, 'http://127.0.0.1:8000/groupDetails/47/438', 0, NULL, NULL, NULL),
	(439, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:10:46', 0, 'http://127.0.0.1:8000/groupDetails/47/439', 0, NULL, NULL, NULL),
	(440, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:13:58', 0, 'http://127.0.0.1:8000/groupDetails/47/440', 0, NULL, NULL, NULL),
	(441, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:19:46', 0, 'http://127.0.0.1:8000/groupDetails/47/441', 0, NULL, NULL, NULL),
	(442, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:04', 0, 'http://127.0.0.1:8000/groupDetails/47/442', 0, NULL, NULL, NULL),
	(443, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:09', 0, 'http://127.0.0.1:8000/groupDetails/47/443', 0, NULL, NULL, NULL),
	(444, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:09', 0, 'http://127.0.0.1:8000/groupDetails/47/444', 0, NULL, NULL, NULL),
	(445, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:27', 0, 'http://127.0.0.1:8000/groupDetails/47/445', 0, NULL, NULL, NULL),
	(446, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:27', 0, 'http://127.0.0.1:8000/groupDetails/47/446', 0, NULL, NULL, NULL),
	(447, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:28', 0, 'http://127.0.0.1:8000/groupDetails/47/447', 0, NULL, NULL, NULL),
	(448, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:28', 0, 'http://127.0.0.1:8000/groupDetails/47/448', 0, NULL, NULL, NULL),
	(449, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:58', 0, 'http://127.0.0.1:8000/groupDetails/47/449', 0, NULL, NULL, NULL),
	(450, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:58', 0, 'http://127.0.0.1:8000/groupDetails/47/450', 0, NULL, NULL, NULL),
	(451, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:58', 0, 'http://127.0.0.1:8000/groupDetails/47/451', 0, NULL, NULL, NULL),
	(452, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:59', 0, 'http://127.0.0.1:8000/groupDetails/47/452', 0, NULL, NULL, NULL),
	(453, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:59', 0, 'http://127.0.0.1:8000/groupDetails/47/453', 0, NULL, NULL, NULL),
	(454, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:59', 0, 'http://127.0.0.1:8000/groupDetails/47/454', 0, NULL, NULL, NULL),
	(455, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:22:00', 0, 'http://127.0.0.1:8000/groupDetails/47/455', 0, NULL, NULL, NULL),
	(456, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:22:00', 0, 'http://127.0.0.1:8000/groupDetails/47/456', 0, NULL, NULL, NULL),
	(457, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:30:44', 0, 'http://127.0.0.1:8000/groupDetails/47/457', 0, NULL, NULL, NULL),
	(458, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:30:56', 0, 'http://127.0.0.1:8000/groupDetails/47/458', 0, NULL, NULL, NULL),
	(459, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:30:56', 0, 'http://127.0.0.1:8000/groupDetails/47/459', 0, NULL, NULL, NULL),
	(460, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:37:42', 0, 'http://127.0.0.1:8000/groupDetails/47/460', 0, NULL, NULL, NULL),
	(461, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:37:47', 0, 'http://127.0.0.1:8000/groupDetails/47/461', 0, NULL, NULL, NULL),
	(462, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:37:47', 0, 'http://127.0.0.1:8000/groupDetails/47/462', 0, NULL, NULL, NULL),
	(463, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:40:04', 0, 'http://127.0.0.1:8000/groupDetails/47/463', 0, NULL, NULL, NULL),
	(464, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:40:08', 0, 'http://127.0.0.1:8000/groupDetails/47/464', 0, NULL, NULL, NULL),
	(465, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:40:08', 0, 'http://127.0.0.1:8000/groupDetails/47/465', 0, NULL, NULL, NULL),
	(466, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:44:00', 0, 'http://127.0.0.1:8000/groupDetails/47/466', 0, NULL, NULL, NULL),
	(467, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:44:04', 0, 'http://127.0.0.1:8000/groupDetails/47/467', 0, NULL, NULL, NULL),
	(468, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:46:41', 0, 'http://127.0.0.1:8000/groupDetails/47/468', 0, NULL, NULL, NULL),
	(469, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:46:54', 0, 'http://127.0.0.1:8000/groupDetails/47/469', 0, NULL, NULL, NULL),
	(470, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "ergheg" qui a lieu le 27/07 à 09:07', '2023-07-27 09:46:59', 0, 'http://127.0.0.1:8000/groupDetails/47/470', 0, NULL, NULL, NULL),
	(471, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:47:38', 0, 'http://127.0.0.1:8000/groupDetails/47/471', 0, NULL, NULL, NULL),
	(473, 8, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Une session a été annulée "sdvsdvdsd". ', '2023-07-27 10:07:50', 0, 'http://127.0.0.1:8000/groupDetails/47/473', 0, NULL, NULL, NULL),
	(474, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" n\'est pas disponible pour la session "ergheg" qui a lieu le 27/07 à 09:07', '2023-07-27 10:12:21', 0, 'http://127.0.0.1:8000/groupDetails/47/474', 0, NULL, NULL, NULL),
	(475, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 10:12:27', 0, 'http://127.0.0.1:8000/groupDetails/47/475', 0, NULL, NULL, NULL),
	(477, 8, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Une session a été planifié "jbgj". ', '2023-07-27 10:17:08', 0, 'http://127.0.0.1:8000/groupDetails/47/477', 0, NULL, NULL, NULL),
	(478, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "jbgj" qui a lieu le 29/07 à 10:07', '2023-07-27 10:32:16', 0, 'http://127.0.0.1:8000/groupDetails/47/478', 0, NULL, NULL, NULL),
	(480, 8, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Une session a été annulée "jbgj". ', '2023-07-27 10:33:13', 0, 'http://127.0.0.1:8000/groupDetails/47/480', 0, NULL, NULL, NULL),
	(481, 7, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 10:36:36', 0, 'http://127.0.0.1:8000/groupDetails/47/481', 0, NULL, NULL, NULL),
	(483, 8, '<span style=\'font-weight:bold;text-decoration:underline;\'>Valo team numéro One</span>: Une session a été planifié "olaaa". ', '2023-07-27 16:26:50', 0, 'http://127.0.0.1:8000/groupDetails/47/483', 0, NULL, NULL, NULL),
	(485, 7, '"basile" a quitté la team "La team overwatch"', '2023-08-22 10:25:23', 0, 'http://127.0.0.1:8000/groupDetails/36/485', 0, NULL, NULL, NULL),
	(486, 7, 'Votre post "jvcjbvbn post" a été upvoté par <strong style=\'font-size:1.1em;\'>2</strong> personnes', '2023-08-22 16:12:19', 0, 'http://127.0.0.1:8000/topicDetail/15/486', 0, 'topicPost', 26, 2),
	(505, 3, 'Nouvelle candidature de "basileeee" pour votre team "fzefzefzef"', '2023-08-22 15:09:50', 0, 'http://127.0.0.1:8000/candidatureDetails/86/505', 0, NULL, NULL, NULL),
	(508, 3, '<span style=\'font-weight:bold;text-decoration:underline;\'>fzefzefzef</span>: Le membre "basileeee" est disponible pour la session "fesfsefs" qui a lieu le 23/08 à 03:08', '2023-08-22 15:13:35', 0, 'http://127.0.0.1:8000/groupDetails/49/508', 0, NULL, NULL, NULL),
	(509, 3, '"basileeee" a quitté la team "fzefzefzef"', '2023-08-22 15:14:00', 0, 'http://127.0.0.1:8000/groupDetails/49/509', 0, NULL, NULL, NULL),
	(510, 3, 'Nouvelle candidature de "basileeee" pour votre team "fzefzefzef"', '2023-08-22 15:16:32', 0, 'http://127.0.0.1:8000/candidatureDetails/87/510', 0, NULL, NULL, NULL),
	(512, 3, '<span style=\'font-weight:bold;text-decoration:underline;\'>fzefzefzef</span>: Le membre "basileeee" est peut-être disponible pour la session "fesfsefs" qui a lieu le 23/08 à 03:08', '2023-08-22 15:20:26', 0, 'http://127.0.0.1:8000/groupDetails/49/512', 0, NULL, NULL, NULL),
	(513, 3, '"basileeee" a quitté la team "fzefzefzef"', '2023-08-22 15:20:41', 0, 'http://127.0.0.1:8000/groupDetails/49/513', 0, NULL, NULL, NULL),
	(514, 3, 'Nouvelle candidature de "basileeee" pour votre team "fzefzefzef"', '2023-08-22 15:22:16', 0, 'http://127.0.0.1:8000/candidatureDetails/88/514', 0, NULL, NULL, NULL),
	(516, 3, '"basileeee" a quitté la team "fzefzefzef"', '2023-08-22 15:23:54', 0, 'http://127.0.0.1:8000/groupDetails/49/516', 0, NULL, NULL, NULL),
	(517, 3, 'Nouvelle candidature de "basileeee" pour votre team "fzefzefzef"', '2023-08-22 15:24:16', 0, 'http://127.0.0.1:8000/candidatureDetails/89/517', 0, NULL, NULL, NULL);

-- Listage de la structure de table squadforge. post_like
CREATE TABLE IF NOT EXISTS `post_like` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `topic_post_id` int DEFAULT NULL,
  `state` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_653627B8A76ED395` (`user_id`),
  KEY `IDX_653627B8C3225EF9` (`topic_post_id`),
  CONSTRAINT `FK_653627B8A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_653627B8C3225EF9` FOREIGN KEY (`topic_post_id`) REFERENCES `topic_post` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.post_like : ~15 rows (environ)
DELETE FROM `post_like`;
INSERT INTO `post_like` (`id`, `user_id`, `topic_post_id`, `state`) VALUES
	(6, 3, 18, 'upvote'),
	(7, 3, 19, 'upvote'),
	(8, 3, 3, 'upvote'),
	(14, 3, 14, 'upvote'),
	(41, 3, 21, 'downvote'),
	(42, 3, 22, 'downvote'),
	(47, 7, 20, 'upvote'),
	(56, 7, 19, 'upvote'),
	(57, 7, 18, 'upvote'),
	(63, 7, 6, 'upvote'),
	(64, 7, 17, 'upvote'),
	(65, 7, 5, 'upvote'),
	(68, 7, 7, 'upvote');

-- Listage de la structure de table squadforge. report
CREATE TABLE IF NOT EXISTS `report` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_reporter_id` int DEFAULT NULL,
  `object_id` int NOT NULL,
  `object_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `report_motif_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C42F778448D42F60` (`user_reporter_id`),
  KEY `IDX_C42F7784D08E2164` (`report_motif_id`),
  CONSTRAINT `FK_C42F778448D42F60` FOREIGN KEY (`user_reporter_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_C42F7784D08E2164` FOREIGN KEY (`report_motif_id`) REFERENCES `report_motif` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.report : ~0 rows (environ)
DELETE FROM `report`;
INSERT INTO `report` (`id`, `user_reporter_id`, `object_id`, `object_type`, `creation_date`, `report_motif_id`) VALUES
	(70, 3, 53, 'topic', '2023-07-19 22:49:29', 3);

-- Listage de la structure de table squadforge. report_motif
CREATE TABLE IF NOT EXISTS `report_motif` (
  `id` int NOT NULL AUTO_INCREMENT,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.report_motif : ~6 rows (environ)
DELETE FROM `report_motif`;
INSERT INTO `report_motif` (`id`, `text`) VALUES
	(1, 'Harcèlement'),
	(3, 'Propos injurieux'),
	(4, 'Racisme/Sexisme/Homophobie'),
	(5, 'Spam'),
	(6, 'Incitation à la haine'),
	(7, 'Contenu illégal (atteinte à la vie privée, etc)');

-- Listage de la structure de table squadforge. topic
CREATE TABLE IF NOT EXISTS `topic` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish_date` datetime NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validated` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `game_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `first_msg` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_9D40DE1BE48FD905` (`game_id`),
  KEY `IDX_9D40DE1BA76ED395` (`user_id`),
  CONSTRAINT `FK_9D40DE1BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_9D40DE1BE48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.topic : ~32 rows (environ)
DELETE FROM `topic`;
INSERT INTO `topic` (`id`, `title`, `publish_date`, `status`, `validated`, `game_id`, `user_id`, `first_msg`) VALUES
	(1, 'Je sais pa vou mé', '2023-04-03 23:45:48', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(2, 'Bla bla bla blbla', '2023-05-03 18:57:03', 'closed', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(3, 'test date', '2023-05-04 02:09:02', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(5, 'aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa', '2023-05-04 22:08:47', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(6, 'Test topic formType aa aa aa', '2023-05-04 22:11:34', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(7, 'Test topic formType aa aa', '2023-05-04 22:16:00', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(8, 'Re test form topic valolo avec 5 mots', '2023-05-04 22:16:23', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(9, 'Overwatch', '2023-05-04 22:28:58', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(10, 'Overwatch2', '2023-05-04 22:31:18', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(11, 'Hg BJ non nbb bb', '2023-05-04 23:54:43', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(12, 'dz zd zd dz dz', '2023-05-05 01:20:15', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(13, 'zz zz zz zz zz', '2023-05-05 01:30:23', 'closed', 'validated', 2, 3, 'test nouveau 1er message'),
	(14, '1er topic pubg battle royal', '2023-05-05 01:56:39', 'open', 'validated', 5, 3, 'Test premier message pubg'),
	(15, 'Test topic en plus pour passer à 6 Topics', '2023-05-05 15:14:42', 'open', 'validated', 2, 3, 'dzd azad z'),
	(16, 'Au moins 5 mots Isaac test', '2023-05-06 00:59:28', 'open', 'validated', 3, 3, 'Nwnwn'),
	(17, 'A a a a a', '2023-05-06 01:00:07', 'open', 'validated', 3, 3, 'Jwn'),
	(20, 'merde merde merde merde merde', '2023-05-17 20:29:21', 'open', 'validated', 4, 3, 'dze'),
	(21, 'Test topic formType dez dze', '2023-05-17 20:44:33', 'open', 'validated', 4, 3, 'zedz'),
	(22, 'tes tdez dezfdef fezfe ezf e', '2023-05-17 20:48:19', 'open', 'validated', 4, 3, 'fezf'),
	(23, 'te de de ed de ****', '2023-05-18 01:28:51', 'open', 'validated', 1, 3, 'dezdze'),
	(24, 'test ajout topic validated 0 default', '2023-05-18 02:13:31', 'open', 'validated', 1, 3, 'dezdezdze'),
	(26, 'Test ajout topic PUBG (y\'en a que 1?)', '2023-05-18 03:57:48', 'open', 'refused', 5, 3, 'Bizarre'),
	(27, 'Test upload topic validation modo', '2023-05-18 05:33:34', 'open', 'validated', 3, 3, 'Vhj'),
	(28, 'Test topic New, modo modo', '2023-06-07 01:54:08', 'open', 'validated', 3, 3, 'Jdkdkd'),
	(29, 'Je créé un Topic (basile08) l\'original', '2023-06-18 06:05:38', 'open', 'refused', 4, 3, 'Voila c\'est mon message voila'),
	(31, 'Test test test test test', '2023-06-18 22:18:14', 'open', 'validated', 4, 7, 'Test'),
	(33, 'Test New topic test t\'es', '2023-06-28 01:37:57', 'open', 'validated', 4, 3, 'Test'),
	(34, 'dd ds d d d d', '2023-07-03 18:41:45', 'open', 'waiting', 2, 3, 'J'),
	(35, 'j j j j j', '2023-07-03 18:42:14', 'open', 'waiting', 2, 3, 'Fe'),
	(37, 'fer erff refe rfee ref', '2023-07-09 22:16:59', 'open', 'waiting', 4, 3, 'feeferfer'),
	(38, 'test topic League of Legend 5', '2023-07-17 18:55:46', 'open', 'waiting', 6, 3, 'description topic tmtc'),
	(53, 'dqzdqz s sd sdf sdf s', '2023-07-19 22:42:53', 'open', 'validated', 4, 3, 'dzdqzdqd'),
	(54, 'test npouveau topic basile 11 test delete User', '2023-08-22 14:34:20', 'open', 'validated', 2, NULL, 'dbgfdbdf');

-- Listage de la structure de table squadforge. topic_post
CREATE TABLE IF NOT EXISTS `topic_post` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `topic_id` int DEFAULT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62610D38A76ED395` (`user_id`),
  KEY `IDX_62610D381F55203D` (`topic_id`),
  CONSTRAINT `FK_62610D381F55203D` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`),
  CONSTRAINT `FK_62610D38A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.topic_post : ~28 rows (environ)
DELETE FROM `topic_post`;
INSERT INTO `topic_post` (`id`, `user_id`, `topic_id`, `text`, `publish_date`) VALUES
	(1, 3, 14, 'test message', '2023-05-05 17:36:59'),
	(3, 3, 14, 'test réponse 2', '2023-05-05 18:24:25'),
	(4, 3, 14, 'Test réponse 3', '2023-05-05 18:25:10'),
	(5, 3, 15, 'topic post cod', '2023-05-05 22:28:01'),
	(6, 3, 15, 'test btn submit entrer', '2023-05-05 23:00:19'),
	(7, 3, 15, 'test message réponse à "test btn submit entrer"', '2023-05-05 23:06:26'),
	(8, 3, 7, 'H', '2023-05-06 01:26:29'),
	(9, 3, 1, '1er comment', '2023-05-06 01:39:02'),
	(10, 3, 1, '2eme comment', '2023-05-06 01:39:11'),
	(11, 3, 1, '3eme comment', '2023-05-06 01:39:23'),
	(12, 3, 1, '4eme comment', '2023-05-06 01:39:29'),
	(13, 3, 1, '5eme comment', '2023-05-06 01:39:34'),
	(14, 3, 14, 'Hhjh', '2023-05-06 03:28:58'),
	(15, 3, 13, 'Ole', '2023-05-06 03:40:11'),
	(17, 3, 15, 'Le commentaire a été supprimé', '2023-05-06 05:17:41'),
	(18, 3, 11, 'Test post', '2023-05-08 23:26:20'),
	(19, 3, 11, 'Le commentaire a été supprimé', '2023-05-10 01:03:04'),
	(20, 3, 15, 'Le commentaire a été supprimé', '2023-05-17 22:01:27'),
	(21, 3, 14, '****** de ***** fils de ****', '2023-05-18 01:18:46'),
	(22, 3, 26, 'Test ajout message alors que topic pas.validated', '2023-05-18 03:58:49'),
	(23, 7, 33, 'Le commentaire a été supprimé', '2023-07-09 16:33:34'),
	(24, 3, 33, 'Le commentaire a été supprimé', '2023-07-14 01:51:54'),
	(25, 7, 33, 'Le commentaire a été supprimé', '2023-07-14 01:52:52'),
	(26, 7, 15, 'jvcjbvbn post', '2023-07-14 03:00:55'),
	(27, 3, 33, 'Le commentaire a été supprimé', '2023-07-17 18:59:44'),
	(28, 3, 33, 'Le commentaire a été supprimé', '2023-07-18 16:56:05'),
	(29, NULL, 15, 'Basile 11 topicPost', '2023-08-22 14:20:31'),
	(30, NULL, 54, 'test post onDelete User', '2023-08-22 16:12:00');

-- Listage de la structure de table squadforge. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `pseudo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auto_play_gifs` tinyint(1) DEFAULT '1',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date_status` datetime DEFAULT NULL,
  `nbr_censures` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.user : ~6 rows (environ)
DELETE FROM `user`;
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `pseudo`, `auto_play_gifs`, `status`, `end_date_status`, `nbr_censures`) VALUES
	(3, 'basile08@hotmail.fr', '["ROLE_MODO"]', '$2y$13$PFkqOIh3ZRbIx6totl7OE.aHyfWw9YQcb7ZqN.XeBtsr1YR/lZDiK', 1, 'basile', 1, '', '2023-07-22 21:46:56', 20),
	(7, 'basile09@hotmail.fr', '[]', '$2y$13$VQhOrGgKRQ1rdIoNWVld9eANrYSBAYbuGOCQQ5rHYbMfzBfpv4Sxa', 1, 'basile2', NULL, 'muted', '2023-07-25 05:33:42', 4),
	(8, 'basile10@hotmail.fr', '[]', '$2y$13$aQvLMfshYRre.ij4LrLkiO9prlAoISs8gqAItt.aC976djlHg5FdK', 1, 'basile3', 1, NULL, NULL, 0),
	(95, 'basile100@hotmail.fr', '[]', '$2y$13$JxJQQGHr6.N.eXLLAQ1PVufgrkOEQ6ux76wEii1CpMa1/SgdU94We', 1, 'basile100', 1, NULL, NULL, 0),
	(97, 'basile1000@hotmail.fr', '[]', '$2y$13$3HEhG1fVIir8OERh24La/unF9ivkRwmYZyxZ0lSpTU/IFqQtcpdh6', 1, 'basile1000', 1, NULL, NULL, 0),
	(98, 'basile22@hotmail.fr', '[]', '$2y$13$1H8ZNrtzJfOuil.Fqy1gg.Nh2AG1wSphWL3opDrhXvQ1TPUGrAn6O', 1, 'basile22', 1, NULL, NULL, 0),
	(99, 'basile00@hotmail.fr', '[]', '$2y$13$Hl7hpUl.gu6iBmHVCaSBo.4jAKqFpt4Z/yXrWKJj1be1kjdPDYEjm', 0, 'basile00', 1, NULL, NULL, 0);

-- Listage de la structure de table squadforge. user_game
CREATE TABLE IF NOT EXISTS `user_game` (
  `user_id` int NOT NULL,
  `game_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`game_id`),
  KEY `IDX_59AA7D45A76ED395` (`user_id`),
  KEY `IDX_59AA7D45E48FD905` (`game_id`),
  CONSTRAINT `FK_59AA7D45A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_59AA7D45E48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table squadforge.user_game : ~0 rows (environ)
DELETE FROM `user_game`;
INSERT INTO `user_game` (`user_id`, `game_id`) VALUES
	(98, 1),
	(98, 2),
	(98, 3),
	(98, 4),
	(98, 5),
	(98, 6),
	(98, 7),
	(98, 8),
	(98, 9);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
