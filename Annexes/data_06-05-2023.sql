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

-- Listage des données de la table squadforge.doctrine_migration_versions : ~2 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230430131901', '2023-05-01 19:46:44', 43),
	('DoctrineMigrations\\Version20230430154643', '2023-05-01 19:46:44', 8);

-- Listage des données de la table squadforge.game : ~5 rows (environ)
INSERT INTO `game` (`id`, `genre_id`, `title`, `editor`, `publish_date`, `description`, `color`, `logo`, `banner`, `font_color`, `tiny_logo`) VALUES
	(1, 1, 'Overwatch', 'Blizzard', '2017-05-01 21:16:23', 'FPS dynamique et efficace', '#ff9b00', 'overwatchLogo.png', NULL, 'white', 'overwatch1.png'),
	(2, 1, 'Call of Duty', 'Activision', '2002-05-01 21:16:54', 'FPS connu ', '#74c814', 'callOfDutyLogo.png', 'codBanner.jpg', 'white', 'cod1.png'),
	(3, 2, 'Isaac', 'Edmund McMillen', '2003-05-01 23:59:12', 'Oulah', 'Brown', 'isaacLogo.jpg', 'isaacBanner.jpg', 'white', NULL),
	(4, 1, 'Valorant', 'Riot Games', '2021-05-02 01:41:56', 'Fps mélange entre Overwatch et Counter Strike', '#ff4655', 'valorantLogo.png', 'valorantBanner.jpg', 'white', 'valorant1.png'),
	(5, 3, 'PUBG', 'Krafton', '2016-05-02 02:52:53', 'Battle royal pas ouf', '#ffd632', 'pubgLogo.jpeg', 'pubgBanner.jpg', 'white', 'pubg1.png');

-- Listage des données de la table squadforge.genre : ~3 rows (environ)
INSERT INTO `genre` (`id`, `name`) VALUES
	(1, 'FPS'),
	(2, 'indie'),
	(3, 'Battle Royal');

-- Listage des données de la table squadforge.messenger_messages : ~0 rows (environ)

-- Listage des données de la table squadforge.notation : ~5 rows (environ)
INSERT INTO `notation` (`id`, `user_id`, `game_id`, `note`) VALUES
	(3, 3, 2, 3),
	(4, 3, 1, 5),
	(5, 3, 4, 5),
	(6, 3, 5, 1),
	(7, 3, 3, 5);

-- Listage des données de la table squadforge.topic : ~17 rows (environ)
INSERT INTO `topic` (`id`, `title`, `publish_date`, `status`, `validated`, `game_id`, `user_id`, `first_msg`) VALUES
	(1, 'Je sais pa vou mé', '2023-04-03 23:45:48', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(2, 'Bla bla bla blbla', '2023-05-03 18:57:03', 'closed', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(3, 'test date', '2023-05-04 02:09:02', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(4, 'Overwatch topic', '2023-05-04 09:30:52', 'open', 'validated', 1, 4, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(5, 'aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa', '2023-05-04 22:08:47', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(6, 'Test topic formType aa aa aa', '2023-05-04 22:11:34', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(7, 'Test topic formType aa aa', '2023-05-04 22:16:00', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(8, 'Re test form topic valolo avec 5 mots', '2023-05-04 22:16:23', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(9, 'aa aa aa aa aa', '2023-05-04 22:28:58', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(10, 'aa aa aa aa aa', '2023-05-04 22:31:18', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(11, 'Hg BJ non nbb bb', '2023-05-04 23:54:43', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(12, 'dz zd zd dz dz', '2023-05-05 01:20:15', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(13, 'zz zz zz zz zz', '2023-05-05 01:30:23', 'open', 'validated', 2, 3, 'test nouveau 1er message'),
	(14, '1er topic pubg battle royal', '2023-05-05 01:56:39', 'open', 'validated', 5, 3, 'Test premier message pubg'),
	(15, 'Test topic en plus pour passer à 6 Topics', '2023-05-05 15:14:42', 'closed', 'validated', 2, 3, 'dzd azad z'),
	(16, 'Au moins 5 mots Isaac test', '2023-05-06 00:59:28', 'open', 'validated', 3, 3, 'Nwnwn'),
	(17, 'A a a a a', '2023-05-06 01:00:07', 'open', 'validated', 3, 3, 'Jwn');

-- Listage des données de la table squadforge.topic_post : ~16 rows (environ)
INSERT INTO `topic_post` (`id`, `user_id`, `topic_id`, `text`, `publish_date`, `parent_id`, `topic_post_id`) VALUES
	(1, 3, 14, 'test message', '2023-05-05 17:36:59', NULL, NULL),
	(2, 4, 14, 'test Réponse (pluvieu)', '2023-05-05 17:59:15', NULL, NULL),
	(3, 3, 14, 'test réponse 2', '2023-05-05 18:24:25', NULL, NULL),
	(4, 3, 14, 'Test réponse 3', '2023-05-05 18:25:10', NULL, NULL),
	(5, 3, 15, 'topic post cod', '2023-05-05 22:28:01', NULL, NULL),
	(6, 3, 15, 'test btn submit entrer', '2023-05-05 23:00:19', NULL, NULL),
	(7, 3, 15, 'test message réponse à "test btn submit entrer"', '2023-05-05 23:06:26', 6, NULL),
	(8, 3, 7, 'H', '2023-05-06 01:26:29', NULL, NULL),
	(9, 3, 1, '1er comment', '2023-05-06 01:39:02', NULL, NULL),
	(10, 3, 1, '2eme comment', '2023-05-06 01:39:11', NULL, NULL),
	(11, 3, 1, '3eme comment', '2023-05-06 01:39:23', NULL, NULL),
	(12, 3, 1, '4eme comment', '2023-05-06 01:39:29', NULL, NULL),
	(13, 3, 1, '5eme comment', '2023-05-06 01:39:34', NULL, NULL),
	(14, 3, 14, 'Hhjh', '2023-05-06 03:28:58', NULL, NULL),
	(15, 3, 13, 'Ole', '2023-05-06 03:40:11', NULL, NULL),
	(16, 4, 15, 'test autre user', '2023-05-06 03:52:37', NULL, NULL),
	(17, 3, 15, 'test apres verif si closed topics', '2023-05-06 05:17:41', NULL, NULL);

-- Listage des données de la table squadforge.topic_post_user : ~5 rows (environ)
INSERT INTO `topic_post_user` (`topic_post_id`, `user_id`) VALUES
	(3, 3),
	(5, 3),
	(15, 3),
	(16, 3),
	(16, 4);

-- Listage des données de la table squadforge.user : ~2 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `pseudo`) VALUES
	(3, 'basile08@hotmail.fr', '[]', '$2y$13$PFkqOIh3ZRbIx6totl7OE.aHyfWw9YQcb7ZqN.XeBtsr1YR/lZDiK', 0, 'basile'),
	(4, 'stgr', '[]', 'dfhydhy', 0, 'bot324');

-- Listage des données de la table squadforge.user_game : ~4 rows (environ)
INSERT INTO `user_game` (`user_id`, `game_id`) VALUES
	(3, 2),
	(3, 3),
	(3, 4),
	(3, 5);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;