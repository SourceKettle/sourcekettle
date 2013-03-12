-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 02, 2012 at 11:23 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `devtrack`
--

-- --------------------------------------------------------

--
-- Table structure for table `collaborators`
--

CREATE TABLE IF NOT EXISTS `collaborators` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `access_level` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `collaborators`
--


-- --------------------------------------------------------

--
-- Table structure for table `email_confirmation_keys`
--

CREATE TABLE IF NOT EXISTS `email_confirmation_keys` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `key` varchar(150)  NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `email_confirmation_keys`
--


-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `public` tinyint(1) NOT NULL,
  `repo_type` int(2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `repo_type` (`repo_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `projects`
--


-- --------------------------------------------------------

--
-- Table structure for table `project_histories`
--

CREATE TABLE IF NOT EXISTS `project_histories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL,
  `model` varchar(25) NOT NULL,
  `row_id` int(10) NOT NULL,
  `row_field` varchar(255) DEFAULT NULL,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `row_title` varchar(255) NOT NULL,
  `row_field_old` text,
  `row_field_new` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `modified` (`modified`),
  KEY `created` (`modified`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `project_histories`
--


-- --------------------------------------------------------

--
-- Table structure for table `ssh_keys`
--

CREATE TABLE IF NOT EXISTS `ssh_keys` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `key` varchar(512) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ssh_keys`
--

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `created`, `modified`) VALUES
(1, 'register_enabled', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'sysadmin_email', 'sysadmin@example.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'sync_required', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'feature_time_enabled', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'feature_source_enabled', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'feature_task_enabled', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'feature_attachment_enabled', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL default '0',
  `is_active` tinyint(1) NOT NULL default '0',
  `theme` varchar(255) NOT NULL default 'default',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `lost_password_keys`
--

CREATE TABLE IF NOT EXISTS `lost_password_keys` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `key` varchar(25) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE utf8_bin;

--
-- Dumping data for table `lost_password_keys`
--

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE IF NOT EXISTS `source` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin;

--
-- Dumping data for table `source`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `key` varchar(20) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE utf8_bin;


--
-- Dumping data for table `api_keys`
--

-- --------------------------------------------------------

--
-- Table structure for table `times`
--

CREATE TABLE IF NOT EXISTS `times` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `task_id` int(10) NOT NULL,
  `mins` int(10) NOT NULL DEFAULT '0',
  `description` text,
  `date` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  KEY `task_id` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1;

--
-- Dumping data for table `times`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL,
  `owner_id` int(10) NOT NULL,
  `task_type_id` int(10) NOT NULL,
  `task_status_id` int(10) NOT NULL,
  `task_priority_id` int(10) NOT NULL,
  `assignee_id` int(10) NULL DEFAULT NULL,
  `milestone_id` int(10) NULL DEFAULT NULL,
  `subject` varchar(50) NOT NULL,
  `description` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `task_status_id` (`task_status_id`),
  KEY `owner_id` (`owner_id`),
  KEY `task_type_id` (`task_type_id`),
  KEY `assignee_id` (`assignee_id`),
  KEY `milestone_id` (`milestone_id`),
  KEY `task_priority_id` (`task_priority_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1;

--
-- Dumping data for table `tasks`
--

-- --------------------------------------------------------

--
-- Table structure for table `task_dependencies`
--
CREATE TABLE IF NOT EXISTS `task_dependencies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `child_task_id` int(10) NOT NULL,
  `parent_task_id` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `child_task_id` (`child_task_id`),
  KEY `parent_task_id` (`parent_task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1;

--
-- Dumping data for table `tasks`
--

-- --------------------------------------------------------

--
-- Table structure for table `task_types`
--
CREATE TABLE IF NOT EXISTS `task_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1;

--
-- Dumping data for table `task_types`
--

INSERT INTO `task_types` (`id`, `name`, `created`, `modified`) VALUES
(1, 'bug','0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'duplicate', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'enhancement','0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'invalid','0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'question','0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'wontfix', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `task_comments`
--
CREATE TABLE IF NOT EXISTS `task_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `comment` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1;

--
-- Dumping data for table `task_comments`
--

-- --------------------------------------------------------

--
-- Table structure for table `task_statuses`
--
CREATE TABLE IF NOT EXISTS `task_statuses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1;

--
-- Dumping data for table `task_statuses`
--

INSERT INTO `task_statuses` (`id`, `name`, `created`, `modified`) VALUES
(1, 'open','0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'in progress', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'resolved','0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'closed', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `task_priorities`
--
CREATE TABLE IF NOT EXISTS `task_priorities` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1;

--
-- Dumping data for table `task_priorities`
--

INSERT INTO `task_priorities` (`id`, `name`, `created`, `modified`) VALUES
(1, 'minor','0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'major', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'urgent', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'blocker', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--
CREATE TABLE IF NOT EXISTS `milestones` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `description` text,
  `due` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1;

--
-- Dumping data for table `milestones`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--
CREATE TABLE IF NOT EXISTS `attachments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `model_id` int(10) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `mime` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `md5` varchar(255) NOT NULL,
  `content` longblob,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1;

--
-- Dumping data for table `attachments`
--

-- --------------------------------------------------------

--
-- Table structure for table `daemon_queue`
--
CREATE TABLE `daemon_queue` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `task` int(10) NOT NULL,
  `subtask` int(10) NOT NULL,
  `focus` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1;

--
-- Dumping data for table `daemon_queue`
--

INSERT INTO `daemon_queue` (`id`, `created`, `task`, `subtask`, `focus`) VALUES
(1, '0000-00-00 00:00:00', 1, 0, 0),
(2, '0000-00-00 00:00:00', 2, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `daemon_history`
--
CREATE TABLE `daemon_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `number_of_nodes` int(10) NOT NULL DEFAULT '0',
  `running_nodes` int(10) NOT NULL DEFAULT '0',
  `queue_length` int(10) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=1;

--
-- Dumping data for table `daemon_history`
--

-- --------------------------------------------------------
