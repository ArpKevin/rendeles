-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2024. Okt 10. 12:41
-- Kiszolgáló verziója: 10.4.28-MariaDB
-- PHP verzió: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `rendeles`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `t_orders`
--

CREATE TABLE `t_orders` (
  `u_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Felhasználó id',
  `p_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Termék id',
  `quantity` tinyint(4) NOT NULL,
  `o_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_hungarian_ci COMMENT='Megrendelés tábla';

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `t_products`
--

CREATE TABLE `t_products` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `p_name` varchar(30) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `price` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_hungarian_ci COMMENT='Termékek tábla';

--
-- A tábla adatainak kiíratása `t_products`
--

INSERT INTO `t_products` (`id`, `p_name`, `unit`, `price`) VALUES
(1, 'vmi', 'darab', 130.00),
(2, 'asd', '12', 1330.00),
(3, 'asd', '12', 1111.00),
(4, '213312', '123231', 123312.00),
(5, '31213', '113', 11.00);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `t_users`
--

CREATE TABLE `t_users` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `u_name` varchar(30) NOT NULL,
  `login` varchar(20) NOT NULL,
  `pw` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_hungarian_ci;

--
-- A tábla adatainak kiíratása `t_users`
--

INSERT INTO `t_users` (`id`, `u_name`, `login`, `pw`, `email`) VALUES
(1, 'arpas', 'ak', 'pass', 'gmail'),
(3, 'asd', 'as', '', '12321'),
(4, 'asd', 'as', '', '12321'),
(5, '11', '11', '11', '11');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `t_orders`
--
ALTER TABLE `t_orders`
  ADD KEY `u_id` (`u_id`);

--
-- A tábla indexei `t_products`
--
ALTER TABLE `t_products`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `t_products`
--
ALTER TABLE `t_products`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT a táblához `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `t_orders`
--
ALTER TABLE `t_orders`
  ADD CONSTRAINT `t_orders_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `t_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
