CREATE TABLE `form_users` (
  `id` bigint(20) NOT NULL,
  `nick` varchar(50) DEFAULT NULL,
  `nazwa_ulicy` varchar(255) NOT NULL,
  `numer_drogi` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8_general_ci;
