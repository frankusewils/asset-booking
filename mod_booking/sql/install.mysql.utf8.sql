CREATE TABLE IF NOT EXISTS `#__booking_calendar` (
  `id` int(11) unsigned NOT NULL auto_increment,  
  `name` varchar(20) NOT NULL default '', 
  `start_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `remarks` text NOT NULL,  
  `price` smallint NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ;
