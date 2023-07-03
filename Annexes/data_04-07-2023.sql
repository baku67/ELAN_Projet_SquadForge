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

-- Listage des données de la table squadforge.candidature : ~0 rows (environ)
DELETE FROM `candidature`;

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
	(21, 3, '2023-05-18 01:09:14', 'salope'),
	(22, 3, '2023-05-18 01:09:25', 'enfoiré'),
	(23, 3, '2023-05-18 01:09:32', 'bitch'),
	(25, 3, '2023-07-03 23:42:12', 'dfvdfv');

-- Listage des données de la table squadforge.doctrine_migration_versions : ~0 rows (environ)
DELETE FROM `doctrine_migration_versions`;
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230430131901', '2023-05-01 19:46:44', 43),
	('DoctrineMigrations\\Version20230430154643', '2023-05-01 19:46:44', 8),
	('DoctrineMigrations\\Version20230527014530', '2023-05-27 03:46:00', 94);

-- Listage des données de la table squadforge.favoris : ~8 rows (environ)
DELETE FROM `favoris`;
INSERT INTO `favoris` (`user_id`, `game_id`) VALUES
	(3, 1),
	(3, 2),
	(3, 3),
	(3, 4),
	(3, 5),
	(3, 6),
	(7, 1),
	(7, 2),
	(7, 3),
	(7, 4);

-- Listage des données de la table squadforge.game : ~6 rows (environ)
DELETE FROM `game`;
INSERT INTO `game` (`id`, `genre_id`, `title`, `editor`, `publish_date`, `description`, `color`, `logo`, `banner`, `font_color`, `tiny_logo`, `sub_banner`, `nbr_max_places`) VALUES
	(1, 1, 'Overwatch', 'Blizzard', '2017-05-01 21:16:23', 'FPS dynamique et efficace', '#ff9b00', 'overwatchLogo.png', 'overwatchBanner.jpg', 'white', 'overwatch1.png', 'overwatchHeaderBg.jpg', 0),
	(2, 1, 'Call of Duty', 'Activision', '2002-05-01 21:16:54', 'FPS connu ', '#74c814', 'callOfDutyLogo.png', 'codBanner.jpg', 'white', 'cod1.png', 'callofdutyHeaderBg.jpg', 0),
	(3, 2, 'Isaac', 'Edmund McMillen', '2003-05-01 23:59:12', 'Oulah', '#e24f37', 'isaacLogo.jpg', 'isaacBanner.jpg', 'white', 'isaac1.png', 'overwatchHeaderBg.jpg', 0),
	(4, 1, 'Valorant', 'Riot Games', '2021-05-02 01:41:56', 'Fps mélange entre Overwatch et Counter Strike', '#ff4655', 'valorantLogo.png', 'valorantBanner.jpg', 'white', 'valorant1.png', 'valorantHeaderBg.jpg', 0),
	(5, 3, 'PUBG', 'Krafton', '2016-05-02 02:52:53', 'Battle royal pas ouf', '#ffd632', 'pubgLogo.jpeg', 'pubgBanner.jpg', 'white', 'pubg1.png', 'pubgHeaderBg.jpg', 0),
	(6, 4, 'League of Legend', 'Riot Games', '2023-07-04 00:15:46', 'Bah c\'est lol', '#d4af61', 'lolLogo.png', 'lolBanner.jpg', 'white', 'lol1.jpg', 'lolHeaderBg.jpg', 0);

-- Listage des données de la table squadforge.genre : ~4 rows (environ)
DELETE FROM `genre`;
INSERT INTO `genre` (`id`, `name`) VALUES
	(1, 'FPS'),
	(2, 'indie'),
	(3, 'Battle Royal'),
	(4, 'Battle Arena');

-- Listage des données de la table squadforge.group : ~1 rows (environ)
DELETE FROM `group`;
INSERT INTO `group` (`id`, `leader_id`, `game_id`, `title`, `description`, `nbr_places`, `creation_date`, `restriction_18`, `restriction_lang`, `status`, `img_url`, `restriction_mic`, `candidature_description`, `restriction_img_proof`) VALUES
	(35, 3, 4, 'Team Number One', 'voila on est la team number One on est trop fort rejoins nous', 5, '2023-06-15 23:53:20', 1, 'fr', 'public', NULL, 1, 'IL FAUT AJOUTER LE CHAMP DANS LE TYPE CREATION', 0),
	(36, 3, 1, 'La team overwatch', 'fzefzef', 2, '2023-06-21 21:39:31', 0, 'fr', 'hidden', NULL, 0, NULL, 0),
	(37, 3, 2, 'La team du samedi', 'On joue tout les samedi de 20h à minuit, lapin=kick', 4, '2023-06-26 21:45:23', 1, 'fr', 'public', NULL, 1, NULL, 1),
	(38, 3, 5, 'Team PUBG du dimanche', 'On joue le dimanche aprèm pour le fun', 3, '2023-06-30 01:14:35', 1, 'fr', 'public', NULL, 1, NULL, 0),
	(39, 3, 6, 'Team lol', 'C\'est la team mdr lol', 5, '2023-07-04 01:03:57', 1, 'en', 'public', NULL, 0, NULL, 0);

-- Listage des données de la table squadforge.group_answer : ~0 rows (environ)
DELETE FROM `group_answer`;

-- Listage des données de la table squadforge.group_blacklist : ~0 rows (environ)
DELETE FROM `group_blacklist`;
INSERT INTO `group_blacklist` (`group_id`, `user_id`) VALUES
	(37, 7);

-- Listage des données de la table squadforge.group_question : ~9 rows (environ)
DELETE FROM `group_question`;
INSERT INTO `group_question` (`id`, `groupe_id`, `text`, `required`) VALUES
	(20, 35, 'Est-tu un rageu un peu ?', 0),
	(21, 35, 'Entre le mot caché dans la description pour prouver que tu as lu', 1),
	(22, 35, 'Question 4 non requise ', 0),
	(25, 37, 'Qu\'est-ce que tu joues ?', 1),
	(26, 37, 'Quel jour joue-t-on ?', 1),
	(27, 37, 'TryHarder ou fun/casual ? (Pas de piège)', 0),
	(30, 36, 'Test 1', 1),
	(36, 38, 'Test', 1);

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
	(37, 'Test ajout média validation etc', '2023-05-18 04:02:08', '646587209781d_1684375328.jpg', 'open', 'validated', 3, 4),
	(38, 'non validé par modo snirf', '2023-05-18 04:51:43', '646592bf97980_1684378303.jpg', 'open', 'refused', 3, 4),
	(39, 'test upload media sa as a', '2023-05-18 05:02:26', '646595425dd82_1684378946.png', 'open', 'validated', 3, 3),
	(40, 'Test upload media en attente', '2023-05-18 05:35:57', '64659d1d9551c_1684380957.png', 'open', 'refused', 3, 2),
	(41, 'Test upload media gif 2Mo', '2023-05-26 01:24:50', '64659f26349ad_1684381478.gif', 'open', 'validated', 3, 2),
	(42, 'test topic test topic  test topic', '2023-06-15 21:03:27', '646673edac499_1684435949.jpg', 'open', 'validated', 3, 4),
	(43, 'Retest gif upload overwatch gif', '2023-05-26 01:05:36', '646fe9944d7af_1685055892.gif', 'closed', 'validated', 3, 1);

-- Listage des données de la table squadforge.media_post : ~21 rows (environ)
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
	(11, 3, 15, 'Test', '2023-05-17 22:03:36'),
	(12, 3, 30, 'Y\'a bois isaac', '2023-06-07 01:50:21'),
	(13, 3, 30, 'Y\'a boy isaac*', '2023-06-07 01:50:47'),
	(14, 3, 30, 'Jdjd', '2023-06-07 01:50:52'),
	(15, 3, 30, '*****', '2023-06-07 01:51:06'),
	(16, 3, 30, 'Test', '2023-06-12 23:57:07'),
	(17, 3, 30, 'Hhhh', '2023-06-12 23:57:15'),
	(18, 3, 41, 'wvdsvqsdv', '2023-06-15 21:27:00'),
	(19, 3, 42, 'vsvdsdv', '2023-06-22 21:09:42'),
	(20, 7, 42, 'test post message upvote notif', '2023-06-22 21:24:31'),
	(21, 3, 42, 'MOIII', '2023-06-22 22:55:12'),
	(22, 7, 30, 'test', '2023-07-04 01:24:41');

-- Listage des données de la table squadforge.media_post_like : ~2 rows (environ)
DELETE FROM `media_post_like`;
INSERT INTO `media_post_like` (`id`, `user_id`, `media_post_id`, `state`) VALUES
	(52, 7, 18, 'upvote'),
	(60, 3, 20, 'upvote'),
	(61, 7, 21, 'upvote'),
	(62, 7, 19, 'downvote');

-- Listage des données de la table squadforge.media_upvotes : ~16 rows (environ)
DELETE FROM `media_upvotes`;
INSERT INTO `media_upvotes` (`media_id`, `user_id`) VALUES
	(3, 3),
	(14, 3),
	(15, 3),
	(25, 3),
	(29, 3),
	(30, 3),
	(30, 7),
	(30, 8),
	(31, 3),
	(31, 8),
	(37, 3),
	(39, 3),
	(41, 7),
	(41, 8),
	(42, 3),
	(42, 7),
	(43, 3);

-- Listage des données de la table squadforge.membre_group : ~7 rows (environ)
DELETE FROM `membre_group`;
INSERT INTO `membre_group` (`group_id`, `user_id`) VALUES
	(35, 3),
	(36, 3),
	(36, 7),
	(37, 3),
	(38, 3),
	(38, 7),
	(39, 3);

-- Listage des données de la table squadforge.messenger_messages : ~0 rows (environ)
DELETE FROM `messenger_messages`;

-- Listage des données de la table squadforge.notation : ~5 rows (environ)
DELETE FROM `notation`;
INSERT INTO `notation` (`id`, `user_id`, `game_id`, `note`) VALUES
	(9, 8, 4, 3),
	(25, 3, 4, 4),
	(41, 3, 5, 4),
	(43, 3, 1, 4),
	(44, 3, 3, 3),
	(45, 3, 2, 4),
	(46, 3, 6, 4),
	(47, 7, 4, 4),
	(48, 7, 2, 4);

-- Listage des données de la table squadforge.notification : ~21 rows (environ)
DELETE FROM `notification`;
INSERT INTO `notification` (`id`, `user_id`, `text`, `date_creation`, `seen`, `link`, `clicked`, `type`, `type_id`, `type_nbr`) VALUES
	(172, 8, '"basile2" a quitté la team "Team Number One"', '2023-06-21 21:17:54', 1, 'http://127.0.0.1:8001/groupDetails/35/172', 1, NULL, NULL, NULL),
	(208, 8, '"basile2" a intégré la team "Team Number One', '2023-06-26 21:59:54', 1, 'http://127.0.0.1:8000/groupDetails/35/208', 0, NULL, NULL, NULL),
	(210, 8, '"basile2" a quitté la team "Team Number One"', '2023-06-26 22:00:05', 1, 'http://127.0.0.1:8000/groupDetails/35/210', 0, NULL, NULL, NULL),
	(235, 8, 'Vous avez été expulsé de la team "Team Number One"', '2023-06-29 22:16:16', 1, 'http://127.0.0.1:8000/groupDetails/35/235', 1, NULL, NULL, NULL),
	(236, 3, '"basile3" a quitté la team "Team Number One"', '2023-06-29 22:16:16', 1, 'http://127.0.0.1:8000/groupDetails/35/236', 1, NULL, NULL, NULL),
	(237, 3, 'Nouvelle candidature de "basile2" pour votre team "La team overwatch"', '2023-06-29 22:23:53', 1, 'http://127.0.0.1:8000/candidatureDetails/77/237', 1, NULL, NULL, NULL),
	(239, 3, 'Nouvelle candidature de "basile2" pour votre team "Team PUBG du dimanche"', '2023-06-30 01:16:43', 1, 'http://192.168.0.24:8000/candidatureDetails/78/239', 1, NULL, NULL, NULL),
	(242, 3, 'Nouvelle candidature de "basile2" pour votre team "Team Number One"', '2023-07-03 00:25:48', 1, 'http://192.168.0.24:8000/candidatureDetails/79/242', 1, NULL, NULL, NULL),
	(245, 3, '"basile2" a quitté la team "La team du samedi"', '2023-07-03 00:45:08', 1, 'http://192.168.0.24:8000/groupDetails/37/245', 1, NULL, NULL, NULL),
	(246, 3, 'Votre média "M\'en on blk black nn" a été approuvé par la modération', '2023-07-03 00:47:17', 1, 'http://192.168.0.24:8000/mediaDetail/46/246', 1, NULL, NULL, NULL),
	(249, 3, 'Votre topic "1er topic League of legend" a été approuvé par la modération', '2023-07-04 01:02:37', 1, 'http://192.168.0.24:8000/topicDetail/36/249', 1, NULL, NULL, NULL),
	(250, 3, 'Nouvelle candidature de "basile2" pour votre team "La team du samedi"', '2023-07-04 01:06:08', 1, 'http://192.168.0.24:8000/candidatureDetails/80/250', 1, NULL, NULL, NULL),
	(252, 3, 'Votre post "MOIII" a été upvoté par <strong style=\'font-size:1.1em;\'>3</strong> personnes', '2023-07-04 01:23:42', 0, 'http://192.168.0.24:8000/mediaDetail/42/252', 0, 'mediaPost', 21, 3),
	(253, 3, 'Votre média "test topic test topic  test topic" a été upvoté', '2023-07-04 01:23:54', 0, 'http://192.168.0.24:8000/mediaDetail/42/253', 0, 'media', 42, 1),
	(254, 3, 'Votre média "Isaac gif media upload kdkd" a été upvoté', '2023-07-04 01:24:32', 0, 'http://192.168.0.24:8000/mediaDetail/30/254', 0, 'media', 30, 1);

-- Listage des données de la table squadforge.post_like : ~9 rows (environ)
DELETE FROM `post_like`;
INSERT INTO `post_like` (`id`, `user_id`, `topic_post_id`, `state`) VALUES
	(6, 3, 18, 'upvote'),
	(7, 3, 19, 'upvote'),
	(8, 3, 3, 'upvote'),
	(14, 3, 14, 'upvote'),
	(41, 3, 21, 'downvote'),
	(42, 3, 22, 'downvote'),
	(47, 7, 20, 'upvote'),
	(48, 7, 7, 'upvote'),
	(56, 7, 19, 'upvote'),
	(57, 7, 18, 'upvote');

-- Listage des données de la table squadforge.report : ~2 rows (environ)
DELETE FROM `report`;
INSERT INTO `report` (`id`, `user_reporter_id`, `object_id`, `object_type`, `creation_date`, `report_motif_id`) VALUES
	(4, 3, 42, 'media', '2023-07-03 23:00:54', 4),
	(5, 3, 33, 'topic', '2023-07-03 23:12:11', 1),
	(6, 3, 36, 'topic', '2023-07-04 01:03:00', 6);

-- Listage des données de la table squadforge.report_motif : ~6 rows (environ)
DELETE FROM `report_motif`;
INSERT INTO `report_motif` (`id`, `text`) VALUES
	(1, 'Harcèlement'),
	(3, 'Propos injurieux'),
	(4, 'Racisme/Sexisme/Homophobie'),
	(5, 'Spam'),
	(6, 'Incitation à la haine'),
	(7, 'Contenu illégal (atteinte à la vie privée, etc)');

-- Listage des données de la table squadforge.topic : ~29 rows (environ)
DELETE FROM `topic`;
INSERT INTO `topic` (`id`, `title`, `publish_date`, `status`, `validated`, `game_id`, `user_id`, `first_msg`) VALUES
	(1, 'Je sais pa vou mé', '2023-04-03 23:45:48', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(2, 'Bla bla bla blbla', '2023-05-03 18:57:03', 'closed', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(3, 'test date', '2023-05-04 02:09:02', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(5, 'aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa', '2023-05-04 22:08:47', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(6, 'Test topic formType aa aa aa', '2023-05-04 22:11:34', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(7, 'Test topic formType aa aa', '2023-05-04 22:16:00', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(8, 'Re test form topic valolo avec 5 mots', '2023-05-04 22:16:23', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(9, 'aa aa aa aa aa', '2023-05-04 22:28:58', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(10, 'aa aa aa aa aa', '2023-05-04 22:31:18', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(11, 'Hg BJ non nbb bb', '2023-05-04 23:54:43', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(12, 'dz zd zd dz dz', '2023-05-05 01:20:15', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za '),
	(13, 'zz zz zz zz zz', '2023-05-05 01:30:23', 'closed', 'validated', 2, 3, 'test nouveau 1er message'),
	(14, '1er topic pubg battle royal', '2023-05-05 01:56:39', 'open', 'validated', 5, 3, 'Test premier message pubg'),
	(15, 'Test topic en plus pour passer à 6 Topics', '2023-05-05 15:14:42', 'open', 'validated', 2, 3, 'dzd azad z'),
	(16, 'Au moins 5 mots Isaac test', '2023-05-06 00:59:28', 'open', 'validated', 3, 3, 'Nwnwn'),
	(17, 'A a a a a', '2023-05-06 01:00:07', 'open', 'validated', 3, 3, 'Jwn'),
	(18, 'test topic  test topic  test topic', '2023-05-09 11:11:00', 'open', 'validated', 4, 3, 'zrfezfzef'),
	(19, 'aa aa aa aa aa', '2023-05-17 20:29:00', 'open', 'validated', 4, 3, 'feferf'),
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
	(32, 'e f ef z zef zef', '2023-06-19 00:05:22', 'open', 'validated', 4, 3, 'fzefze'),
	(33, 'Test New topic test t\'es', '2023-06-28 01:37:57', 'open', 'validated', 4, 3, 'Test'),
	(34, 'dd ds d d d d', '2023-07-03 18:41:45', 'open', 'waiting', 2, 3, 'J'),
	(35, 'j j j j j', '2023-07-03 18:42:14', 'open', 'waiting', 2, 3, 'Fe'),
	(36, '1er topic League of legend', '2023-07-04 01:02:37', 'open', 'validated', 6, 3, 'Ddd');

-- Listage des données de la table squadforge.topic_post : ~22 rows (environ)
DELETE FROM `topic_post`;
INSERT INTO `topic_post` (`id`, `user_id`, `topic_id`, `text`, `publish_date`, `parent_id`, `topic_post_id`) VALUES
	(1, 3, 14, 'test message', '2023-05-05 17:36:59', NULL, NULL),
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
	(17, 3, 15, 'test apres verif si closed topics', '2023-05-06 05:17:41', NULL, NULL),
	(18, 3, 11, 'Test post', '2023-05-08 23:26:20', NULL, NULL),
	(19, 3, 11, 'Bien dit !', '2023-05-10 01:03:04', NULL, NULL),
	(20, 3, 15, 'Merde ****** ****** Merde', '2023-05-17 22:01:27', NULL, NULL),
	(21, 3, 14, '****** de ***** fils de ****', '2023-05-18 01:18:46', NULL, NULL),
	(22, 3, 26, 'Test ajout message alors que topic pas.validated', '2023-05-18 03:58:49', NULL, NULL);

-- Listage des données de la table squadforge.user : ~3 rows (environ)
DELETE FROM `user`;
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `pseudo`, `auto_play_gifs`) VALUES
	(3, 'basile08@hotmail.fr', '["ROLE_MODO"]', '$2y$13$PFkqOIh3ZRbIx6totl7OE.aHyfWw9YQcb7ZqN.XeBtsr1YR/lZDiK', 0, 'basile', 0),
	(7, 'basile09@hotmail.fr', '[]', '$2y$13$VQhOrGgKRQ1rdIoNWVld9eANrYSBAYbuGOCQQ5rHYbMfzBfpv4Sxa', 0, 'basile2', NULL),
	(8, 'basile10@hotmail.fr', '[]', '$2y$13$aQvLMfshYRre.ij4LrLkiO9prlAoISs8gqAItt.aC976djlHg5FdK', 0, 'basile3', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
