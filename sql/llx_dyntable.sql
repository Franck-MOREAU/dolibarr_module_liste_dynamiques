CREATE TABLE IF NOT EXISTS `llx_dyntable` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `default_sortfield` varchar(255) NOT NULL,
  `export_name` varchar(255),
  `context` varchar(255) NOT NULL,
  `search_button` int(1) NOT NULL DEFAULT '1',
  `remove_filter_button` int(1) NOT NULL DEFAULT '1',
  `export_button` int(1) NOT NULL DEFAULT '1',
  `select_fields_button` int(1) NOT NULL DEFAULT '1',
  `mode` varchar(255) NOT NULL,
  `limite` int(11) NOT NULL DEFAULT '25',
  `filter_clause` longtext NOT NULL,
  `filter_mode` varchar(255) NOT NULL,
  `filter_line` int(1),
  `sql_from` longtext NOT NULL,
  `sql_where` longtext,
  `sql_having` longtext,
  `sql_group` longtext,
  `sql_filter_action` longtext,
  `sql_select` longtext NOT NULL,
  `subtitle` longtext,
  `active` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB;





