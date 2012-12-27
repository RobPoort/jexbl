-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 27 dec 2012 om 13:45
-- Serverversie: 5.5.16
-- PHP-Versie: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `j25`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `localtest_jexbooking_arrangements`
--

CREATE TABLE IF NOT EXISTS `localtest_jexbooking_arrangements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `desc` varchar(500) NOT NULL,
  `start_date` int(150) NOT NULL,
  `end_date` int(150) NOT NULL,
  `nights` int(3) NOT NULL,
  `price` float(10,2) NOT NULL,
  `is_pa` int(1) NOT NULL DEFAULT '1',
  `required` int(1) NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `localtest_jexbooking_attributes`
--

CREATE TABLE IF NOT EXISTS `localtest_jexbooking_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `desc` varchar(500) NOT NULL,
  `price` float(10,2) NOT NULL,
  `is_pn` int(1) NOT NULL DEFAULT '1',
  `has_price` int(1) NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_attributes`
--

INSERT INTO `localtest_jexbooking_attributes` (`id`, `name`, `desc`, `price`, `is_pn`, `has_price`, `published`) VALUES
(1, 'hond', '1 hond is bij ons toegestaan', 3.00, 1, 1, 1),
(2, 'bijzettent', 'een bijzettent voor bijvoorbeeld uw kinderen', 4.00, 1, 1, 1),
(3, 'avondzon', 'een kampeerplaats met volop zon ''s avonds', 0.00, 1, 0, 1),
(4, 'visplek', 'een kampeerplaats aan de visvijver', 0.00, 1, 0, 1),
(5, 'WiFi', 'Draadloos internet', 2.75, 1, 1, 1),
(6, 'Koelie', 'Een slaafje voor bij de tent, handig!', 34.00, 1, 1, 1),
(7, 'Geisha', 'Een Geisha voor meneer, voor als mevrouw ook eens rust wil!', 45.90, 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `localtest_jexbooking_default_prices`
--

CREATE TABLE IF NOT EXISTS `localtest_jexbooking_default_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `min_price` float(10,2) NOT NULL,
  `is_pn_min_price` int(1) NOT NULL DEFAULT '1',
  `extra` float(10,2) NOT NULL,
  `is_pn_extra` int(1) NOT NULL DEFAULT '1',
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `localtest_jexbooking_location`
--

CREATE TABLE IF NOT EXISTS `localtest_jexbooking_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `desc` varchar(500) NOT NULL,
  `total_number` int(11) NOT NULL,
  `available_number` int(11) NOT NULL,
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_location`
--

INSERT INTO `localtest_jexbooking_location` (`id`, `type_id`, `name`, `desc`, `total_number`, `available_number`, `published`) VALUES
(5, 7, 'trekkershut', 'simpele bungalow voor wandelaars', 50, 46, 1),
(4, 6, 'standaardplaats (sp)', 'standaard kampeerplek', 40, 29, 1),
(6, 6, 'diamantsnip', 'luxe, 6-persoons bungalow', 8, 6, 1),
(7, 5, 'comfortplaats', 'luxe kampeerplek, met internetaansluiting', 25, 23, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `localtest_jexbooking_type`
--

CREATE TABLE IF NOT EXISTS `localtest_jexbooking_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `desc` varchar(500) NOT NULL,
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_type`
--

INSERT INTO `localtest_jexbooking_type` (`id`, `name`, `desc`, `published`) VALUES
(5, 'kampeerplek', 'voor mensen met een tentje of huifkarretje of paddestoelhuisje', 1),
(6, 'bungalow', 'voor mensen in een huisje', 1),
(7, 'trekkershuisje', 'voor wandelaars in een huisje', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `localtest_jexbooking_xref_attributes`
--

CREATE TABLE IF NOT EXISTS `localtest_jexbooking_xref_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `default_id` int(11) NOT NULL,
  `arr_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_xref_attributes`
--

INSERT INTO `localtest_jexbooking_xref_attributes` (`id`, `attribute_id`, `type_id`, `location_id`, `default_id`, `arr_id`) VALUES
(43, 2, 0, 5, 0, 0),
(42, 1, 0, 5, 0, 0),
(27, 2, 5, 0, 0, 0),
(19, 2, 6, 0, 0, 0),
(20, 5, 6, 0, 0, 0),
(21, 7, 6, 0, 0, 0),
(22, 1, 7, 0, 0, 0),
(23, 2, 7, 0, 0, 0),
(24, 4, 7, 0, 0, 0),
(50, 6, 0, 4, 0, 0),
(49, 2, 0, 4, 0, 0),
(41, 4, 0, 7, 0, 0),
(44, 4, 0, 5, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
