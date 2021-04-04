# create admin table
CREATE TABLE IF NOT EXISTS `admin` (
  `username` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastlogin` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL default '1',
  `superadmin` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`username`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='PostVis Admin';
INSERT INTO `admin` (`username`, `password`, `domain`, `created`, `modified`, `lastlogin`, `active`, `superadmin`) VALUES
('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '', '2006-09-07 09:02:46', '2009-02-01 11:09:52', '2009-05-23 16:24:46', 1, 1);

# mise à jour pour le domaine
update admin set domain = 'com.borysiak' where username='admin';

# ajout dans policy
alter table policy add spam_modifies_subj   char(1) default NULL;

alter table policy add message_size_limit  integer     default NULL;
UPDATE policy set message_size_limit = 0;