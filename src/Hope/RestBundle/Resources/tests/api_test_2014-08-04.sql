# ************************************************************
# Sequel Pro SQL dump
# Версия 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Адрес: mq.local (MySQL 5.5.38)
# Схема: api_test
# Время создания: 2014-08-04 15:09:12 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Дамп таблицы banner
# ------------------------------------------------------------

DROP TABLE IF EXISTS `banner`;

CREATE TABLE `banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `banner` WRITE;
/*!40000 ALTER TABLE `banner` DISABLE KEYS */;

INSERT INTO `banner` (`id`, `image`, `url`)
VALUES
	(1,'http://hope.ua/test.jpg','http://hope.ua'),
	(2,'http://hope.ua/test2.jpg','http://hope.ua');

/*!40000 ALTER TABLE `banner` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;

INSERT INTO `category` (`id`, `title`, `sort`)
VALUES
	(1,'молодежные',2),
	(3,'детские',4),
	(4,'социальные проекты',3);

/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы page
# ------------------------------------------------------------

DROP TABLE IF EXISTS `page`;

CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;

INSERT INTO `page` (`id`, `section`, `title`, `text`)
VALUES
	(1,'first','first','first'),
	(2,'second','second','second'),
	(3,'third','third','third');

/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы program
# ------------------------------------------------------------

DROP TABLE IF EXISTS `program`;

CREATE TABLE `program` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `code` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `desc_short` longtext COLLATE utf8_unicode_ci NOT NULL,
  `desc_full` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_92ED778412469DE2` (`category_id`),
  CONSTRAINT `FK_92ED778412469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `program` WRITE;
/*!40000 ALTER TABLE `program` DISABLE KEYS */;

INSERT INTO `program` (`id`, `category_id`, `code`, `title`, `desc_short`, `desc_full`)
VALUES
	(7,1,'CYCU','Поспілкуймося','Молодіжне ток-шоу на теми, які найбільше хвилюють молодь. Це відкритий діалог молодих людей та досвідчених духовних лідерів.',''),
	(23,3,'HDVU','В гостях у Добрячка','Добрячок завжди радий вас вітати і в школі добрих порад буде вас навчати.',''),
	(25,3,'FLNU','Дружболандия','Дружболандия — это замечательная страна, где нет слез, болезней, а все жители радуются и помогают друг другу.',''),
	(28,4,'WUCU','Вихід поруч','Програма про людей, які переживали труднощі йдучі до Бога. Навіть в безвихідних ситуаціях, з Богом, можна побачити – вихід поруч.',''),
	(36,1,'FBNU','Модная книга','Программа даёт ответы на острые вопросы, волнующие современную молодёжь. Источник ответов – книга, модная во все времена, способная утолить жажду знаний даже самых пытливых скептиков – Библия.',''),
	(73,4,'SVCU','Счастье вопреки всему','Как обрести счастье вопреки препятствиям и сложностям?','');

/*!40000 ALTER TABLE `program` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы schedule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `schedule`;

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `issue_time` datetime NOT NULL,
  `program` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `episode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `schedule` WRITE;
/*!40000 ALTER TABLE `schedule` DISABLE KEYS */;

INSERT INTO `schedule` (`id`, `issue_time`, `program`, `episode`)
VALUES
	(111547,'2014-08-01 05:50:00','Біблія, як вона є','Дії апостолів 12:1-25'),
	(111548,'2014-08-01 06:00:00','В гостях у Добрячка','Що таке добро?'),
	(111549,'2014-08-01 06:20:00','Мастерская добрых дел','Возвращение Бориса (Вознесение Иисуса Христа)'),
	(111550,'2014-08-01 06:30:00','Так промовляє Біблія','Славний день відновлення правосуддя'),
	(111551,'2014-08-01 07:00:00','Восхождение на Голгофу','Притчи восхождение: Протест против формализма'),
	(111680,'2014-08-04 00:00:00','Інша сторона','Сімейна трагедія'),
	(111681,'2014-08-04 00:40:00','Біблія свідчить','Молитва'),
	(111682,'2014-08-04 00:50:00','Музична скринька','Випуск №11 (14)'),
	(111683,'2014-08-04 01:30:00','Счастье вопреки всему','Покажи свою веру'),
	(111684,'2014-08-04 02:20:00','Настав час','Економія, чи якість?');

/*!40000 ALTER TABLE `schedule` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы video
# ------------------------------------------------------------

DROP TABLE IF EXISTS `video`;

CREATE TABLE `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `code` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `publish_time` datetime NOT NULL,
  `hd` tinyint(1) NOT NULL,
  `watch` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7CC7DA2C3EB8070A` (`program_id`),
  CONSTRAINT `FK_7CC7DA2C3EB8070A` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;

INSERT INTO `video` (`id`, `program_id`, `code`, `title`, `description`, `author`, `duration`, `publish_time`, `hd`, `watch`)
VALUES
	(25,7,'CYCU00112','Залежності','Кожен із нас у своєму житті неодноразово казав «СТОП». Стоп бажанню смачно поїсти о 11-ій вечора, стоп приводу нечесно заробити чималу суму грошей, стоп  перед пешохідним переходом на пустій дорозі але з червоним кольором світлофора, стоп  бажанню отримати насолоду через заборонене. Іноді нам вдається зупинитися, іноді — ні.<br />Сьогодні наша тема — стоп соціально шкідливим залежностям.<br /><br /><i>Гості: Ірина Дніпровська та Андрій Шамрай</i>','Ведущая',300,'2014-07-28 04:03:12',0,'youtubeLink'),
	(26,7,'CYCU00712','Самогубство','Життя… Одна людина тримається за нього до останнього подиху, а інша власноруч з ним прощається.  Чому декому набридає такий безцінний дар? Хто більше за все схильний до самогубства і як допомогти такій людині? Відповідь на ці та інші питання кожен зможе отримати на «Поспілкуймося».<br /><br /><i>Гості: Олена Яковенко, Олександр Мещєряков</i>','Ведущая',25,'2012-08-31 00:00:00',0,'youtubeLink'),
	(123,25,'FLNU00412','Праздник который всегда с тобой','Почти все праздники придумали люди, но есть один памятный день, который устновил Сам Бог. Узнайте об этом празднике прямо сейчас.','Ведущая',150,'2014-07-28 10:04:12',0,'youtubeLink'),
	(132,25,'FLNU02412','Вместе веселей','Если мы любим наших друзей, то почему с ними ссоримся? Давайте это выясним в \"Дружболандии\"!','Ведущая',11,'2014-07-28 01:01:13',0,'youtubeLink'),
	(136,36,'FBNU00312','Греховна ли красота?','Вы красивы? Не торопитесь отвечать на этот вопрос,потому что стандарты красоты всегда меняются. Но если вас интересует отношение Бога к красоте, давайте откроем Модную Книгу.<br /><br />Гость студии: <i>Владимир Лукин.</i>','Ведущая',500,'2014-07-28 04:07:12',0,'youtubeLink'),
	(138,36,'FBNU00512','Жизнь в азарте','Азарт присущ практически каждому. Но что делать, если он в избытке?Подскажет Модная Книга.<br /><br /><i>Гость студии: Александр Созинов</i>','Ведущая',100,'2014-07-28 18:07:12',0,'youtubeLink'),
	(164,23,'HDVU00412','Мама','Хто є і в мами, і в тата, і в сестрички, і в брата, і в усіх-усіх на світі? Відповідь на це запитання ви зможете дізнати, якщо переглянете нашу передачу.','Добрячек',200,'2014-07-28 11:03:12',1,'youtubeHDLink'),
	(168,23,'HDVU01612','Най-най-най','Тим, хто хоче дізнатись щось най-най-най, буде цікаво послухати розповіді Добрячка Даші та Ганнусі.','Добрячек',1000,'2014-07-28 22:07:12',1,'youtubeHDLink'),
	(228,28,'WUCU00511','Між смертю і життям','Діагноз лікаря: «У вас — саркома!», — для Наталі пролунав як вирок. А вона – зовсім молода, в неї — маленька дитина, їй так хочеться жити… І вона вижила. Як?! Про це у програмі.','Ведущая',100,'2011-09-25 00:00:00',0,'youtubeLink'),
	(284,28,'WUCU00312','Візок — не перешкода','Уже тридцять років, як Раїса не робила жодного кроку. Вона — інвалід. Її дитинство проминуло в інтернатах. Оточуючі не бачили для неї виходу, та жінка не зневірилася. Отримала дві вищі освіти, створила громадську організацію для інвалідів, побувала за кордоном. Де Раїса знайшла вихід — дивіться у програмі.','Ведущая',10,'2012-09-28 00:00:00',1,'youtubeHDLink'),
	(1630,73,'SVCU00513','Не за твои грехи','Ярослав и Светлана  давно узнали о Боге и поверили в Него. Именно Он подарил им двух замечательных детей.  Но, когда  их сыну Максиму было десять лет, мальчику поставили смертельный диагноз. «Почему  это с нами случилось? За что страдает ребенок?» —  эти вопросы долго мучили родительские сердца.  Прошло время, и они нашли ответы. Об этом смотрите в программе…','Виктор Алексеенко',21,'2014-07-28 07:09:13',0,'youtubeLink'),
	(1741,73,'SVCU00913','Рай в шалаше','Правдиво ли выражение: «С милым рай и в шалаше»? Как превратить маленькую комнатушку в уютное гнездышко? Юра и Ульяна, не понаслышке знают, что значит  жить в тесноте, да еще и с двумя маленькими детьми. Собственными руками они создали в своем шалаше настоящий рай. Супруги уверены, что счастье, не зависит от простора и достатка.','Виктор Алексеенко',21,'2014-07-28 05:10:13',0,'youtubeLink');

/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
