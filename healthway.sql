-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2025 a las 18:10:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `healthway`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camas`
--

CREATE TABLE `camas` (
  `IdCama` int(11) NOT NULL,
  `IdHabitacion` int(11) NOT NULL,
  `NumeroCama` int(11) NOT NULL,
  `EstadoCama` enum('Disponible','Ocupada','En limpieza') DEFAULT 'Disponible',
  `Habilitada` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `camas`
--

INSERT INTO `camas` (`IdCama`, `IdHabitacion`, `NumeroCama`, `EstadoCama`, `Habilitada`) VALUES
(1, 1, 1, 'Disponible', 1),
(2, 1, 2, 'Disponible', 1),
(3, 2, 3, 'Disponible', 1),
(4, 2, 4, 'Disponible', 1),
(5, 3, 5, 'Disponible', 1),
(6, 3, 6, 'Disponible', 1),
(7, 4, 7, 'Disponible', 1),
(8, 4, 8, 'Disponible', 1),
(9, 5, 9, 'Disponible', 1),
(10, 5, 10, 'Disponible', 1),
(11, 6, 11, 'Disponible', 1),
(12, 6, 12, 'Disponible', 1),
(13, 7, 13, 'Disponible', 1),
(14, 7, 14, 'Ocupada', 0),
(15, 8, 15, 'Disponible', 1),
(16, 8, 16, 'Disponible', 1),
(17, 9, 17, 'Disponible', 1),
(18, 9, 18, 'Disponible', 1),
(19, 10, 19, 'Disponible', 1),
(20, 10, 20, 'Disponible', 1),
(21, 11, 21, 'Disponible', 1),
(22, 11, 22, 'Disponible', 1),
(23, 12, 23, 'Disponible', 1),
(24, 12, 24, 'Disponible', 1),
(25, 13, 25, 'Disponible', 1),
(26, 13, 26, 'Disponible', 1),
(27, 14, 27, 'Disponible', 1),
(28, 14, 28, 'Disponible', 1),
(29, 15, 29, 'Disponible', 1),
(30, 15, 30, 'Disponible', 1),
(31, 16, 31, 'Disponible', 1),
(32, 16, 32, 'Disponible', 1),
(33, 17, 33, 'Disponible', 1),
(34, 17, 34, 'Disponible', 1),
(35, 18, 35, 'Disponible', 1),
(36, 18, 36, 'Ocupada', 0),
(37, 19, 37, 'Disponible', 1),
(38, 19, 38, 'Disponible', 1),
(39, 20, 39, 'Disponible', 1),
(40, 20, 40, 'Disponible', 1),
(41, 21, 41, 'Disponible', 1),
(42, 21, 42, 'Disponible', 1),
(43, 22, 43, 'Disponible', 1),
(44, 22, 44, 'Disponible', 1),
(45, 23, 45, 'Disponible', 1),
(46, 23, 46, 'Disponible', 1),
(47, 24, 47, 'Disponible', 1),
(48, 24, 48, 'Disponible', 1),
(49, 25, 49, 'Disponible', 1),
(50, 25, 50, 'Disponible', 1),
(51, 26, 51, 'Disponible', 1),
(52, 26, 52, 'Disponible', 1),
(53, 27, 53, 'Disponible', 1),
(54, 27, 54, 'Disponible', 1),
(55, 28, 55, 'Disponible', 1),
(56, 28, 56, 'Disponible', 1),
(57, 29, 57, 'Disponible', 1),
(58, 29, 58, 'Disponible', 1),
(59, 30, 59, 'Disponible', 1),
(60, 30, 60, 'Disponible', 1),
(61, 31, 61, 'Disponible', 1),
(62, 31, 62, 'Disponible', 1),
(63, 32, 63, 'Disponible', 1),
(64, 32, 64, 'Disponible', 1),
(65, 33, 65, 'Disponible', 1),
(66, 33, 66, 'Disponible', 1),
(67, 34, 67, 'Disponible', 1),
(68, 34, 68, 'Disponible', 1),
(69, 35, 69, 'Disponible', 1),
(70, 35, 70, 'Disponible', 1),
(71, 36, 71, 'Disponible', 1),
(72, 36, 72, 'Disponible', 1),
(73, 37, 73, 'Disponible', 1),
(74, 37, 74, 'Disponible', 1),
(75, 38, 75, 'Disponible', 1),
(76, 38, 76, 'Disponible', 1),
(77, 39, 77, 'Disponible', 1),
(78, 39, 78, 'Disponible', 1),
(79, 40, 79, 'Disponible', 1),
(80, 40, 80, 'Disponible', 1),
(81, 41, 81, 'Disponible', 1),
(82, 41, 82, 'Disponible', 1),
(83, 42, 83, 'Disponible', 1),
(84, 42, 84, 'Disponible', 1),
(85, 43, 85, 'Disponible', 1),
(86, 43, 86, 'Disponible', 1),
(87, 44, 87, 'Disponible', 1),
(88, 44, 88, 'Disponible', 1),
(89, 45, 89, 'Disponible', 1),
(90, 45, 90, 'Disponible', 1),
(91, 46, 91, 'Disponible', 1),
(92, 46, 92, 'Disponible', 1),
(93, 47, 93, 'Ocupada', 0),
(94, 47, 94, 'Disponible', 1),
(95, 48, 95, 'Disponible', 1),
(96, 48, 96, 'Disponible', 1),
(97, 49, 97, 'Disponible', 1),
(98, 49, 98, 'Disponible', 1),
(99, 50, 99, 'Disponible', 1),
(100, 50, 100, 'Disponible', 1),
(101, 51, 101, 'Disponible', 1),
(102, 52, 102, 'Disponible', 1),
(103, 53, 103, 'Disponible', 1),
(104, 54, 104, 'Disponible', 1),
(105, 55, 105, 'Disponible', 1),
(106, 56, 106, 'Disponible', 1),
(107, 57, 107, 'Disponible', 1),
(108, 58, 108, 'Disponible', 1),
(109, 59, 109, 'Disponible', 1),
(110, 60, 110, 'Disponible', 1),
(111, 61, 111, 'Disponible', 1),
(112, 62, 112, 'Disponible', 1),
(113, 63, 113, 'Disponible', 1),
(114, 64, 114, 'Disponible', 1),
(115, 65, 115, 'Disponible', 1),
(116, 66, 116, 'Disponible', 1),
(117, 67, 117, 'Disponible', 1),
(118, 68, 118, 'Disponible', 1),
(119, 69, 119, 'Disponible', 1),
(120, 70, 120, 'Disponible', 1),
(121, 71, 121, 'Disponible', 1),
(122, 72, 122, 'Disponible', 1),
(123, 73, 123, 'Disponible', 1),
(124, 74, 124, 'Disponible', 1),
(125, 75, 125, 'Disponible', 1),
(126, 76, 126, 'Disponible', 1),
(127, 77, 127, 'Disponible', 1),
(128, 78, 128, 'Disponible', 1),
(129, 79, 129, 'Disponible', 1),
(130, 80, 130, 'Disponible', 1),
(131, 81, 131, 'Disponible', 1),
(132, 82, 132, 'Disponible', 1),
(133, 83, 133, 'Disponible', 1),
(134, 84, 134, 'Disponible', 1),
(135, 85, 135, 'Disponible', 1),
(136, 86, 136, 'Disponible', 1),
(137, 87, 137, 'Disponible', 1),
(138, 88, 138, 'Disponible', 1),
(139, 89, 139, 'Disponible', 1),
(140, 90, 140, 'Disponible', 1),
(141, 91, 141, 'Disponible', 1),
(142, 92, 142, 'Disponible', 1),
(143, 93, 143, 'Disponible', 1),
(144, 94, 144, 'Disponible', 1),
(145, 95, 145, 'Disponible', 1),
(146, 96, 146, 'Disponible', 1),
(147, 97, 147, 'Disponible', 1),
(148, 98, 148, 'Disponible', 1),
(149, 99, 149, 'Disponible', 1),
(150, 100, 150, 'Disponible', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `IdDireccion` int(11) NOT NULL,
  `Direccion` varchar(30) NOT NULL,
  `Numero` int(11) NOT NULL,
  `Ciudad` varchar(20) NOT NULL,
  `Provincia` varchar(15) NOT NULL,
  `Pais` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`IdDireccion`, `Direccion`, `Numero`, `Ciudad`, `Provincia`, `Pais`) VALUES
(1, 'Jose Maria Ezeiza', 623, 'Ezeiza', 'Buenos Aires', 'Argentina'),
(2, 'Tuyuti', 700, 'Ezeiza', 'Provincia2', 'Buenos Aires'),
(3, 'San Martin', 122, 'Caballito', 'Buenos Aires', 'Argentina'),
(4, 'French', 532, 'Lomas de Zamora', 'Buenos Aires', 'Argentina'),
(5, 'Manuel Belgrano', 432, 'CABA', 'Buenos Aires', 'Argentina'),
(6, 'Larralde', 434, 'Ezeiza', 'Buenos Aires', 'Argentina'),
(7, 'Perito Moreno', 323, 'CABA', 'Buenos Aires', 'Argentina'),
(8, 'Lamadrid', 342, 'Ezeiza', 'Buenos Aires', 'Argentina'),
(9, 'San Martin', 432, 'CABA', 'Buenos Aires', 'Argentina'),
(10, 'Alfonsina Stormi', 850, 'Barrio Uno', 'Buenos Aires', 'Argentina');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadorevisiones`
--

CREATE TABLE `estadorevisiones` (
  `IdEstadoRev` int(11) NOT NULL,
  `DescEstadoRev` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estadorevisiones`
--

INSERT INTO `estadorevisiones` (`IdEstadoRev`, `DescEstadoRev`) VALUES
(1, 'Rutina'),
(2, 'Urgencia'),
(3, 'Programada'),
(4, 'Alta'),
(5, 'Fallecimiento'),
(6, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `IdHabitacion` int(11) NOT NULL,
  `NumeroHabitacion` int(11) NOT NULL,
  `TipoHabitacion` enum('Individual','Compartida') NOT NULL,
  `Habilitada` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`IdHabitacion`, `NumeroHabitacion`, `TipoHabitacion`, `Habilitada`) VALUES
(1, 101, 'Compartida', 1),
(2, 102, 'Compartida', 1),
(3, 103, 'Compartida', 1),
(4, 104, 'Compartida', 1),
(5, 105, 'Compartida', 1),
(6, 106, 'Compartida', 1),
(7, 107, 'Compartida', 1),
(8, 108, 'Compartida', 1),
(9, 109, 'Compartida', 1),
(10, 110, 'Compartida', 1),
(11, 111, 'Compartida', 1),
(12, 112, 'Compartida', 1),
(13, 113, 'Compartida', 1),
(14, 114, 'Compartida', 1),
(15, 115, 'Compartida', 1),
(16, 116, 'Compartida', 1),
(17, 117, 'Compartida', 1),
(18, 118, 'Compartida', 1),
(19, 119, 'Compartida', 1),
(20, 120, 'Compartida', 1),
(21, 121, 'Compartida', 1),
(22, 122, 'Compartida', 1),
(23, 123, 'Compartida', 1),
(24, 124, 'Compartida', 1),
(25, 125, 'Compartida', 1),
(26, 126, 'Compartida', 1),
(27, 127, 'Compartida', 1),
(28, 128, 'Compartida', 1),
(29, 129, 'Compartida', 1),
(30, 130, 'Compartida', 1),
(31, 131, 'Compartida', 1),
(32, 132, 'Compartida', 1),
(33, 133, 'Compartida', 1),
(34, 134, 'Compartida', 1),
(35, 135, 'Compartida', 1),
(36, 136, 'Compartida', 1),
(37, 137, 'Compartida', 1),
(38, 138, 'Compartida', 1),
(39, 139, 'Compartida', 1),
(40, 140, 'Compartida', 1),
(41, 141, 'Compartida', 1),
(42, 142, 'Compartida', 1),
(43, 143, 'Compartida', 1),
(44, 144, 'Compartida', 1),
(45, 145, 'Compartida', 1),
(46, 146, 'Compartida', 1),
(47, 147, 'Compartida', 1),
(48, 148, 'Compartida', 1),
(49, 149, 'Compartida', 1),
(50, 150, 'Compartida', 1),
(51, 151, 'Individual', 1),
(52, 152, 'Individual', 1),
(53, 153, 'Individual', 1),
(54, 154, 'Individual', 1),
(55, 155, 'Individual', 1),
(56, 156, 'Individual', 1),
(57, 157, 'Individual', 1),
(58, 158, 'Individual', 1),
(59, 159, 'Individual', 1),
(60, 160, 'Individual', 1),
(61, 161, 'Individual', 1),
(62, 162, 'Individual', 1),
(63, 163, 'Individual', 1),
(64, 164, 'Individual', 1),
(65, 165, 'Individual', 1),
(66, 166, 'Individual', 1),
(67, 167, 'Individual', 1),
(68, 168, 'Individual', 1),
(69, 169, 'Individual', 1),
(70, 170, 'Individual', 1),
(71, 171, 'Individual', 1),
(72, 172, 'Individual', 1),
(73, 173, 'Individual', 1),
(74, 174, 'Individual', 1),
(75, 175, 'Individual', 1),
(76, 176, 'Individual', 1),
(77, 177, 'Individual', 1),
(78, 178, 'Individual', 1),
(79, 179, 'Individual', 1),
(80, 180, 'Individual', 1),
(81, 181, 'Individual', 1),
(82, 182, 'Individual', 1),
(83, 183, 'Individual', 1),
(84, 184, 'Individual', 1),
(85, 185, 'Individual', 1),
(86, 186, 'Individual', 1),
(87, 187, 'Individual', 1),
(88, 188, 'Individual', 1),
(89, 189, 'Individual', 1),
(90, 190, 'Individual', 1),
(91, 191, 'Individual', 1),
(92, 192, 'Individual', 1),
(93, 193, 'Individual', 1),
(94, 194, 'Individual', 1),
(95, 195, 'Individual', 1),
(96, 196, 'Individual', 1),
(97, 197, 'Individual', 1),
(98, 198, 'Individual', 1),
(99, 199, 'Individual', 1),
(100, 200, 'Individual', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `internaciones`
--

CREATE TABLE `internaciones` (
  `IdInternacion` int(11) NOT NULL,
  `IdSolicitud` int(11) NOT NULL,
  `IdCama` int(11) NOT NULL,
  `IdHabitacion` int(11) NOT NULL,
  `IdPaciente` int(11) NOT NULL,
  `FechaInicio` datetime NOT NULL,
  `FechaFin` datetime NOT NULL,
  `EstadoInternacion` enum('Activa','Finalizada','Reprogramada','Trasladada','Fallecido') DEFAULT 'Activa',
  `Observaciones` varchar(100) DEFAULT NULL,
  `QR` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `internaciones`
--

INSERT INTO `internaciones` (`IdInternacion`, `IdSolicitud`, `IdCama`, `IdHabitacion`, `IdPaciente`, `FechaInicio`, `FechaFin`, `EstadoInternacion`, `Observaciones`, `QR`) VALUES
(4, 1, 1, 1, 1, '2025-10-30 20:29:59', '2025-11-06 20:29:59', 'Activa', 'Dolor abdominal intenso', 0x68747470733a2f2f6c6f63616c686f73742f6865616c74687761792f6170692f696e7465726e6163696f6e65732f4d6f7374726172496e7465726e6163696f6e657351522e7068703f69643d34),
(5, 2, 2, 1, 2, '2025-10-30 20:29:59', '2025-11-06 20:29:59', 'Activa', 'Cirugía ortopédica', 0x68747470733a2f2f6c6f63616c686f73742f6865616c74687761792f6170692f696e7465726e6163696f6e65732f4d6f7374726172496e7465726e6163696f6e657351522e7068703f69643d35),
(6, 3, 3, 2, 3, '2025-10-30 20:29:59', '2025-11-06 20:29:59', 'Activa', 'Descompensación cardíaca', 0x68747470733a2f2f6c6f63616c686f73742f6865616c74687761792f6170692f696e7465726e6163696f6e65732f4d6f7374726172496e7465726e6163696f6e657351522e7068703f69643d36),
(7, 4, 4, 2, 4, '2025-10-30 20:29:59', '2025-11-06 20:29:59', 'Activa', 'Tratamiento postoperatorio', 0x68747470733a2f2f6c6f63616c686f73742f6865616c74687761792f6170692f696e7465726e6163696f6e65732f4d6f7374726172496e7465726e6163696f6e657351522e7068703f69643d37),
(8, 5, 5, 3, 5, '2025-10-30 20:29:59', '2025-11-06 20:29:59', 'Activa', 'Chequeo general', 0x68747470733a2f2f6c6f63616c686f73742f6865616c74687761792f6170692f696e7465726e6163696f6e65732f4d6f7374726172496e7465726e6163696f6e657351522e7068703f69643d38),
(9, 6, 6, 3, 6, '2025-10-30 20:29:59', '2025-11-06 20:29:59', 'Activa', 'Traumatismo craneal', 0x68747470733a2f2f6c6f63616c686f73742f6865616c74687761792f6170692f696e7465726e6163696f6e65732f4d6f7374726172496e7465726e6163696f6e657351522e7068703f69643d39),
(10, 7, 7, 4, 7, '2025-10-30 20:29:59', '2025-11-06 20:29:59', 'Activa', 'Cirugía de rodilla', 0x68747470733a2f2f6c6f63616c686f73742f6865616c74687761792f6170692f696e7465726e6163696f6e65732f4d6f7374726172496e7465726e6163696f6e657351522e7068703f69643d3130),
(11, 8, 8, 4, 8, '2025-10-30 20:29:59', '2025-11-06 20:29:59', 'Activa', 'Neumonía grave', 0x68747470733a2f2f6c6f63616c686f73742f6865616c74687761792f6170692f696e7465726e6163696f6e65732f4d6f7374726172496e7465726e6163696f6e657351522e7068703f69643d3131),
(12, 9, 9, 5, 9, '2025-10-30 20:29:59', '2025-11-06 20:29:59', 'Activa', 'Evaluación neurológica', 0x68747470733a2f2f6c6f63616c686f73742f6865616c74687761792f6170692f696e7465726e6163696f6e65732f4d6f7374726172496e7465726e6163696f6e657351522e7068703f69643d3132),
(13, 10, 10, 5, 10, '2025-10-30 20:29:59', '2025-11-06 20:29:59', 'Activa', 'Terapia de rehabilitación', 0x68747470733a2f2f6c6f63616c686f73742f6865616c74687761792f6170692f696e7465726e6163696f6e65732f4d6f7374726172496e7465726e6163696f6e657351522e7068703f69643d3133);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `rol_destino` varchar(50) NOT NULL,
  `evento` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obrassociales`
--

CREATE TABLE `obrassociales` (
  `IdOS` int(11) NOT NULL,
  `NombreOS` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `obrassociales`
--

INSERT INTO `obrassociales` (`IdOS`, `NombreOS`) VALUES
(1, 'OSPE'),
(2, 'OSPE'),
(3, 'UOCRA'),
(4, 'UOCRA'),
(5, 'UPCN'),
(6, 'UPCN'),
(7, 'OSPE'),
(8, 'OSPE'),
(9, 'UOCRA'),
(10, 'UOCRA'),
(11, 'UPCN'),
(12, 'UPCN'),
(13, 'OSPE'),
(14, 'UOCRA'),
(15, 'UPCN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `IdPaciente` int(11) NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  `IdPlan_OS` int(11) NOT NULL,
  `IdDireccion` int(11) DEFAULT NULL,
  `FechaNac` datetime NOT NULL,
  `Genero` enum('Hombre','Mujer','Otro') NOT NULL,
  `DNI` bigint(20) NOT NULL,
  `EstadoCivil` enum('Casado','Soltero') DEFAULT NULL,
  `Estado` enum('Normal','Trasladado','Internado') NOT NULL DEFAULT 'Normal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`IdPaciente`, `IdUsuario`, `IdPlan_OS`, `IdDireccion`, `FechaNac`, `Genero`, `DNI`, `EstadoCivil`, `Estado`) VALUES
(1, 11, 1, 1, '1990-05-12 00:30:00', 'Mujer', 12345689, 'Casado', 'Normal'),
(2, 12, 2, 2, '1990-06-13 04:00:00', 'Hombre', 12345690, 'Casado', 'Normal'),
(3, 13, 3, 3, '1990-04-14 17:00:00', 'Mujer', 12345691, 'Casado', 'Normal'),
(4, 14, 4, 4, '1990-07-15 23:00:00', 'Hombre', 12345692, 'Soltero', 'Normal'),
(5, 15, 5, 5, '1990-12-14 21:00:00', 'Mujer', 12345691, 'Soltero', 'Normal'),
(6, 16, 6, 6, '1990-11-11 00:00:00', 'Hombre', 12345688, 'Soltero', 'Normal'),
(7, 17, 7, 7, '1990-03-12 15:00:00', 'Mujer', 12345689, 'Casado', 'Normal'),
(8, 18, 8, 8, '1990-01-13 12:30:00', 'Hombre', 12345690, 'Soltero', 'Normal'),
(9, 19, 9, 9, '1990-02-14 11:00:00', 'Mujer', 12345691, 'Soltero', 'Normal'),
(10, 20, 10, 10, '1990-04-15 09:00:00', 'Hombre', 12345692, 'Soltero', 'Normal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `IdPermiso` int(11) NOT NULL,
  `DescPermiso` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`IdPermiso`, `DescPermiso`) VALUES
(1, 'Visualizar dashboard administrador'),
(2, 'Visualizar dashboard paciente'),
(3, 'Visualizar dashboard jefe internaciones'),
(4, 'Visualizar dashboard personal medico'),
(5, 'Escanear QR'),
(6, 'Visualizar Internaciones'),
(7, 'Crear Internaciones'),
(8, 'Editar Internaciones'),
(9, 'Visualizar Revisiones'),
(10, 'Crear Revisiones'),
(11, 'Editar Revisiones'),
(12, 'Visualizar Recordatorios'),
(13, 'Crear Recordatorios'),
(14, 'Editar Recordatorios'),
(15, 'Crear revision Signos Vitales'),
(16, 'Crear revision Alimentacion'),
(17, 'Crear revision higienizacion'),
(18, 'Crear revision medicacion'),
(19, 'Crear revision intervención'),
(20, 'Crear revision intervención quirurgica'),
(21, 'Editar revision signos vitales'),
(22, 'Editar revision alimentacion'),
(23, 'Editar revision higienizacion'),
(24, 'Editar revision medicacion'),
(25, 'Editar revision intervención'),
(26, 'Editar revision intervención quirurgica'),
(27, 'Crear revision otro tipo'),
(28, 'Editar revision otro tipo'),
(29, 'Crear Revision De Rutina'),
(30, 'Crear Revision Programada'),
(31, 'Crear Revision Urgencia'),
(32, 'Crear Revision Alta'),
(33, 'Crear Revision Fallecimiento'),
(34, 'Crear Revision Otro Estado'),
(35, 'Editar Revision Otro Estado'),
(36, 'Editar Revision Fallecimiento'),
(37, 'Editar Revision Alta'),
(38, 'Editar Revision Programada'),
(39, 'Editar Revision Urgencia'),
(40, 'Editar Revision Rutina'),
(43, 'Iniciar sesion'),
(44, 'Visualizar informacion personal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personascontacto`
--

CREATE TABLE `personascontacto` (
  `IdPC` int(11) NOT NULL,
  `IdDireccion` int(11) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `Apellido` varchar(20) NOT NULL,
  `Email` varchar(25) NOT NULL,
  `Telefono` bigint(20) NOT NULL,
  `TipoContacto` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personascontacto_pacientes`
--

CREATE TABLE `personascontacto_pacientes` (
  `IdPC_Paciente` int(11) NOT NULL,
  `IdPaciente` int(11) NOT NULL,
  `IdPC` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes_obrassociales`
--

CREATE TABLE `planes_obrassociales` (
  `IdPlan` int(11) NOT NULL,
  `IdOS` int(11) NOT NULL,
  `NombrePlan` varchar(30) NOT NULL,
  `TipoHabitacion` enum('Individual','Compartida') NOT NULL,
  `HorasInternacion` int(11) NOT NULL,
  `PrecioHora` decimal(10,2) NOT NULL DEFAULT 1000.00,
  `PrecioHoraExtra` decimal(10,2) NOT NULL DEFAULT 1500.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `planes_obrassociales`
--

INSERT INTO `planes_obrassociales` (`IdPlan`, `IdOS`, `NombrePlan`, `TipoHabitacion`, `HorasInternacion`, `PrecioHora`, `PrecioHoraExtra`) VALUES
(1, 1, 'Plan Básico', 'Compartida', 24, 1000.00, 1500.00),
(2, 2, 'Plan Básico', 'Individual', 36, 1000.00, 1500.00),
(3, 3, 'Plan Básico', 'Compartida', 48, 1000.00, 1500.00),
(4, 4, 'Plan Básico', 'Individual', 24, 1000.00, 1500.00),
(5, 5, 'Plan Básico', 'Compartida', 36, 1000.00, 1500.00),
(6, 6, 'Plan Básico', 'Individual', 48, 1000.00, 1500.00),
(7, 7, 'Plan Básico', 'Compartida', 24, 1000.00, 1500.00),
(8, 8, 'Plan Básico', 'Individual', 36, 1000.00, 1500.00),
(9, 9, 'Plan Básico', 'Compartida', 48, 1000.00, 1500.00),
(10, 10, 'Plan Básico', 'Individual', 24, 1000.00, 1500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recordatorio`
--

CREATE TABLE `recordatorio` (
  `IdRecordatorio` int(11) NOT NULL,
  `IdInternacion` int(11) DEFAULT NULL,
  `IdUsuario` int(11) DEFAULT NULL,
  `TipoRevision` enum('Signos Vitales','Alimentacion','Higienizacion','Medicacion','Intervencion','Intervencion Quirurgica','Otro') NOT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `Estado` enum('Hecho','No Hecho') DEFAULT 'No Hecho',
  `FechaInicioRec` datetime NOT NULL,
  `FechaFinRec` datetime NOT NULL,
  `Frecuencia` enum('Diaria','Semanal','Unica Vez') NOT NULL,
  `FrecuenciaHoras` int(11) DEFAULT NULL,
  `FrecuenciaDias` int(11) DEFAULT NULL,
  `FrecuenciaSem` int(11) DEFAULT NULL,
  `RepetirLunes` tinyint(1) DEFAULT 0,
  `RepetirMartes` tinyint(1) DEFAULT 0,
  `RepetirMiercoles` tinyint(1) DEFAULT 0,
  `RepetirJueves` tinyint(1) DEFAULT 0,
  `RepetirViernes` tinyint(1) DEFAULT 0,
  `RepetirSabado` tinyint(1) DEFAULT 0,
  `RepetirDomingo` tinyint(1) DEFAULT 0,
  `Observaciones` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `revisiones`
--

CREATE TABLE `revisiones` (
  `IdRevisiones` int(11) NOT NULL,
  `IdInternacion` int(11) DEFAULT NULL,
  `IdUsuario` int(11) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `TipoRevision` int(11) NOT NULL,
  `EstadoRevision` int(11) NOT NULL,
  `Sintomas` varchar(50) NOT NULL,
  `Diagnostico` varchar(50) NOT NULL,
  `Tratamiento` varchar(50) NOT NULL,
  `Observaciones` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `revisiones`
--

INSERT INTO `revisiones` (`IdRevisiones`, `IdInternacion`, `IdUsuario`, `FechaCreacion`, `TipoRevision`, `EstadoRevision`, `Sintomas`, `Diagnostico`, `Tratamiento`, `Observaciones`) VALUES
(1, 4, 3, '2025-10-31 08:00:00', 1, 2, 'Dolor agudo fosa iliaca', 'Posible apendicitis', 'Analgesicos IV y Ayuno', 'Se solicita ecografia urgente'),
(2, 5, 4, '2025-10-31 09:30:00', 6, 3, 'Dolor en extremidad inf', 'Fractura de femur', 'Cirugia de reduccion', 'Preparacion prequirurgica lista'),
(3, 6, 5, '2025-10-31 10:15:00', 4, 2, 'Disnea y taquicardia', 'Insuficiencia Cardiaca', 'Diureticos y O2', 'Monitoreo de signos vitales'),
(4, 7, 6, '2025-10-31 11:00:00', 3, 1, 'Dolor leve en herida', 'Evolucion postquirurgica', 'Limpieza y vendaje', 'Herida sin signos de infeccion'),
(5, 8, 7, '2025-10-31 12:45:00', 1, 1, 'Asintomatico', 'Control de rutina', 'Examen fisico completo', 'Paciente estable'),
(6, 9, 8, '2025-10-31 14:20:00', 5, 2, 'Cefalea y mareos', 'Conmocion cerebral leve', 'Observacion neurologica', 'TAC realizado sin particularidades'),
(7, 10, 9, '2025-10-31 15:30:00', 6, 3, 'Inestabilidad rodilla', 'Rotura ligamento cruzado', 'Artroscopia programada', 'Firma de consentimiento informado'),
(8, 11, 10, '2025-10-31 16:40:00', 4, 4, 'Fiebre alta y tos', 'Neumonia bacteriana', 'Antibioticos espectro', 'Aislamiento preventivo'),
(9, 12, 3, '2025-10-31 17:50:00', 1, 1, 'Perdida de sensibilidad', 'Evaluacion nerviosa', 'Test de reflejos', 'Interconsulta con especialista'),
(10, 13, 4, '2025-10-31 18:15:00', 7, 3, 'Rigidez muscular', 'Recuperacion movilidad', 'Ejercicios kinesiologia', 'Primera sesion satisfactoria'),
(11, 4, 5, '2025-11-01 08:00:00', 4, 1, 'Dolor abdominal moderado', 'Evolucion favorable', 'Continuar antibioticos', 'Paciente tolera dieta liquida'),
(12, 5, 6, '2025-11-01 09:15:00', 3, 1, 'Movilidad reducida', 'Higiene en cama', 'Baño asistido', 'Se requiere cambio de sabanas'),
(13, 6, 7, '2025-11-01 10:30:00', 1, 2, 'Presion arterial elevada', 'Hipertension leve', 'Ajuste de Enalapril', 'Controlar presion c/2 horas'),
(14, 7, 8, '2025-11-01 11:45:00', 2, 1, 'Hambre excesiva', 'Tolerancia digestiva', 'Dieta blanda', 'Sin nauseas ni vomitos'),
(15, 8, 9, '2025-11-01 13:00:00', 7, 4, 'Ninguno', 'Alta Medica', 'Reposo en domicilio', 'Se entregan indicaciones escritas'),
(16, 9, 10, '2025-11-01 14:20:00', 1, 1, 'Leve cefalea', 'Traumatismo estable', 'Paracetamol SOS', 'Pupilas isocoricas'),
(17, 10, 3, '2025-11-01 15:40:00', 3, 1, 'Molestia en venda', 'Curacion de herida', 'Cambio de vendaje', 'Herida limpia sin supuracion'),
(18, 11, 4, '2025-11-01 17:00:00', 1, 1, 'Dificultad respiratoria leve', 'Saturacion 96%', 'Oxigeno por canula', 'Mantener posicion semi-sentado'),
(19, 12, 5, '2025-11-02 08:30:00', 5, 3, 'Hormigueo en manos', 'Evaluacion sensitiva', 'Test de conductividad', 'Resultados pendientes'),
(20, 13, 6, '2025-11-02 09:45:00', 5, 3, 'Dolor muscular al mover', 'Sesion Kinesiologia', 'Masajes y estiramiento', 'Paciente coopera con ejercicios'),
(21, 4, 7, '2025-11-02 11:00:00', 1, 1, 'Sin dolor', 'Recuperacion abdomen', 'Dieta solida', 'Transito intestinal normal'),
(22, 5, 8, '2025-11-02 12:15:00', 4, 1, 'Dolor post-quirurgico', 'Control analgesia', 'Tramadol IV', 'Paciente descansa mejor'),
(23, 6, 9, '2025-11-02 14:00:00', 7, 4, 'Estable', 'Alta por mejoria', 'Control ambulatorio', 'Cita cardiologo en 1 semana'),
(24, 10, 10, '2025-11-02 16:30:00', 5, 1, 'Rigidez articulacion', 'Movilizacion pasiva', 'Ejercicios suaves', 'Logra flexion 45 grados'),
(25, 11, 3, '2025-11-03 08:00:00', 4, 1, 'Fiebre remitio', 'Evolucion neumonia', 'Completar antibioticos', 'Se retira oxigeno suplementario'),
(26, 9, 4, '2025-11-03 10:00:00', 7, 4, 'Asintomatico', 'Alta neurologica', 'Vida normal progresiva', 'Evitar pantallas por 48hs'),
(27, 7, 5, '2025-11-03 12:00:00', 3, 1, 'Sudoracion', 'Higiene personal', 'Ducha asistida', 'Se siente mas comodo'),
(28, 13, 6, '2025-11-03 15:00:00', 1, 1, 'Cansancio post-ejercicio', 'Control signos vitales', 'Hidratacion oral', 'Frecuencia cardiaca normal'),
(29, 12, 7, '2025-11-03 18:00:00', 4, 1, 'Ansiedad', 'Estado animico', 'Ansiolitico suave', 'Interconsulta psicologia'),
(30, 5, 8, '2025-11-04 09:00:00', 1, 4, 'Buen estado general', 'Alta traumatologia', 'Uso de muletas', 'Control radiografico en 15 dias');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `IdRol` int(11) NOT NULL,
  `DescRol` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`IdRol`, `DescRol`) VALUES
(1, 'Administrador'),
(2, 'Paciente'),
(3, 'Jefe de Interna'),
(4, 'Personal Medico'),
(5, 'Medico'),
(6, 'Medico Especial'),
(7, 'Enfermero/a');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_permisos`
--

CREATE TABLE `roles_permisos` (
  `IdRoles_Perm` int(11) NOT NULL,
  `IdRol` int(11) NOT NULL,
  `IdPermiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_permisos`
--

INSERT INTO `roles_permisos` (`IdRoles_Perm`, `IdRol`, `IdPermiso`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 2, 5),
(6, 3, 6),
(7, 3, 7),
(8, 3, 8),
(9, 4, 6),
(10, 4, 9),
(11, 4, 10),
(12, 4, 11),
(13, 4, 12),
(14, 4, 13),
(15, 4, 14),
(16, 4, 5),
(17, 7, 15),
(18, 7, 16),
(19, 7, 17),
(20, 7, 18),
(21, 6, 20),
(22, 5, 15),
(23, 5, 18),
(24, 5, 19),
(25, 7, 21),
(26, 7, 22),
(27, 7, 23),
(28, 7, 24),
(29, 6, 26),
(30, 5, 21),
(31, 5, 24),
(32, 5, 25),
(33, 5, 27),
(34, 5, 28),
(35, 6, 27),
(36, 6, 28),
(37, 7, 27),
(38, 7, 28),
(39, 7, 29),
(40, 7, 40),
(41, 7, 30),
(42, 7, 38),
(43, 7, 31),
(44, 7, 39),
(45, 7, 34),
(46, 7, 35),
(47, 6, 29),
(48, 6, 30),
(49, 6, 31),
(50, 6, 32),
(51, 6, 33),
(52, 6, 34),
(53, 6, 35),
(54, 6, 36),
(55, 6, 37),
(56, 6, 38),
(57, 6, 39),
(58, 6, 40),
(59, 5, 29),
(60, 5, 30),
(61, 5, 31),
(62, 5, 32),
(63, 5, 33),
(64, 5, 34),
(65, 5, 35),
(66, 5, 36),
(67, 5, 37),
(68, 5, 38),
(69, 5, 39),
(70, 5, 40),
(71, 1, 43),
(72, 2, 43),
(73, 3, 43),
(74, 4, 43),
(75, 5, 43),
(76, 6, 43),
(77, 7, 43),
(78, 1, 44),
(79, 2, 44),
(80, 3, 44),
(81, 4, 44),
(82, 5, 44),
(83, 6, 44),
(84, 7, 44);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_usuarios`
--

CREATE TABLE `roles_usuarios` (
  `IdRoles_Usuarios` int(11) NOT NULL,
  `IdRol` int(11) NOT NULL,
  `IdUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_usuarios`
--

INSERT INTO `roles_usuarios` (`IdRoles_Usuarios`, `IdRol`, `IdUsuario`) VALUES
(1, 1, 1),
(2, 3, 2),
(3, 4, 3),
(4, 4, 4),
(5, 4, 5),
(6, 4, 6),
(7, 4, 7),
(8, 4, 8),
(9, 4, 9),
(10, 4, 10),
(11, 2, 11),
(12, 2, 12),
(13, 2, 13),
(14, 2, 14),
(15, 2, 15),
(16, 2, 16),
(17, 2, 17),
(18, 2, 18),
(19, 2, 19),
(20, 2, 20),
(37, 5, 3),
(38, 5, 4),
(39, 5, 5),
(40, 5, 6),
(41, 6, 7),
(42, 6, 8),
(43, 7, 9),
(44, 7, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudesinternacion`
--

CREATE TABLE `solicitudesinternacion` (
  `IdSolicitud` int(11) NOT NULL,
  `IdPaciente` int(11) NOT NULL,
  `TipoSolicitud` enum('Urgencia','Programada','Reprogramada') NOT NULL,
  `EstadoSolicitud` enum('Abierta','Cerrada','En espera') DEFAULT 'Abierta',
  `FechaCreacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `MotivoSolicitud` varchar(100) DEFAULT NULL,
  `observacion_cierre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudesinternacion`
--

INSERT INTO `solicitudesinternacion` (`IdSolicitud`, `IdPaciente`, `TipoSolicitud`, `EstadoSolicitud`, `FechaCreacion`, `MotivoSolicitud`, `observacion_cierre`) VALUES
(1, 1, 'Urgencia', 'Abierta', '2025-10-30 23:29:59', 'Dolor abdominal intenso', NULL),
(2, 2, 'Programada', 'Abierta', '2025-10-30 23:29:59', 'Cirugía ortopédica', NULL),
(3, 3, 'Urgencia', 'Abierta', '2025-10-30 23:29:59', 'Descompensación cardíaca', NULL),
(4, 4, 'Programada', 'Abierta', '2025-10-30 23:29:59', 'Tratamiento postoperatorio', NULL),
(5, 5, 'Reprogramada', 'Abierta', '2025-10-30 23:29:59', 'Chequeo general', NULL),
(6, 6, 'Urgencia', 'Abierta', '2025-10-30 23:29:59', 'Traumatismo craneal', NULL),
(7, 7, 'Programada', 'Abierta', '2025-10-30 23:29:59', 'Cirugía de rodilla', NULL),
(8, 8, 'Urgencia', 'Abierta', '2025-10-30 23:29:59', 'Neumonía grave', NULL),
(9, 9, 'Programada', 'Abierta', '2025-10-30 23:29:59', 'Evaluación neurológica', NULL),
(10, 10, 'Reprogramada', 'Abierta', '2025-10-30 23:29:59', 'Terapia de rehabilitación', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiporevisiones`
--

CREATE TABLE `tiporevisiones` (
  `idTipoRevision` int(11) NOT NULL,
  `DescTipoRevision` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiporevisiones`
--

INSERT INTO `tiporevisiones` (`idTipoRevision`, `DescTipoRevision`) VALUES
(1, 'Signos Vitales'),
(2, 'Alimentacion'),
(3, 'Higienizacion'),
(4, 'Medicacion'),
(5, 'Intervencion'),
(6, 'Intervencion Quirurgica'),
(7, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `IdUsuario` int(11) NOT NULL,
  `Usuario` varchar(15) NOT NULL,
  `Clave` varchar(255) NOT NULL,
  `Habilitado` tinyint(1) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Telefono` bigint(20) NOT NULL,
  `token_recuperacion` varchar(100) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IdUsuario`, `Usuario`, `Clave`, `Habilitado`, `Nombre`, `Apellido`, `Email`, `Telefono`, `token_recuperacion`, `token_expiracion`) VALUES
(1, 'agarcia', '3cd6e84a34793dabc32d3748bc890aa88cd1e018d3d86170c0565248f9ab0d49', 1, 'Ana', 'García', 'ana.garcia@mail.com.ar', 5491145678901, NULL, NULL),
(2, 'cmartinez', '04a61557a469ef923d66fa3b7617212283cd35e59b4bc33ed00975e4d1832e9a', 1, 'Carlos', 'Martínez', 'c.martinez@empresa.com', 5493515550001, NULL, NULL),
(3, 'vsanchez', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 1, 'Valeria', 'Sánchez', 'gomezdelfina25@gmail.com', 5493414445566, '4ec38108779f378e4ec949f9181c496e', '2025-11-24 23:28:20'),
(4, 'jdiaz', '629cb398b51eb025476cb4e3176659d8e2e52a8b8cd25b13ee5d55669313d422', 1, 'Jorge', 'Díaz', 'jorge.diaz@mail.com', 5492616667788, NULL, NULL),
(5, 'csosa', '55885176009fc065a7add27d23a3245d14488922edf068df36afa2e490d83331', 0, 'Camila', 'Sosa', 'cami.sosa@test.ar', 5491122334455, NULL, NULL),
(6, 'fruiz', 'ca792355c5821ad530003360ac31a982759a4127e67c037c5a353074af36edd1', 1, 'Federico', 'Ruiz', 'fede.ruiz@servidor.com', 5492217778899, NULL, NULL),
(7, 'ntorres', 'b2fc677635e97f5124cd7a209d83e13609b3403daf1f5730921fc3929f8ffdce', 1, 'Natalia', 'Torres', 'nati.torres@mail.com', 5493819990011, NULL, NULL),
(8, 'psilva', '6fc6fe9b6404ed2fb80f762836349e53d6a2f680f5e23a322bd631dda5820fd0', 1, 'Pablo', 'Silva', 'pablo.silva@tech.com.ar', 5491133332211, NULL, NULL),
(9, 'mcastro', '2d0bb132cdf675ab1ec10806d332a85e9aef84c4f73a2214d5318bc6c5905210', 1, 'Micaela', 'Castro', 'mica.castro@mail.com', 5492995556677, NULL, NULL),
(10, 'grios', 'fdfc8b3bf947e5ad7e1181921d0c9169337d3227df0ed62a7f21c2d2a6a065db', 1, 'Gonzalo', 'Ríos', 'gonza.rios@red.ar', 5493424441122, NULL, NULL),
(11, 'vflores', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 1, 'Valentina', 'Flores', 'leandro8421@outlook.es', 5491167891234, '498f2a489f17656356c4f2defd655dc0', '2025-11-24 13:30:30'),
(12, 'mbenitez', '3fbfcbcf63d64948270d1999029fb5c202c7360d9ce3a1aac752df71ad8ebed5', 0, 'Martín', 'Benítez', 'martin.b@empresa.com', 5493512223344, NULL, NULL),
(13, 'jramirez', 'e0bacc282ea23f576aec4f05ca9c591e099b3a931cfb8b037c96c043ee367128', 1, 'Julieta', 'Ramírez', 'juli.ramirez@web.com', 5492235559988, NULL, NULL),
(14, 'nherrera', 'd89f2df4b212c5b06029ba429003e592e6fb4230afe56e5033ebce4be83bc357', 1, 'Nicolás', 'Herrera', 'nico.herrera@mail.com.ar', 5491155556666, NULL, NULL),
(15, 'amedina', 'ebd21dd8574c240b7aee66171e9a657aaafee06437ab3c4f5bfce98496fdf481', 1, 'Agustina', 'Medina', 'agus.medina@soft.com', 5493874445566, NULL, NULL),
(16, 'facosta', '2836b05d1aab1af7f219b2610e28d278634257742b8dc136b062a15752203f2c', 1, 'Facundo', 'Acosta', 'facu.acosta@mail.com', 5493794112233, NULL, NULL),
(17, 'rluna', 'e74a3753440a4b820b2b5c8763fc3ae604c457f99fb886dccf505afcef796fef', 1, 'Rocío', 'Luna', 'rocio.luna@service.ar', 5491198765432, NULL, NULL),
(18, 'mcabrera', '76208212d641cc6c9243b38ed0440ef7672523e5f8894247167294fe5ae3d3a3', 1, 'Matías', 'Cabrera', 'mati.cabrera@mail.com', 5493436667788, NULL, NULL),
(19, 'dmorales', '559a4df8e6e72dded5bff515f985304308161f1987f3ed13fe0f1c4dd0ac8f11', 1, 'Daniela', 'Morales', 'dani.morales@club.com', 5492645554433, NULL, NULL),
(20, 'eperalta', '9db689ad20004aeebfe8c531cb69459ab235376c52caae0f9a196fbc8d4ac31a', 1, 'Ezequiel', 'Peralta', 'eze.peralta@mail.com.ar', 5491132165498, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `camas`
--
ALTER TABLE `camas`
  ADD PRIMARY KEY (`IdCama`),
  ADD KEY `IdHabitacion` (`IdHabitacion`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`IdDireccion`);

--
-- Indices de la tabla `estadorevisiones`
--
ALTER TABLE `estadorevisiones`
  ADD PRIMARY KEY (`IdEstadoRev`);

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`IdHabitacion`);

--
-- Indices de la tabla `internaciones`
--
ALTER TABLE `internaciones`
  ADD PRIMARY KEY (`IdInternacion`),
  ADD KEY `IdSolicitud` (`IdSolicitud`),
  ADD KEY `IdCama` (`IdCama`),
  ADD KEY `IdHabitacion` (`IdHabitacion`),
  ADD KEY `IdPaciente` (`IdPaciente`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `obrassociales`
--
ALTER TABLE `obrassociales`
  ADD PRIMARY KEY (`IdOS`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`IdPaciente`),
  ADD KEY `IdUsuario` (`IdUsuario`),
  ADD KEY `IdOS` (`IdPlan_OS`),
  ADD KEY `IdDireccion` (`IdDireccion`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`IdPermiso`);

--
-- Indices de la tabla `personascontacto`
--
ALTER TABLE `personascontacto`
  ADD PRIMARY KEY (`IdPC`),
  ADD KEY `IdDireccion` (`IdDireccion`);

--
-- Indices de la tabla `personascontacto_pacientes`
--
ALTER TABLE `personascontacto_pacientes`
  ADD PRIMARY KEY (`IdPC_Paciente`),
  ADD KEY `IdPaciente` (`IdPaciente`),
  ADD KEY `IdPC` (`IdPC`);

--
-- Indices de la tabla `planes_obrassociales`
--
ALTER TABLE `planes_obrassociales`
  ADD PRIMARY KEY (`IdPlan`),
  ADD KEY `IdOS` (`IdOS`);

--
-- Indices de la tabla `recordatorio`
--
ALTER TABLE `recordatorio`
  ADD PRIMARY KEY (`IdRecordatorio`),
  ADD KEY `IdInternacion` (`IdInternacion`),
  ADD KEY `IdUsuario` (`IdUsuario`);

--
-- Indices de la tabla `revisiones`
--
ALTER TABLE `revisiones`
  ADD PRIMARY KEY (`IdRevisiones`),
  ADD KEY `IdInternacion` (`IdInternacion`),
  ADD KEY `IdUsuario` (`IdUsuario`),
  ADD KEY `revisiones_ibfk_3` (`TipoRevision`),
  ADD KEY `revisiones_ibfk_4` (`EstadoRevision`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`IdRol`);

--
-- Indices de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD PRIMARY KEY (`IdRoles_Perm`),
  ADD KEY `roles_permisos_permiso` (`IdPermiso`),
  ADD KEY `roles_permisos_rol` (`IdRol`);

--
-- Indices de la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  ADD PRIMARY KEY (`IdRoles_Usuarios`),
  ADD KEY `roles_usuarios_rol` (`IdRol`),
  ADD KEY `roles_usuarios_usuario` (`IdUsuario`);

--
-- Indices de la tabla `solicitudesinternacion`
--
ALTER TABLE `solicitudesinternacion`
  ADD PRIMARY KEY (`IdSolicitud`),
  ADD KEY `IdPaciente` (`IdPaciente`);

--
-- Indices de la tabla `tiporevisiones`
--
ALTER TABLE `tiporevisiones`
  ADD PRIMARY KEY (`idTipoRevision`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IdUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `camas`
--
ALTER TABLE `camas`
  MODIFY `IdCama` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `IdDireccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `estadorevisiones`
--
ALTER TABLE `estadorevisiones`
  MODIFY `IdEstadoRev` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `IdHabitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT de la tabla `internaciones`
--
ALTER TABLE `internaciones`
  MODIFY `IdInternacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `obrassociales`
--
ALTER TABLE `obrassociales`
  MODIFY `IdOS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `IdPaciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `IdPermiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `personascontacto`
--
ALTER TABLE `personascontacto`
  MODIFY `IdPC` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personascontacto_pacientes`
--
ALTER TABLE `personascontacto_pacientes`
  MODIFY `IdPC_Paciente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `planes_obrassociales`
--
ALTER TABLE `planes_obrassociales`
  MODIFY `IdPlan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `recordatorio`
--
ALTER TABLE `recordatorio`
  MODIFY `IdRecordatorio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `revisiones`
--
ALTER TABLE `revisiones`
  MODIFY `IdRevisiones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `IdRol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  MODIFY `IdRoles_Perm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT de la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  MODIFY `IdRoles_Usuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `solicitudesinternacion`
--
ALTER TABLE `solicitudesinternacion`
  MODIFY `IdSolicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `tiporevisiones`
--
ALTER TABLE `tiporevisiones`
  MODIFY `idTipoRevision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IdUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `camas`
--
ALTER TABLE `camas`
  ADD CONSTRAINT `camas_ibfk_1` FOREIGN KEY (`IdHabitacion`) REFERENCES `habitaciones` (`IdHabitacion`);

--
-- Filtros para la tabla `internaciones`
--
ALTER TABLE `internaciones`
  ADD CONSTRAINT `internaciones_ibfk_1` FOREIGN KEY (`IdSolicitud`) REFERENCES `solicitudesinternacion` (`IdSolicitud`),
  ADD CONSTRAINT `internaciones_ibfk_2` FOREIGN KEY (`IdCama`) REFERENCES `camas` (`IdCama`),
  ADD CONSTRAINT `internaciones_ibfk_3` FOREIGN KEY (`IdHabitacion`) REFERENCES `habitaciones` (`IdHabitacion`),
  ADD CONSTRAINT `internaciones_ibfk_4` FOREIGN KEY (`IdPaciente`) REFERENCES `pacientes` (`IdPaciente`);

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`),
  ADD CONSTRAINT `pacientes_ibfk_2` FOREIGN KEY (`IdPlan_OS`) REFERENCES `planes_obrassociales` (`IdPlan`),
  ADD CONSTRAINT `pacientes_ibfk_3` FOREIGN KEY (`IdDireccion`) REFERENCES `direcciones` (`IdDireccion`);

--
-- Filtros para la tabla `personascontacto`
--
ALTER TABLE `personascontacto`
  ADD CONSTRAINT `personascontacto_ibfk_1` FOREIGN KEY (`IdDireccion`) REFERENCES `direcciones` (`IdDireccion`);

--
-- Filtros para la tabla `personascontacto_pacientes`
--
ALTER TABLE `personascontacto_pacientes`
  ADD CONSTRAINT `personascontacto_pacientes_ibfk_1` FOREIGN KEY (`IdPaciente`) REFERENCES `pacientes` (`IdPaciente`),
  ADD CONSTRAINT `personascontacto_pacientes_ibfk_2` FOREIGN KEY (`IdPC`) REFERENCES `personascontacto` (`IdPC`);

--
-- Filtros para la tabla `planes_obrassociales`
--
ALTER TABLE `planes_obrassociales`
  ADD CONSTRAINT `planes_obrassociales_ibfk_1` FOREIGN KEY (`IdOS`) REFERENCES `obrassociales` (`IdOS`);

--
-- Filtros para la tabla `recordatorio`
--
ALTER TABLE `recordatorio`
  ADD CONSTRAINT `recordatorio_ibfk_1` FOREIGN KEY (`IdInternacion`) REFERENCES `internaciones` (`IdInternacion`),
  ADD CONSTRAINT `recordatorio_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`);

--
-- Filtros para la tabla `revisiones`
--
ALTER TABLE `revisiones`
  ADD CONSTRAINT `revisiones_ibfk_1` FOREIGN KEY (`IdInternacion`) REFERENCES `internaciones` (`IdInternacion`),
  ADD CONSTRAINT `revisiones_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`),
  ADD CONSTRAINT `revisiones_ibfk_3` FOREIGN KEY (`TipoRevision`) REFERENCES `tiporevisiones` (`idTipoRevision`),
  ADD CONSTRAINT `revisiones_ibfk_4` FOREIGN KEY (`EstadoRevision`) REFERENCES `estadorevisiones` (`IdEstadoRev`);

--
-- Filtros para la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD CONSTRAINT `roles_permisos_permiso` FOREIGN KEY (`IdPermiso`) REFERENCES `permisos` (`IdPermiso`),
  ADD CONSTRAINT `roles_permisos_rol` FOREIGN KEY (`IdRol`) REFERENCES `roles` (`IdRol`);

--
-- Filtros para la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  ADD CONSTRAINT `roles_usuarios_rol` FOREIGN KEY (`IdRol`) REFERENCES `roles` (`IdRol`),
  ADD CONSTRAINT `roles_usuarios_usuario` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`);

--
-- Filtros para la tabla `solicitudesinternacion`
--
ALTER TABLE `solicitudesinternacion`
  ADD CONSTRAINT `solicitudesinternacion_ibfk_1` FOREIGN KEY (`IdPaciente`) REFERENCES `pacientes` (`IdPaciente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
