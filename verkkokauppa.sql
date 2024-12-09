-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 13.11.2024 klo 06:50
-- Palvelimen versio: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `verkkokauppa`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Rakenne taululle `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nimi` varchar(255) NOT NULL,
  `kuvaus` text NOT NULL,
  `hinta` decimal(10,2) NOT NULL,
  `kuva_url` varchar(255) NOT NULL,
  `kategoria` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Vedos taulusta `products`
--

INSERT INTO `products` (`id`, `nimi`, `kuvaus`, `hinta`, `kuva_url`, `kategoria`) VALUES
(1, 'Product 1', 'Description of Product 1', '10.00', 'https://placehold.co/600x400/000000/FFFFFF/png', 1),
(2, 'Product 2', 'Description of Product 2', '20.00', 'https://placehold.co/600x400', 2),
(3, 'Product 3', 'Description of Product 3', '30.00', 'https://placehold.co/600x400/000000/FFFFFF/png', 3),
(4, 'Product 4', 'Description of Product 4', '40.00', 'https://placehold.co/600x400', 4),
(5, 'Product 5', 'Description of Product 5', '50.00', 'https://placehold.co/600x400', 1),
(6, 'Product 6', 'Description of Product 6', '60.00', 'https://placehold.co/600x400', 2),
(7, 'Product 7', 'Description of Product 7', '70.00', 'https://placehold.co/600x400', 3),
(8, 'Product 8', 'Description of Product 8', '80.00', 'https://placehold.co/600x400', 4);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
