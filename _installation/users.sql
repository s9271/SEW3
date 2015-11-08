CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
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
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data' AUTO_INCREMENT=11 ;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `first_name`, `second_name`, `birthday`, `user_password_hash`, `user_email`, `phone`, `militaryRank`, `code`, `street`, `numberHouse`, `jwNumber`) VALUES
(6, 'jagna', '', '', '0000-00-00', '$2y$10$lKwReMU42IO8k.98KEUs0.KDZSldynJwYIZ7bORRGhwqhZDmvvwjS', 'jkluk@pjwstk.edu.pl', '', '', 0, '', '', ''),
(7, 'admin', '', '', '0000-00-00', '$2y$10$QpGxSfXTGjNE5oiPWMFplu/O0DTPox0jasgrXsVD4M.gOqMNAMxBu', 'milosz.talmon@gmail.com', '', '', 0, '', '', ''),
(8, 'admTalmon', '', '', '0000-00-00', '$2y$10$MRXzyRWBYlj1a0UgYkbR4Oa1zvTvVPswGQtEkqa0R8H7JytxSPecu', 'miloszsamol@o2.pl', '', '', 0, '', '', ''),
(9, 'admRaczkowski', '', '', '0000-00-00', '$2y$10$CPGF4mT6GD5sXwcAhkRcKeOvuajDzsfJnLl2X1yXucsdB3LaaVNhi', 'kris@o2.pl', '', '', 0, '', '', ''),
(10, 'admPanek', '', '', '0000-00-00', '$2y$10$4RUplTu8cAu3MxDykisk0OyaTd5gGKet.jVhOLetMPrgGzW18eEeK', 'panek@o2.pl', '', '', 0, '', '', '');
