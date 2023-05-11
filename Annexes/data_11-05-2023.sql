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

-- Listage des données de la table squadforge.doctrine_migration_versions : ~0 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230430131901', '2023-05-01 19:46:44', 43),
	('DoctrineMigrations\\Version20230430154643', '2023-05-01 19:46:44', 8);

-- Listage des données de la table squadforge.game : ~5 rows (environ)
INSERT INTO `game` (`id`, `genre_id`, `title`, `editor`, `publish_date`, `description`, `color`, `logo`, `banner`, `font_color`, `tiny_logo`) VALUES
	(1, 1, 'Overwatch', 'Blizzard', '2017-05-01 21:16:23', 'FPS dynamique et efficace', '#ff9b00', 'overwatchLogo.png', 'overwatchBanner.jpg', 'white', 'overwatch1.png'),
	(2, 1, 'Call of Duty', 'Activision', '2002-05-01 21:16:54', 'FPS connu ', '#74c814', 'callOfDutyLogo.png', 'codBanner.jpg', 'white', 'cod1.png'),
	(3, 2, 'Isaac', 'Edmund McMillen', '2003-05-01 23:59:12', 'Oulah', 'Brown', 'isaacLogo.jpg', 'isaacBanner.jpg', 'white', 'isaac1.png'),
	(4, 1, 'Valorant', 'Riot Games', '2021-05-02 01:41:56', 'Fps mélange entre Overwatch et Counter Strike', '#ff4655', 'valorantLogo.png', 'valorantBanner.jpg', 'white', 'valorant1.png'),
	(5, 3, 'PUBG', 'Krafton', '2016-05-02 02:52:53', 'Battle royal pas ouf', '#ffd632', 'pubgLogo.jpeg', 'pubgBanner.jpg', 'white', 'pubg1.png');

-- Listage des données de la table squadforge.genre : ~2 rows (environ)
INSERT INTO `genre` (`id`, `name`) VALUES
	(1, 'FPS'),
	(2, 'indie'),
	(3, 'Battle Royal');

-- Listage des données de la table squadforge.media : ~15 rows (environ)
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
	(28, 'PUBG uoplad media original n0', '2023-05-11 03:00:18', '645c3e227000b_1683766818.gif', 'closed', 'validated', 3, 5);

-- Listage des données de la table squadforge.media_post : ~7 rows (environ)
INSERT INTO `media_post` (`id`, `user_id`, `media_id`, `text`, `publish_date`) VALUES
	(1, 3, 17, 'Test publiccation de media Post', '2023-05-10 09:17:16'),
	(2, 3, 3, 'test post media', '2023-05-10 09:26:29'),
	(3, 3, 20, 'test post biscotte', '2023-05-10 11:13:21'),
	(4, 3, 16, 'Test post', '2023-05-11 02:35:51'),
	(5, 3, 28, 'test post media', '2023-05-11 04:03:47'),
	(6, 3, 26, 'Pog', '2023-05-11 05:19:39'),
	(7, 3, 26, 'First', '2023-05-11 05:20:01');

-- Listage des données de la table squadforge.media_post_like : ~3 rows (environ)
INSERT INTO `media_post_like` (`id`, `user_id`, `media_post_id`, `state`) VALUES
	(9, 3, 1, 'downvote'),
	(10, 3, 4, 'upvote'),
	(11, 3, 7, 'downvote');

-- Listage des données de la table squadforge.media_user : ~0 rows (environ)
INSERT INTO `media_user` (`media_id`, `user_id`) VALUES
	(14, 3),
	(16, 3),
	(20, 3),
	(28, 3);

-- Listage des données de la table squadforge.messenger_messages : ~0 rows (environ)

-- Listage des données de la table squadforge.notation : ~5 rows (environ)
INSERT INTO `notation` (`id`, `user_id`, `game_id`, `note`) VALUES
	(3, 3, 2, 3),
	(4, 3, 1, 3),
	(5, 3, 4, 3),
	(6, 3, 5, 1),
	(7, 3, 3, 5);

-- Listage des données de la table squadforge.post_like : ~10 rows (environ)
INSERT INTO `post_like` (`id`, `user_id`, `topic_post_id`, `state`) VALUES
	(2, 3, 15, 'upvote'),
	(3, 3, 17, 'downvote'),
	(4, 3, 14, 'downvote'),
	(5, 3, 16, 'upvote'),
	(6, 3, 18, 'upvote'),
	(7, 3, 19, 'upvote'),
	(8, 3, 3, 'upvote'),
	(11, 3, 7, 'upvote'),
	(12, 3, 6, 'upvote'),
	(13, 3, 5, 'downvote');

-- Listage des données de la table squadforge.topic : ~12 rows (environ)
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
	(13, 'zz zz zz zz zz', '2023-05-05 01:30:23', 'closed', 'validated', 2, 3, 'test nouveau 1er message'),
	(14, '1er topic pubg battle royal', '2023-05-05 01:56:39', 'closed', 'validated', 5, 3, 'Test premier message pubg'),
	(15, 'Test topic en plus pour passer à 6 Topics', '2023-05-05 15:14:42', 'open', 'validated', 2, 3, 'dzd azad z'),
	(16, 'Au moins 5 mots Isaac test', '2023-05-06 00:59:28', 'open', 'validated', 3, 3, 'Nwnwn'),
	(17, 'A a a a a', '2023-05-06 01:00:07', 'open', 'validated', 3, 3, 'Jwn'),
	(18, 'test topic  test topic  test topic', '2023-05-09 11:11:00', 'open', 'validated', 4, 3, 'zrfezfzef');

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
	(17, 3, 15, 'test apres verif si closed topics', '2023-05-06 05:17:41', NULL, NULL),
	(18, 3, 11, 'Test post', '2023-05-08 23:26:20', NULL, NULL),
	(19, 3, 11, 'Bien dit !', '2023-05-10 01:03:04', NULL, NULL);

-- Listage des données de la table squadforge.topic_post_user : ~4 rows (environ)
INSERT INTO `topic_post_user` (`topic_post_id`, `user_id`) VALUES
	(3, 3),
	(5, 3),
	(15, 3),
	(16, 3),
	(16, 4);

-- Listage des données de la table squadforge.user : ~2 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `pseudo`, `auto_play_gifs`) VALUES
	(3, 'basile08@hotmail.fr', '[]', '$2y$13$PFkqOIh3ZRbIx6totl7OE.aHyfWw9YQcb7ZqN.XeBtsr1YR/lZDiK', 0, 'basile', 0),
	(4, 'stgr', '[]', 'dfhydhy', 0, 'bot324', 1);

-- Listage des données de la table squadforge.user_game : ~4 rows (environ)
INSERT INTO `user_game` (`user_id`, `game_id`) VALUES
	(3, 1),
	(3, 2),
	(3, 5);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
