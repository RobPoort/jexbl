-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 04 jan 2013 om 19:18
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
  `location_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `desc` varchar(500) NOT NULL,
  `start_date` varchar(10) NOT NULL,
  `end_date` varchar(10) NOT NULL,
  `nights` int(3) NOT NULL,
  `price` float(10,2) NOT NULL,
  `is_pa` int(1) NOT NULL DEFAULT '1',
  `required` int(1) NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_arrangements`
--

INSERT INTO `localtest_jexbooking_arrangements` (`id`, `location_id`, `type_id`, `name`, `desc`, `start_date`, `end_date`, `nights`, `price`, `is_pa`, `required`, `published`) VALUES
(1, 6, 6, 'diamantsnip normaal', 'diamantsnip normaal tarief midweek', '18-01-2013', '25-01-2013', 5, 650.75, 1, 0, 1),
(2, 4, 5, 'standaardplaats (sp)', 'sp voorjaars arrangement', '15-03-2013', '31-01-2013', 0, 5.00, 0, 0, 1),
(3, 4, 5, 'Frisse, fruitige lente week', '', '15-04-2013', '21-04-2013', 0, 0.00, 1, 0, 1),
(4, 5, 6, 'hooikiep juni', '', '04-06-2013', '18-06-2013', 0, 500.00, 1, 0, 1);

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
  `location_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `start_date` varchar(10) NOT NULL,
  `end_date` varchar(10) NOT NULL,
  `min_price` float(10,2) NOT NULL,
  `is_pn_min_price` int(1) NOT NULL DEFAULT '1',
  `extra` float(10,2) NOT NULL,
  `is_pn_extra` int(1) NOT NULL DEFAULT '1',
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_default_prices`
--

INSERT INTO `localtest_jexbooking_default_prices` (`id`, `location_id`, `name`, `start_date`, `end_date`, `min_price`, `is_pn_min_price`, `extra`, `is_pn_extra`, `published`) VALUES
(1, 4, 'sp mei', '16-01-2013', '12-10-2013', 8.50, 1, 3.50, 0, 1),
(2, 4, 'sp juli', '13-10-2013', '09-01-2014', 9.50, 1, 3.75, 1, 1),
(6, 7, 'zonnezicht januari', '11-01-2013', '31-01-2013', 0.00, 1, 0.00, 1, 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_location`
--

INSERT INTO `localtest_jexbooking_location` (`id`, `type_id`, `name`, `desc`, `total_number`, `available_number`, `published`) VALUES
(5, 7, 'hooikiep', 'simpele hut voor wandelaars', 50, 46, 1),
(4, 6, 'standaardplaats (sp)', 'standaard kampeerplek', 40, 29, 1),
(6, 6, 'diamantsnip', 'luxe, 6-persoons bungalow', 8, 6, 1),
(7, 5, 'comfortplaats', 'luxe kampeerplek, met internetaansluiting', 25, 23, 1),
(9, 7, 'hooikiep luxe', 'luxere uitvoering van de hooikiep', 0, 0, 0);

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
(7, 'hooikiep', 'ouderwets overnachten in een hooiberg', 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_xref_attributes`
--

INSERT INTO `localtest_jexbooking_xref_attributes` (`id`, `attribute_id`, `type_id`, `location_id`, `default_id`, `arr_id`) VALUES
(74, 4, 0, 5, 0, 0),
(73, 2, 0, 5, 0, 0),
(27, 2, 5, 0, 0, 0),
(19, 2, 6, 0, 0, 0),
(20, 5, 6, 0, 0, 0),
(21, 7, 6, 0, 0, 0),
(100, 6, 0, 0, 0, 3),
(99, 4, 0, 0, 0, 3),
(98, 3, 0, 0, 0, 3),
(62, 5, 0, 6, 0, 0),
(61, 3, 0, 6, 0, 0),
(67, 2, 0, 0, 0, 1),
(66, 1, 0, 0, 0, 1),
(50, 6, 0, 4, 0, 0),
(49, 2, 0, 4, 0, 0),
(41, 4, 0, 7, 0, 0),
(72, 1, 0, 5, 0, 0),
(60, 1, 0, 6, 0, 0),
(63, 7, 0, 6, 0, 0),
(91, 4, 0, 0, 0, 2),
(90, 1, 0, 0, 0, 2),
(68, 4, 0, 0, 0, 1),
(69, 5, 0, 0, 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
