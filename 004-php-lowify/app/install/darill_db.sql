SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- 1. NETTOYAGE COMPLET DES TABLES
-- --------------------------------------------------------
TRUNCATE TABLE `song`;
TRUNCATE TABLE `album`;
TRUNCATE TABLE `artist`;

-- --------------------------------------------------------
-- 2. INSERTION ARTISTES (40 Artistes Réels)
-- Images via Picsum Seed (Format Db d'origine)
-- --------------------------------------------------------
INSERT INTO `artist` (`id`, `name`, `biography`, `cover`, `monthly_listeners`) VALUES
   (1, 'The Weeknd', 'Star mondiale du R&B et de la Pop sombre.', 'https://picsum.photos/seed/weeknd/800/800', 110000000),
   (2, 'Daft Punk', 'Légendes de la French Touch.', 'https://picsum.photos/seed/daftpunk/800/800', 25000000),
   (3, 'Damso', 'Rappeur à la plume sombre et technique.', 'https://picsum.photos/seed/damso/800/800', 8000000),
   (4, 'PNL', 'Le duo des Tarterêts qui a changé le rap.', 'https://picsum.photos/seed/pnl/800/800', 5500000),
   (5, 'Lana Del Rey', 'La reine du vintage et de la mélancolie.', 'https://picsum.photos/seed/lana/800/800', 56000000),
   (6, 'Travis Scott', 'Energie pure et ambiance psychédélique.', 'https://picsum.photos/seed/travis/800/800', 72000000),
   (7, 'Drake', 'Le rappeur canadien numéro 1.', 'https://picsum.photos/seed/drake/800/800', 85000000),
   (8, 'Rihanna', 'Icône de la mode et de la pop.', 'https://picsum.photos/seed/rihanna/800/800', 90000000),
   (9, 'Ninho', 'Le recordman des certifications.', 'https://picsum.photos/seed/ninho/800/800', 6000000),
   (10, 'Hans Zimmer', 'Le compositeur de musiques de films.', 'https://picsum.photos/seed/hans/800/800', 12000000),
   (11, 'Kanye West', 'Producteur visionnaire.', 'https://picsum.photos/seed/kanye/800/800', 65000000),
   (12, 'Orelsan', 'Le rappeur normand simple et basique.', 'https://picsum.photos/seed/orel/800/800', 4500000),
   (13, 'Angèle', 'La star belge de la pop.', 'https://picsum.photos/seed/angele/800/800', 6000000),
   (14, 'Arctic Monkeys', 'Rock britannique.', 'https://picsum.photos/seed/arctic/800/800', 45000000),
   (15, 'Dua Lipa', 'Pop disco moderne.', 'https://picsum.photos/seed/dua/800/800', 78000000),
   (16, 'Eminem', 'Le Rap God de Detroit.', 'https://picsum.photos/seed/eminem/800/800', 62000000),
   (17, 'Adele', 'La voix de Londres qui brise les coeurs.', 'https://picsum.photos/seed/adele/800/800', 50000000),
   (18, 'Coldplay', 'Le groupe de pop-rock qui remplit les stades.', 'https://picsum.photos/seed/coldplay/800/800', 80000000),
   (19, 'Booba', 'Le Duc de Boulogne.', 'https://picsum.photos/seed/booba/800/800', 4000000),
   (20, 'Stromae', 'Le maestro belge.', 'https://picsum.photos/seed/stromae/800/800', 9000000),
   (21, 'Billie Eilish', 'La voix chuchotée d\'une génération.', 'https://picsum.photos/seed/billie/800/800', 70000000),
   (22, 'Kendrick Lamar', 'Le poète du hip-hop, prix Pulitzer.', 'https://picsum.photos/seed/kendrick/800/800', 55000000),
   (23, 'Taylor Swift', 'L\'industrie musicale à elle seule.', 'https://picsum.photos/seed/taylor/800/800', 100000000),
   (24, 'Jul', 'L\'ovni marseillais le plus productif.', 'https://picsum.photos/seed/jul/800/800', 7000000),
   (25, 'SCH', 'Le S, Mathafack.', 'https://picsum.photos/seed/sch/800/800', 4500000),
   (26, 'Gazo', 'Le chef de la Drill FR.', 'https://picsum.photos/seed/gazo/800/800', 6500000),
   (27, 'Imagine Dragons', 'Pop rock énergique.', 'https://picsum.photos/seed/imaginedragons/800/800', 60000000),
   (28, 'Queen', 'Freddie Mercury et sa légende.', 'https://picsum.photos/seed/queen/800/800', 48000000),
   (29, 'Michael Jackson', 'Le Roi de la Pop.', 'https://picsum.photos/seed/mj/800/800', 35000000),
   (30, 'Bruno Mars', 'Le funk et la pop dans le sang.', 'https://picsum.photos/seed/bruno/800/800', 68000000),
   (31, 'Beyoncé', 'Queen B, icône absolue.', 'https://picsum.photos/seed/beyonce/800/800', 85000000),
   (32, 'Post Malone', 'Le rockstar du rap mélodique.', 'https://picsum.photos/seed/posty/800/800', 65000000),
   (33, 'Ed Sheeran', 'L\'homme à la guitare et aux hits.', 'https://picsum.photos/seed/edsheeran/800/800', 95000000),
   (34, 'Lady Gaga', 'L\'artiste complète et excentrique.', 'https://picsum.photos/seed/gaga/800/800', 55000000),
   (35, 'Justin Bieber', 'La pop star canadienne.', 'https://picsum.photos/seed/bieber/800/800', 82000000),
   (36, 'Ariana Grande', 'La voix de sifflet et la queue de cheval.', 'https://picsum.photos/seed/ariana/800/800', 88000000),
   (37, 'Harry Styles', 'L\'icône de la mode et de la pop british.', 'https://picsum.photos/seed/harry/800/800', 75000000),
   (38, 'SZA', 'La reine du R&B alternatif actuel.', 'https://picsum.photos/seed/sza/800/800', 68000000),
   (39, 'Frank Ocean', 'Le mystère et l\'émotion pure.', 'https://picsum.photos/seed/frank/800/800', 25000000),
   (40, 'Tyler, The Creator', 'Le créateur visionnaire et coloré.', 'https://picsum.photos/seed/tyler/800/800', 30000000);

-- --------------------------------------------------------
-- 3. INSERTION ALBUMS (80 Albums Réels)
-- Images Picsum uniques via Seed
-- --------------------------------------------------------
INSERT INTO `album` (`id`, `name`, `artist_id`, `cover`, `release_date`) VALUES
-- The Weeknd
(1, 'After Hours', 1, 'https://picsum.photos/seed/afterhours/800/800', '2020-03-20'),
(2, 'Starboy', 1, 'https://picsum.photos/seed/starboy/800/800', '2016-11-25'),
-- Daft Punk
(3, 'RAM', 2, 'https://picsum.photos/seed/ram/800/800', '2013-05-17'),
(4, 'Discovery', 2, 'https://picsum.photos/seed/discovery/800/800', '2001-03-12'),
-- Damso
(5, 'Ipséité', 3, 'https://picsum.photos/seed/ipseite/800/800', '2017-04-28'),
(6, 'Lithopédion', 3, 'https://picsum.photos/seed/litho/800/800', '2018-06-15'),
-- PNL
(7, 'Deux Frères', 4, 'https://picsum.photos/seed/deuxfreres/800/800', '2019-04-05'),
(8, 'Dans la légende', 4, 'https://picsum.photos/seed/dll/800/800', '2016-09-16'),
-- Lana
(9, 'Born to Die', 5, 'https://picsum.photos/seed/btd/800/800', '2012-01-27'),
(10, 'Ultraviolence', 5, 'https://picsum.photos/seed/ultra/800/800', '2014-06-13'),
-- Travis
(11, 'Astroworld', 6, 'https://picsum.photos/seed/astro/800/800', '2018-08-03'),
(12, 'Utopia', 6, 'https://picsum.photos/seed/utopia/800/800', '2023-07-28'),
-- Drake
(13, 'Scorpion', 7, 'https://picsum.photos/seed/scorpion/800/800', '2018-06-29'),
(14, 'Views', 7, 'https://picsum.photos/seed/views/800/800', '2016-04-29'),
-- Rihanna
(15, 'Anti', 8, 'https://picsum.photos/seed/anti/800/800', '2016-01-28'),
(16, 'Loud', 8, 'https://picsum.photos/seed/loud/800/800', '2010-11-12'),
-- Ninho
(17, 'Destin', 9, 'https://picsum.photos/seed/destin/800/800', '2019-03-22'),
(18, 'Jefe', 9, 'https://picsum.photos/seed/jefe/800/800', '2021-12-03'),
-- Hans
(19, 'Interstellar', 10, 'https://picsum.photos/seed/interstellar/800/800', '2014-11-17'),
(20, 'Inception', 10, 'https://picsum.photos/seed/inception/800/800', '2010-07-13'),
-- Kanye
(21, 'Graduation', 11, 'https://picsum.photos/seed/graduation/800/800', '2007-09-11'),
(22, 'Yeezus', 11, 'https://picsum.photos/seed/yeezus/800/800', '2013-06-18'),
-- Orelsan
(23, 'Civilisation', 12, 'https://picsum.photos/seed/civilisation/800/800', '2021-11-19'),
(24, 'La fête est finie', 12, 'https://picsum.photos/seed/lafete/800/800', '2017-10-20'),
-- Angèle
(25, 'Brol', 13, 'https://picsum.photos/seed/brol/800/800', '2018-10-05'),
(26, 'Nonante-Cinq', 13, 'https://picsum.photos/seed/nonante/800/800', '2021-12-03'),
-- Arctic Monkeys
(27, 'AM', 14, 'https://picsum.photos/seed/am/800/800', '2013-09-09'),
(28, 'FWN', 14, 'https://picsum.photos/seed/fwn/800/800', '2007-04-23'),
-- Dua Lipa
(29, 'Future Nostalgia', 15, 'https://picsum.photos/seed/futurenostalgia/800/800', '2020-03-27'),
(30, 'Dua Lipa', 15, 'https://picsum.photos/seed/dualipa/800/800', '2017-06-02'),
-- Eminem
(31, 'The Eminem Show', 16, 'https://picsum.photos/seed/eminemshow/800/800', '2002-05-26'),
(32, 'Recovery', 16, 'https://picsum.photos/seed/recovery/800/800', '2010-06-18'),
-- Adele
(33, '21', 17, 'https://picsum.photos/seed/adele21/800/800', '2011-01-24'),
(34, '30', 17, 'https://picsum.photos/seed/adele30/800/800', '2021-11-19'),
-- Coldplay
(35, 'Parachutes', 18, 'https://picsum.photos/seed/parachutes/800/800', '2000-07-10'),
(36, 'A Rush of Blood', 18, 'https://picsum.photos/seed/arush/800/800', '2002-08-26'),
-- Booba
(37, 'Ultra', 19, 'https://picsum.photos/seed/boobaultra/800/800', '2021-03-05'),
(38, 'Trône', 19, 'https://picsum.photos/seed/trone/800/800', '2017-12-01'),
-- Stromae
(39, 'Racine carrée', 20, 'https://picsum.photos/seed/racine/800/800', '2013-08-16'),
(40, 'Multitude', 20, 'https://picsum.photos/seed/multitude/800/800', '2022-03-04'),
-- Billie
(41, 'Happier Than Ever', 21, 'https://picsum.photos/seed/happier/800/800', '2021-07-30'),
(42, 'WWAFA', 21, 'https://picsum.photos/seed/wwafa/800/800', '2019-03-29'),
-- Kendrick
(43, 'DAMN.', 22, 'https://picsum.photos/seed/damn/800/800', '2017-04-14'),
(44, 'GKMC', 22, 'https://picsum.photos/seed/gkmc/800/800', '2012-10-22'),
-- Taylor
(45, '1989', 23, 'https://picsum.photos/seed/1989/800/800', '2014-10-27'),
(46, 'Midnights', 23, 'https://picsum.photos/seed/midnights/800/800', '2022-10-21'),
-- Jul
(47, 'My World', 24, 'https://picsum.photos/seed/myworld/800/800', '2015-12-04'),
(48, 'L\'Ovni', 24, 'https://picsum.photos/seed/lovni/800/800', '2016-12-02'),
-- SCH
(49, 'JVLIVS', 25, 'https://picsum.photos/seed/jvlvis/800/800', '2018-10-19'),
(50, 'JVLIVS II', 25, 'https://picsum.photos/seed/jvlvis2/800/800', '2021-03-19'),
-- Gazo
(51, 'KMT', 26, 'https://picsum.photos/seed/kmt/800/800', '2022-07-01'),
(52, 'Drill FR', 26, 'https://picsum.photos/seed/drillfr/800/800', '2021-02-26'),
-- Imagine Dragons
(53, 'Night Visions', 27, 'https://picsum.photos/seed/nightvisions/800/800', '2012-09-04'),
(54, 'Evolve', 27, 'https://picsum.photos/seed/evolve/800/800', '2017-06-23'),
-- Queen
(55, 'A Night at the Opera', 28, 'https://picsum.photos/seed/anight/800/800', '1975-11-21'),
(56, 'News of the World', 28, 'https://picsum.photos/seed/news/800/800', '1977-10-28'),
-- MJ
(57, 'Thriller', 29, 'https://picsum.photos/seed/thriller/800/800', '1982-11-30'),
(58, 'Bad', 29, 'https://picsum.photos/seed/bad/800/800', '1987-08-31'),
-- Bruno Mars
(59, '24K Magic', 30, 'https://picsum.photos/seed/24k/800/800', '2016-11-18'),
(60, 'Doo-Wops', 30, 'https://picsum.photos/seed/doowops/800/800', '2010-10-04'),
-- Beyonce
(61, 'Renaissance', 31, 'https://picsum.photos/seed/beyonce/800/800', '2022-07-29'),
(62, 'Lemonade', 31, 'https://picsum.photos/seed/lemonade/800/800', '2016-04-23'),
-- Post Malone
(63, 'Beerbongs', 32, 'https://picsum.photos/seed/beerbongs/800/800', '2018-04-27'),
(64, 'Hollywood', 32, 'https://picsum.photos/seed/hollywood/800/800', '2019-09-06'),
-- Ed Sheeran
(65, 'Divide', 33, 'https://picsum.photos/seed/divide/800/800', '2017-03-03'),
(66, 'Multiply', 33, 'https://picsum.photos/seed/multiply/800/800', '2014-06-20'),
-- Lady Gaga
(67, 'Chromatica', 34, 'https://picsum.photos/seed/chromatica/800/800', '2020-05-29'),
(68, 'The Fame', 34, 'https://picsum.photos/seed/thefame/800/800', '2008-08-19'),
-- Justin Bieber
(69, 'Justice', 35, 'https://picsum.photos/seed/justice/800/800', '2021-03-19'),
(70, 'Purpose', 35, 'https://picsum.photos/seed/purpose/800/800', '2015-11-13'),
-- Ariana
(71, 'Thank U Next', 36, 'https://picsum.photos/seed/thanku/800/800', '2019-02-08'),
(72, 'Sweetener', 36, 'https://picsum.photos/seed/sweetener/800/800', '2018-08-17'),
-- Harry Styles
(73, 'Harrys House', 37, 'https://picsum.photos/seed/harryhouse/800/800', '2022-05-20'),
(74, 'Fine Line', 37, 'https://picsum.photos/seed/fineline/800/800', '2019-12-13'),
-- SZA
(75, 'SOS', 38, 'https://picsum.photos/seed/sos/800/800', '2022-12-09'),
(76, 'Ctrl', 38, 'https://picsum.photos/seed/ctrl/800/800', '2017-06-09'),
-- Frank Ocean
(77, 'Blonde', 39, 'https://picsum.photos/seed/blonde/800/800', '2016-08-20'),
(78, 'Channel Orange', 39, 'https://picsum.photos/seed/channel/800/800', '2012-07-10'),
-- Tyler
(79, 'IGOR', 40, 'https://picsum.photos/seed/igor/800/800', '2019-05-17'),
(80, 'Flower Boy', 40, 'https://picsum.photos/seed/flower/800/800', '2017-07-21');

-- --------------------------------------------------------
-- 4. CHANSONS (3 par album = 240 Chansons Réelles)
-- --------------------------------------------------------
INSERT INTO `song` (`id`, `name`, `artist_id`, `album_id`, `duration`, `note`) VALUES
-- Weeknd - After Hours
(1, 'Blinding Lights', 1, 1, 200, 5.0), (2, 'In Your Eyes', 1, 1, 237, 4.6), (3, 'Save Your Tears', 1, 1, 215, 4.8),
-- Weeknd - Starboy
(4, 'Starboy', 1, 2, 230, 4.9), (5, 'I Feel It Coming', 1, 2, 269, 4.7), (6, 'Party Monster', 1, 2, 249, 4.2),
-- Daft Punk - RAM
(7, 'Get Lucky', 2, 3, 369, 5.0), (8, 'Instant Crush', 2, 3, 337, 4.9), (9, 'Lose Yourself to Dance', 2, 3, 353, 4.5),
-- Daft Punk - Discovery
(10, 'One More Time', 2, 4, 320, 5.0), (11, 'Aerodynamic', 2, 4, 207, 4.7), (12, 'Digital Love', 2, 4, 298, 4.8),
-- Damso - Ipséité
(13, 'Macarena', 3, 5, 208, 4.4), (14, 'Signaler', 3, 5, 202, 4.2), (15, 'Kietu', 3, 5, 230, 4.1),
-- Damso - Lithopédion
(16, 'Feu de bois', 3, 6, 203, 4.7), (17, 'Smog', 3, 6, 180, 4.3), (18, 'Dix Leurres', 3, 6, 195, 4.0),
-- PNL - Deux Frères
(19, 'Au DD', 4, 7, 240, 5.0), (20, 'Deux Frères', 4, 7, 250, 4.6), (21, 'Blanka', 4, 7, 195, 4.3),
-- PNL - DLL
(22, 'DA', 4, 8, 210, 4.8), (23, 'Naha', 4, 8, 235, 4.7), (24, 'Onizuka', 4, 8, 245, 4.9),
-- Lana - BTD
(25, 'Video Games', 5, 9, 282, 4.9), (26, 'Born to Die', 5, 9, 286, 4.8), (27, 'Blue Jeans', 5, 9, 210, 4.6),
-- Lana - Ultra
(28, 'West Coast', 5, 10, 256, 4.5), (29, 'Brooklyn Baby', 5, 10, 351, 4.4), (30, 'Ultraviolence', 5, 10, 251, 4.3),
-- Travis - Astro
(31, 'Sicko Mode', 6, 11, 312, 4.9), (32, 'Stargazing', 6, 11, 271, 4.6), (33, 'Butterfly Effect', 6, 11, 205, 4.5),
-- Travis - Utopia
(34, 'Meltdown', 6, 12, 246, 4.4), (35, 'Fe!n', 6, 12, 191, 4.7), (36, 'I Know ?', 6, 12, 211, 4.5),
-- Drake - Scorpion
(37, 'God\'s Plan', 7, 13, 198, 4.8), (38, 'Nice For What', 7, 13, 210, 4.5), (39, 'In My Feelings', 7, 13, 217, 4.3),
-- Drake - Views
(40, 'Hotline Bling', 7, 14, 267, 4.7), (41, 'One Dance', 7, 14, 173, 4.9), (42, 'Controlla', 7, 14, 245, 4.2),
-- Rihanna - Anti
(43, 'Work', 8, 15, 219, 4.6), (44, 'Needed Me', 8, 15, 191, 4.5), (45, 'Kiss It Better', 8, 15, 253, 4.4),
-- Rihanna - Loud
(46, 'Only Girl', 8, 16, 235, 4.8), (47, 'What\'s My Name?', 8, 16, 263, 4.5), (48, 'S&M', 8, 16, 243, 4.3),
-- Ninho - Destin
(49, 'Goutte d\'eau', 9, 17, 188, 4.6), (50, 'Maman ne le sait pas', 9, 17, 205, 4.7), (51, 'La vie qu\'on mène', 9, 17, 220, 4.9),
-- Ninho - Jefe
(52, 'Jefe', 9, 18, 185, 4.5), (53, 'VVS', 9, 18, 195, 4.4), (54, 'Vérité', 9, 18, 205, 4.2),
-- Hans - Interstellar
(55, 'Cornfield Chase', 10, 19, 126, 5.0), (56, 'No Time for Caution', 10, 19, 246, 4.9), (57, 'Stay', 10, 19, 383, 4.8),
-- Hans - Inception
(58, 'Time', 10, 20, 275, 5.0), (59, 'Dream Is Collapsing', 10, 20, 143, 4.7), (60, 'Mombasa', 10, 20, 294, 4.5),
-- Kanye - Graduation
(61, 'Stronger', 11, 21, 311, 4.9), (62, 'Good Life', 11, 21, 207, 4.6), (63, 'Flashing Lights', 11, 21, 237, 5.0),
-- Kanye - Yeezus
(64, 'Black Skinhead', 11, 22, 188, 4.7), (65, 'Bound 2', 11, 22, 229, 4.5), (66, 'New Slaves', 11, 22, 256, 4.6),
-- Orelsan - Civilisation
(67, 'L\'odeur de l\'essence', 12, 23, 282, 4.9), (68, 'Jour meilleur', 12, 23, 215, 4.7), (69, 'La Quête', 12, 23, 243, 4.8),
-- Orelsan - La fête
(70, 'Basique', 12, 24, 196, 4.8), (71, 'San', 12, 24, 242, 4.5), (72, 'Défaite de famille', 12, 24, 223, 4.6),
-- Angèle - Brol
(73, 'Balance ton quoi', 13, 25, 189, 4.7), (74, 'Tout oublier', 13, 25, 202, 4.8), (75, 'Ta reine', 13, 25, 210, 4.6),
-- Angèle - Nonante
(76, 'Bruxelles je t\'aime', 13, 26, 235, 4.6), (77, 'Libre', 13, 26, 200, 4.5), (78, 'Démons', 13, 26, 215, 4.7),
-- Arctic - AM
(79, 'Do I Wanna Know?', 14, 27, 272, 4.9), (80, 'R U Mine?', 14, 27, 201, 4.7), (81, 'Arabella', 14, 27, 207, 4.6),
-- Arctic - FWN
(82, 'Fluorescent Adolescent', 14, 28, 177, 4.8), (83, '505', 14, 28, 253, 5.0), (84, 'Teddy Picker', 14, 28, 163, 4.5),
-- Dua Lipa - FN
(85, 'Don\'t Start Now', 15, 29, 183, 4.8), (86, 'Levitating', 15, 29, 203, 4.7), (87, 'Physical', 15, 29, 193, 4.5),
-- Dua Lipa - DL
(88, 'New Rules', 15, 30, 209, 4.8), (89, 'IDGAF', 15, 30, 217, 4.6), (90, 'Be The One', 15, 30, 202, 4.5),
-- Eminem - TES
(91, 'Without Me', 16, 31, 290, 4.9), (92, 'Sing For The Moment', 16, 31, 339, 4.8), (93, 'Superman', 16, 31, 350, 4.6),
-- Eminem - Recovery
(94, 'Not Afraid', 16, 32, 248, 4.7), (95, 'Love The Way You Lie', 16, 32, 263, 4.6), (96, 'Space Bound', 16, 32, 278, 4.5),
-- Adele - 21
(97, 'Rolling in the Deep', 17, 33, 228, 5.0), (98, 'Someone Like You', 17, 33, 285, 4.9), (99, 'Set Fire to the Rain', 17, 33, 242, 4.8),
-- Adele - 30
(100, 'Easy On Me', 17, 34, 224, 4.8), (101, 'Oh My God', 17, 34, 225, 4.6), (102, 'I Drink Wine', 17, 34, 376, 4.7),
-- Coldplay - Parachutes
(103, 'Yellow', 18, 35, 269, 4.8), (104, 'Shiver', 18, 35, 304, 4.5), (105, 'Trouble', 18, 35, 270, 4.6),
-- Coldplay - AROBTTH
(106, 'The Scientist', 18, 36, 309, 5.0), (107, 'Clocks', 18, 36, 307, 4.9), (108, 'In My Place', 18, 36, 228, 4.7),
-- Booba - Ultra
(109, 'Mona Lisa', 19, 37, 190, 4.6), (110, '5G', 19, 37, 200, 4.3), (111, 'GP', 19, 37, 180, 4.4),
-- Booba - Trone
(112, 'Petite Fille', 19, 38, 210, 4.7), (113, 'Trône', 19, 38, 195, 4.6), (114, 'Friday', 19, 38, 205, 4.5),
-- Stromae - Racine
(115, 'Papaoutai', 20, 39, 231, 4.9), (116, 'Formidable', 20, 39, 213, 4.8), (117, 'Tous les mêmes', 20, 39, 213, 4.7),
-- Stromae - Multitude
(118, 'Santé', 20, 40, 190, 4.5), (119, 'L\'enfer', 20, 40, 189, 4.7), (120, 'Fils de joie', 20, 40, 200, 4.6),
-- Billie - Happier
(121, 'Happier Than Ever', 21, 41, 298, 4.9), (122, 'Oxytocin', 21, 41, 210, 4.6), (123, 'NDA', 21, 41, 195, 4.5),
-- Billie - WWAFA
(124, 'Bad Guy', 21, 42, 194, 4.8), (125, 'Bury a Friend', 21, 42, 193, 4.7), (126, 'When the Party\'s Over', 21, 42, 196, 4.9),
-- Kendrick - DAMN
(127, 'HUMBLE.', 22, 43, 177, 4.9), (128, 'DNA.', 22, 43, 185, 4.8), (129, 'ELEMENT.', 22, 43, 208, 4.7),
-- Kendrick - GKMC
(130, 'Bitch, Don\'t Kill My Vibe', 22, 44, 310, 4.8), (131, 'Money Trees', 22, 44, 386, 5.0), (132, 'Swimming Pools', 22, 44, 313, 4.9),
-- Taylor - 1989
(133, 'Blank Space', 23, 45, 231, 4.8), (134, 'Style', 23, 45, 231, 4.7), (135, 'Shake It Off', 23, 45, 219, 4.6),
-- Taylor - Midnights
(136, 'Anti-Hero', 23, 46, 200, 4.9), (137, 'Lavender Haze', 23, 46, 191, 4.6), (138, 'Karma', 23, 46, 204, 4.7),
-- Jul - My World
(139, 'Wesh alors', 24, 47, 200, 4.8), (140, 'Amnésia', 24, 47, 210, 4.7), (141, 'En Y', 24, 47, 205, 4.6),
-- Jul - Ovni
(142, 'Tchikita', 24, 48, 195, 4.9), (143, 'On m\'appelle l\'ovni', 24, 48, 190, 4.8), (144, 'C\'est le son de la gratte', 24, 48, 180, 4.7),
-- SCH - JVLIVS
(145, 'Otto', 25, 49, 185, 4.7), (146, 'Pharmacie', 25, 49, 210, 4.6), (147, 'Le code', 25, 49, 200, 4.5),
-- SCH - JVLIVS 2
(148, 'Mannschaft', 25, 50, 230, 4.8), (149, 'Mode Akimbo', 25, 50, 215, 4.7), (150, 'Crack', 25, 50, 190, 4.6),
-- Gazo - KMT
(151, 'DIE', 26, 51, 200, 4.8), (152, 'Fleurs', 26, 51, 195, 4.7), (153, 'Rappel', 26, 51, 210, 4.6),
-- Gazo - Drill FR
(154, 'Haine&Sex', 26, 52, 205, 4.9), (155, 'Tchin 2x', 26, 52, 215, 4.8), (156, 'Kassav', 26, 52, 200, 4.7),
-- Imagine - NV
(157, 'Radioactive', 27, 53, 186, 4.8), (158, 'Demons', 27, 53, 177, 4.9), (159, 'It\'s Time', 27, 53, 240, 4.7),
-- Imagine - Evolve
(160, 'Believer', 27, 54, 204, 4.8), (161, 'Thunder', 27, 54, 187, 4.6), (162, 'Whatever It Takes', 27, 54, 201, 4.7),
-- Queen - Opera
(163, 'Bohemian Rhapsody', 28, 55, 354, 5.0), (164, 'Love of My Life', 28, 55, 217, 4.9), (165, 'You\'re My Best Friend', 28, 55, 172, 4.8),
-- Queen - News
(166, 'We Will Rock You', 28, 56, 121, 5.0), (167, 'We Are The Champions', 28, 56, 179, 5.0), (168, 'Spread Your Wings', 28, 56, 272, 4.7),
-- MJ - Thriller
(169, 'Billie Jean', 29, 57, 294, 5.0), (170, 'Beat It', 29, 57, 258, 4.9), (171, 'Thriller', 29, 57, 357, 5.0),
-- MJ - Bad
(172, 'Smooth Criminal', 29, 58, 257, 5.0), (173, 'Bad', 29, 58, 247, 4.9), (174, 'The Way You Make Me Feel', 29, 58, 298, 4.8),
-- Bruno - 24K
(175, '24K Magic', 30, 59, 226, 4.8), (176, 'That\'s What I Like', 30, 59, 206, 4.7), (177, 'Versace on the Floor', 30, 59, 261, 4.6),
-- Bruno - DooWops
(178, 'Grenade', 30, 60, 222, 4.8), (179, 'Just the Way You Are', 30, 60, 220, 4.9), (180, 'The Lazy Song', 30, 60, 195, 4.7),
-- Beyonce - Renaissance
(181, 'Break My Soul', 31, 61, 278, 4.8), (182, 'Alien Superstar', 31, 61, 215, 4.7), (183, 'Cuff It', 31, 61, 225, 4.9),
-- Beyonce - Lemonade
(184, 'Formation', 31, 62, 206, 4.9), (185, 'Hold Up', 31, 62, 221, 4.7), (186, 'Sorry', 31, 62, 232, 4.8),
-- Post Malone - Beer
(187, 'Rockstar', 32, 63, 218, 4.8), (188, 'Psycho', 32, 63, 221, 4.7), (189, 'Better Now', 32, 63, 231, 4.6),
-- Post - Hollywood
(190, 'Circles', 32, 64, 215, 4.9), (191, 'Sunflower', 32, 64, 158, 5.0), (192, 'Wow.', 32, 64, 149, 4.5),
-- Ed - Divide
(193, 'Shape of You', 33, 65, 233, 4.9), (194, 'Perfect', 33, 65, 263, 4.8), (195, 'Castle on the Hill', 33, 65, 261, 4.7),
-- Ed - Multiply
(196, 'Thinking Out Loud', 33, 66, 281, 4.9), (197, 'Photograph', 33, 66, 258, 4.8), (198, 'Don\'t', 33, 66, 219, 4.6),
-- Gaga - Chromatica
(199, 'Rain On Me', 34, 67, 182, 4.8), (200, 'Stupid Love', 34, 67, 193, 4.5), (201, '911', 34, 67, 172, 4.4),
-- Gaga - Fame
(202, 'Just Dance', 34, 68, 241, 4.9), (203, 'Poker Face', 34, 68, 237, 5.0), (204, 'Paparazzi', 34, 68, 208, 4.8),
-- Bieber - Justice
(205, 'Peaches', 35, 69, 198, 4.7), (206, 'Hold On', 35, 69, 170, 4.6), (207, 'Ghost', 35, 69, 153, 4.8),
-- Bieber - Purpose
(208, 'Sorry', 35, 70, 200, 4.9), (209, 'Love Yourself', 35, 70, 233, 4.8), (210, 'What Do You Mean?', 35, 70, 205, 4.7),
-- Ariana - Thank U
(211, 'Thank U, Next', 36, 71, 207, 4.9), (212, '7 Rings', 36, 71, 178, 4.8), (213, 'Break Up With Your GF', 36, 71, 200, 4.6),
-- Ariana - Sweetener
(214, 'No Tears Left to Cry', 36, 72, 205, 4.8), (215, 'God Is A Woman', 36, 72, 197, 4.7), (216, 'Breathin', 36, 72, 198, 4.6),
-- Harry - House
(217, 'As It Was', 37, 73, 167, 5.0), (218, 'Late Night Talking', 37, 73, 177, 4.7), (219, 'Matilda', 37, 73, 245, 4.8),
-- Harry - Fine Line
(220, 'Watermelon Sugar', 37, 74, 174, 4.9), (221, 'Adore You', 37, 74, 207, 4.8), (222, 'Falling', 37, 74, 240, 4.7),
-- SZA - SOS
(223, 'Kill Bill', 38, 75, 153, 4.9), (224, 'Snooze', 38, 75, 201, 4.8), (225, 'Nobody Gets Me', 38, 75, 180, 4.6),
-- SZA - Ctrl
(226, 'The Weekend', 38, 76, 272, 4.8), (227, 'Love Galore', 38, 76, 275, 4.7), (228, 'Broken Clocks', 38, 76, 231, 4.6),
-- Frank - Blonde
(229, 'Nikes', 39, 77, 314, 4.8), (230, 'Ivy', 39, 77, 249, 4.9), (231, 'Pink + White', 39, 77, 184, 5.0),
-- Frank - Channel
(232, 'Thinkin Bout You', 39, 78, 200, 4.9), (233, 'Pyramids', 39, 78, 592, 5.0), (234, 'Lost', 39, 78, 234, 4.8),
-- Tyler - IGOR
(235, 'Earfquake', 40, 79, 190, 4.9), (236, 'Gone, Gone', 40, 79, 375, 4.8), (237, 'New Magic Wand', 40, 79, 195, 4.7),
-- Tyler - Flower Boy
(238, 'See You Again', 40, 80, 180, 4.9), (239, '911 / Mr. Lonely', 40, 80, 255, 4.8), (240, 'Boredom', 40, 80, 320, 4.7);

SET FOREIGN_KEY_CHECKS = 1;