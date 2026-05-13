-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Трв 11 2026 р., 07:57
-- Версія сервера: 5.5.64-MariaDB
-- Версія PHP: 7.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `lavoks`
--

-- --------------------------------------------------------

--
-- Структура таблиці `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(10) unsigned NOT NULL,
  `table_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foreign_key` int(10) unsigned NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12564 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `translations`
--

INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES
(888, 'lazer_types', 'name', 4, 'ru', 'кайзер', '2019-01-26 19:21:51', '2019-01-26 19:21:51'),
(894, 'lazer_types', 'name', 6, 'ru', 'торонто', '2019-01-26 19:22:00', '2019-01-26 19:22:00'),
(897, 'lazer_types', 'name', 7, 'ru', 'алькор', '2019-01-26 19:22:03', '2019-01-26 19:22:03'),
(903, 'lazer_types', 'name', 8, 'ru', 'крейзи хорс', '2019-01-26 19:22:20', '2019-01-26 19:22:20'),
(918, 'lazer_types', 'name', 9, 'ru', 'сафьян', '2019-01-26 19:28:09', '2019-01-26 19:28:09'),
(946, 'lazer_types', 'name', 11, 'ru', 'крокодил', '2019-01-26 19:30:21', '2019-01-26 19:30:21'),
(1189, 'lazer_types', 'name', 13, 'ru', 'Ременная кожа', '2019-01-27 06:11:55', '2019-10-10 16:57:00'),
(1471, 'lazer_types', 'name', 16, 'ru', 'флотар', '2019-01-27 08:25:08', '2019-01-27 08:25:08'),
(1490, 'lazer_types', 'name', 17, 'ru', 'кинг', '2019-01-27 08:31:27', '2019-01-27 08:31:27'),
(3809, 'lazer_types', 'name', 18, 'ru', 'Ременная итальянская кожа', '2019-05-12 16:45:44', '2019-11-10 14:59:25'),
(4236, 'lazer_types', 'name', 19, 'ru', 'Бостон', '2019-06-22 18:33:20', '2019-11-10 14:53:55'),
(4264, 'lazer_types', 'name', 20, 'ru', 'Краст', '2019-06-28 16:42:48', '2019-11-10 14:55:11'),
(4493, 'lazer_types', 'name', 21, 'ru', 'Боттега', '2019-07-17 14:22:09', '2019-11-10 14:57:51'),
(5596, 'lazer_types', 'name', 22, 'ru', 'Наппа', '2019-10-31 19:58:36', '2019-10-31 19:58:36'),
(6525, 'lazer_types', 'name', 24, 'ru', 'Ткань', '2020-04-12 23:28:38', '2020-04-12 23:28:38'),
(6739, 'lazer_types', 'name', 25, 'ru', 'Игуана', '2020-05-24 02:11:14', '2020-05-24 02:11:14'),
(7038, 'lazer_types', 'name', 26, 'ru', 'Крейзи хорс роял', '2020-08-23 01:05:04', '2020-08-23 01:05:04'),
(7941, 'lazer_types', 'name', 30, 'ru', 'Оскар', '2021-02-19 04:49:04', '2021-02-19 04:49:04'),
(9078, 'lazer_types', 'name', 31, 'ru', 'Питон', '2022-02-06 17:52:52', '2022-02-06 17:52:52'),
(9092, 'lazer_types', 'name', 32, 'ru', 'Кайман', '2022-02-16 04:46:42', '2022-02-16 04:46:42'),
(9368, 'lazer_types', 'name', 33, 'ru', 'Веревка', '2022-08-20 12:17:16', '2022-08-20 12:17:16'),
(10092, 'lazer_types', 'name', 34, 'ru', 'Рептилия', '2023-05-11 03:19:48', '2023-05-11 03:19:48'),
(10576, 'lazer_types', 'name', 35, 'ru', 'Страус', '2024-01-08 02:17:39', '2024-01-08 02:17:39'),
(10583, 'lazer_types', 'name', 36, 'ru', 'Замша', '2024-01-08 18:59:39', '2024-01-08 18:59:39'),
(10663, 'lazer_types', 'name', 37, 'ru', 'Лаковая', '2024-01-31 02:59:22', '2024-01-31 02:59:22');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `translations_table_name_column_name_foreign_key_locale_unique` (`table_name`,`column_name`,`foreign_key`,`locale`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12564;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
