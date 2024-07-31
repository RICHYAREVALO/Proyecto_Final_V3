-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 31-07-2024 a las 03:45:31
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
-- Base de datos: `pazysalvo_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`ID`, `Nombre`) VALUES
(1, 'Recursos Humanos'),
(2, 'Contabilidad'),
(3, 'IT'),
(4, 'Ventas'),
(5, 'Gerencia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_paz_salvo`
--

CREATE TABLE `registro_paz_salvo` (
  `ID` int(11) NOT NULL,
  `Usuario_Empleado_ID` int(11) DEFAULT NULL,
  `Fecha_Emision` date DEFAULT NULL,
  `Detalles` text DEFAULT NULL,
  `Estado` varchar(50) DEFAULT NULL,
  `Razon_Rechazo` text DEFAULT NULL,
  `Fecha_Actualizacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_paz_salvo`
--

INSERT INTO `registro_paz_salvo` (`ID`, `Usuario_Empleado_ID`, `Fecha_Emision`, `Detalles`, `Estado`, `Razon_Rechazo`, `Fecha_Actualizacion`) VALUES
(1, 17, '2024-07-30', NULL, 'Aprobado', NULL, '2024-07-31 00:04:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumento`
--

CREATE TABLE `tipodocumento` (
  `ID` int(11) NOT NULL,
  `TipoDocumento` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipodocumento`
--

INSERT INTO `tipodocumento` (`ID`, `TipoDocumento`) VALUES
(1, 'Cédula de Ciudadanía'),
(2, 'Cédula de Extranjería'),
(3, 'NIT');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_empleados`
--

CREATE TABLE `usuarios_empleados` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Apellido` varchar(255) NOT NULL,
  `DocumentoIdentidad` varchar(255) NOT NULL,
  `Departamento_ID` int(11) DEFAULT NULL,
  `NombreUsuario` varchar(255) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Rol` enum('empleado','administrador','recursos_humanos') DEFAULT 'empleado',
  `TipoDocumento_ID` int(11) NOT NULL,
  `CorreoElectronico` varchar(255) NOT NULL,
  `FechaContratacion` date DEFAULT NULL,
  `FechaRetiro` date DEFAULT NULL,
  `FotoPerfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_empleados`
--

INSERT INTO `usuarios_empleados` (`ID`, `Nombre`, `Apellido`, `DocumentoIdentidad`, `Departamento_ID`, `NombreUsuario`, `Contrasena`, `Rol`, `TipoDocumento_ID`, `CorreoElectronico`, `FechaContratacion`, `FechaRetiro`, `FotoPerfil`) VALUES
(15, 'Brayan', 'moreno', '1023665984', 5, 'bryam', '$2y$10$vt1/sXONhED6l/Yvbbai9utrkUry1Khjy57xETHgnQPfn36uE5tw6', 'administrador', 2, 'b@gmail.com', '2024-07-05', NULL, NULL),
(16, 'maycol', 'tejada', '103256487', 1, 'dante', '$2y$10$nQ/gs2yHPRHRibU7xDm0ZuktA3a4z2xY6FaoyG4CdUxUWCb/Xrvtq', 'recursos_humanos', 3, 'm@gmail.com', '2016-06-22', NULL, NULL),
(17, 'ricardo', 'arevalo', '954112345', 4, 'richi', '$2y$10$ECdeQYY6lUe46mkmRAFuaO5wdI2.FfedREK7H/Hhy4hF3Ik7u4E0e', 'empleado', 2, 'r@gmail.com', '2024-07-09', NULL, NULL),
(18, 'prueba', 'hernandez', '22222', 4, 'pr1', '$2y$10$wCLHs9hz9GAAKnyhtdB72eDUlGt/sDZgh15lrLEfpjflXK7ZJ7OQq', 'empleado', 2, 'p@gmail.com', '2024-07-02', NULL, NULL),
(19, 'prueba', 'ddd', '6663362', 4, 'pr2', '$2y$10$t7uYPt7D35NSpBIOlJWCLuQRnfKLxdU/7c/jsxf3WRAH0.7jmg3ei', 'empleado', 3, 'p@gmail.com', '2024-07-04', NULL, 'uploads/perfil1.jpg'),
(20, 'Diego', 'hernandez', '965441', 3, 'pr3', '$2y$10$/Xl4VaF0iuD0PQP0UCFwFuP7Jc0jSDdkeZtffnFJqlQlW3qMa4HK2', 'empleado', 2, 'p@gmail.com', '2024-07-19', NULL, 'uploads/perfil1.jpg'),
(21, 'victor daniel', 'tt', '1654565', 4, 'pr4', '$2y$10$gsPW5IEMSvJLGV4mvWthUenxUYBo2glSeE/zc6usY8LvnVGZWbppO', 'empleado', 3, 'p@gmail.com', '2024-07-19', NULL, 'uploads/perdil2.jpg'),
(22, 'Diego', 'hernandez', '655616', 4, 'pr5', '$2y$10$FNRRyGmRQlRahUoSQAU7uuahcNKINWF/C1av/qq8cEegBHH.fCmN.', 'empleado', 2, 'p@gmail.com', '2024-05-06', NULL, 'uploads/perfil3.jpg'),
(23, 'brayan', 'pp', '445112', 3, 'prueba1', '$2y$10$it74gypxKfM9/I5QePFhPumqdXwknQ20vLzj2aVNTuLYcKb4OdTOq', 'empleado', 2, 'p@gmail.com', '2024-07-17', NULL, 'uploads/perfil1.jpg'),
(24, 'maicol', 'hhhh', '44125211', 4, 'maycol1', '$2y$10$GWbpxbbrLQZ28FUsdIUQZOTqSa/sHOwjIt22kjWch4M0kEAtxGXZ2', 'empleado', 2, 'p@gmail.com', '2024-07-09', NULL, '../empleado/uploads/perfil4.jpg'),
(25, 'edgar', 'hernandez', '51515151', 4, 'edgar1', '$2y$10$K/32tW3kadn8Fv7HP1TVZe9vAJD24kVbVICQlr7ufu0Mu43kGVmZe', 'empleado', 3, 'p@gmail.com', '2024-07-11', NULL, '../empleado/uploads/perfil5.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `registro_paz_salvo`
--
ALTER TABLE `registro_paz_salvo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Usuario_Empleado_ID` (`Usuario_Empleado_ID`);

--
-- Indices de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `usuarios_empleados`
--
ALTER TABLE `usuarios_empleados`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Departamento_ID` (`Departamento_ID`),
  ADD KEY `TipoDocumento_ID` (`TipoDocumento_ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `registro_paz_salvo`
--
ALTER TABLE `registro_paz_salvo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios_empleados`
--
ALTER TABLE `usuarios_empleados`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `registro_paz_salvo`
--
ALTER TABLE `registro_paz_salvo`
  ADD CONSTRAINT `registro_paz_salvo_ibfk_1` FOREIGN KEY (`Usuario_Empleado_ID`) REFERENCES `usuarios_empleados` (`ID`);

--
-- Filtros para la tabla `usuarios_empleados`
--
ALTER TABLE `usuarios_empleados`
  ADD CONSTRAINT `usuarios_empleados_ibfk_1` FOREIGN KEY (`Departamento_ID`) REFERENCES `departamentos` (`ID`),
  ADD CONSTRAINT `usuarios_empleados_ibfk_2` FOREIGN KEY (`TipoDocumento_ID`) REFERENCES `tipodocumento` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
