-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           5.7.26 - MySQL Community Server (GPL)
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para e-commerce
CREATE DATABASE IF NOT EXISTS `e-commerce` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `e-commerce`;

-- Copiando estrutura para tabela e-commerce.ads
CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_category` int(11) DEFAULT NULL,
  `state` tinyint(4) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela e-commerce.ads: 3 rows
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;
INSERT INTO `ads` (`id`, `id_user`, `id_category`, `state`, `price`, `title`, `description`) VALUES
	(11, 1, 1, 1, 38.19, 'Microsoft Xbox 360 Wired Controller for Windows & Xbox 360 Console', ' Play in comfort - A compact, ergonomic shape lets you play comfortably for hours on your PC or Xbox 360'),
	(12, 1, 2, 0, 12.99, 'FLY HAWK Mens Dress Shirts, Fitted Bamboo Fiber Short Sleeve Elastic Casual Button Down Shirts', ' Short Sleeved Dress Shirts for Mens: 25% Bamboo Fiber,40% Tencel, 30% Cotton and 5% Spandex. 8 color options for choice'),
	(10, 1, 1, 1, 98.32, 'Smartphone Samsung Galaxy Gran Prime Duos', 'The GALAXY Grand Prime has a 5 MP front camera with an ultra-wide view angle of 85 degrees. Other Features include: Wi-Fi 802.11 b/g/n, Wi-Fi Direct, Wi-Fi hotspot, Bluetooth: v4.0, A2DP, USB: v2.0, GPS: with A-GPS, Beidou, Browser: HTML5, Messaging: SMS (threaded view), MMS, Email, Push Mail, IM, Li-Ion 2600 mAh Battery, Talk time: (2G) / Up to 17 Hours (3G)');
/*!40000 ALTER TABLE `ads` ENABLE KEYS */;

-- Copiando estrutura para tabela e-commerce.ads_images
CREATE TABLE IF NOT EXISTS `ads_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ad` int(11) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela e-commerce.ads_images: 5 rows
/*!40000 ALTER TABLE `ads_images` DISABLE KEYS */;
INSERT INTO `ads_images` (`id`, `id_ad`, `url`) VALUES
	(1, 10, 'assets/images/ads/9f089c56048c81b5e9db9babd29d02dc.jpg'),
	(2, 10, 'assets/images/ads/3962e8a59482c164308a6219b8ea2b2a.jpg'),
	(3, 10, 'assets/images/ads/c590d4cbfa71a36d3e7ce943e210e000.jpg'),
	(4, 11, 'assets/images/ads/a2306f02c66ff3cadd4f8c2ae7201e50.jpg'),
	(5, 12, 'assets/images/ads/914f9581b7110302fc611a0524b2399a.jpg');
/*!40000 ALTER TABLE `ads_images` ENABLE KEYS */;

-- Copiando estrutura para tabela e-commerce.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela e-commerce.categories: 9 rows
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`) VALUES
	(1, 'Electronics'),
	(2, 'Clothes'),
	(3, 'Fashion'),
	(4, 'Home & Garden'),
	(5, 'Sporting Goods'),
	(6, 'Toy & Hobbies'),
	(7, 'Business & Industrial'),
	(8, 'Health & Beauty'),
	(9, 'Others');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Copiando estrutura para tabela e-commerce.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `pass` varchar(32) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela e-commerce.users: 2 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `pass`, `phone`) VALUES
	(1, 'William', 'william@test.com', '59a9fd481727704f87826fc8ebe6e624', '12345678'),
	(3, 'Test', 'test@gmail.com', '59a9fd481727704f87826fc8ebe6e624', '87654321');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
