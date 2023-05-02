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
INSERT INTO `game` (`id`, `genre_id`, `title`, `editor`, `publish_date`, `description`, `color`, `logo`, `banner`, `font_color`) VALUES
	(1, 1, 'Overwatch', 'Blizzard', '2017-05-01 21:16:23', 'FPS dynamique et efficace', '#ff9b00', 'overwatchLogo.png', NULL, 'white'),
	(2, 1, 'Call of Duty', 'Activision', '2002-05-01 21:16:54', 'FPS connu ', '#74c814', 'callOfDutyLogo.png', 'codBanner.jpg', 'white'),
	(3, 2, 'Isaac', 'Edmund McMillen', '2003-05-01 23:59:12', 'Oulah', 'Brown', NULL, NULL, NULL),
	(4, 1, 'Valorant', 'Riot Games', '2021-05-02 01:41:56', 'Fps mélange entre Overwatch et Counter Strike', '#ff4655', 'valorantLogo.png', 'valorantBanner.jpg', 'white'),
	(5, 1, 'PUBG', 'Krafton', '2016-05-02 02:52:53', 'Battle royal pas ouf', '#ffd632', 'pubgLogo.jpeg', 'pubgBanner.jpg', 'white');

-- Listage des données de la table squadforge.genre : ~2 rows (environ)
INSERT INTO `genre` (`id`, `name`) VALUES
	(1, 'FPS'),
	(2, 'indie');

-- Listage des données de la table squadforge.messenger_messages : ~0 rows (environ)

-- Listage des données de la table squadforge.user : ~1 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `pseudo`) VALUES
	(3, 'basile08@hotmail.fr', '[]', '$2y$13$PFkqOIh3ZRbIx6totl7OE.aHyfWw9YQcb7ZqN.XeBtsr1YR/lZDiK', 0, 'basile');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
