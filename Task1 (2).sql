-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 29 2022 г., 16:28
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Task1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `productid` int NOT NULL,
  `userid` int NOT NULL,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `productid`, `userid`, `comment`, `date_added`) VALUES
(1, 3, 2, '11', '2022-11-22 15:54:20'),
(2, 3, 2, '11', '2022-11-22 15:55:48'),
(3, 7, 1, '111', '2022-11-23 07:14:16'),
(4, 7, 1, '111', '2022-11-23 07:14:47'),
(5, 7, 1, '111', '2022-11-23 07:19:15'),
(6, 6, 1, '111', '2022-11-23 07:19:21'),
(7, 7, 2, '', '2022-11-23 08:43:19'),
(8, 6, 2, '111', '2022-11-23 08:44:22'),
(9, 8, 2, '111', '2022-11-23 08:44:34'),
(10, 9, 1, '11', '2022-11-23 14:48:44'),
(11, 9, 1, '11', '2022-11-24 08:10:10'),
(12, 9, 1, '111', '2022-11-24 09:35:15'),
(13, 9, 1, '111', '2022-11-24 09:35:35'),
(14, 9, 1, '111\'111', '2022-11-24 09:35:46'),
(15, 9, 1, '1111\n1111\n\n1\n11\n\n11', '2022-11-24 09:36:15'),
(16, 10, 2, '111', '2022-11-24 13:40:13'),
(17, 12, 1, '11', '2022-11-24 14:32:57'),
(18, 10, 1, '111', '2022-11-24 15:48:32'),
(19, 11, 1, '111', '2022-11-24 15:49:52'),
(20, 11, 1, '111', '2022-11-25 08:37:29'),
(21, 11, 1, '111', '2022-11-25 08:46:21'),
(22, 3, 1, '111', '2022-11-28 09:30:57'),
(23, 3, 1, '111', '2022-11-28 09:33:23'),
(24, 3, 1, '111', '2022-11-28 09:38:59'),
(25, 3, 1, '111', '2022-11-28 09:38:59'),
(26, 3, 1, '111', '2022-11-28 09:39:04'),
(27, 3, 1, '111111', '2022-11-28 09:43:42'),
(28, 3, 1, '111', '2022-11-28 09:43:50'),
(29, 3, 1, '111', '2022-11-28 09:45:01'),
(30, 3, 1, '111', '2022-11-28 09:51:08'),
(31, 3, 1, '111', '2022-11-28 10:22:05'),
(32, 5, 1, '111', '2022-11-28 15:45:18');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` varchar(25) DEFAULT NULL,
  `size` varchar(2) DEFAULT NULL,
  `price` double NOT NULL,
  `img` varchar(100) DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `date_deleted` datetime DEFAULT NULL,
  `date_edited` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `color`, `size`, `price`, `img`, `date_added`, `date_deleted`, `date_edited`, `is_active`) VALUES
(1, '!!!!!!!!!', '!!!!!!!!!', 'S', 14.99, ' ', '2022-01-01 00:00:00', '2022-11-25 09:39:57', '2022-11-22 12:51:15', 0),
(2, '!!!!!!!', '!!!!!!!!', 'M', 147.99, ' ', '2022-11-22 11:08:14', '2022-11-28 09:01:30', '2022-11-22 12:51:43', 0),
(3, '111112', '111112', 'L', 11112, ' ', '2022-11-22 11:10:13', '2022-11-28 15:20:50', '2022-11-28 14:30:41', 0),
(4, 'Товар лучший', 'Красный', 'M', 5, ' ', '2022-11-23 07:06:22', '2022-11-28 15:20:59', NULL, 0),
(5, '1', '1', 'XS', 1111, ' ', '2022-11-23 07:08:44', '2022-11-23 07:09:57', '2022-11-28 15:45:24', 1),
(6, '11111111111111111', '111111111111', 'XL', 111111111, ' ', '2022-11-23 07:10:04', '2022-11-23 08:49:20', '2022-11-23 08:49:10', 1),
(7, '22', '22', 'M', 22, ' ', '2022-11-23 07:10:10', '2022-11-23 08:49:23', '2022-11-23 07:11:04', 1),
(8, '3', '3', 'XS', 3, ' ', '2022-11-23 07:10:26', '2022-11-23 08:49:26', NULL, 1),
(9, 'Товар 1', 'Синий', 'S', 11, ' ', '2022-11-23 08:49:38', '2022-11-24 14:49:57', '2022-11-24 08:10:05', 1),
(10, 'Товар 2', 'Красный', 'L', 2, ' ', '2022-11-23 08:49:50', '2022-11-24 15:49:29', '2022-11-24 15:48:53', 1),
(11, 'Товар 3111', 'Серый', 'M', 5, ' ', '2022-11-23 08:50:11', '2022-11-25 09:16:09', '2022-11-25 08:52:04', 1),
(12, '11', '11', 'XS', 11, ' ', '2022-11-24 08:10:02', '2022-11-24 14:53:30', NULL, 1),
(13, '1', '1', 'XS', 1, ' ', '2022-11-24 14:24:48', '2022-11-24 14:52:27', NULL, 1),
(14, '111', '111', 'XS', 111, ' ', '2022-11-24 14:41:53', '2022-11-24 15:49:32', NULL, 1),
(15, 'Товар 1', 'Серый', 'XS', 1, ' ', '2022-11-24 15:49:41', '2022-11-25 09:39:52', NULL, 0),
(16, 'Товар 4', 'Серый', 'XS', 1, ' ', '2022-11-24 15:56:38', NULL, NULL, 1),
(17, '1', '1', 'XS', 1, ' ', '2022-11-24 15:58:08', NULL, NULL, 1),
(18, '11', '11', 'XS', 11, ' ', '2022-11-25 07:36:39', NULL, NULL, 1),
(19, 'Товар', 'Красный', 'XS', 5, ' ', '2022-11-25 07:39:44', NULL, NULL, 1),
(20, 'Товар 1', '11', 'XS', 11, ' ', '2022-11-25 08:10:26', NULL, NULL, 1),
(21, '11', '11', 'XS', 11, ' ', '2022-11-25 08:49:40', NULL, NULL, 1),
(22, '11', '11', 'XS', 11, ' ', '2022-11-28 09:25:46', NULL, NULL, 1),
(23, '1', '1', 'XS', 1, ' ', '2022-11-28 10:22:01', NULL, NULL, 1),
(24, '1', '1', 'XS', 1, ' ', '2022-11-28 14:30:31', NULL, NULL, 1),
(25, '1', '1', 'XS', 1, ' ', '2022-11-28 15:22:47', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `rate` double NOT NULL,
  `productid` int NOT NULL,
  `userid` int NOT NULL,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `date_edited` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `rate`, `productid`, `userid`, `comment`, `date_added`, `is_active`, `date_edited`) VALUES
(54, 4, 6, 1, '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!11111111111111111111111111111111111111111111111111111111111111111111111111111111', '2022-11-29 12:11:15', 1, '2022-11-29 15:07:11'),
(57, 5, 5, 1, '111', '2022-11-29 12:30:59', NULL, NULL),
(58, 5, 5, 1, '111', '2022-11-29 12:31:24', NULL, NULL),
(59, 5, 8, 1, '111', '2022-11-29 12:52:17', NULL, NULL),
(60, 5, 9, 1, '111', '2022-11-29 12:52:43', NULL, NULL),
(61, 5, 21, 1, '111', '2022-11-29 13:06:26', NULL, NULL),
(62, 1, 16, 1, '111', '2022-11-29 13:53:19', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL,
  `rights` int NOT NULL DEFAULT '0',
  `session` varchar(255) DEFAULT NULL,
  `date_registered` datetime DEFAULT '2022-01-01 00:00:00',
  `date_authorized` datetime DEFAULT '2022-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `rights`, `session`, `date_registered`, `date_authorized`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'egrmhu2rnudhp10lqppk7n9c6o727vso', '2022-01-01 00:00:00', '2022-01-01 00:00:00'),
(2, 'user', '1a1dc91c907325c69271ddf0c944bc72', 0, '1ol2sd249dro5i645qm4sjr8dhfe9fip', '2022-01-01 00:00:00', '2022-01-01 00:00:00'),
(5, 'user2', '698d51a19d8a121ce581499d7b701668', 0, 'egrmhu2rnudhp10lqppk7n9c6o727vso', '2022-11-22 16:53:18', '2022-01-01 00:00:00'),
(6, '111', '698d51a19d8a121ce581499d7b701668', 0, NULL, '2022-11-23 08:40:45', '2022-01-01 00:00:00'),
(7, ' User2 ', '698d51a19d8a121ce581499d7b701668', 0, NULL, '2022-11-23 15:24:04', '2022-01-01 00:00:00'),
(8, '111a', '698d51a19d8a121ce581499d7b701668', 0, NULL, '2022-11-25 08:45:49', '2022-01-01 00:00:00');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
