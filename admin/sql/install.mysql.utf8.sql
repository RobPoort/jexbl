CREATE TABLE IF NOT EXISTS `#__jexbooking_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `desc` varchar(500) NOT NULL,
  `total_number` int(11) NOT NULL,
  `available_number` int(11) NOT NULL,
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#__jexbooking_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `desc` varchar(500) NOT NULL,
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#__jexbooking_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `desc` varchar(500) NOT NULL,
  `price` float(10,2) NOT NULL,
  `is_pn` int(1) NOT NULL DEFAULT '1',
  `has_price` int(1) NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#__jexbooking_default_prices` (
	`id` int(11)NOT NULL AUTO_INCREMENT,
	`min_price` float(10,2) NOT NULL,
	`is_pn_min_price` int(1) NOT NULL DEFAULT '1',
	`extra` float(10,2) NOT NULL,
	`is_pn_extra` int(1) NOT NULL DEFAULT '1',
	`published` int(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#__jexbooking_arrangements` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#__jexbooking_xref_attributes` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`attribute_id` int(11) NOT NULL,
	`type_id` int(11) NOT NULL,
	`location_id` int(11) NOT NULL,
	`default_id` int(11) NOT NULL,
	`arr_id` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;