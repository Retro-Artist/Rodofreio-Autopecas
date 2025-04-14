-- Database backup for rodofreios
-- Generated on 2025-04-14 10:48:51
-- Using PHP PDO Backup Method

SET FOREIGN_KEY_CHECKS = 0;


-- Table structure for table `categories`

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text,
  `parent_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table `categories`
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `created_at`) VALUES
('1', 'Alimentação/ Refrigeração', 'alimentacao-refrigeracao', 'Sistemas de freios e componentes', NULL, '2025-03-11 18:08:17'),
('2', 'Eixo/ Freios/ Suspensão', 'eixo-freios-suspensao', '', NULL, '2025-03-11 18:08:17'),
('3', 'Motor', 'motor', 'Peças e sistemas de motor', NULL, '2025-03-11 18:08:17'),
('4', 'Admissão/ Escape', 'admissao-escape', '', NULL, '2025-03-11 18:08:17'),
('5', 'Cardan/ Diferencial', 'cardan-diferencial', '', NULL, '2025-03-11 18:08:17'),
('6', 'Câmbio', 'cambio', 'Acessórios para veículos', NULL, '2025-03-11 18:08:17'),
('11', 'Direção', 'direcao', '', NULL, '2025-03-29 08:08:17'),
('12', 'Cabine', 'cabine', '', NULL, '2025-03-29 08:08:34'),
('13', 'Elétrica', 'eletrica', '', NULL, '2025-03-29 08:09:10'),
('14', 'Diversos', 'diversos', '', NULL, '2025-03-29 08:09:29'),
('15', 'Carretas em Geral', 'carretas-em-geral', '', NULL, '2025-03-29 08:10:13'),
('16', 'Categoria teste', 'categoria-teste', 'teste', '1', '2025-04-11 17:12:04');


-- Table structure for table `posts`

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text,
  `description` varchar(500) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `status` varchar(20) DEFAULT 'draft',
  `main_picture` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `featured` tinyint(1) DEFAULT '0',
  `availability` tinyint(1) NOT NULL DEFAULT '1',
  `original_code` varchar(100) DEFAULT NULL,
  `manufacturer_code` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `user_id` (`user_id`),
  KEY `type_status` (`status`),
  KEY `posts_ibfk_1` (`category_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table `posts`
INSERT INTO `posts` (`id`, `title`, `content`, `description`, `tags`, `category_id`, `status`, `main_picture`, `slug`, `user_id`, `created_at`, `updated_at`, `featured`, `availability`, `original_code`, `manufacturer_code`) VALUES
('26', 'Turbina Scania Evolução S/Válvula Holset', 'TURBINA SC-124 S/4-S/5 EVOLUCAO 380/400/420 ( S/VALVULA ) HOLSET', 'TURBINA SC-124 S/4-S/5 EVOLUCAO 380/400/420 ( S/VALVULA ) HOLSET', 'Turbina, Holset, Escape, Scania, Evolução, Original, 5644541, SC 124', '4', 'published', 'product_67f7d6efe069d.png', 'turbina-scania-evolucao-s-valvula-holset', '3', '2025-04-10 10:54:39', '2025-04-10 11:35:21', '1', '1', '5644541', '5644541');


-- Table structure for table `product_events`

DROP TABLE IF EXISTS `product_events`;
CREATE TABLE `product_events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `event_type` enum('view','cart_add','whatsapp_click') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `event_type` (`event_type`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `product_events_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=332 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table `product_events`
INSERT INTO `product_events` (`id`, `product_id`, `event_type`, `created_at`, `user_ip`, `user_agent`, `referrer`) VALUES
('146', '26', 'view', '2025-04-10 11:00:40', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('147', '26', 'view', '2025-04-10 11:00:52', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('148', '26', 'view', '2025-04-10 11:02:09', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('149', '26', 'view', '2025-04-10 11:03:29', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('150', '26', 'whatsapp_click', '2025-04-10 11:03:35', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('151', '26', 'whatsapp_click', '2025-04-10 11:03:35', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('152', '26', 'cart_add', '2025-04-10 11:04:37', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('153', '26', 'whatsapp_click', '2025-04-10 11:05:15', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('154', '26', 'whatsapp_click', '2025-04-10 11:07:05', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('155', '26', 'whatsapp_click', '2025-04-10 11:07:31', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('156', '26', 'view', '2025-04-10 11:12:06', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('157', '26', 'view', '2025-04-10 11:27:36', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('158', '26', 'view', '2025-04-10 11:30:39', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('159', '26', 'view', '2025-04-10 11:30:41', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('160', '26', 'view', '2025-04-10 11:30:42', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('161', '26', 'view', '2025-04-10 11:30:43', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('162', '26', 'view', '2025-04-10 11:30:43', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('163', '26', 'view', '2025-04-10 11:30:44', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('164', '26', 'view', '2025-04-10 11:30:44', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('165', '26', 'view', '2025-04-10 11:34:31', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('166', '26', 'view', '2025-04-10 11:35:09', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('167', '26', 'view', '2025-04-10 11:35:25', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('168', '26', 'view', '2025-04-10 12:02:31', '45.6.176.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('169', '26', 'view', '2025-04-10 13:17:42', '138.99.105.60', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('170', '26', 'view', '2025-04-10 13:17:55', '138.99.105.60', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('171', '26', 'view', '2025-04-10 13:17:59', '138.99.105.60', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('172', '26', 'view', '2025-04-10 13:23:30', '138.99.105.60', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('173', '26', 'view', '2025-04-10 14:17:24', '138.186.111.255', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Mobile Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('174', '26', 'view', '2025-04-10 14:21:01', '138.186.111.255', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Mobile Safari/537.36', 'https://rodofreios.com.br/produto/turbina-scania-evolucao-s-valvula-holset'),
('175', '26', 'view', '2025-04-10 14:23:07', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('178', '26', 'view', '2025-04-10 14:25:30', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('179', '26', 'view', '2025-04-10 16:48:31', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('180', '26', 'whatsapp_click', '2025-04-10 16:48:34', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('181', '26', 'whatsapp_click', '2025-04-10 16:48:34', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('182', '26', 'cart_add', '2025-04-10 16:48:46', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('183', '26', 'cart_add', '2025-04-10 16:48:46', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('184', '26', 'whatsapp_click', '2025-04-10 16:48:51', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('185', '26', 'view', '2025-04-10 16:50:55', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('186', '26', 'view', '2025-04-10 16:51:02', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('187', '26', 'cart_add', '2025-04-10 16:51:06', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('188', '26', 'whatsapp_click', '2025-04-10 16:51:17', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('189', '26', 'whatsapp_click', '2025-04-10 16:52:18', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('190', '26', 'whatsapp_click', '2025-04-10 16:52:18', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('191', '26', 'view', '2025-04-10 16:58:17', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('192', '26', 'view', '2025-04-10 16:58:27', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('193', '26', 'whatsapp_click', '2025-04-10 16:58:29', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('194', '26', 'whatsapp_click', '2025-04-10 16:58:29', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('195', '26', 'cart_add', '2025-04-10 16:58:38', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('196', '26', 'whatsapp_click', '2025-04-10 16:58:41', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('206', '26', 'view', '2025-04-11 09:11:36', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('228', '26', 'cart_add', '2025-04-11 09:25:22', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/teste'),
('229', '26', 'whatsapp_click', '2025-04-11 09:25:22', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/teste'),
('230', '26', 'whatsapp_click', '2025-04-11 09:25:23', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/teste'),
('231', '26', 'cart_add', '2025-04-11 09:25:23', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/teste'),
('232', '26', 'view', '2025-04-11 09:30:01', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('233', '26', 'cart_add', '2025-04-11 09:30:02', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('234', '26', 'view', '2025-04-11 09:55:07', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('237', '26', 'view', '2025-04-11 10:06:54', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('252', '26', 'view', '2025-04-11 16:08:01', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('253', '26', 'view', '2025-04-11 16:08:59', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('258', '26', 'view', '2025-04-11 16:32:12', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('259', '26', 'view', '2025-04-11 16:32:18', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', NULL),
('260', '26', 'view', '2025-04-11 16:35:51', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', NULL),
('277', '26', 'view', '2025-04-11 16:55:40', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('278', '26', 'cart_add', '2025-04-11 16:55:48', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('279', '26', 'view', '2025-04-11 16:55:51', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', NULL),
('280', '26', 'view', '2025-04-11 16:56:14', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produtos'),
('281', '26', 'cart_add', '2025-04-11 16:56:17', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('282', '26', 'view', '2025-04-11 17:01:07', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', NULL),
('286', '26', 'view', '2025-04-11 17:04:47', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/aaaaaa'),
('287', '26', 'view', '2025-04-11 17:09:09', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', NULL),
('297', '26', 'view', '2025-04-11 17:20:03', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/admin/index.php?page=product_list'),
('298', '26', 'cart_add', '2025-04-11 17:59:49', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('299', '26', 'whatsapp_click', '2025-04-11 17:59:52', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('300', '26', 'view', '2025-04-11 17:59:55', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('301', '26', 'whatsapp_click', '2025-04-11 17:59:56', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('302', '26', 'whatsapp_click', '2025-04-11 17:59:56', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('304', '26', 'view', '2025-04-14 10:03:12', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produtos'),
('305', '26', 'whatsapp_click', '2025-04-14 10:03:19', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('306', '26', 'whatsapp_click', '2025-04-14 10:03:19', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('307', '26', 'view', '2025-04-14 10:04:50', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', NULL),
('308', '26', 'cart_add', '2025-04-14 10:04:53', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('309', '26', 'cart_add', '2025-04-14 10:04:53', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('310', '26', 'cart_add', '2025-04-14 10:04:53', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('311', '26', 'whatsapp_click', '2025-04-14 10:04:55', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('312', '26', 'view', '2025-04-14 10:05:09', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('313', '26', 'cart_add', '2025-04-14 10:05:11', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('314', '26', 'whatsapp_click', '2025-04-14 10:05:12', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('315', '26', 'whatsapp_click', '2025-04-14 10:05:18', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('316', '26', 'whatsapp_click', '2025-04-14 10:05:18', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('317', '26', 'cart_add', '2025-04-14 10:08:19', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('318', '26', 'whatsapp_click', '2025-04-14 10:08:21', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('319', '26', 'view', '2025-04-14 10:08:39', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('320', '26', 'cart_add', '2025-04-14 10:08:42', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('321', '26', 'whatsapp_click', '2025-04-14 10:08:45', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('322', '26', 'view', '2025-04-14 10:10:09', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produtos'),
('323', '26', 'cart_add', '2025-04-14 10:10:12', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('324', '26', 'whatsapp_click', '2025-04-14 10:10:15', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('325', '26', 'view', '2025-04-14 10:12:02', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('326', '26', 'cart_add', '2025-04-14 10:12:03', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('327', '26', 'whatsapp_click', '2025-04-14 10:12:06', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produto/turbina-scania-evolucao-s-valvula-holset'),
('328', '26', 'cart_add', '2025-04-14 10:12:18', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('329', '26', 'whatsapp_click', '2025-04-14 10:12:20', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/'),
('330', '26', 'cart_add', '2025-04-14 10:12:26', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produtos'),
('331', '26', 'whatsapp_click', '2025-04-14 10:12:28', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'http://localhost:8888/produtos');


-- Table structure for table `product_images`

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;


-- Table structure for table `users`

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
('3', 'rodofreios', '$2y$10$OhKoCTYTjeCdOXn8hNepU.FI62uWQPSPNeYFfD3gUlLLM.ZwZhpvW', 'admin@simplesquare.local', '2025-03-18 20:54:03');

SET FOREIGN_KEY_CHECKS = 1;
