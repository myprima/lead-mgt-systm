CREATE TABLE `threads` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `last_update` datetime NOT NULL default '0000-00-00 00:00:00',
  `stats_id` int(8) unsigned NOT NULL default '0',
  `status` enum('start','init','run','paused','waiting','finished','aborted') NOT NULL default 'start',
  `local_no` tinyint(4) NOT NULL default '0',
  `smtp` varchar(30) NOT NULL default '',
  `counter` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
);
