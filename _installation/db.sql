-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Czas generowania: 26 Wrz 2015, 18:20
-- Wersja serwera: 5.5.42
-- Wersja PHP: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `login`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `form_users`
--

CREATE TABLE `form_users` (
  `id` int(11) NOT NULL,
  `nick` varchar(50) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `street_number` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `form_users`
--

INSERT INTO `form_users` (`id`, `nick`, `street`, `street_number`, `created_at`) VALUES
(1, 'Karol', 'Nowodworska', '11', '2015-09-26 17:48:38'),
(2, 'Jan', 'Nowodworska', '11', '2015-09-26 17:48:39'),
(3, 'Marek', 'Nowodworska', '11', '2015-09-26 17:55:04'),
(4, 'Ewa', 'Nowodworska', '123', '2015-09-26 17:55:14'),
(5, 'Adam', 'Nowodworska', '123', '2015-09-26 17:55:34');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_password_hash`, `user_email`) VALUES
(1, 'karol', '$2y$10$vhw.J64WEi9hRUNQTQgmaOelVV7OCSr0VvU7xHznhOZGY1M8UZSWe', 'parfienczyk@gmail.com');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `form_users`
--
ALTER TABLE `form_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `form_users`
--
ALTER TABLE `form_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',AUTO_INCREMENT=2;
