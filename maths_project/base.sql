-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 29, 2015 at 09:20 PM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `etna_projet_maths`
--

--
-- Dumping data for table `Digit`
--

INSERT INTO `Digit` (`id`, `polynome_id`, `position`, `value`) VALUES
(129, 33, 0, -1),
(130, 33, 1, 0),
(131, 33, 2, 12),
(132, 33, 3, -16),
(133, 34, 0, -1),
(134, 34, 1, 3),
(135, 34, 2, -3),
(136, 34, 3, 1),
(193, 49, 0, 34),
(194, 49, 1, 23),
(195, 49, 2, 34),
(196, 49, 3, 87),
(197, 50, 0, -23),
(198, 50, 1, 43),
(199, 50, 2, 35),
(200, 50, 3, 53),
(201, 51, 0, -1),
(202, 51, 1, 6),
(203, 51, 2, -11),
(204, 51, 3, 6);

--
-- Dumping data for table `Polynome`
--

INSERT INTO `Polynome` (`id`, `degre`, `nom`, `resultat`, `concat_form`) VALUES
(33, 3, 'Exemple 2', '0', ''),
(34, 3, 'Exemple 3', '0', ''),
(49, 3, 'test ph2', 'test ph287342334', NULL),
(50, 3, 'test2 ph2', 'test2 ph2533543-23', NULL),
(51, 3, 'Exemple 1', 'Exemple 16-116-1', NULL);
