
--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `filename` varchar(1000) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
