INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `pseudo`, `auto_play_gifs`, `status`, `end_date_status`, `nbr_censures`) VALUES
	(3, 'basile08@hotmail.fr', '["ROLE_MODO"]', '$2y$13$PFkqOIh3ZRbIx6totl7OE.aHyfWw9YQcb7ZqN.XeBtsr1YR/lZDiK', 1, 'basile', 1, '', '2023-07-22 21:46:56', 20),
	(7, 'basile09@hotmail.fr', '[]', '$2y$13$VQhOrGgKRQ1rdIoNWVld9eANrYSBAYbuGOCQQ5rHYbMfzBfpv4Sxa', 1, 'basile2', NULL, 'muted', '2023-07-25 05:33:42', 4),
	(8, 'basile10@hotmail.fr', '[]', '$2y$13$aQvLMfshYRre.ij4LrLkiO9prlAoISs8gqAItt.aC976djlHg5FdK', 1, 'basile3', 1, NULL, NULL, 0),
	(95, 'basile100@hotmail.fr', '[]', '$2y$13$JxJQQGHr6.N.eXLLAQ1PVufgrkOEQ6ux76wEii1CpMa1/SgdU94We', 1, 'basile100', 1, NULL, NULL, 0),
	(97, 'basile1000@hotmail.fr', '[]', '$2y$13$3HEhG1fVIir8OERh24La/unF9ivkRwmYZyxZ0lSpTU/IFqQtcpdh6', 1, 'basile1000', 1, NULL, NULL, 0),
	(98, 'basile22@hotmail.fr', '[]', '$2y$13$1H8ZNrtzJfOuil.Fqy1gg.Nh2AG1wSphWL3opDrhXvQ1TPUGrAn6O', 1, 'basile22', 1, NULL, NULL, 0),
	(99, 'basile00@hotmail.fr', '[]', '$2y$13$Hl7hpUl.gu6iBmHVCaSBo.4jAKqFpt4Z/yXrWKJj1be1kjdPDYEjm', 0, 'basile00', 1, NULL, NULL, 0);


INSERT INTO `genre` (`id`, `name`, `slug`) VALUES
	(1, 'FPS', 'fps'),
	(2, 'indie', 'indie'),
	(3, 'Battle Royal', 'battle-royal'),
	(4, 'MOBA', 'moba');



INSERT INTO `game` (`id`, `genre_id`, `title`, `editor`, `publish_date`, `description`, `color`, `logo`, `banner`, `font_color`, `tiny_logo`, `sub_banner`, `nbr_places`, `site_logo`, `show_icon_search_page`, `slug`) VALUES
	(1, 1, 'Overwatch', 'Blizzard', '2017-05-01 21:16:23', 'FPS dynamique et efficace', '#ff9b00', 'overwatchLogo.png', 'overwatchBanner.jpg', '#141414', 'overwatch1.png', 'overwatchHeaderBg.jpg', 0, 'logoSquadForge_overwatch.png', 1, 'overwatch'),
	(2, 1, 'Call of Duty', 'Activision', '2002-05-01 21:16:54', 'FPS connu ', '#74c814', 'callOfDutyLogo.png', 'codBanner.jpg', '#141414', 'cod1.png', 'callofdutyHeaderBg.jpg', 0, 'logoSquadForge_cod.png', 0, 'call-of-duty'),
	(3, 2, 'Isaac', 'Edmund McMillen', '2003-05-01 23:59:12', 'Oulah', '#e24f37', 'isaacLogo.jpg', 'isaacBanner.jpg', 'white', 'isaac1.png', 'overwatchHeaderBg.jpg', 0, 'logoSquadForge_White_Rogned.png', 0, 'isaac'),
	(4, 1, 'Valorant', 'Riot Games', '2021-05-02 01:41:56', 'Fps mélange entre Overwatch et Counter Strike', '#ff4655', 'valorantLogo.png', 'valorantBanner.jpg', '#141414', 'valorant1.png', 'valorantHeaderBg.jpg', 0, 'logoSquadForge_valorant.png', 1, 'valorant'),
	(5, 3, 'PUBG', 'Krafton', '2016-05-02 02:52:53', 'Battle royal pas ouf', '#ffd632', 'pubgLogo.jpeg', 'pubgBanner.jpg', '#141414', 'pubg1.png', 'pubgHeaderBg.jpg', 0, 'logoSquadForge_pubg.png', 0, 'pubg'),
	(6, 4, 'League of Legend', 'Riot Games', '2023-07-04 00:15:46', 'Bah c''est lol', '#d4af61', 'lolLogo.jpg', 'lolBanner.jpg', '#141414', 'lol1.png', 'lolHeaderBg.jpg', 0, 'logoSquadForge_lol.png', 1, 'league-of-legend'),
	(10, 4, 'Dota 2', 'Valve', '2013-09-04 14:43:13', 'Description jeu', '#ae3018', '', 'dota2Banner.png', 'white', 'dota1.png', 'dota2HeaderBg.png', 0, 'logoSquadForge_dota2.png', 1, 'dota-2');



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



INSERT INTO `group` (`id`, `leader_id`, `game_id`, `title`, `description`, `nbr_places`, `creation_date`, `restriction_18`, `restriction_lang`, `status`, `img_url`, `restriction_mic`, `candidature_description`, `restriction_img_proof`, `candidature_txt`, `slug`) VALUES
	(36, 7, 1, 'La team overwatch', 'fzefzef', 2, '2023-06-21 21:39:31', 0, 'fr', 'hidden', 'test1.jpg', 0, NULL, 0, NULL, 'la-team-overwatch'),
	(38, 3, 5, 'Team PUBG du dimanche', 'On joue le dimanche aprèm pour le fun', 3, '2023-06-30 01:14:35', 1, 'fr', 'public', 'test2.jpg', 0, NULL, 1, 'Yolo ', 'team-pubg-du-dimanche'),
	(39, 3, 6, 'Team lol', 'C''est la team mdr lol', 5, '2023-07-04 01:03:57', 1, 'en', 'public', 'test3.png', 1, NULL, 1, 'hello\r\n', 'team-lol'),
	(47, 7, 4, 'Valo team numéro One', 'Ola', 6, '2023-07-08 06:14:54', 1, NULL, 'public', 'test4.png', 0, NULL, 1, NULL, 'valo-team-numero-one'),
	(49, 3, 2, 'fzefzefzef', 'zefzefzef', 2, '2023-08-22 14:57:39', 1, NULL, 'public', 'test5.jpg', 1, NULL, 1, NULL, 'fzefzefzef'),
	(50, 3, 6, 'fsefse', 'Miaou miaou miaou miaou mioau ', 4, '2023-08-30 20:24:05', 1, 'fr', 'public', 'test6.jpg', 1, NULL, 0, NULL, 'fsefse'),
	(51, 7, 2, 'HELOO', 'efsefseqzdqz dqz dqz', 5, '2023-08-30 20:25:48', 1, 'fr', 'public', 'test7.jpg', 1, NULL, 1, 'fsefse', 'helloo'),
	(52, 3, 4, 'Team Valorant chill', 'Sessions : \r\n - Vendredi 18h-00h\r\n - Samedi 20h-00h', 6, '2023-09-04 00:20:12', 1, NULL, 'public', 'test8.jpg', 1, NULL, 0, NULL, 'team-valorant-chill'),
	(53, 3, 1, 'Team Overwatch 01', 'TEAM NUMBER ONE, sessions le samedi soir', 5, '2023-09-04 00:50:31', 1, NULL, 'public', 'test9.jpg', 1, NULL, 0, NULL, 'team-overwatch-01');



INSERT INTO `group_question` (`id`, `groupe_id`, `text`, `required`) VALUES
	(30, 36, 'Test 1', 1),
	(36, 38, 'Test', 1),
	(38, 39, 'Question 1', 1);


INSERT INTO `group_session` (`id`, `team_id`, `date_start`, `date_end`, `title`, `comfirm_needed`) VALUES
	(4, 47, '2023-07-26 20:25:00', '2023-07-26 21:25:00', 'hrhrtyh', 0),
	(5, 47, '2023-07-27 09:10:00', '2023-07-28 09:10:00', 'ergheg', 1),
	(8, 39, '2023-07-27 13:33:00', '2023-07-27 13:53:00', 'bfgbfgbf', 0),
	(9, 39, '2023-07-28 13:44:00', '2023-07-28 17:34:00', 'cbvcvbcvb', NULL),
	(10, 39, '2023-07-29 13:41:00', '2023-07-29 17:41:00', 'test ajout session', NULL),
	(11, 47, '2023-07-31 16:26:00', '2023-07-31 20:26:00', 'olaaa', NULL),
	(13, 39, '2023-08-22 10:00:00', '2023-08-30 10:00:00', 'rzeqffeqf', NULL),
	(14, 49, '2023-08-23 15:10:00', '2023-08-23 17:10:00', 'fesfsefs', NULL);



INSERT INTO `group_session_dispo` (`id`, `session_id`, `member_id`, `disponibility`) VALUES
	(3, 4, 3, 'perhaps'),
	(4, 4, 8, 'dispo'),
	(6, 5, 3, 'notdispo');




INSERT INTO `media` (`id`, `title`, `publish_date`, `url`, `status`, `validated`, `user_id`, `game_id`, `slug`) VALUES
	(1, 'Regardez donc ce 360° no scope, il doit etre dégouterrrr', '2023-05-09 08:59:47', 'valorant.gif', 'open', 'validated', 3, 4, 'regardez-donc-ce-360°-no-scope,-il-doit-etre-degouterrrr'),
	(2, 'test topic test topic  test topic', '2023-05-09 11:08:54', 'valorant2.gif', 'open', 'validated', 3, 4, 'test-topic-test-topic--test-topic'),
	(3, 'test ajout media sans img', '2023-05-09 11:11:23', 'valorant3.gif', 'open', 'validated', 3, 4, 'test-ajout-media-sans-img'),
	(13, 'Test upload Gif Overwatch Test', '2023-05-10 01:54:11', 'overwatch2.gif', 'open', 'validated', 3, 1, 'test-upload-gif-overwatch-Test'),
	(14, 'Test upload gif2 Overwatch Pog', '2023-05-10 01:56:42', 'overwatch.gif', 'open', 'validated', 3, 1, 'test-upload-gif2-overwatch-Pog'),
	(15, 'test upload Gif Call of Duty', '2023-05-10 02:00:56', 'cod2.gif', 'open', 'validated', 3, 2, 'test-upload-gif-call-of-duty'),
	(16, 'Test upload meme Call of', '2023-05-10 02:02:12', 'cod.gif', 'open', 'validated', 3, 2, 'test-upload-meme-call-of'),
	(17, 'test topic test topic  test topicc', '2023-05-10 02:11:54', 'valorant2.gif', 'open', 'validated', 3, 4, 'test-topic-test-topic--test-topicc'),
	(20, 'Test upload im toast Isaac.', '2023-05-10 03:08:54', 'isaac.jpg', 'open', 'validated', 3, 3, 'test-upload-im-toast-isaac.'),
	(21, 'Upload PUBG clip from mobile', '2023-05-11 02:47:04', '645c3b0808a20_1683766024.gif', 'open', 'validated', 3, 5, 'upload-pubg-clip-from-mobile'),
	(22, 'Test pubg upload media again', '2023-05-11 02:50:14', '645c3bc60ed31_1683766214.gif', 'open', 'validated', 3, 5, 'test-pubg-upload-media-again'),
	(23, 'Re test upload PUBG media', '2023-05-11 02:52:30', '645c3c4e24ea3_1683766350.gif', 'open', 'validated', 3, 5, 're-test-upload-pubg-media'),
	(24, 'Upload n4 gif pubg test', '2023-05-11 02:55:47', '645c3d1339ee3_1683766547.gif', 'open', 'validated', 3, 5, 'upload-n4-gif-pubg-test'),
	(25, 'Upload 5gif pubg gif os', '2023-05-11 02:57:03', '645c3d5f0e346_1683766623.gif', 'open', 'validated', 3, 5, 'upload-5gif-pubg-gif-os'),
	(26, 'Test upoad N2 pibg uolo', '2023-05-11 02:58:00', '645c3d98a16c5_1683766680.gif', 'open', 'validated', 3, 5, 'test-upoad-n2-pibg-uolo'),
	(27, 'PUBG uoplad IMG media N1', '2023-05-11 02:58:50', '645c3dca01159_1683766730.gif', 'open', 'validated', 3, 5, 'pubg-uoplad-img-media-n1'),
	(28, 'PUBG uoplad media original n0', '2023-05-11 03:00:18', '645c3e227000b_1683766818.gif', 'open', 'validated', 3, 5, 'pubg-uoplad-media-original-n0'),
	(29, 'Test upload media overwatcj dndnd d', '2023-05-14 23:10:03', '64614e2b395a0_1684098603.gif', 'open', 'validated', 3, 1, 'test-upload-media-overwatcj-dndnd-d'),
	(30, 'Isaac gif media upload kdkd', '2023-05-14 23:13:01', '64614edd486d2_1684098781.gif', 'open', 'validated', 3, 3, 'isaac-gif-media-upload-kdkd'),
	(31, 'Test upload Valolo gif fig', '2023-05-15 23:15:17', '6462a0e5a1fb4_1684185317.gif', 'open', 'validated', 3, 4, 'test-upload-valolo-gif-fig'),
	(38, 'non validé par modo snirf', '2023-05-18 04:51:43', '646592bf97980_1684378303.jpg', 'open', 'refused', 3, 4, 'non-valide-par-modo-snirf'),
	(39, 'test upload media sa as a', '2023-05-18 05:02:26', '646595425dd82_1684378946.png', 'open', 'validated', 3, 3, 'test-upload-media-sa-as-a'),
	(40, 'Test upload media en attente', '2023-05-18 05:35:57', '64659d1d9551c_1684380957.png', 'open', 'refused', 3, 2, 'test-upload-media-en-attente'),
	(41, 'Test upload media gif 2Mo', '2023-05-26 01:24:50', '64659f26349ad_1684381478.gif', 'open', 'validated', 3, 2, 'test-upload-media-gif-2mo'),
	(43, 'Retest gif upload overwatch gif', '2023-05-26 01:05:36', '646fe9944d7af_1685055892.gif', 'closed', 'validated', 3, 1, 'retest-gif-upload-overwatch-gif'),
	(74, 'test topic test topic  test topiccc', '2023-07-19 22:35:14', '64b8490234ab6_1689798914.jpg', 'open', 'waiting', 3, 4, 'test-topic-test-topic--test-topiccc');



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
	(12, 3, 30, 'Y''a bois isaac', '2023-06-07 01:50:21'),
	(13, 3, 30, 'Y''a boy isaac*', '2023-06-07 01:50:47'),
	(14, 3, 30, 'Jdjd', '2023-06-07 01:50:52'),
	(15, 3, 30, '*****', '2023-06-07 01:51:06'),
	(16, 3, 30, 'Test', '2023-06-12 23:57:07'),
	(17, 3, 30, 'Hhhh', '2023-06-12 23:57:15'),
	(18, 3, 41, 'wvdsvqsdv', '2023-06-15 21:27:00'),
	(22, 7, 30, 'test', '2023-07-04 01:24:41');



INSERT INTO `media_post_like` (`id`, `user_id`, `media_post_id`, `state`) VALUES
	(52, 7, 18, 'upvote');




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


INSERT INTO `membre_group` (`group_id`, `user_id`) VALUES
	(36, 7),
	(38, 3),
	(38, 7),
	(39, 3),
	(47, 3),
	(47, 7),
	(47, 8),
	(49, 3);


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


INSERT INTO `notification` (`id`, `user_id`, `text`, `date_creation`, `seen`, `link`, `clicked`, `type`, `type_id`, `type_nbr`) VALUES
	(408, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est dispo pour la session "hrhrtyh" qui a lieu le ', '2023-07-26 21:03:23', 1, NULL, 0, NULL, NULL, NULL),
	(409, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est dispo pour la session "hrhrtyh" qui a lieu le ', '2023-07-26 21:03:42', 1, 'http://127.0.0.1:8000/groupDetails/47/409', 0, NULL, NULL, NULL),
	(410, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le ', '2023-07-26 21:07:45', 1, 'http://127.0.0.1:8000/groupDetails/47/410', 0, NULL, NULL, NULL),
	(411, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 2023/07/26', '2023-07-26 21:08:45', 1, 'http://127.0.0.1:8000/groupDetails/47/411', 0, NULL, NULL, NULL),
	(412, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 08:07', '2023-07-26 21:09:24', 1, 'http://127.0.0.1:8000/groupDetails/47/412', 0, NULL, NULL, NULL),
	(413, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 21:09:38', 1, 'http://127.0.0.1:8000/groupDetails/47/413', 0, NULL, NULL, NULL),
	(414, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:40:38', 1, 'http://127.0.0.1:8000/groupDetails/47/414', 0, NULL, NULL, NULL),
	(415, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:40:45', 1, 'http://127.0.0.1:8000/groupDetails/47/415', 0, NULL, NULL, NULL),
	(416, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:40:51', 1, 'http://127.0.0.1:8000/groupDetails/47/416', 0, NULL, NULL, NULL),
	(417, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:48:30', 1, 'http://127.0.0.1:8000/groupDetails/47/417', 0, NULL, NULL, NULL),
	(418, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:52:50', 1, 'http://127.0.0.1:8000/groupDetails/47/418', 0, NULL, NULL, NULL),
	(419, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:52:53', 1, 'http://127.0.0.1:8000/groupDetails/47/419', 0, NULL, NULL, NULL),
	(420, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:53:55', 1, 'http://127.0.0.1:8000/groupDetails/47/420', 0, NULL, NULL, NULL),
	(421, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 22:57:46', 1, 'http://127.0.0.1:8000/groupDetails/47/421', 0, NULL, NULL, NULL),
	(422, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 23:01:02', 1, 'http://127.0.0.1:8000/groupDetails/47/422', 0, NULL, NULL, NULL),
	(423, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 23:01:05', 1, 'http://127.0.0.1:8000/groupDetails/47/423', 1, NULL, NULL, NULL),
	(424, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-26 23:33:16', 1, 'http://127.0.0.1:8000/groupDetails/47/424', 0, NULL, NULL, NULL),
	(425, 7, 'Nouvelle candidature de "basile3" pour votre team "Valo team numéro One"', '2023-07-26 23:36:52', 1, 'http://127.0.0.1:8000/candidatureDetails/83/425', 1, NULL, NULL, NULL),
	(426, 8, 'Votre candidature pour rejoindre la team "Valo team numéro One" a été acceptée.', '2023-07-26 23:37:14', 1, 'http://127.0.0.1:8000/groupDetails/47/426', 1, NULL, NULL, NULL),
	(428, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile3" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 00:46:24', 1, 'http://127.0.0.1:8000/groupDetails/47/428', 0, NULL, NULL, NULL),
	(429, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 01:06:42', 1, 'http://127.0.0.1:8000/groupDetails/47/429', 0, NULL, NULL, NULL),
	(430, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 01:06:54', 1, 'http://127.0.0.1:8000/groupDetails/47/430', 0, NULL, NULL, NULL),
	(431, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 01:07:25', 1, 'http://127.0.0.1:8000/groupDetails/47/431', 0, NULL, NULL, NULL),
	(432, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:00:21', 0, 'http://127.0.0.1:8000/groupDetails/47/432', 0, NULL, NULL, NULL),
	(433, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:00:37', 0, 'http://127.0.0.1:8000/groupDetails/47/433', 0, NULL, NULL, NULL),
	(435, 8, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Une session a été planifié "ergheg". Confirmation demandée par le leader de la team.', '2023-07-27 09:10:14', 0, 'http://127.0.0.1:8000/groupDetails/47/435', 0, NULL, NULL, NULL),
	(437, 8, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Une session a été planifié "sdvsdvdsd". ', '2023-07-27 09:10:30', 0, 'http://127.0.0.1:8000/groupDetails/47/437', 0, NULL, NULL, NULL),
	(438, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:10:38', 0, 'http://127.0.0.1:8000/groupDetails/47/438', 0, NULL, NULL, NULL),
	(439, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:10:46', 0, 'http://127.0.0.1:8000/groupDetails/47/439', 0, NULL, NULL, NULL),
	(440, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:13:58', 0, 'http://127.0.0.1:8000/groupDetails/47/440', 0, NULL, NULL, NULL),
	(441, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:19:46', 0, 'http://127.0.0.1:8000/groupDetails/47/441', 0, NULL, NULL, NULL),
	(442, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:04', 0, 'http://127.0.0.1:8000/groupDetails/47/442', 0, NULL, NULL, NULL),
	(443, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:09', 0, 'http://127.0.0.1:8000/groupDetails/47/443', 0, NULL, NULL, NULL),
	(444, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:09', 0, 'http://127.0.0.1:8000/groupDetails/47/444', 0, NULL, NULL, NULL),
	(445, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:27', 0, 'http://127.0.0.1:8000/groupDetails/47/445', 0, NULL, NULL, NULL),
	(446, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:27', 0, 'http://127.0.0.1:8000/groupDetails/47/446', 0, NULL, NULL, NULL),
	(447, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:28', 0, 'http://127.0.0.1:8000/groupDetails/47/447', 0, NULL, NULL, NULL),
	(448, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:28', 0, 'http://127.0.0.1:8000/groupDetails/47/448', 0, NULL, NULL, NULL),
	(449, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:58', 0, 'http://127.0.0.1:8000/groupDetails/47/449', 0, NULL, NULL, NULL),
	(450, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:58', 0, 'http://127.0.0.1:8000/groupDetails/47/450', 0, NULL, NULL, NULL),
	(451, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:58', 0, 'http://127.0.0.1:8000/groupDetails/47/451', 0, NULL, NULL, NULL),
	(452, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:59', 0, 'http://127.0.0.1:8000/groupDetails/47/452', 0, NULL, NULL, NULL),
	(453, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:21:59', 0, 'http://127.0.0.1:8000/groupDetails/47/453', 0, NULL, NULL, NULL),
	(454, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:21:59', 0, 'http://127.0.0.1:8000/groupDetails/47/454', 0, NULL, NULL, NULL),
	(455, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:22:00', 0, 'http://127.0.0.1:8000/groupDetails/47/455', 0, NULL, NULL, NULL),
	(456, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:22:00', 0, 'http://127.0.0.1:8000/groupDetails/47/456', 0, NULL, NULL, NULL),
	(457, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:30:44', 0, 'http://127.0.0.1:8000/groupDetails/47/457', 0, NULL, NULL, NULL),
	(458, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:30:56', 0, 'http://127.0.0.1:8000/groupDetails/47/458', 0, NULL, NULL, NULL),
	(459, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:30:56', 0, 'http://127.0.0.1:8000/groupDetails/47/459', 0, NULL, NULL, NULL),
	(460, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:37:42', 0, 'http://127.0.0.1:8000/groupDetails/47/460', 0, NULL, NULL, NULL),
	(461, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:37:47', 0, 'http://127.0.0.1:8000/groupDetails/47/461', 0, NULL, NULL, NULL),
	(462, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:37:47', 0, 'http://127.0.0.1:8000/groupDetails/47/462', 0, NULL, NULL, NULL),
	(463, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:40:04', 0, 'http://127.0.0.1:8000/groupDetails/47/463', 0, NULL, NULL, NULL),
	(464, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:40:08', 0, 'http://127.0.0.1:8000/groupDetails/47/464', 0, NULL, NULL, NULL),
	(465, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:40:08', 0, 'http://127.0.0.1:8000/groupDetails/47/465', 0, NULL, NULL, NULL),
	(466, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 09:44:00', 0, 'http://127.0.0.1:8000/groupDetails/47/466', 0, NULL, NULL, NULL),
	(467, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:44:04', 0, 'http://127.0.0.1:8000/groupDetails/47/467', 0, NULL, NULL, NULL),
	(468, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:46:41', 0, 'http://127.0.0.1:8000/groupDetails/47/468', 0, NULL, NULL, NULL),
	(469, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:46:54', 0, 'http://127.0.0.1:8000/groupDetails/47/469', 0, NULL, NULL, NULL),
	(470, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "ergheg" qui a lieu le 27/07 à 09:07', '2023-07-27 09:46:59', 0, 'http://127.0.0.1:8000/groupDetails/47/470', 0, NULL, NULL, NULL),
	(471, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "sdvsdvdsd" qui a lieu le 30/07 à 09:07', '2023-07-27 09:47:38', 0, 'http://127.0.0.1:8000/groupDetails/47/471', 0, NULL, NULL, NULL),
	(473, 8, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Une session a été annulée "sdvsdvdsd". ', '2023-07-27 10:07:50', 0, 'http://127.0.0.1:8000/groupDetails/47/473', 0, NULL, NULL, NULL),
	(474, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" n''est pas disponible pour la session "ergheg" qui a lieu le 27/07 à 09:07', '2023-07-27 10:12:21', 0, 'http://127.0.0.1:8000/groupDetails/47/474', 0, NULL, NULL, NULL),
	(475, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 10:12:27', 0, 'http://127.0.0.1:8000/groupDetails/47/475', 0, NULL, NULL, NULL),
	(477, 8, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Une session a été planifié "jbgj". ', '2023-07-27 10:17:08', 0, 'http://127.0.0.1:8000/groupDetails/47/477', 0, NULL, NULL, NULL),
	(478, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "jbgj" qui a lieu le 29/07 à 10:07', '2023-07-27 10:32:16', 0, 'http://127.0.0.1:8000/groupDetails/47/478', 0, NULL, NULL, NULL),
	(480, 8, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Une session a été annulée "jbgj". ', '2023-07-27 10:33:13', 0, 'http://127.0.0.1:8000/groupDetails/47/480', 0, NULL, NULL, NULL),
	(481, 7, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Le membre "basile" est peut-être disponible pour la session "hrhrtyh" qui a lieu le 26/07 à 08:07', '2023-07-27 10:36:36', 0, 'http://127.0.0.1:8000/groupDetails/47/481', 0, NULL, NULL, NULL),
	(483, 8, '<span style=''font-weight:bold;text-decoration:underline;''>Valo team numéro One</span>: Une session a été planifié "olaaa". ', '2023-07-27 16:26:50', 0, 'http://127.0.0.1:8000/groupDetails/47/483', 0, NULL, NULL, NULL),
	(485, 7, '"basile" a quitté la team "La team overwatch"', '2023-08-22 10:25:23', 0, 'http://127.0.0.1:8000/groupDetails/36/485', 0, NULL, NULL, NULL),
	(486, 7, 'Votre post "jvcjbvbn post" a été upvoté par <strong style=''font-size:1.1em;''>2</strong> personnes', '2023-08-22 16:12:19', 0, 'http://127.0.0.1:8000/topicDetail/15/486', 0, 'topicPost', 26, 2),
	(505, 3, 'Nouvelle candidature de "basileeee" pour votre team "fzefzefzef"', '2023-08-22 15:09:50', 0, 'http://127.0.0.1:8000/candidatureDetails/86/505', 0, NULL, NULL, NULL),
	(508, 3, '<span style=''font-weight:bold;text-decoration:underline;''>fzefzefzef</span>: Le membre "basileeee" est disponible pour la session "fesfsefs" qui a lieu le 23/08 à 03:08', '2023-08-22 15:13:35', 0, 'http://127.0.0.1:8000/groupDetails/49/508', 0, NULL, NULL, NULL),
	(509, 3, '"basileeee" a quitté la team "fzefzefzef"', '2023-08-22 15:14:00', 0, 'http://127.0.0.1:8000/groupDetails/49/509', 0, NULL, NULL, NULL),
	(510, 3, 'Nouvelle candidature de "basileeee" pour votre team "fzefzefzef"', '2023-08-22 15:16:32', 0, 'http://127.0.0.1:8000/candidatureDetails/87/510', 0, NULL, NULL, NULL),
	(512, 3, '<span style=''font-weight:bold;text-decoration:underline;''>fzefzefzef</span>: Le membre "basileeee" est peut-être disponible pour la session "fesfsefs" qui a lieu le 23/08 à 03:08', '2023-08-22 15:20:26', 0, 'http://127.0.0.1:8000/groupDetails/49/512', 0, NULL, NULL, NULL),
	(513, 3, '"basileeee" a quitté la team "fzefzefzef"', '2023-08-22 15:20:41', 0, 'http://127.0.0.1:8000/groupDetails/49/513', 0, NULL, NULL, NULL),
	(514, 3, 'Nouvelle candidature de "basileeee" pour votre team "fzefzefzef"', '2023-08-22 15:22:16', 0, 'http://127.0.0.1:8000/candidatureDetails/88/514', 0, NULL, NULL, NULL),
	(516, 3, '"basileeee" a quitté la team "fzefzefzef"', '2023-08-22 15:23:54', 0, 'http://127.0.0.1:8000/groupDetails/49/516', 0, NULL, NULL, NULL),
	(517, 3, 'Nouvelle candidature de "basileeee" pour votre team "fzefzefzef"', '2023-08-22 15:24:16', 0, 'http://127.0.0.1:8000/candidatureDetails/89/517', 0, NULL, NULL, NULL);


INSERT INTO `report_motif` (`id`, `text`) VALUES
	(1, 'Harcèlement'),
	(3, 'Propos injurieux'),
	(4, 'Racisme/Sexisme/Homophobie'),
	(5, 'Spam'),
	(6, 'Incitation à la haine'),
	(7, 'Contenu illégal (atteinte à la vie privée, etc)');



INSERT INTO `report` (`id`, `user_reporter_id`, `object_id`, `object_type`, `creation_date`, `report_motif_id`) VALUES
	(70, 3, 53, 'topic', '2023-07-19 22:49:29', 3);


-- /!\ Slugs de test
INSERT INTO `topic` (`id`, `title`, `publish_date`, `status`, `validated`, `game_id`, `user_id`, `first_msg`, `slug`) VALUES
	(1, 'Je sais pa vou mé', '2023-04-03 23:45:48', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za ', 'je-sais-pa-vou-mais'),
	(2, 'Bla bla bla blbla', '2023-05-03 18:57:03', 'closed', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za ', 'bla-bla-bla-blbla'),
	(3, 'test date', '2023-05-04 02:09:02', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za ', 'test-date'),
	(5, 'aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa aa', '2023-05-04 22:08:47', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za ', 'aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa-aa'),
	(6, 'Test topic formType aa aa aa', '2023-05-04 22:11:34', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za ', 'test-topic-formtype-aa-aa-aa'),
	(7, 'Test topic formType aa aa', '2023-05-04 22:16:00', 'open', 'validated', 1, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za ', 'test-topic-formtype-aa-aa'),
	(8, 'Re test form topic valolo avec 5 mots', '2023-05-04 22:16:23', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za ', 're-test-form-topic-valolo-avec-5-mots'),
	(9, 'Overwatch', '2023-05-04 22:28:58', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za ', 'overwatch'),
	(10, 'Overwatch2', '2023-05-04 22:31:18', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za ', 'overwatch2'),
	(11, 'Hg BJ non nbb bb', '2023-05-04 23:54:43', 'open', 'validated', 4, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za ', 'hg-bj-non-nbb-bb'),
	(12, 'dz zd zd dz dz', '2023-05-05 01:20:15', 'open', 'validated', 2, 3, 'da zadaz zadad az azd azd a azdad az a azd az az azd zd  zadzdaz azdad zd a zd  zd za zaadazd azda ad az zd azdaza za ', 'dz-zd-zd-dz-dz'),
	(13, 'zz zz zz zz zz', '2023-05-05 01:30:23', 'closed', 'validated', 2, 3, 'test nouveau 1er message' , 'zz-zz-zz-zz-zz'),
	(14, '1er topic pubg battle royal', '2023-05-05 01:56:39', 'open', 'validated', 5, 3, 'Test premier message pubg', '1er-topic-pubg-battle-royal'),
	(15, 'Test topic en plus pour passer à 6 Topics', '2023-05-05 15:14:42', 'open', 'validated', 2, 3, 'dzd azad z', 'test-topic-en-plus-pour-passer-à-6-topics'),
	(16, 'Au moins 5 mots Isaac test', '2023-05-06 00:59:28', 'open', 'validated', 3, 3, 'Nwnwn', 'au-moins-5-mots-isaac-test'),
	(17, 'A a a a a', '2023-05-06 01:00:07', 'open', 'validated', 3, 3, 'Jwn', 'a-a-a-a-a'),
	(20, 'merde merde merde merde merde', '2023-05-17 20:29:21', 'open', 'validated', 4, 3, 'dze', 'merde-merde-merde-merde-merde'),
	(21, 'Test topic formType dez dze', '2023-05-17 20:44:33', 'open', 'validated', 4, 3, 'zedz', 'test-topic-formtype-dez-dze'),
	(22, 'tes tdez dezfdef fezfe ezf e', '2023-05-17 20:48:19', 'open', 'validated', 4, 3, 'fezf', 'tes-tdez-dezfdef-fezfe-ezf-e'),
	(23, 'te de de ed de ****', '2023-05-18 01:28:51', 'open', 'validated', 1, 3, 'dezdze', 'te-de-de-ed-de-****'),
	(24, 'test ajout topic validated 0 default', '2023-05-18 02:13:31', 'open', 'validated', 1, 3, 'dezdezdze', 'test-ajout-topic-validated-0-default'),
	(26, 'Test ajout topic PUBG (y''en a que 1?)', '2023-05-18 03:57:48', 'open', 'refused', 5, 3, 'Bizarre', 'test-ajout-topic-pubg-(y''en-a-que-1?)'),
	(27, 'Test upload topic validation modo', '2023-05-18 05:33:34', 'open', 'validated', 3, 3, 'Vhj', 'test-upload-topic-validation-modo'),
	(28, 'Test topic New, modo modo', '2023-06-07 01:54:08', 'open', 'validated', 3, 3, 'Jdkdkd', 'test-topic-new,-modo-modo'),
	(29, 'Je créé un Topic (basile08) l''original', '2023-06-18 06:05:38', 'open', 'refused', 4, 3, 'voila c''est mon message voila', 'je-cree-un-topic-(basile08)-l''original'),
	(31, 'Test test test test test', '2023-06-18 22:18:14', 'open', 'validated', 4, 7, 'Test', 'test-test-test-test-test'),
	(33, 'Test New topic test t''es', '2023-06-28 01:37:57', 'open', 'validated', 4, 3, 'Test', 'test-New-topic-test-t''es'),
	(34, 'dd ds d d d d', '2023-07-03 18:41:45', 'open', 'waiting', 2, 3, 'J', 'dd-ds-d-d-d-d'),
	(35, 'j j j j j', '2023-07-03 18:42:14', 'open', 'waiting', 2, 3, 'Fe', 'j-j-j-j-j'),
	(37, 'fer erff refe rfee ref', '2023-07-09 22:16:59', 'open', 'waiting', 4, 3, 'feeferfer', 'fer-erff-refe-rfee-ref'),
	(38, 'test topic League of Legend 5', '2023-07-17 18:55:46', 'open', 'waiting', 6, 3, 'description topic tmtc', 'test-topic-league-of-legend-5'),
	(53, 'dqzdqz s sd sdf sdf s', '2023-07-19 22:42:53', 'open', 'validated', 4, 3, 'dzdqzdqd', 'dqzdqz-s-sd-sdf-sdf-s'),
	(54, 'test npouveau topic basile 11 test delete User', '2023-08-22 14:34:20', 'open', 'validated', 2, NULL, 'dbgfdbdf', 'test-npouveau-topic-basile-11-test-delete-User');




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



INSERT INTO `user_game` (`user_id`, `game_id`) VALUES
	(98, 1),
	(98, 2),
	(98, 3),
	(98, 4),
	(98, 5),
	(98, 6),
	(98, 10);



