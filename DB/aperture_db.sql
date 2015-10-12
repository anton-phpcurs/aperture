-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Окт 12 2015 г., 08:59
-- Версия сервера: 5.5.44-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `zend`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `profile_name` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `file_name`, `profile_name`, `text`, `date`) VALUES
(4, '4ebd411a134c369b351664e266dda25a', 'baziak', 'gfhnjgfdh', '2015-10-09'),
(5, 'a795463c108e2f68ad46664ca3ef8db6', 'anton', 'hjghjgh', '2015-10-09'),
(6, '919bf95aae0e74833491d04ec3b205bc', 'anton', 'cat', '2015-10-09');

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `folder` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ext` varchar(10) NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `likes` int(10) unsigned NOT NULL,
  `comments` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`id`, `folder`, `name`, `ext`, `id_user`, `likes`, `comments`, `views`) VALUES
(37, '/files/c4ca4238a0b923820dcc509a6f75849b/', '2a63cefab65d27551f981637c51674ca', '.jpg', 1, 0, 0, 13),
(38, '/files/c4ca4238a0b923820dcc509a6f75849b/', 'a795463c108e2f68ad46664ca3ef8db6', '.jpg', 1, 0, 1, 44),
(40, '/files/c4ca4238a0b923820dcc509a6f75849b/', '919bf95aae0e74833491d04ec3b205bc', '.jpg', 1, 1, 1, 76),
(44, '/files/c4ca4238a0b923820dcc509a6f75849b/', '4ebd411a134c369b351664e266dda25a', '.png', 1, 1, 1, 13),
(47, '/files/c4ca4238a0b923820dcc509a6f75849b/', 'c96f232ec4bbe2671de3cd3d0aadb007', '.png', 1, 0, 0, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `follows`
--

CREATE TABLE IF NOT EXISTS `follows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_follower` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `follows`
--

INSERT INTO `follows` (`id`, `id_user`, `id_follower`) VALUES
(1, 2, 1),
(2, 1, 2),
(3, 7, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `profile_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Дамп данных таблицы `likes`
--

INSERT INTO `likes` (`id`, `file_name`, `profile_name`) VALUES
(13, '919bf95aae0e74833491d04ec3b205bc', 'an'),
(14, '4ebd411a134c369b351664e266dda25a', 'baziak');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_active` int(1) NOT NULL DEFAULT '1',
  `activation` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `profile_name` varchar(255) NOT NULL,
  `avatar_path` varchar(255) NOT NULL,
  `bio` text NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `count_following` int(11) NOT NULL DEFAULT '0',
  `count_followers` int(11) NOT NULL DEFAULT '0',
  `count_photos` int(11) NOT NULL DEFAULT '0',
  `link_vk` varchar(255) NOT NULL,
  `link_fb` varchar(255) NOT NULL,
  `link_tw` varchar(255) NOT NULL,
  `link_skype` varchar(255) NOT NULL,
  `link_site` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `is_active`, `activation`, `email`, `password`, `profile_name`, `avatar_path`, `bio`, `full_name`, `count_following`, `count_followers`, `count_photos`, `link_vk`, `link_fb`, `link_tw`, `link_skype`, `link_site`) VALUES
(1, 1, '', 'anton@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'anton', '/files/c4ca4238a0b923820dcc509a6f75849b/avatar.png', '<p>One can look at the world differently, have an opportunity to see unusual things in the surrounding reality - that''s why I love photography and try to show it in my work. It''s my hobby, my leisure, joy, passion, part of my soul. And no matter what I shoot: bright moments and events, children or adults, architecture or landscape ... I do what I love and love what I do ....</p>\r\n\r\n<p>Life scenes. People, animals, landscape and... balloons!\r\nIf you would like to purchase one of my photos, please email me. </p>\r\n\r\n<p>Welcome to my Facebook Page</p>', 'Anton Abrosimov', 2, 1, 6, 'vk.com/neonum', 'neonum.deviantart.com', 'tw', 'ineonum', 'neonum.deviantart.com'),
(2, 1, '', 'email@email.ru', '827ccb0eea8a706c4c34a16891f84e7b', 'an', '/files/c4ca4238a0b923820dcc509a6f75849b/avatar.jpg', '', '', 1, 1, 0, '', '', '', '', ''),
(3, 1, '', 'test@test.ru', '827ccb0eea8a706c4c34a16891f84e7b', 'test', '', '', '', 0, 0, 0, '', '', '', '', ''),
(4, 1, '', 'test2@test.ru', '827ccb0eea8a706c4c34a16891f84e7b', 'test2', '', '', '', 0, 0, 0, '', '', '', '', ''),
(5, 1, '', 'poiu@poiu.ru', '827ccb0eea8a706c4c34a16891f84e7b', 'poiu', '', '', '', 0, 0, 0, '', '', '', '', ''),
(6, 1, '', 'baziak@nixsolutions.com', '4297f44b13955235245b2497399d7a93', 'baziak', '', '', '', 0, 0, 0, '', '', '', '', ''),
(7, 1, '', 'alex@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Alexandr', '', '', '', 0, 1, 0, '', '', '', '', ''),
(8, 1, '', 'vika@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Vika', '', '', '', 0, 0, 0, '', '', '', '', ''),
(9, 1, '', 'dima@ffff.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Dima', '', '', '', 0, 0, 0, '', '', '', '', ''),
(10, 0, 'd6f503314aba1e6ee3418276a7548fea', 'viking@ban.com', '827ccb0eea8a706c4c34a16891f84e7b', 'viking', '', '', '', 0, 0, 0, '', '', '', '', ''),
(11, 0, 'ccd369fff3f95b7aab89f7633750c607', 'kostya@ggg.com', '827ccb0eea8a706c4c34a16891f84e7b', 'kostya', '', '', '', 0, 0, 0, '', '', '', '', ''),
(12, 0, 'a1923f3586ce5eb201355661792488d7', 'vitya@ppp.com', '827ccb0eea8a706c4c34a16891f84e7b', 'vitya', '', '', '', 0, 0, 0, '', '', '', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
