CREATE TABLE `military` (
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
)
