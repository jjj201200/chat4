-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-02-28 10:59:28
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chat`
--

-- --------------------------------------------------------

--
-- 表的结构 `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `publisher` int(20) NOT NULL,
  `tucao` mediumtext CHARACTER SET utf8 NOT NULL,
  `imgJson` mediumtext NOT NULL,
  `soundJson` mediumtext NOT NULL,
  PRIMARY KEY (`cid`),
  UNIQUE KEY `cid_2` (`cid`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `chat`
--

INSERT INTO `chat` (`cid`, `date`, `time`, `publisher`, `tucao`, `imgJson`, `soundJson`) VALUES
(1, '2015-02-28', '10:58:37', 1, 'キラメキ ——四月是你的谎言OP', '', '2015_02_28 10_58_37 160.mp3');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 NOT NULL,
  `password` varchar(20) NOT NULL,
  `signup_date` date NOT NULL,
  `last_time` datetime NOT NULL,
  `last_ip_v4` varchar(15) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`uid`, `username`, `password`, `signup_date`, `last_time`, `last_ip_v4`) VALUES
(1, 'jjj201200', '641b22c2da3fa88eb42e', '2015-02-28', '2015-02-28 09:12:00', '127.0.0.1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
