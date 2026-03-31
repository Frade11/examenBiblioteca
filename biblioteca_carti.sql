-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 31 2026 г., 09:48
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `biblioteca_carti`
--

-- --------------------------------------------------------

--
-- Структура таблицы `carti`
--

CREATE TABLE `carti` (
  `ID_carte` int(11) NOT NULL,
  `Titlu` varchar(255) NOT NULL,
  `Autor` varchar(255) NOT NULL,
  `Anul_publicarii` int(11) DEFAULT NULL,
  `Gen` varchar(100) DEFAULT NULL,
  `Cantitate` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `carti`
--

INSERT INTO `carti` (`ID_carte`, `Titlu`, `Autor`, `Anul_publicarii`, `Gen`, `Cantitate`) VALUES
(3, 'Dimineți cu ceață pe Nistru', 'Vasile Cojocaru', 1987, 'Proză', 4),
(4, 'Umbrele de la Soroca', 'Elena Rusu', 2003, 'Roman', 2),
(5, 'Pâinea de joi', 'Ion Stratan', 1975, 'Poezie', 7),
(6, 'Tată, unde ești?', 'Gheorghe Moraru', 1994, 'Memorii', 1),
(7, 'Drumul spre Cahul', 'Natalia Bîrsan', 2011, 'Roman', 5),
(8, 'Câinele lui Petru', 'Andrei Vrabie', 1968, 'Nuvelă', 3),
(9, 'Toamna fără mere', 'Maria Croitor', 1999, 'Proză scurtă', 0),
(10, 'Satul de sub deal', 'Dumitru Lisnic', 1982, 'Roman', 6),
(11, 'Scrisori neterminate', 'Veronica Plămădeală', 2007, 'Epistolar', 2),
(12, 'Ograda bunicului', 'Petru Harabagiu', 1971, 'Amintiri', 8);

-- --------------------------------------------------------

--
-- Структура таблицы `cititori`
--

CREATE TABLE `cititori` (
  `ID_cititor` int(11) NOT NULL,
  `Nume` varchar(100) NOT NULL,
  `Prenume` varchar(100) NOT NULL,
  `Telefon` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Data_inregistrarii` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `cititori`
--

INSERT INTO `cititori` (`ID_cititor`, `Nume`, `Prenume`, `Telefon`, `Email`, `Data_inregistrarii`) VALUES
(3, 'Tcaci', 'Mihai', '069341782', 'mihai.tcaci@mail.md', '2023-02-14'),
(4, 'Botnaru', 'Svetlana', '078654923', 'svetlana.btn@gmail.com', '2023-05-07'),
(5, 'Grozavu', 'Andrei', '060123847', 'andrei.grz@yahoo.com', '2022-11-30'),
(6, 'Moroi', 'Tatiana', '079834561', 'tanya.moroi@inbox.ru', '2024-01-18'),
(7, 'Friptuleac', 'Ion', '068247130', 'i.friptuleac@mail.md', '2023-08-25'),
(8, 'Chirtoagă', 'Alina', '076591038', 'alina.chirtoaga@gmail.com', '2022-09-03'),
(9, 'Bujac', 'Vasile', '069087345', 'v.bujac@rambler.ru', '2024-03-11'),
(10, 'Scutari', 'Olga', '078312694', 'olga.scutari@mail.md', '2023-12-22'),
(11, 'Negară', 'Dmitri', '060748291', 'dmitri.negara@gmail.com', '2022-07-16'),
(12, 'Rusnac', 'Corina', '079265813', 'corina.rusnac@yahoo.com', '2024-02-05');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `carti`
--
ALTER TABLE `carti`
  ADD PRIMARY KEY (`ID_carte`);

--
-- Индексы таблицы `cititori`
--
ALTER TABLE `cititori`
  ADD PRIMARY KEY (`ID_cititor`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `carti`
--
ALTER TABLE `carti`
  MODIFY `ID_carte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `cititori`
--
ALTER TABLE `cititori`
  MODIFY `ID_cititor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
