CREATE TABLE IF NOT EXISTS `#__asset_booking` (
  `id` int(11) unsigned NOT NULL auto_increment,  
  `name` varchar(20) NOT NULL default '', 
  `start_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `remarks` text NOT NULL,  
  `price` smallint,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ;

INSERT INTO `#__asset_booking` (id, name, start_date, end_date, remarks)
SELECT id, name, start_date, end_date, remarks FROM `#__avail_calendar`;
