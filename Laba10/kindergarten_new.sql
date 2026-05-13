-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 13 2026 г., 09:49
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kindergarten_new`
--

-- --------------------------------------------------------

--
-- Структура таблицы `children`
--

CREATE TABLE `children` (
  `id` int(11) NOT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `id_group` int(11) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `year_of_birth` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `children`
--

INSERT INTO `children` (`id`, `img_path`, `name`, `id_group`, `bio`, `year_of_birth`) VALUES
(1, 'inc/ChupanovKolya.jpg', 'Чупанов Николай', 1, 'Николай из полной семьи. Папа - юрист. Мама - врач. Хронических заболеваний нет', '2020'),
(2, 'inc/DudkinaRita.jpg', 'Дудкина Маргарита', 1, 'бронхиальная астма', '2020'),
(3, 'inc/FaleevaMira.jpg', 'Фалеева Мира', 1, '', '2020'),
(4, 'inc/FomichevaKatya.jpg', 'Фомичёва Екатерина', 1, 'Родилась в Волгограде. Мама - инженер-экономист. Папа - безработный. Занимается спортивной аэробикой', '2020'),
(8, 'inc/KulikovSasha.jpg', 'Куликов Александр', 2, 'Из неполной семьи. Мама воспитывает Сашу одна с его 1 года', '2021'),
(9, 'inc/LagutinaAlisa.jpg', 'Лагутина Алиса', 3, '', '2022'),
(10, 'inc/LupinaSveta.jpg', 'Лупина Светлана', 3, '', '2022'),
(11, 'inc/MakarovaLiza.jpg', 'Макарова Елизавета', 3, '', '2022'),
(12, 'inc/NechiporenkoOleg.jpeg', 'Нечипоренко Олег', 3, 'диабет', '2022'),
(13, 'inc/ObydennikovMakar.jpg', 'Обыдёнников Макар', 4, 'Здоров', '2019'),
(14, 'inc/PetrovSasha.jpg', 'Петров Александр', 4, '', '2019'),
(15, 'inc/PopovaKsusha.jpg', 'Попова Ксения', 4, 'из неполной семьи. Папа - преподаватель в ВолГУ', '2019'),
(16, 'inc/PupSasha.jpg', 'Пуп Александр', 4, '', '2022'),
(17, 'inc/ShkuratovaSofa.webp', 'Шкуратова Софа', 5, '', '2022'),
(18, 'inc/SlavinaKira.jpg', 'Славина Кира', 5, 'Из полной семьи. Родилась в Санкт-Петербурге', '2019');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name_group` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name_group`) VALUES
(1, 'Цветочки'),
(2, 'Колобок'),
(3, 'Три богатыря '),
(4, 'Смешарики'),
(5, 'Звёздочки');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `rh_factor` varchar(10) DEFAULT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `last_name`, `first_name`, `middle_name`, `birth_date`, `blood_type`, `rh_factor`, `login`, `password`) VALUES
(1, 'Самохвалова', 'Маргарита', 'Сергеевна', '2004-09-24', '1', 'positive', 'margoshasam', '$2y$10$MGo8o0.5JHT3nuYOw5pIZ.woba/5plVvDKWPbmVoyrYYEnnBcgEA6'),
(2, 'С', 'М', 'С', '2004-09-24', '1', 'positive', 'admin', '$2y$10$el8BcyAb8JAEqHxD6OS.xuoc1FFw/DnpwW4hMorJNYBCGWkNPc1Pa');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `children`
--
ALTER TABLE `children`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_group` (`id_group`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `children`
--
ALTER TABLE `children`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `children`
--
ALTER TABLE `children`
  ADD CONSTRAINT `children_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `groups` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
