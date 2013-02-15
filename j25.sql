-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 15 feb 2013 om 11:53
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
  `extra_pp` float(10,2) NOT NULL,
  `is_pp` int(1) NOT NULL DEFAULT '1',
  `use_extra_pp` int(1) NOT NULL DEFAULT '0',
  `is_pa` int(1) NOT NULL DEFAULT '1',
  `required` int(1) NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_arrangements`
--

INSERT INTO `localtest_jexbooking_arrangements` (`id`, `location_id`, `type_id`, `name`, `desc`, `start_date`, `end_date`, `nights`, `price`, `extra_pp`, `is_pp`, `use_extra_pp`, `is_pa`, `required`, `published`) VALUES
(1, 6, 6, 'diamantsnip normaal', 'diamantsnip normaal tarief midweek', '18-01-2013', '25-01-2013', 5, 650.75, 0.00, 0, 0, 1, 0, 1),
(2, 4, 6, 'standaardplaats voorjaarsvoordeel', 'sp voorjaars arrangement', '15-02-2013', '25-02-2013', 0, 805.30, 0.00, 0, 0, 0, 1, 1),
(3, 4, 6, 'Frisse, fruitige lente maand', '', '04-05-2013', '04-06-2013', 0, 765.80, 0.00, 0, 0, 1, 1, 1),
(4, 5, 6, 'hooikiep juni', '', '04-06-2013', '18-06-2013', 0, 500.00, 0.00, 1, 0, 1, 0, 1);

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
  `is_pp` int(1) NOT NULL DEFAULT '0',
  `has_price` int(1) NOT NULL DEFAULT '0',
  `has_number` int(1) NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '1',
  `is_special` int(1) NOT NULL DEFAULT '0',
  `is_required` int(1) NOT NULL DEFAULT '0',
  `percent` float(4,2) NOT NULL,
  `percent_desc` varchar(250) NOT NULL,
  `use_percent` int(1) NOT NULL DEFAULT '0',
  `special_price` float(4,2) NOT NULL,
  `special_price_desc` varchar(250) NOT NULL,
  `use_special_price` int(1) NOT NULL DEFAULT '0',
  `is_pp_special` int(1) NOT NULL DEFAULT '0',
  `is_pn_special` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_attributes`
--

INSERT INTO `localtest_jexbooking_attributes` (`id`, `name`, `desc`, `price`, `is_pn`, `is_pp`, `has_price`, `has_number`, `published`, `is_special`, `is_required`, `percent`, `percent_desc`, `use_percent`, `special_price`, `special_price_desc`, `use_special_price`, `is_pp_special`, `is_pn_special`) VALUES
(1, 'hond', '1 hond is bij ons toegestaan', 3.00, 1, 0, 1, 1, 1, 0, 0, 0.00, '', 0, 0.00, '', 0, 0, 0),
(2, 'bijzettent', 'een bijzettent voor bijvoorbeeld uw kinderen', 4.00, 1, 0, 1, 1, 1, 0, 0, 0.00, '', 0, 0.00, '', 0, 0, 0),
(3, 'avondzon', 'een kampeerplaats met volop zon ''s avonds', 0.00, 1, 0, 0, 0, 1, 0, 0, 0.00, '', 0, 0.00, '', 0, 0, 0),
(4, 'visplek', 'een kampeerplaats aan de visvijver', 0.00, 1, 0, 0, 0, 1, 0, 0, 0.00, '', 0, 0.00, '', 0, 0, 0),
(5, 'WiFi', 'Draadloos internet', 2.75, 0, 1, 1, 0, 1, 0, 0, 0.00, '', 0, 0.00, '', 0, 0, 0),
(6, 'Koelie', 'Een slaafje voor bij de tent, handig!', 34.00, 0, 0, 1, 0, 1, 0, 0, 0.00, '', 0, 0.00, '', 0, 0, 0),
(8, 'annuleringsverzekering', 'U kunt, tegen redelijk tarief, een annuleringsverzekering afsluiten', 0.00, 0, 0, 1, 0, 1, 1, 1, 15.20, '5,5 % annuleringskosten en 9,7% assurantie kosten', 1, 3.50, '3,50 administratiekosten', 1, 0, 0),
(10, 'afvalbijdrage', 'Kosten die gerekend worden voor het ophalen van vuilnis', 0.00, 0, 0, 1, 1, 1, 1, 1, 5.00, '', 0, 2.00, '', 1, 0, 1),
(9, 'schoonmaakkosten', 'Na uw verblijf worden de bedden opgemaakt', 0.00, 0, 0, 1, 1, 1, 1, 1, 0.00, '', 0, 4.00, '4 euro schoonmaakkosten', 1, 1, 0),
(11, 'annuleringsverzekering plus', 'U kunt, tegen redelijk tarief, een annuleringsverzekering afsluiten', 0.00, 0, 0, 1, 0, 1, 1, 0, 25.20, '5,5 % annuleringskosten en 19,7% assurantie kosten', 1, 3.50, '3,50 administratiekosten', 1, 0, 0),
(12, 'annuleringsverzekering plus deLuxe', 'U kunt, tegen redelijk tarief, een annuleringsverzekering afsluiten', 0.00, 0, 0, 1, 0, 1, 1, 0, 25.20, '5,5 % annuleringskosten en 19,7% assurantie kosten', 1, 3.50, '3,50 administratiekosten', 1, 0, 0);

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
(6, 7, 'zonnezicht januari', '11-01-2013', '31-01-2013', 0.00, 1, 0.00, 1, 1);

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
(9, 7, 'hooikiep luxe', 'luxere uitvoering van de hooikiep', 0, 0, 1);

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
(7, 'hooikiep', 'ouderwets overnachten in een hooiberg', 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=336 ;

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
(172, 1, 0, 0, 0, 4),
(289, 6, 0, 0, 0, 3),
(288, 4, 0, 0, 0, 3),
(62, 5, 0, 6, 0, 0),
(61, 3, 0, 6, 0, 0),
(329, 11, 0, 4, 0, 0),
(328, 9, 0, 4, 0, 0),
(327, 10, 0, 4, 0, 0),
(326, 8, 0, 4, 0, 0),
(41, 4, 0, 7, 0, 0),
(72, 1, 0, 5, 0, 0),
(60, 1, 0, 6, 0, 0),
(63, 7, 0, 6, 0, 0),
(319, 11, 0, 0, 0, 2),
(318, 9, 0, 0, 0, 2),
(335, 9, 0, 0, 0, 1),
(334, 8, 0, 0, 0, 1),
(317, 10, 0, 0, 0, 2),
(316, 8, 0, 0, 0, 2),
(315, 6, 0, 0, 0, 2),
(314, 5, 0, 0, 0, 2),
(313, 4, 0, 0, 0, 2),
(287, 3, 0, 0, 0, 3),
(173, 2, 0, 0, 0, 4),
(174, 3, 0, 0, 0, 4),
(175, 4, 0, 0, 0, 4),
(176, 5, 0, 0, 0, 4),
(177, 6, 0, 0, 0, 4),
(178, 7, 0, 0, 0, 4),
(333, 6, 0, 0, 0, 1),
(332, 5, 0, 0, 0, 1),
(331, 4, 0, 0, 0, 1),
(312, 3, 0, 0, 0, 2),
(311, 2, 0, 0, 0, 2),
(310, 1, 0, 0, 0, 2),
(325, 6, 0, 4, 0, 0),
(324, 5, 0, 4, 0, 0),
(323, 4, 0, 4, 0, 0),
(322, 3, 0, 4, 0, 0),
(321, 2, 0, 4, 0, 0),
(320, 1, 0, 4, 0, 0),
(330, 12, 0, 4, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
