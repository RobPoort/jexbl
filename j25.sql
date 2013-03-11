-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 11 mrt 2013 om 10:23
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
(1, 6, 6, 'diamantsnip normaal', 'diamantsnip normaal tarief midweek', '18-01-2013', '25-01-2013', 5, 650.75, 51.25, 0, 0, 1, 0, 1),
(2, 4, 6, 'standaardplaats voorjaarsvoordeel', 'sp voorjaars arrangement', '10-03-2013', '26-03-2013', 0, 805.30, 75.50, 1, 1, 0, 1, 1),
(3, 5, 6, 'Frisse, fruitige lente maand', '', '04-05-2013', '04-06-2013', 0, 765.80, 0.00, 0, 0, 1, 1, 1),
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
  `is_discount` int(1) NOT NULL DEFAULT '0',
  `is_discount_subtotal` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_attributes`
--

INSERT INTO `localtest_jexbooking_attributes` (`id`, `name`, `desc`, `price`, `is_pn`, `is_pp`, `has_price`, `has_number`, `published`, `is_special`, `is_required`, `percent`, `percent_desc`, `use_percent`, `special_price`, `special_price_desc`, `use_special_price`, `is_pp_special`, `is_pn_special`, `is_discount`, `is_discount_subtotal`) VALUES
(1, 'hond', '1 hond is bij ons toegestaan', 3.00, 1, 0, 1, 1, 1, 0, 0, 0.00, '', 0, 0.00, '', 0, 0, 0, 0, 0),
(2, 'bijzettent', 'een bijzettent voor bijvoorbeeld uw kinderen', 4.00, 1, 0, 1, 1, 1, 0, 0, 0.00, '', 0, 0.00, '', 0, 0, 0, 0, 0),
(3, 'avondzon', 'een kampeerplaats met volop zon ''s avonds', 0.00, 1, 0, 0, 0, 1, 0, 0, 0.00, '', 0, 0.00, '', 0, 0, 0, 0, 0),
(4, 'visplek', 'een kampeerplaats aan de visvijver', 0.00, 1, 0, 0, 0, 1, 0, 0, 0.00, '', 0, 0.00, '', 0, 0, 0, 0, 0),
(5, 'WiFi', 'Draadloos internet', 2.75, 0, 1, 1, 0, 1, 0, 0, 0.00, '', 0, 0.00, '', 0, 0, 0, 0, 0),
(6, 'Koelie', 'Een slaafje voor bij de tent, handig!', 0.00, 0, 0, 1, 0, 1, 1, 0, 10.00, '', 0, 50.00, 'Onze koelies zijn razendsnel', 1, 0, 0, 1, 1),
(8, 'annuleringsverzekering', 'U kunt, tegen redelijk tarief, een annuleringsverzekering afsluiten', 0.00, 0, 0, 1, 0, 1, 1, 1, 15.20, '5,5 % annuleringskosten en 9,7% assurantie kosten', 1, 3.50, '3,50 administratiekosten', 1, 0, 0, 0, 0),
(10, 'afvalbijdrage', 'Kosten die gerekend worden voor het ophalen van vuilnis', 0.00, 0, 0, 1, 1, 1, 1, 1, 5.00, '', 0, 2.00, '', 1, 0, 1, 0, 0),
(9, 'schoonmaakkosten', 'Na uw verblijf worden de bedden opgemaakt', 0.00, 0, 0, 1, 1, 1, 1, 1, 0.00, '', 0, 4.00, '4 euro schoonmaakkosten', 1, 1, 0, 0, 0),
(11, 'kortingskaart plus', 'geef een Papillon-kortingskaart cadeau!', 0.00, 0, 0, 1, 0, 1, 1, 0, 25.20, 'kortingskaart', 1, 3.50, '3,50 ', 1, 0, 0, 1, 0),
(12, 'annuleringsverzekering plus deLuxe', 'U kunt, tegen redelijk tarief, een annuleringsverzekering afsluiten', 0.00, 0, 0, 1, 0, 1, 1, 0, 25.20, '5,5 % annuleringskosten en 19,7% assurantie kosten', 1, 3.50, '3,50 administratiekosten', 1, 0, 0, 0, 0),
(13, 'Seniorenkorting', '20% korting op de verblijfskosten, indien tenminste 2 leden van uw groep boven de 50 zijn', 0.00, 0, 0, 1, 1, 1, 1, 0, 20.00, '20% korting op de verblijfskosten, indien tenminste 2 leden van uw groep boven de 50 zijn', 1, 54.00, 'Korting op de verblijfskosten, indien tenminste 2 leden van uw groep boven de 50 zijn', 0, 0, 0, 1, 1),
(14, 'Papillon Jubileum Korting', 'Om te vieren dat we zoveel jaar bestaan, een korting over uw prijs!', 0.00, 0, 0, 1, 1, 1, 1, 1, 5.00, 'Om te vieren dat we zoveel jaar bestaan, een korting over uw prijs!', 1, 21.00, '', 1, 0, 0, 1, 1);

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
  `is_default` int(1) NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Gegevens worden uitgevoerd voor tabel `localtest_jexbooking_default_prices`
--

INSERT INTO `localtest_jexbooking_default_prices` (`id`, `location_id`, `name`, `start_date`, `end_date`, `min_price`, `is_pn_min_price`, `extra`, `is_pn_extra`, `is_default`, `published`) VALUES
(1, 4, 'sp voorjaarstarief', '16-01-2013', '12-05-2013', 5.50, 1, 3.50, 1, 0, 1),
(2, 4, 'sp hoogseizoen', '12-05-2013', '20-06-2013', 9.50, 1, 3.75, 1, 0, 1),
(6, 7, 'zonnezicht januari', '11-01-2013', '31-01-2013', 0.00, 1, 0.00, 1, 0, 1),
(7, 4, 'sp American Summer', '20-06-2013', '01-08-2013', 6.00, 1, 4.00, 1, 0, 1),
(8, 4, 'sp defaults', '01-02-2013', '28-02-2014', 5.00, 1, 0.00, 1, 1, 1),
(9, 4, 'sp naseizoen', '01-08-2013', '12-09-2013', 6.00, 1, 4.00, 1, 0, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `localtest_jexbooking_fixedbookings`
--

CREATE TABLE IF NOT EXISTS `localtest_jexbooking_fixedbookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `start_day` varchar(10) NOT NULL,
  `end_day` varchar(10) NOT NULL,
  `is_pp` int(1) NOT NULL DEFAULT '0',
  `price` double(9,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
(4, 5, 'standaardplaats (sp)', 'standaard kampeerplek', 40, 29, 1),
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=478 ;

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
(373, 6, 0, 0, 0, 3),
(372, 4, 0, 0, 0, 3),
(62, 5, 0, 6, 0, 0),
(61, 3, 0, 6, 0, 0),
(477, 14, 0, 4, 0, 0),
(476, 13, 0, 4, 0, 0),
(475, 12, 0, 4, 0, 0),
(474, 11, 0, 4, 0, 0),
(41, 4, 0, 7, 0, 0),
(72, 1, 0, 5, 0, 0),
(60, 1, 0, 6, 0, 0),
(63, 7, 0, 6, 0, 0),
(443, 11, 0, 0, 0, 2),
(442, 9, 0, 0, 0, 2),
(340, 9, 0, 0, 0, 1),
(339, 8, 0, 0, 0, 1),
(441, 10, 0, 0, 0, 2),
(440, 8, 0, 0, 0, 2),
(439, 6, 0, 0, 0, 2),
(438, 5, 0, 0, 0, 2),
(437, 4, 0, 0, 0, 2),
(371, 3, 0, 0, 0, 3),
(173, 2, 0, 0, 0, 4),
(174, 3, 0, 0, 0, 4),
(175, 4, 0, 0, 0, 4),
(176, 5, 0, 0, 0, 4),
(177, 6, 0, 0, 0, 4),
(178, 7, 0, 0, 0, 4),
(338, 6, 0, 0, 0, 1),
(337, 5, 0, 0, 0, 1),
(336, 4, 0, 0, 0, 1),
(436, 3, 0, 0, 0, 2),
(435, 2, 0, 0, 0, 2),
(434, 1, 0, 0, 0, 2),
(473, 10, 0, 4, 0, 0),
(472, 8, 0, 4, 0, 0),
(471, 6, 0, 4, 0, 0),
(470, 5, 0, 4, 0, 0),
(469, 4, 0, 4, 0, 0),
(468, 3, 0, 4, 0, 0),
(467, 2, 0, 4, 0, 0),
(466, 1, 0, 4, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
