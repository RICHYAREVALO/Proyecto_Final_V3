-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 30-07-2024 a las 02:54:12
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
(3, 'Pasaporte');

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
  `FechaRetiro` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_empleados`
--

INSERT INTO `usuarios_empleados` (`ID`, `Nombre`, `Apellido`, `DocumentoIdentidad`, `Departamento_ID`, `NombreUsuario`, `Contrasena`, `Rol`, `TipoDocumento_ID`, `CorreoElectronico`, `FechaContratacion`, `FechaRetiro`) VALUES
(15, 'Brayan', 'moreno', '1023665984', 5, 'bryam', '$2y$10$vt1/sXONhED6l/Yvbbai9utrkUry1Khjy57xETHgnQPfn36uE5tw6', 'administrador', 2, 'b@gmail.com', '2024-07-05', NULL),
(16, 'maycol', 'tejada', '103256487', 1, 'dante', '$2y$10$nQ/gs2yHPRHRibU7xDm0ZuktA3a4z2xY6FaoyG4CdUxUWCb/Xrvtq', 'recursos_humanos', 3, 'm@gmail.com', '2016-06-22', NULL),
(17, 'ricardo', 'arevalo', '954112345', 4, 'richi', '$2y$10$ECdeQYY6lUe46mkmRAFuaO5wdI2.FfedREK7H/Hhy4hF3Ik7u4E0e', 'empleado', 2, 'r@gmail.com', '2024-07-09', NULL);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios_empleados`
--
ALTER TABLE `usuarios_empleados`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
