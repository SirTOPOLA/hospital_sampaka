-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-07-2025 a las 15:19:26
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hospital_sampaka`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `analiticas`
--

CREATE TABLE `analiticas` (
  `id` int(10) UNSIGNED NOT NULL,
  `resultado` text DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `id_prueba` int(10) UNSIGNED DEFAULT NULL,
  `id_consulta` int(10) UNSIGNED DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(10) UNSIGNED DEFAULT NULL,
  `id_paciente` int(10) UNSIGNED DEFAULT NULL,
  `codigo_paciente` varchar(50) DEFAULT NULL,
  `pagado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta`
--

CREATE TABLE `consulta` (
  `id` int(10) UNSIGNED NOT NULL,
  `motivo_consulta` text DEFAULT NULL,
  `temperatura` decimal(4,1) DEFAULT NULL,
  `frecuencia_cardiaca` int(11) DEFAULT NULL,
  `frecuencia_respiratoria` int(11) DEFAULT NULL,
  `tension_arterial` varchar(20) DEFAULT NULL,
  `pulso` int(11) DEFAULT NULL,
  `saturacion_oxigeno` decimal(4,1) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `masa_indice_corporal` decimal(5,2) DEFAULT NULL,
  `id_usuario` int(10) UNSIGNED DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_paciente` int(10) UNSIGNED DEFAULT NULL,
  `codigo_paciente` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_consulta`
--

CREATE TABLE `detalle_consulta` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_consulta` int(10) UNSIGNED DEFAULT NULL,
  `id_usuario` int(10) UNSIGNED DEFAULT NULL,
  `operacion` tinyint(1) DEFAULT NULL,
  `orina` tinyint(1) DEFAULT NULL,
  `defeca` tinyint(1) DEFAULT NULL,
  `intervalo_defecacion_dias` int(11) DEFAULT NULL,
  `duerme_bien` tinyint(1) DEFAULT NULL,
  `horas_sueno` int(11) DEFAULT NULL,
  `antecedentes_patologicos` text DEFAULT NULL,
  `alergico` text DEFAULT NULL,
  `antecedentes_patologicos_familiares` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_paciente` int(10) UNSIGNED DEFAULT NULL,
  `codigo_paciente` varchar(50) DEFAULT NULL,
  `id_usuario` int(10) UNSIGNED DEFAULT NULL,
  `id_consulta` int(10) UNSIGNED DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_ingreso` date DEFAULT NULL,
  `id_sala` int(10) UNSIGNED DEFAULT NULL,
  `numero_cama` varchar(20) DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(10) UNSIGNED NOT NULL,
  `codigo_paciente` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `residencia` varchar(150) DEFAULT NULL,
  `profesion` varchar(100) DEFAULT NULL,
  `ocupacion` varchar(100) DEFAULT NULL,
  `nacionalidad` varchar(50) DEFAULT NULL,
  `tutor` varchar(100) DEFAULT NULL,
  `telefono_tutor` varchar(20) DEFAULT NULL,
  `id_usuario` int(10) UNSIGNED DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id` int(10) UNSIGNED NOT NULL,
  `codigo_personal` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `residencia` varchar(150) DEFAULT NULL,
  `nivel_educacional` varchar(100) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id`, `codigo_personal`, `nombre`, `apellidos`, `fecha_nacimiento`, `residencia`, `nivel_educacional`, `cargo`, `telefono`, `correo`, `fecha_registro`) VALUES
(1, 'PHS-01', 'salvador', 'Mete', '1997-07-08', 'bar estadio', 'Master', 'Informatico', '222541447', 'salvador@gmail.com', '2025-07-15 13:09:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pruebas_hospital`
--

CREATE TABLE `pruebas_hospital` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre_prueba` varchar(100) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta`
--

CREATE TABLE `receta` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_usuario` int(10) UNSIGNED DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `id_consulta` int(10) UNSIGNED DEFAULT NULL,
  `id_paciente` int(10) UNSIGNED DEFAULT NULL,
  `codigo_paciente` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas_ingreso`
--

CREATE TABLE `salas_ingreso` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre_sala` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_hospital`
--

CREATE TABLE `usuarios_hospital` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre_usuario` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` varchar(50) DEFAULT NULL,
  `id_personal` int(10) UNSIGNED DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_hospital`
--

INSERT INTO `usuarios_hospital` (`id`, `nombre_usuario`, `password`, `rol`, `id_personal`, `estado`, `fecha_registro`) VALUES
(1, 'admin@gmail.com', '123456', 'Administrador', 1, 1, '2025-07-15 13:12:49');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `analiticas`
--
ALTER TABLE `analiticas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_prueba` (`id_prueba`),
  ADD KEY `id_consulta` (`id_consulta`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `detalle_consulta`
--
ALTER TABLE `detalle_consulta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_consulta` (`id_consulta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_consulta` (`id_consulta`),
  ADD KEY `id_sala` (`id_sala`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_paciente` (`codigo_paciente`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_personal` (`codigo_personal`);

--
-- Indices de la tabla `pruebas_hospital`
--
ALTER TABLE `pruebas_hospital`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `receta`
--
ALTER TABLE `receta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_consulta` (`id_consulta`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `salas_ingreso`
--
ALTER TABLE `salas_ingreso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios_hospital`
--
ALTER TABLE `usuarios_hospital`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD KEY `id_personal` (`id_personal`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `analiticas`
--
ALTER TABLE `analiticas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consulta`
--
ALTER TABLE `consulta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_consulta`
--
ALTER TABLE `detalle_consulta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pruebas_hospital`
--
ALTER TABLE `pruebas_hospital`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `receta`
--
ALTER TABLE `receta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `salas_ingreso`
--
ALTER TABLE `salas_ingreso`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios_hospital`
--
ALTER TABLE `usuarios_hospital`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `analiticas`
--
ALTER TABLE `analiticas`
  ADD CONSTRAINT `analiticas_ibfk_1` FOREIGN KEY (`id_prueba`) REFERENCES `pruebas_hospital` (`id`),
  ADD CONSTRAINT `analiticas_ibfk_2` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id`),
  ADD CONSTRAINT `analiticas_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_hospital` (`id`),
  ADD CONSTRAINT `analiticas_ibfk_4` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id`);

--
-- Filtros para la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD CONSTRAINT `consulta_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_hospital` (`id`),
  ADD CONSTRAINT `consulta_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id`);

--
-- Filtros para la tabla `detalle_consulta`
--
ALTER TABLE `detalle_consulta`
  ADD CONSTRAINT `detalle_consulta_ibfk_1` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id`),
  ADD CONSTRAINT `detalle_consulta_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_hospital` (`id`);

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `ingresos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_hospital` (`id`),
  ADD CONSTRAINT `ingresos_ibfk_3` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id`),
  ADD CONSTRAINT `ingresos_ibfk_4` FOREIGN KEY (`id_sala`) REFERENCES `salas_ingreso` (`id`);

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_hospital` (`id`);

--
-- Filtros para la tabla `receta`
--
ALTER TABLE `receta`
  ADD CONSTRAINT `receta_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_hospital` (`id`),
  ADD CONSTRAINT `receta_ibfk_2` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id`),
  ADD CONSTRAINT `receta_ibfk_3` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id`);

--
-- Filtros para la tabla `salas_ingreso`
--
ALTER TABLE `salas_ingreso`
  ADD CONSTRAINT `salas_ingreso_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_hospital` (`id`);

--
-- Filtros para la tabla `usuarios_hospital`
--
ALTER TABLE `usuarios_hospital`
  ADD CONSTRAINT `usuarios_hospital_ibfk_1` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
