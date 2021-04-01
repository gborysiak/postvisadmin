SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `postfix`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

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
-- --------------------------------------------------------

--
-- Table structure for table `alias`
--

CREATE TABLE IF NOT EXISTS `alias` (
  `address` varchar(255) NOT NULL,
  `goto` text NOT NULL,
  `domain` varchar(255) NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Postvis Admin - Virtual Aliases';

-- --------------------------------------------------------

--
-- Table structure for table `awl`
--

CREATE TABLE IF NOT EXISTS `awl` (
  `username` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `ip` varchar(10) NOT NULL,
  `count` int(11) default '0',
  `totscore` float default '0',
  `lastupdate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`username`,`email`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bayes_expire`
--

CREATE TABLE IF NOT EXISTS `bayes_expire` (
  `id` int(11) NOT NULL default '0',
  `runtime` int(11) NOT NULL default '0',
  KEY `bayes_expire_idx1` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bayes_global_vars`
--

CREATE TABLE IF NOT EXISTS `bayes_global_vars` (
  `variable` varchar(30) character set utf8 collate utf8_unicode_ci NOT NULL,
  `value` varchar(200) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `bayes_global_vars` (`variable`, `value`) VALUES
('VERSION', '3');
-- --------------------------------------------------------

--
-- Table structure for table `bayes_seen`
--

CREATE TABLE IF NOT EXISTS `bayes_seen` (
  `id` int(11) NOT NULL default '0',
  `msgid` varchar(200) NOT NULL,
  `flag` char(1) NOT NULL,
  PRIMARY KEY  (`id`,`msgid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bayes_token`
--

CREATE TABLE IF NOT EXISTS `bayes_token` (
  `id` int(11) NOT NULL default '0',
  `token` char(5) character set latin1 NOT NULL default '',
  `spam_count` int(11) NOT NULL default '0',
  `ham_count` int(11) NOT NULL default '0',
  `atime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`,`token`),
  KEY `bayes_token_idx1` (`token`),
  KEY `bayes_token_idx2` (`id`,`atime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bayes_vars`
--

CREATE TABLE IF NOT EXISTS `bayes_vars` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(200) character set latin1 NOT NULL default '',
  `spam_count` int(11) NOT NULL default '0',
  `ham_count` int(11) NOT NULL default '0',
  `token_count` int(11) NOT NULL default '0',
  `last_expire` int(11) NOT NULL default '0',
  `last_atime_delta` int(11) NOT NULL default '0',
  `last_expire_reduce` int(11) NOT NULL default '0',
  `oldest_token_age` int(11) NOT NULL default '2147483647',
  `newest_token_age` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `bayes_vars_idx1` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `id` int(11) NOT NULL,
  `option` varchar(10) character set latin1 NOT NULL,
  `value` varchar(10) character set latin1 NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `domain`
--

CREATE TABLE IF NOT EXISTS `domain` (
  `domain` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `aliases` int(10) NOT NULL default '0',
  `mailboxes` int(10) NOT NULL default '0',
  `maxquota` int(10) NOT NULL default '0',
  `transport` varchar(255) default NULL,
  `backupmx` tinyint(1) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='PostVis Admin';

-- --------------------------------------------------------

--
-- Table structure for table `maddr`
--

CREATE TABLE IF NOT EXISTS `maddr` (
  `partition_tag` int(11) default '0',
  `id` int(10) unsigned NOT NULL auto_increment,
  `email` varbinary(255) default NULL,
  `domain` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `part_email` (`partition_tag`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mailaddr`
--

CREATE TABLE IF NOT EXISTS `mailaddr` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `priority` int(11) NOT NULL default '7',
  `email` varbinary(255) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mailbox`
--

CREATE TABLE IF NOT EXISTS `mailbox` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `maildir` varchar(255) NOT NULL,
  `quota` int(10) NOT NULL default '0',
  `domain` varchar(255) NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `quarantine_notify` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Postfix Mailboxes';

-- --------------------------------------------------------

--
-- Table structure for table `msgrcpt`
--

CREATE TABLE IF NOT EXISTS `msgrcpt` (
  `partition_tag` int(11) NOT NULL default '0',
  `mail_id` varchar(12) character set latin1 NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `ds` char(1) character set latin1 NOT NULL,
  `rs` char(1) character set latin1 NOT NULL,
  `bl` char(1) character set latin1 default '',
  `wl` char(1) character set latin1 default '',
  `bspam_level` float default NULL,
  `smtp_resp` varchar(255) default NULL,
  KEY `msgrcpt_idx_mail_id` (`mail_id`),
  KEY `msgrcpt_idx_rid` (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `msgs`
--

CREATE TABLE IF NOT EXISTS `msgs` (
  `partition_tag` int(11) NOT NULL default '0',
  `mail_id` varchar(12) character set latin1 NOT NULL,
  `secret_id` varchar(12) default NULL,
  `am_id` varchar(20) NOT NULL,
  `time_num` int(10) unsigned NOT NULL,
  `time_iso` timestamp NOT NULL default '0000-00-00 00:00:00',
  `sid` int(10) unsigned NOT NULL,
  `policy` varchar(255) default NULL,
  `client_addr` varchar(255) default NULL,
  `size` int(10) unsigned NOT NULL,
  `content` char(1) default NULL,
  `quar_type` char(1) default NULL,
  `quar_loc` varchar(255) default NULL,
  `dsn_sent` char(1) default NULL,
  `spam_level` float default NULL,
  `message_id` varchar(255) default NULL,
  `from_addr` varchar(255) default NULL,
  `subject` varchar(255) default NULL,
  `host` varchar(255) NOT NULL,
  PRIMARY KEY  (`mail_id`),
  KEY `msgs_idx_sid` (`sid`),
  KEY `msgs_idx_time_iso` (`time_iso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `policy`
--

CREATE TABLE IF NOT EXISTS `policy` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `policy_name` varchar(32) default NULL,
  `virus_lover` char(1) character set latin1 default NULL,
  `spam_lover` char(1) character set latin1 default NULL,
  `banned_files_lover` char(1) character set latin1 default NULL,
  `bad_header_lover` char(1) character set latin1 default NULL,
  `bypass_virus_checks` char(1) character set latin1 default NULL,
  `bypass_spam_checks` char(1) character set latin1 default NULL,
  `bypass_banned_checks` char(1) character set latin1 default NULL,
  `bypass_header_checks` char(1) character set latin1 default NULL,
  `spam_modifies_subj` char(1) character set latin1 default NULL,
  `virus_quarantine_to` varchar(64) default NULL,
  `spam_quarantine_to` varchar(64) default NULL,
  `banned_quarantine_to` varchar(64) default NULL,
  `bad_header_quarantine_to` varchar(64) default NULL,
  `spam_tag_level` float default NULL,
  `spam_tag2_level` float default NULL,
  `spam_kill_level` float default NULL,
  `spam_dsn_cutoff_level` float default NULL,
  `spam_quarantine_cutoff_level` float(10,2) default NULL,
  `addr_extension_virus` varchar(64) default NULL,
  `addr_extension_spam` varchar(64) default NULL,
  `addr_extension_banned` varchar(64) default NULL,
  `addr_extension_bad_header` varchar(64) default NULL,
  `warnvirusrecip` char(1) default NULL,
  `warnbannedrecip` char(1) default NULL,
  `warnbadhrecip` char(1) default NULL,
  `newvirus_admin` varchar(64) default NULL,
  `virus_admin` varchar(64) default NULL,
  `banned_admin` varchar(64) default NULL,
  `bad_header_admin` varchar(64) default NULL,
  `spam_admin` varchar(64) default NULL,
  `spam_subject_tag` varchar(64) default NULL,
  `spam_subject_tag2` varchar(64) default NULL,
  `message_size_limit` int(11) default NULL,
  `banned_rulenames` varchar(64) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `quarantine`
--

CREATE TABLE IF NOT EXISTS `quarantine` (
  `partition_tag` int(11) NOT NULL default '0',
  `mail_id` varchar(12) character set latin1 NOT NULL,
  `chunk_ind` int(10) unsigned NOT NULL,
  `mail_text` blob,
  PRIMARY KEY  (`mail_id`,`chunk_ind`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sa_rules`
--

CREATE TABLE IF NOT EXISTS `sa_rules` (
  `rule` varchar(100) NOT NULL,
  `rule_desc` varchar(200) NOT NULL,
  PRIMARY KEY  (`rule`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `priority` int(11) NOT NULL default '7',
  `policy_id` int(10) unsigned NOT NULL default '1',
  `email` varbinary(255) default NULL,
  `fullname` varchar(255) default NULL,
  `local` char(1) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vacation`
--

CREATE TABLE IF NOT EXISTS `vacation` (
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `cache` text NOT NULL,
  `domain` varchar(255) NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`email`),
  KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='PostVis Admin - Vacation -Not Implemented-';

-- --------------------------------------------------------

--
-- Table structure for table `wblist`
--

CREATE TABLE IF NOT EXISTS `wblist` (
  `rid` int(10) unsigned NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `wb` varchar(10) NOT NULL,
  PRIMARY KEY  (`rid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `msgrcpt`
--
ALTER TABLE `msgrcpt`
  ADD CONSTRAINT `msgrcpt_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `maddr` (`id`),
  ADD CONSTRAINT `msgrcpt_ibfk_2` FOREIGN KEY (`mail_id`) REFERENCES `msgs` (`mail_id`) ON DELETE CASCADE;

--
-- Constraints for table `msgs`
--
ALTER TABLE `msgs`
  ADD CONSTRAINT `msgs_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `maddr` (`id`);

--
-- Constraints for table `quarantine`
--
ALTER TABLE `quarantine`
  ADD CONSTRAINT `quarantine_ibfk_1` FOREIGN KEY (`mail_id`) REFERENCES `msgs` (`mail_id`) ON DELETE CASCADE;
