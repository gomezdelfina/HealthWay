-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2025 a las 04:19:03
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
-- Base de datos: `healthwaybd`
--

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
(5, 'Escanear QR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `IdRol` int(11) NOT NULL,
  `DescRol` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`IdRol`, `DescRol`) VALUES
(1, 'Administrador'),
(2, 'Paciente'),
(3, 'Jefe de Internaciones'),
(4, 'Personal Medico'),
(5, 'Medico'),
(6, 'Medico Especialista'),
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
(5, 2, 5);

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
(29, 5, 3),
(30, 5, 4),
(31, 5, 5),
(32, 6, 6),
(33, 7, 7),
(34, 7, 8),
(35, 7, 9),
(36, 6, 10);

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
(3, 'vsanchez', '21aaf754ee10f0bfcef7edfd5fef05a38fb00f58649f3012b8f807764ec9b140', 1, 'Valeria', 'Sánchez', 'vale.sanchez@web.net', 5493414445566, NULL, NULL),
(4, 'jdiaz', '629cb398b51eb025476cb4e3176659d8e2e52a8b8cd25b13ee5d55669313d422', 1, 'Jorge', 'Díaz', 'jorge.diaz@mail.com', 5492616667788, NULL, NULL),
(5, 'csosa', '55885176009fc065a7add27d23a3245d14488922edf068df36afa2e490d83331', 0, 'Camila', 'Sosa', 'cami.sosa@test.ar', 5491122334455, NULL, NULL),
(6, 'fruiz', 'ca792355c5821ad530003360ac31a982759a4127e67c037c5a353074af36edd1', 1, 'Federico', 'Ruiz', 'fede.ruiz@servidor.com', 5492217778899, NULL, NULL),
(7, 'ntorres', 'b2fc677635e97f5124cd7a209d83e13609b3403daf1f5730921fc3929f8ffdce', 1, 'Natalia', 'Torres', 'nati.torres@mail.com', 5493819990011, NULL, NULL),
(8, 'psilva', '6fc6fe9b6404ed2fb80f762836349e53d6a2f680f5e23a322bd631dda5820fd0', 1, 'Pablo', 'Silva', 'pablo.silva@tech.com.ar', 5491133332211, NULL, NULL),
(9, 'mcastro', '2d0bb132cdf675ab1ec10806d332a85e9aef84c4f73a2214d5318bc6c5905210', 1, 'Micaela', 'Castro', 'mica.castro@mail.com', 5492995556677, NULL, NULL),
(10, 'grios', 'fdfc8b3bf947e5ad7e1181921d0c9169337d3227df0ed62a7f21c2d2a6a065db', 1, 'Gonzalo', 'Ríos', 'gonza.rios@red.ar', 5493424441122, NULL, NULL),
(11, 'vflores', '8f68e8779e83450f02a9c769555468014b4c5bd8838c98734c5b5de74070888b', 1, 'Valentina', 'Flores', 'valen.flores@mail.com', 5491167891234, NULL, NULL),
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
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`IdPermiso`);

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
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IdUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `IdPermiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `IdRol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  MODIFY `IdRoles_Perm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  MODIFY `IdRoles_Usuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IdUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
