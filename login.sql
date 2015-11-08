-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 01 Lis 2015, 18:07
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `equipment`
--

CREATE TABLE IF NOT EXISTS `equipment` (
  `equipmentNumber` int(11) NOT NULL AUTO_INCREMENT,
  `equipmentType` varchar(255) NOT NULL,
  `equipmentMark` varchar(255) NOT NULL,
  `equipmentSerial` varchar(255) NOT NULL,
  PRIMARY KEY (`equipmentNumber`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Zrzut danych tabeli `equipment`
--

INSERT INTO `equipment` (`equipmentNumber`, `equipmentType`, `equipmentMark`, `equipmentSerial`) VALUES
(25, 'Bron dluga', 'AK47', '443634722'),
(26, 'Bron krotka', 'Berrette 92', '12446543268'),
(27, 'Bron dluga', 'HK USP', '23268965743');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `military`
--

CREATE TABLE IF NOT EXISTS `military` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `militaryName` varchar(128) NOT NULL,
  `militaryNumber` varchar(128) NOT NULL,
  `militarySelect` varchar(255) NOT NULL,
  `militaryCommander` varchar(128) NOT NULL,
  `militaryCommander1` varchar(128) NOT NULL,
  `phone` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` int(6) NOT NULL,
  `city` varchar(64) NOT NULL,
  `street` varchar(64) NOT NULL,
  `numberHouse` varchar(64) NOT NULL,
  `someText` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `military`
--

INSERT INTO `military` (`id`, `militaryName`, `militaryNumber`, `militarySelect`, `militaryCommander`, `militaryCommander1`, `phone`, `email`, `code`, `city`, `street`, `numberHouse`, `someText`) VALUES
(1, 'Jednostka Zmechanizowana5', 'JW1113', 'Wojska Lądowe', 'Miłosz Talmon', 'major', '987654321', 'milosz.talmon@gmail.com', 12345, 'gdańsk', 'Porębskiego', '37', ''),
(2, 'I Brygada Pancerna', 'jw0909', 'Wojska Lądowe', 'Miłosz Talmon', 'kapitan', '312313231', 'miloszsamol@o2.pl', 80180, 'leszno', 'kolorowa', '2', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `mission`
--

CREATE TABLE IF NOT EXISTS `mission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `missionNumber` varchar(255) CHARACTER SET utf8 NOT NULL,
  `missionName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `missionLocation` varchar(255) CHARACTER SET utf8 NOT NULL,
  `missionDate` datetime NOT NULL,
  `missionDate1` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Zrzut danych tabeli `mission`
--

INSERT INTO `mission` (`id`, `missionNumber`, `missionName`, `missionLocation`, `missionDate`, `missionDate1`) VALUES
(5, '123456', 'Misja Specjalna', 'Ukraina', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, '123456789', 'Misja Dora?na', 'Afganistan', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'uuititityyty', 'Misja Sta?a', 'Syria', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'S.E.W', 'Misja Specjalna', 'Afganistan', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'S.E.W.', 'Misja Doraźna', 'Ukraina', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'S.E.W.', 'Misja Specjalna', 'Syria', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'K.I.T', 'Misja Stała', 'Syria', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `ordersNumber` int(11) NOT NULL AUTO_INCREMENT,
  `ordersName` varchar(255) NOT NULL,
  PRIMARY KEY (`ordersNumber`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Zrzut danych tabeli `orders`
--

INSERT INTO `orders` (`ordersNumber`, `ordersName`) VALUES
(1, 'Krzyz Walecznych'),
(3, 'Order VIRTUTI MILITARI'),
(4, 'Order Or?a Bia?ego'),
(5, 'Order Odrodzenia Polski '),
(6, 'Order Krzy?a Wojskowego '),
(7, 'Order Krzy?a Niepodleg?o?ci '),
(8, 'Order Zas?ugi Rzeczypospolitej Polskiej '),
(9, 'Krzy? Wojskowy'),
(10, 'Krzy? Zas?ugi za Dzielno??');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `soldiers`
--

CREATE TABLE IF NOT EXISTS `soldiers` (
  `soldierName` varchar(128) NOT NULL,
  `soldierSurname` varchar(128) NOT NULL,
  `birthday` date NOT NULL,
  `sex` varchar(64) NOT NULL,
  `phone` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` int(6) NOT NULL,
  `city` varchar(64) NOT NULL,
  `street` varchar(64) NOT NULL,
  `numberHouse` varchar(64) NOT NULL,
  `militaryRank` varchar(255) NOT NULL,
  `jwNumber` varchar(64) NOT NULL,
  `missions` varchar(255) NOT NULL,
  `training` varchar(255) NOT NULL,
  `weapon` varchar(64) NOT NULL,
  `weaponsNumber` varchar(64) NOT NULL,
  `equipmentSoldier` varchar(64) NOT NULL,
  `someText` varchar(64) NOT NULL,
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Zrzut danych tabeli `soldiers`
--

INSERT INTO `soldiers` (`soldierName`, `soldierSurname`, `birthday`, `sex`, `phone`, `email`, `code`, `city`, `street`, `numberHouse`, `militaryRank`, `jwNumber`, `missions`, `training`, `weapon`, `weaponsNumber`, `equipmentSoldier`, `someText`, `id`) VALUES
('Mi?osz', 'Mi?osz', '0000-00-00', 'M', '987654321', 'milosz.talmon@gmail.com', 12345, 'gda?sk', 'kolorowa', '2', 'szeregowy', 'jw1234', 'Brak', 'Brak', 'Brak', 'brak', 'Brak', 'brak', 1),
('milosz', 'talmon', '0000-00-00', 'M', '312313231', 'miloszsamol@o2.pl', 12345, 'gda?sk', 'kolorowa', '2', 'kapral', 'jw1234', 'Misje sta?e', 'Techniczne', 'Brak', 'brak', 'Brak', 'brak', 2),
('Mi?osz ', 'Talmon', '0000-00-00', 'M', '123456789', 'milosz@talmon.pl', 80180, 'Gda?sk', 'Kolorowa', '8', 'sier?ant', 'JW1212', 'Misje dora?ne', 'Techniczne', 'Pistolet', 'RE4447', 'Pe?ne', 'Opis', 3),
('Miłosz ', 'Świerczewski', '0000-00-00', 'M', '123456789', 'milosz@talmon.pl', 12345, 'Gdańsk', 'Porębskiego', '2', 'mł. sierżant', 'jw1234', 'Misje doraźne', 'Sprawnościowe', 'Brak', 'brak', 'Pełne', 'Opis', 4),
('milosz', 'talmon', '0000-00-00', 'M', '987654321', 'milosz@talmon.pl', 12345, 'gdańsk', 'kolorowa', '2', 'szeregowy', 'jw1234', 'Brak', 'Brak', 'Brak', 'brak', 'Brak', 'Opis', 5),
('janel', 'Talmon', '0000-00-00', 'K', '987654321', 'milosz@talmon.pl', 12345, 'leszno', 'rtryr', '2', 'kapral', 'JW1212', 'Brak', 'Brak', 'Brak', 'brak', 'Brak', 'Opis', 6),
('Miłosz ', 'Talmon', '0000-00-00', 'M', '987654321', 'miloszsamol@o2.pl', 12345, 'Gdańsk', 'kolorowa', '2', 'sierżant', 'jw1234', 'Brak', 'Sprawnościowe', 'Pistolet', 'brak', 'Pełne', 'Opis', 7),
('Mariusz', 'Panek', '0000-00-00', 'M', '987654321', 'milosz@talmon.pl', 80180, 'leszno', 'kolorowa', '37', 'kapral', 'jw1234', 'Brak', 'Brak', 'Pistolet', 'we3456', 'Brak', 'brak', 8),
('Miłosz ', 'Talmon', '0000-00-00', 'M', '312313231', 'milosz.talmon@gmail.com', 12345, 'gdańsk', 'Porębskiego', '2', 'mł. chorąży', 'jw1234', 'Misje doraźne', 'Sprawnościowe', 'Pistolet', 'we3456', 'Brak', 'lalal', 9);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `training`
--

CREATE TABLE IF NOT EXISTS `training` (
  `trainingNumber` int(11) NOT NULL AUTO_INCREMENT,
  `trainingName` varchar(255) NOT NULL,
  `trainingLocation` varchar(255) NOT NULL,
  `trainingDate` date NOT NULL,
  `trainingDate1` date NOT NULL,
  PRIMARY KEY (`trainingNumber`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Zrzut danych tabeli `training`
--

INSERT INTO `training` (`trainingNumber`, `trainingName`, `trainingLocation`, `trainingDate`, `trainingDate1`) VALUES
(1, 'Szkolenie Wstepne', 'Osrodek 123', '0000-00-00', '0000-00-00'),
(2, 'Szkolenie BHP', 'Osrodek 555', '0000-00-00', '0000-00-00'),
(3, 'test', 'test', '2015-10-07', '2015-10-19'),
(4, 'Szkolenie Specjalne', 'Osrodek 995A', '0000-00-00', '0000-00-00'),
(5, 'Szkolenie BHP', 'Osrodek 8732T', '0000-00-00', '0000-00-00'),
(6, 'Szkolenie Specjalne', 'Osrodek 8732T', '0000-00-00', '0000-00-00'),
(7, 'Szkolenie Bojowe SZB3456', 'Osrodek 8732T', '0000-00-00', '0000-00-00'),
(8, 'Szkolenie Specjalne SZ2354', 'Osrodek 555', '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `first_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `second_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  `phone` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `militaryRank` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` int(6) NOT NULL,
  `street` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `numberHouse` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `jwNumber` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data' AUTO_INCREMENT=6 ;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`user_name`, `first_name`, `second_name`, `birthday`, `user_password_hash`, `user_email`, `phone`, `militaryRank`, `code`, `street`, `numberHouse`, `jwNumber`, `user_id`) VALUES
('admAdministrator0', 'Admin', 'Admin', '0000-00-00', '$2y$10$F4fGTduq8M.mmCVEvNF0FegS53itH4bE8Irs8P5J1jDRXJtb8IIEO', 'admin@wp.pl', '544-765-908', 'porucznik', 23234, 'Senna', '789', 'JW0987', 1),
('admJagna', 'Jagna', 'Kluk', '0000-00-00', '$2y$10$str.FpdJefuuCbf/dODe4ub90AcWoSAfEDW5WCLeERhDkwCRocLEe', 'jagna@o2.pl', '222-222-222', 'kapral', 80180, 'Niska', '12', 'JW1212', 2),
('admKrzysztof', 'Krzysztof', 'Raczkowski', '0000-00-00', '$2y$10$EFdvVSJTa8m7zMkAh6hmKOHTFNi9gf0NroHEBAAbZcQkesazdk5FS', 'kris@o2.pl', '888-999-000', 'st. kapral', 45789, 'Zasypana', '78', 'JW8765', 3),
('admMariusz', 'Mariusz', 'Panek', '0000-00-00', '$2y$10$wwg4UY60P1b.xmaxfaq6s.VCadfUCaR7n9pe60XNuqyXfjPYU2oMK', 'mariusz@o2.pl', '544-667-665', 'szeregowy', 12345, 'Krzywa', '34', 'JW9095', 4),
('admMilosz', 'Milosz', 'Talmon', '0000-00-00', '$2y$10$FSxiWCnCBoLiZrruHV.I5O9cSzXl5m/bT689T/4yYKf65p6eNYa/O', 'milosz@o2.pl', '777-777-777', 'plutonowy', 80180, 'Porebskiego', '37', 'JW7768', 5);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
