-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-03-2020 a las 02:27:26
-- Versión del servidor: 10.3.16-MariaDB
-- Versión de PHP: 7.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestioneducacion`
--

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `grado`
--

INSERT INTO `grado` (`id_grado`, `nombre_grado`, `codigo_grado`, `cantidad_asignatura`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Primer Grado', 'GP1', 12, 'A', NULL, NULL),
(2, 'Segundo Grado', 'GS2', 12, 'A', NULL, NULL),
(3, 'Tercer Grado', 'GT3', 12, 'A', NULL, NULL),
(4, 'Cuarto Grado', 'GC4', 12, 'A', NULL, NULL),
(5, 'Quinto Grado', 'GQ5', 12, 'A', NULL, NULL),
(6, 'Sexto Grado', 'GS6', 12, 'A', NULL, NULL),
(7, 'I Ciclo Común Grado', 'CC1', 12, 'A', NULL, NULL),
(8, 'II Ciclo Común', 'CC2', 12, 'A', NULL, NULL),
(9, 'III Ciclo Común', 'CC3', 12, 'A', NULL, NULL),
(10, 'Bachiller en Ciencias y Humanidades', 'BCH', 12, 'A', NULL, NULL),
(11, 'Bachiller en Contaduría y Finanzas', 'BCF', 12, 'A', NULL, NULL),
(12, 'Bachiller Técnico en Ciencias de la Computación', 'BTC', 12, 'A', NULL, NULL),
(13, 'Bachiller en Ciencias Agropecuarias', 'BCA', 12, 'A', NULL, NULL);

