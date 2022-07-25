-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 12. Mai 2022 um 11:32
-- Server-Version: 10.4.24-MariaDB
-- PHP-Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Datenbank: `terminplaner`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer`
--

CREATE TABLE `benutzer` (
  `nr` int(10) NOT NULL,
  `benutzername` varchar(50) NOT NULL,
  `passwort` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `benutzer`
--

INSERT INTO `benutzer` (`nr`, `benutzername`, `passwort`) VALUES
(1, 'admin', '$2y$10$bUbGm.oWyg9mQdnsHeHAwuHkvi2NhGfJVBofGorCHq6IQcfiGjcp6'),
(2, 'max', '$2y$10$xTYoP3TbkMq1zK/J1pLaXeCqPBZgkj8u3stnKK7xKadAAGhIjnmFC');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `intervalle`
--

CREATE TABLE `intervalle` (
  `nr` int(10) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `intervalle`
--

INSERT INTO `intervalle` (`nr`, `name`) VALUES
(2, 'täglich'),
(3, 'wochentlich'),
(4, 'monatlich'),
(5, 'jährlich');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `termine`
--

CREATE TABLE `termine` (
  `nr` int(10) UNSIGNED NOT NULL,
  `benutzer_nr` int(10) NOT NULL,
  `interval_nr` int(10) DEFAULT NULL,
  `beschreibung` varchar(100) NOT NULL,
  `datum` datetime NOT NULL,
  `interval_end_datum` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `termine`
--

INSERT INTO `termine` (`nr`, `benutzer_nr`, `interval_nr`, `beschreibung`, `datum`, `interval_end_datum`) VALUES
(1, 1, 5, 'Jährlicher Termin Torsten B-Day ', '2022-05-04 13:26:17', NULL),
(4, 1, NULL, 'Einmaliger Termin Abschluss Arbeit', '2022-05-13 09:14:53', NULL),
(5, 1, 5, 'Jährlicher Termin Mustafa B-Day', '2021-05-21 09:37:23', NULL),
(6, 1, NULL, 'Einmaliger Termin Abschluss Project start', '2021-05-05 09:40:44', NULL),
(7, 1, 4, 'Monatlicher Termin Spiele Abend', '2020-01-01 09:43:17', '2025-05-22 12:23:57'),
(8, 1, 4, 'Monatlicher Termin Stammtisch Treffen bei Oliver', '2021-05-30 09:51:36', NULL),
(9, 1, 4, 'Monatlicher Termin Kumpels Treffen', '2019-01-05 12:25:16', '2021-12-22 12:23:57'),
(10, 1, 5, 'Jährlicher Termin Marco B-Day', '2022-05-08 14:12:00', '2090-05-26 14:12:01'),
(11, 1, 5, 'Jährlicher termin Abdullah B-Day', '2022-05-04 14:52:07', NULL),
(12, 1, NULL, 'Einmaliger Termin Project Präsentation', '2022-05-13 14:53:09', NULL),
(13, 1, 4, 'Monatliche Termin Treffen mit Marco und Co Team ', '2022-06-19 13:59:34', '2025-06-01 13:59:34'),
(14, 1, 3, 'Wochentliche Termin Unsichtbare Sport termin', '2022-05-06 13:59:34', '2032-05-26 12:18:51'),
(15, 2, 2, 'Tägliche Termin unsichbare Sparziergang ', '2022-05-01 14:00:00', NULL),
(22, 1, 2, 'Tägliche spatziergang', '2022-05-04 16:00:00', '2023-05-11 16:00:00');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD PRIMARY KEY (`nr`),
  ADD UNIQUE KEY `udx_benutzername` (`benutzername`);

--
-- Indizes für die Tabelle `intervalle`
--
ALTER TABLE `intervalle`
  ADD PRIMARY KEY (`nr`);

--
-- Indizes für die Tabelle `termine`
--
ALTER TABLE `termine`
  ADD PRIMARY KEY (`nr`),
  ADD KEY `idx_benutzer_id` (`benutzer_nr`),
  ADD KEY `idx_intervall_id` (`interval_nr`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  MODIFY `nr` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `intervalle`
--
ALTER TABLE `intervalle`
  MODIFY `nr` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `termine`
--
ALTER TABLE `termine`
  MODIFY `nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `termine`
--
ALTER TABLE `termine`
  ADD CONSTRAINT `termine_ibfk_2` FOREIGN KEY (`interval_nr`) REFERENCES `intervalle` (`nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `termine_ibfk_3` FOREIGN KEY (`benutzer_nr`) REFERENCES `benutzer` (`nr`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
