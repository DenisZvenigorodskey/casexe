-- phpMyAdmin SQL Dump
-- version 4.4.15.6
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 26 2019 г., 16:46
-- Версия сервера: 5.5.50
-- Версия PHP: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `casexe`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Lottery`
--

CREATE TABLE IF NOT EXISTS `Lottery` (
  `id` int(5) NOT NULL,
  `UserID` int(5) NOT NULL,
  `Cart` longtext CHARACTER SET utf8 NOT NULL,
  `__1` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `Lottery`
--

INSERT INTO `Lottery` (`id`, `UserID`, `Cart`, `__1`) VALUES
(0, 1, 'a:4:{s:5:"Bonus";a:17:{i:0;a:2:{s:5:"Value";i:2;s:6:"Status";s:6:"Active";}i:1;a:2:{s:5:"Value";i:39;s:6:"Status";s:6:"Active";}i:2;a:2:{s:5:"Value";i:32;s:6:"Status";s:6:"Active";}i:3;a:2:{s:5:"Value";i:19;s:6:"Status";s:6:"Active";}i:4;a:2:{s:5:"Value";i:43;s:6:"Status";s:6:"Active";}i:5;a:2:{s:5:"Value";i:2;s:6:"Status";s:6:"Active";}i:6;a:2:{s:5:"Value";i:23;s:6:"Status";s:6:"Active";}i:7;a:2:{s:5:"Value";i:3;s:6:"Status";s:6:"Active";}i:8;a:2:{s:5:"Value";i:41;s:6:"Status";s:6:"Active";}i:9;a:2:{s:5:"Value";i:36;s:6:"Status";s:6:"Active";}i:10;a:2:{s:5:"Value";d:16.875;s:6:"Status";s:6:"Active";}i:11;a:2:{s:5:"Value";i:27;s:6:"Status";s:6:"Active";}i:12;a:2:{s:5:"Value";i:3;s:6:"Status";s:6:"Active";}i:13;a:2:{s:5:"Value";i:37;s:6:"Status";s:6:"Active";}i:14;a:2:{s:5:"Value";d:28.125;s:6:"Status";s:6:"Active";}i:15;a:2:{s:5:"Value";i:26;s:6:"Status";s:6:"Active";}i:16;a:2:{s:5:"Value";i:13;s:6:"Status";s:6:"Active";}}s:10:"LastResult";a:3:{s:4:"Type";s:4:"Item";s:3:"Pos";i:4;s:5:"Value";s:8:"Lollipop";}s:4:"Item";a:3:{s:5:"Badge";a:2:{i:0;a:2:{s:5:"Value";i:1;s:6:"Status";s:6:"Active";}i:1;a:2:{s:5:"Value";i:1;s:6:"Status";s:6:"Active";}}s:8:"Lollipop";a:5:{i:0;a:2:{s:5:"Value";i:1;s:6:"Status";s:6:"Active";}i:1;a:2:{s:5:"Value";i:1;s:6:"Status";s:6:"Active";}i:2;a:2:{s:5:"Value";i:1;s:6:"Status";s:6:"Active";}i:3;a:2:{s:5:"Value";i:1;s:6:"Status";s:6:"Active";}i:4;a:2:{s:5:"Value";i:1;s:6:"Status";s:6:"Active";}}s:13:"SomethingElse";a:1:{i:0;a:2:{s:5:"Value";i:1;s:6:"Status";s:6:"Active";}}}s:5:"Money";a:7:{i:0;a:2:{s:5:"Value";i:42;s:6:"Status";s:11:"TrasferTrue";}i:1;a:2:{s:5:"Value";i:33;s:6:"Status";s:11:"TrasferTrue";}i:2;a:2:{s:5:"Value";i:41;s:6:"Status";s:11:"TrasferTrue";}i:3;a:2:{s:5:"Value";i:47;s:6:"Status";s:11:"TrasferTrue";}i:4;a:2:{s:5:"Value";i:5;s:6:"Status";s:11:"TrasferTrue";}i:5;a:2:{s:5:"Value";i:23;s:6:"Status";s:11:"TrasferTrue";}i:6;a:2:{s:5:"Value";i:30;s:6:"Status";s:11:"TrasferTrue";}}}', 0),
(1, 2, '', 0),
(2, 3, '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `id` int(5) NOT NULL,
  `Login` varchar(20) NOT NULL,
  `Passwd` varchar(40) NOT NULL,
  `Status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`id`, `Login`, `Passwd`, `Status`) VALUES
(1, 'user1@casexe.com', '816b09aa255516ec745de7b215e2e158', '1'),
(2, 'user2@casexe.com', '969fc4473f2c0647dee8819c35fed602', '0'),
(3, 'user3@casexe.com', '48f1eeff4ecb1c394af1eae4e88c183b', '1');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Lottery`
--
ALTER TABLE `Lottery`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `UserID` (`UserID`);

--
-- Индексы таблицы `Users`
--
ALTER TABLE `Users`
  ADD UNIQUE KEY `id` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
