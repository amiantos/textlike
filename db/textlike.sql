-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 06, 2018 at 05:47 PM
-- Server version: 5.6.38
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amiaarfz_textlike`
--

-- --------------------------------------------------------

--
-- Table structure for table `armors`
--

CREATE TABLE `armors` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `quality` int(11) NOT NULL,
  `material` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `shortName` text NOT NULL,
  `protect` int(11) NOT NULL,
  `characterid` int(11) NOT NULL,
  `mobid` int(11) NOT NULL,
  `inRoom` int(11) NOT NULL,
  `created_for` tinytext NOT NULL,
  `total_durability` int(11) NOT NULL,
  `current_durability` int(11) NOT NULL,
  `weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `battlelog`
--

CREATE TABLE `battlelog` (
  `id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `mob_id` int(11) NOT NULL,
  `history` text NOT NULL,
  `created_for` tinytext NOT NULL,
  `type` tinytext NOT NULL,
  `turn` int(11) NOT NULL,
  `part` tinytext NOT NULL,
  `location` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE `characters` (
  `id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` text NOT NULL,
  `name` text NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `turns` int(11) NOT NULL DEFAULT '0',
  `experience` int(11) NOT NULL DEFAULT '0',
  `next_level` int(11) NOT NULL DEFAULT '100',
  `strength` int(11) NOT NULL DEFAULT '10',
  `stamina` int(11) NOT NULL DEFAULT '10',
  `dexterity` int(11) NOT NULL DEFAULT '10',
  `intelligence` int(11) NOT NULL DEFAULT '10',
  `luck` int(11) NOT NULL DEFAULT '10',
  `availablePoints` int(11) NOT NULL DEFAULT '0',
  `inRoom` int(11) NOT NULL,
  `lastRoom` int(11) NOT NULL,
  `totalHealth` int(11) NOT NULL DEFAULT '150',
  `cur_health` int(11) NOT NULL DEFAULT '150',
  `totalCarry` int(11) NOT NULL DEFAULT '0',
  `equippedWeapon` int(11) NOT NULL,
  `equippedTome` int(11) NOT NULL,
  `equippedArmor` int(11) NOT NULL,
  `attacking` int(11) NOT NULL DEFAULT '0',
  `head` int(11) NOT NULL,
  `torso` int(11) NOT NULL DEFAULT '0',
  `upper_left` int(11) NOT NULL DEFAULT '0',
  `upper_right` int(11) NOT NULL DEFAULT '0',
  `lower_left` int(11) NOT NULL DEFAULT '0',
  `lower_right` int(11) NOT NULL DEFAULT '0',
  `dead` int(11) NOT NULL DEFAULT '0',
  `encumbered` int(11) NOT NULL DEFAULT '0',
  `last_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `highscores`
--

CREATE TABLE `highscores` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` tinytext NOT NULL,
  `character_id` int(11) NOT NULL,
  `character_name` tinytext NOT NULL,
  `total_score` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `floor` varchar(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `kind` int(11) NOT NULL,
  `character_id` int(11) NOT NULL DEFAULT '0',
  `possessed` int(11) NOT NULL DEFAULT '0',
  `inRoom` int(11) NOT NULL DEFAULT '0',
  `created_for` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mobs`
--

CREATE TABLE `mobs` (
  `id` int(11) NOT NULL,
  `characterid` int(11) NOT NULL,
  `disposition` tinytext NOT NULL,
  `kind` text NOT NULL,
  `level` int(11) NOT NULL,
  `total_health` int(11) NOT NULL,
  `cur_health` int(11) NOT NULL,
  `strength` int(11) NOT NULL DEFAULT '10',
  `stamina` int(11) NOT NULL DEFAULT '10',
  `intelligence` int(11) NOT NULL DEFAULT '10',
  `dexterity` int(11) NOT NULL DEFAULT '10',
  `luck` int(11) NOT NULL DEFAULT '10',
  `equipped_weapon` int(11) NOT NULL,
  `equipped_armor` int(11) NOT NULL,
  `head` int(11) NOT NULL,
  `torso` int(11) NOT NULL,
  `upper_left` int(11) NOT NULL,
  `upper_right` int(11) NOT NULL,
  `lower_left` int(11) NOT NULL,
  `lower_right` int(11) NOT NULL,
  `experience` int(11) NOT NULL,
  `inRoom` int(11) NOT NULL,
  `corpse` int(11) NOT NULL,
  `created_for` tinytext NOT NULL,
  `weakness` tinytext NOT NULL,
  `resistance` tinytext NOT NULL,
  `boss` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roomhistory`
--

CREATE TABLE `roomhistory` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `history` text NOT NULL,
  `created_for` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `characterid` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `north` int(11) NOT NULL,
  `south` int(11) NOT NULL,
  `east` int(11) NOT NULL,
  `west` int(11) NOT NULL,
  `up` int(11) NOT NULL,
  `down` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `roomsGenned` int(11) NOT NULL DEFAULT '0',
  `mobsGenned` int(11) NOT NULL DEFAULT '0',
  `roomDesc` text NOT NULL,
  `created_for` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `structures`
--

CREATE TABLE `structures` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` text NOT NULL,
  `inRoom` int(11) NOT NULL,
  `created_for` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tomes`
--

CREATE TABLE `tomes` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `characterid` int(11) NOT NULL,
  `mobid` int(11) NOT NULL DEFAULT '0',
  `material` int(11) NOT NULL,
  `damage` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `shortName` text NOT NULL,
  `inRoom` int(11) NOT NULL,
  `created_for` tinytext NOT NULL,
  `total_durability` int(11) NOT NULL,
  `current_durability` int(11) NOT NULL,
  `weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tz_members`
--

CREATE TABLE `tz_members` (
  `id` int(11) NOT NULL,
  `usr` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pass` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `regIP` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `characterSelected` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weapons`
--

CREATE TABLE `weapons` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `characterid` int(11) NOT NULL,
  `mobid` int(11) NOT NULL DEFAULT '0',
  `quality` int(11) NOT NULL,
  `material` int(11) NOT NULL,
  `damage` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `shortName` text NOT NULL,
  `inRoom` int(11) NOT NULL,
  `created_for` tinytext NOT NULL,
  `total_durability` int(11) NOT NULL,
  `current_durability` int(11) NOT NULL,
  `weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `armors`
--
ALTER TABLE `armors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `battlelog`
--
ALTER TABLE `battlelog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `highscores`
--
ALTER TABLE `highscores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobs`
--
ALTER TABLE `mobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomhistory`
--
ALTER TABLE `roomhistory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `structures`
--
ALTER TABLE `structures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tomes`
--
ALTER TABLE `tomes`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tz_members`
--
ALTER TABLE `tz_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usr` (`usr`);

--
-- Indexes for table `weapons`
--
ALTER TABLE `weapons`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `armors`
--
ALTER TABLE `armors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4096;
--
-- AUTO_INCREMENT for table `battlelog`
--
ALTER TABLE `battlelog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102932;
--
-- AUTO_INCREMENT for table `characters`
--
ALTER TABLE `characters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;
--
-- AUTO_INCREMENT for table `highscores`
--
ALTER TABLE `highscores`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2594;
--
-- AUTO_INCREMENT for table `mobs`
--
ALTER TABLE `mobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2896;
--
-- AUTO_INCREMENT for table `roomhistory`
--
ALTER TABLE `roomhistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4195;
--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1969;
--
-- AUTO_INCREMENT for table `structures`
--
ALTER TABLE `structures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=588;
--
-- AUTO_INCREMENT for table `tomes`
--
ALTER TABLE `tomes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2125;
--
-- AUTO_INCREMENT for table `tz_members`
--
ALTER TABLE `tz_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;
--
-- AUTO_INCREMENT for table `weapons`
--
ALTER TABLE `weapons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4107;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
