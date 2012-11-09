--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `status`) VALUES
(1, 'Article 1', 'Ut lobortis varius velit, a pretium nibh eleifend at. Donec blandit congue ultricies. Maecenas cursus cursus nisi id vehicula. Aliquam a turpis velit. Nullam elit tortor, vehicula sed molestie nec, euismod gravida nisi. Integer imperdiet aliquam elementum. Etiam semper, neque sit amet rutrum adipiscing, ante augue blandit ligula, a tristique massa est sit amet mi.', 1),
(2, 'Article 2', 'Ut lobortis varius velit, a pretium nibh eleifend at. Donec blandit congue ultricies. Maecenas cursus cursus nisi id vehicula. Aliquam a turpis velit. Nullam elit tortor, vehicula sed molestie nec, euismod gravida nisi. Integer imperdiet aliquam elementum. Etiam semper, neque sit amet rutrum adipiscing, ante augue blandit ligula, a tristique massa est sit amet mi.', 1),
(3, 'Article 3', 'Ut lobortis varius velit, a pretium nibh eleifend at. Donec blandit congue ultricies. Maecenas cursus cursus nisi id vehicula. Aliquam a turpis velit. Nullam elit tortor, vehicula sed molestie nec, euismod gravida nisi. Integer imperdiet aliquam elementum. Etiam semper, neque sit amet rutrum adipiscing, ante augue blandit ligula, a tristique massa est sit amet mi.', 1),
(4, 'Article 4', 'Ut lobortis varius velit, a pretium nibh eleifend at. Donec blandit congue ultricies. Maecenas cursus cursus nisi id vehicula. Aliquam a turpis velit. Nullam elit tortor, vehicula sed molestie nec, euismod gravida nisi. Integer imperdiet aliquam elementum. Etiam semper, neque sit amet rutrum adipiscing, ante augue blandit ligula, a tristique massa est sit amet mi.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `article_ids` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `article_ids`) VALUES
(1, 'php', 'a:1:{i:0;i:1;}'),
(2, 'php5', 'a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}'),
(3, 'oop', 'a:2:{i:0;i:1;i:1;i:4;}'),
(4, 'mvc', 'a:2:{i:0;i:1;i:1;i:2;}'),
(5, 'codeigniter', 'a:1:{i:0;i:2;}'),
(6, 'rwd', 'a:1:{i:0;i:2;}'),
(7, 'bootstrap', 'a:1:{i:0;i:3;}'),
(10, 'jquery', 'a:1:{i:0;i:4;}');