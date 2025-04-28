-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 28, 2025 at 03:53 PM
-- Server version: 10.6.20-MariaDB-cll-lve
-- PHP Version: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xlikqebq_apolink`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumno_apoderado`
--

CREATE TABLE `alumno_apoderado` (
  `alumno_id` int(11) NOT NULL,
  `apoderado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `alumno_apoderado`
--

INSERT INTO `alumno_apoderado` (`alumno_id`, `apoderado_id`) VALUES
(1, 3),
(4, 5),
(5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `aportes`
--

CREATE TABLE `aportes` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  `numero_comprobante` varchar(50) NOT NULL,
  `archivo_comprobante` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `comentario_tesorero` text DEFAULT NULL,
  `creado_por` enum('apoderado','tesorero') NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `aportes`
--

INSERT INTO `aportes` (`id`, `alumno_id`, `cantidad`, `motivo`, `fecha`, `numero_comprobante`, `archivo_comprobante`, `estado`, `comentario_tesorero`, `creado_por`, `fecha_creacion`) VALUES
(1, 1, 5000.00, 'Rifa', '2025-04-18', '12345', NULL, 'aprobado', NULL, 'apoderado', '2025-04-17 23:51:06'),
(2, 1, 10000.00, 'Aporte Centro de Alumnos', '2025-03-10', '12345', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 00:21:16'),
(3, 1, 1000.00, 'Aporte de luca mensual', '2025-02-02', '12346', NULL, 'pendiente', NULL, 'apoderado', '2025-04-18 00:21:16'),
(4, 1, 20000.00, 'Donación', '2025-01-20', '12347', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 00:21:16'),
(5, 1, 1500.00, 'Compra de materiales escolares como donación', '2025-01-09', '12346', NULL, 'pendiente', NULL, 'apoderado', '2025-04-18 14:37:09'),
(6, 1, 2000.00, 'Donación para el mantenimiento de aulas', '2025-02-10', '', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 14:37:09'),
(7, 1, 1200.00, 'Colaboración para actividades deportivas', '2025-03-20', '', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 14:37:09'),
(8, 1, 1800.00, 'Apoyo para la feria cultural', '2025-04-05', '', NULL, 'pendiente', NULL, 'apoderado', '2025-04-18 14:37:09'),
(9, 1, 2500.00, 'Donación para la biblioteca', '2025-05-18', '', NULL, 'pendiente', NULL, 'apoderado', '2025-04-18 14:37:09'),
(10, 1, 3000.00, 'Colaboración para el día del estudiante', '2025-06-12', '', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 14:37:09'),
(11, 1, 2200.00, 'Apoyo para actividades de invierno', '2025-07-25', '', NULL, 'pendiente', NULL, 'apoderado', '2025-04-18 14:37:09'),
(12, 1, 1900.00, 'Donación para la compra de uniformes deportivos', '2025-08-07', '', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 14:37:09'),
(13, 1, 2100.00, 'Colaboración para celebraciones patrias', '2025-09-15', '', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 14:37:09'),
(14, 1, 1600.00, 'Apoyo para concurso de ciencias', '2025-10-03', '', NULL, 'pendiente', NULL, 'apoderado', '2025-04-18 14:37:09'),
(15, 1, 2300.00, 'Donación para premios de fin de año', '2025-11-20', '', NULL, 'pendiente', NULL, 'apoderado', '2025-04-18 14:37:09'),
(16, 1, 3000.00, 'Colaboración para la fiesta navideña', '2025-12-10', '', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 14:37:09'),
(17, 1, 1700.00, 'Apoyo al proyecto de reciclaje escolar', '2025-01-25', '', NULL, 'pendiente', NULL, 'apoderado', '2025-04-18 14:37:09'),
(18, 1, 1400.00, 'Donación para la compra de instrumentos musicales', '2025-03-28', '', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 14:37:09'),
(19, 1, 2600.00, 'Colaboración para excursión educativa', '2025-06-30', '', NULL, 'pendiente', NULL, 'apoderado', '2025-04-18 14:37:09'),
(20, 1, 8000.00, 'Compras extras kermesse colegio', '2025-04-19', '1234567', '1745084481_E-RUT Oscar Arlegui EIRL.pdf', 'pendiente', NULL, 'apoderado', '2025-04-19 17:41:21'),
(21, 1, 2500.00, 'Compras extras kermesse colegio', '2025-04-20', '1234567', '1234567_1_20250419.pdf', 'pendiente', NULL, 'apoderado', '2025-04-19 17:54:25'),
(22, 1, 2800.00, 'Compra de materiales escolares como donación', '2025-04-15', '245467', '245467_1_20250419.pdf', 'pendiente', NULL, 'apoderado', '2025-04-19 17:58:17'),
(23, 4, 5000.00, 'Rifa', '2025-04-18', '12345', NULL, 'aprobado', NULL, 'apoderado', '2025-04-17 23:51:06'),
(24, 4, 10000.00, 'Aporte Centro de Alumnos', '2025-03-10', '12345', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 00:21:16'),
(25, 4, 100000.00, 'Aporte de luca mensuales', '2025-02-02', '12346', NULL, 'pendiente', NULL, 'apoderado', '2025-04-18 00:21:16'),
(26, 4, 20000.00, 'Donación', '2025-01-20', '12347', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 00:21:16'),
(27, 4, 1500.00, 'Compra de materiales escolares como donación', '2025-01-09', '12346', NULL, 'pendiente', NULL, 'apoderado', '2025-04-18 14:37:09'),
(28, 4, 2000.00, 'Donación para el mantenimiento de aulas', '2025-02-10', '', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 14:37:09'),
(29, 4, 1200.00, 'Colaboración para actividades deportivas', '2025-03-20', '', NULL, 'aprobado', NULL, 'apoderado', '2025-04-18 14:37:09'),
(30, 4, 1233.00, 'Día Pueblos Originarios', '2025-04-21', '12346', '12346_4_20250421.pdf', 'rechazado', NULL, 'apoderado', '2025-04-21 20:54:37'),
(31, 4, 2500.00, 'prueba 3 b', '2025-04-23', '12346', '12346_4_20250422.pdf', 'pendiente', NULL, 'apoderado', '2025-04-22 20:06:28'),
(34, 5, 2500.00, 'prueba medio mayor', '2025-04-22', '12346', '12346_5_20250422.pdf', 'pendiente', NULL, 'apoderado', '2025-04-22 20:36:08');

-- --------------------------------------------------------

--
-- Table structure for table `aportes_gira`
--

CREATE TABLE `aportes_gira` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `fecha` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `aportes_gira`
--

INSERT INTO `aportes_gira` (`id`, `alumno_id`, `monto`, `fecha`) VALUES
(1, 1, 700000, '2025-04-17');

-- --------------------------------------------------------

--
-- Table structure for table `asignaturas`
--

CREATE TABLE `asignaturas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `color` varchar(7) DEFAULT '#007bff',
  `curso_id` int(11) NOT NULL,
  `tipo` enum('comun','extra_programatica') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `asignaturas`
--

INSERT INTO `asignaturas` (`id`, `nombre`, `descripcion`, `color`, `curso_id`, `tipo`) VALUES
(1, 'Matemáticas', 'Clase de matemáticas básica', '#FF5733', 1, 'comun'),
(2, 'Lenguaje', 'Clase de lenguaje y comunicación', '#33FF57', 1, 'comun'),
(3, 'Historia', 'Clase de historia mundial', '#3375FF', 1, 'comun'),
(4, 'Música', 'Clase de música y canto', '#FFC300', 1, 'extra_programatica'),
(5, 'Deportes', 'Clase de actividad física y deportes', '#FF33A8', 1, 'extra_programatica');

-- --------------------------------------------------------

--
-- Table structure for table `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `accion` varchar(20) DEFAULT NULL,
  `modulo` varchar(50) DEFAULT NULL,
  `detalle` text DEFAULT NULL,
  `fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `auditoria`
--

INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `modulo`, `detalle`, `fecha`) VALUES
(1, 3, 'crear', 'pagos', 'Registró pago de 8999 para alumno ID 1', '2025-04-20 15:16:21'),
(2, 3, 'crear', 'pagos', 'Registr?? pago de 34990 para alumno ID 1 con comprobante 1234567_1_20250420.pdf', '2025-04-20 15:49:55');

-- --------------------------------------------------------

--
-- Table structure for table `bloqueos_ip`
--

CREATE TABLE `bloqueos_ip` (
  `id` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `fecha_bloqueo` datetime DEFAULT current_timestamp(),
  `motivo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendario_asignaturas`
--

CREATE TABLE `calendario_asignaturas` (
  `id` int(11) NOT NULL,
  `asignatura_id` int(11) NOT NULL,
  `dia_semana` enum('lunes','martes','miercoles','jueves','viernes') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `calendario_asignaturas`
--

INSERT INTO `calendario_asignaturas` (`id`, `asignatura_id`, `dia_semana`, `hora_inicio`, `hora_fin`) VALUES
(1, 1, 'lunes', '08:00:00', '08:45:00'),
(2, 2, 'lunes', '09:00:00', '09:45:00'),
(3, 3, 'lunes', '10:00:00', '10:45:00'),
(4, 4, 'lunes', '11:00:00', '11:45:00'),
(5, 5, 'lunes', '12:00:00', '12:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `colegios`
--

CREATE TABLE `colegios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `logo_colegio` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `colegios`
--

INSERT INTO `colegios` (`id`, `nombre`, `logo_colegio`, `description`, `direccion`, `phone`) VALUES
(1, 'Colegio Apolink', 'uploads/colegios/logos/logo-tei.svg', '', '', ''),
(2, 'The English Institute', '', '', '', ''),
(3, 'Colegio San Pedro Nolasco', '', '', '', ''),
(4, 'Escuela Básica Francisco Ramírez', 'uploads/colegios/logos/escuela-basica-francisco-ramirez.jpg', '', '', ''),
(5, 'Escuela el Arca de los Niños', 'uploads/colegios/logos/el-arca-de-los-niños.png', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `colegio_id` int(11) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cursos`
--

INSERT INTO `cursos` (`id`, `colegio_id`, `nombre`) VALUES
(1, 1, '4° Básico A'),
(2, 4, '3° Básico B'),
(3, 5, 'Medio Mayor B');

-- --------------------------------------------------------

--
-- Table structure for table `curso_modulo`
--

CREATE TABLE `curso_modulo` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `colegio_id` int(11) DEFAULT NULL,
  `modulo` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `curso_modulo`
--

INSERT INTO `curso_modulo` (`id`, `curso_id`, `colegio_id`, `modulo`, `activo`) VALUES
(1, 1, 1, 'alumnos', 1),
(2, 1, 1, 'pagos', 1),
(3, 1, 1, 'gastos', 1),
(4, 1, 1, 'eventos', 1),
(5, 1, 1, 'empresas', 1),
(6, 1, 3, 'alumnos', 1),
(7, 1, 3, 'Apoderados', 1),
(8, 1, 3, 'Pagos', 1),
(9, 1, 3, 'Eventos', 1),
(10, 1, 3, 'Empresas', 1),
(11, 1, 3, 'Gastos', 1),
(12, 2, 4, 'alumnos', 1);

-- --------------------------------------------------------

--
-- Table structure for table `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `imagen_principal` varchar(255) DEFAULT NULL,
  `galeria1` varchar(255) DEFAULT NULL,
  `galeria2` varchar(255) DEFAULT NULL,
  `galeria3` varchar(255) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `comuna` varchar(100) DEFAULT NULL,
  `clics_telefono` int(11) DEFAULT 0,
  `clics_email` int(11) DEFAULT 0,
  `patrocinada` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `empresas`
--

INSERT INTO `empresas` (`id`, `nombre`, `descripcion`, `telefono`, `email`, `imagen_principal`, `galeria1`, `galeria2`, `galeria3`, `region`, `comuna`, `clics_telefono`, `clics_email`, `patrocinada`) VALUES
(1, 'CumpleEventos Kids', 'Servicio de decoración y animación para cumpleaños y eventos escolares.', '+56912345678', 'contacto@cumpleeventoskids.cl', 'principal.jpg', 'img1.jpg', 'img2.jpg', 'img3.jpg', 'Valparaíso', 'Viña del Mar', 0, 0, 0),
(2, 'Parcela Entretenida', 'Servicio de decoración y animación para cumpleaños y eventos escolares.', '+56912345678', 'contacto@cumpleeventoskids.cl', 'principal1.jpg', 'img1.jpg', 'img2.jpg', 'img3.jpg', 'Valparaíso', 'Viña del Mar', 2000, 500, 1),
(3, 'Casa de Ferrari', 'Servicio de decoración y animación para cumpleaños y eventos escolares.', '+56912345678', 'contacto@cumpleeventoskids.cl', 'principal2.jpg', 'img1.jpg', 'img2.jpg', 'img3.jpg', 'Valparaíso', 'Viña del Mar', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descip_corta` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('evaluación','evento','otro') DEFAULT 'evento',
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `eventos`
--

INSERT INTO `eventos` (`id`, `curso_id`, `titulo`, `descip_corta`, `descripcion`, `tipo`, `fecha`) VALUES
(1, 1, 'Quiz Formativo', 'Organización política de Chile', 'El sistema político chileno es la república democrática. Pese a existir una división clásica de los poderes, los politólogos concuerdan en que la Constitución de 1980 define otros poderes o funciones, como las del Tribunal Constitucional, el Banco Central, el Consejo de Seguridad Nacional y otros órganos.', 'evaluación', '2025-06-30'),
(2, 1, 'Paseo a Escalar', '', 'Actividad cultural con disfraces y cuenta cuentos', 'evento', '2025-04-20'),
(3, 2, 'Prueba 3B', 'Revolución Francesa y Constitución', 'La Revolución Francesa y la Constitución francesa están íntimamente ligadas. La Revolución impulsó la creación de la primera constitución escrita de Francia, la de 1791, que establecía una monarquía constitucional, y luego se generaron otras durante la revolución y después de ella. La Declaración de los Derechos del Hombre y del Ciudadano, aprobada en 1789, fue un pilar fundamental de estas constituciones, garantizando derechos como la libertad, la igualdad y la propiedad. ', 'evaluación', '2025-04-22'),
(4, 1, 'Cumpleaños Oscar', '', NULL, 'evento', '2025-04-21'),
(5, 3, 'prueba medio mayor', '', NULL, 'evaluación', '2025-04-22');

-- --------------------------------------------------------

--
-- Table structure for table `fondos_gira`
--

CREATE TABLE `fondos_gira` (
  `curso_id` int(11) NOT NULL,
  `monto_meta` int(11) NOT NULL,
  `monto_actual` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fondos_gira`
--

INSERT INTO `fondos_gira` (`curso_id`, `monto_meta`, `monto_actual`) VALUES
(1, 5000000, 45000);

-- --------------------------------------------------------

--
-- Table structure for table `galerias`
--

CREATE TABLE `galerias` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `galerias`
--

INSERT INTO `galerias` (`id`, `curso_id`, `titulo`, `descripcion`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 2, 'Paseo de Curso 2024', 'Galería del paseo de curso realizado en 2024.', '2025-04-17 17:33:24', '2025-04-25 23:27:42'),
(2, 3, 'Graduación 2024', 'Recuerdos de la ceremonia de graduación del 2024.', '2025-04-17 17:33:24', '2025-04-22 15:13:20'),
(3, 2, 'Evento Deportivo 2023', 'Fotos del torneo deportivo interescolar 2023.', '2025-04-17 17:33:24', '2025-04-22 15:13:16'),
(4, 1, 'Feria de Ciencias 2023', 'Imágenes de los mejores proyectos presentados en la feria de ciencias.', '2025-04-17 17:33:24', '2025-04-21 18:00:41');

-- --------------------------------------------------------

--
-- Table structure for table `gastos`
--

CREATE TABLE `gastos` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `monto` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `registrado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `gastos`
--

INSERT INTO `gastos` (`id`, `curso_id`, `categoria`, `monto`, `fecha`, `descripcion`, `registrado_por`) VALUES
(1, 1, 'Paseo', 20000, '2025-03-20', 'Bus para paseo de curso', 2);

-- --------------------------------------------------------

--
-- Table structure for table `historial_alumno`
--

CREATE TABLE `historial_alumno` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) DEFAULT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `imagenes_eventos`
--

CREATE TABLE `imagenes_eventos` (
  `id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL,
  `ruta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `imagenes_eventos`
--

INSERT INTO `imagenes_eventos` (`id`, `evento_id`, `ruta`) VALUES
(1, 1, 'evento1_1.jpg'),
(2, 1, 'evento1_2.jpg'),
(3, 2, 'evento2_1.jpg'),
(4, 2, 'evento2_2.jpg'),
(5, 3, 'evento3_1.jpg'),
(6, 3, 'evento3_2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `imagenes_galeria`
--

CREATE TABLE `imagenes_galeria` (
  `id` int(11) NOT NULL,
  `galeria_id` int(11) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `imagenes_galeria`
--

INSERT INTO `imagenes_galeria` (`id`, `galeria_id`, `ruta_imagen`) VALUES
(1, 1, 'uploads/eventos/paseo_curso_2024_1.jpg'),
(2, 1, 'uploads/eventos/paseo_curso_2024_2.jpg'),
(3, 1, 'uploads/eventos/paseo_curso_2024_3.jpg'),
(4, 2, 'uploads/eventos/graduacion_2024_1.jpg'),
(5, 2, 'uploads/eventos/graduacion_2024_2.jpg'),
(6, 2, 'uploads/eventos/graduacion_2024_3.jpg'),
(7, 3, 'uploads/eventos/evento_deportivo_2023_1.jpg'),
(8, 3, 'uploads/eventos/evento_deportivo_2023_2.jpg'),
(9, 3, 'uploads/eventos/evento_deportivo_2023_3.jpg'),
(10, 4, 'uploads/eventos/feria_ciencias_2023_1.jpg'),
(11, 4, 'uploads/eventos/feria_ciencias_2023_2.jpg'),
(12, 4, 'uploads/eventos/feria_ciencias_2023_3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) DEFAULT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `monto` int(11) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `metodo` enum('efectivo','transferencia') DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  `numero_comprobante` varchar(50) NOT NULL,
  `archivo_comprobante` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pagos`
--

INSERT INTO `pagos` (`id`, `alumno_id`, `curso_id`, `monto`, `fecha_pago`, `metodo`, `observacion`, `numero_comprobante`, `archivo_comprobante`, `estado`, `usuario_id`) VALUES
(1, 1, 1, 5000, '2025-03-15', 'transferencia', 'Cuota primer semestre', '', NULL, 'aprobado', 3),
(2, 1, 1, 21011, '2025-04-17', 'efectivo', NULL, '', NULL, 'pendiente', 3),
(3, 1, 1, 8999, '2025-04-20', 'efectivo', 'prueba', '', NULL, 'pendiente', 3),
(4, 1, 1, 34990, '2025-04-20', 'efectivo', 'prueba', '1234567', '1234567_1_20250420.pdf', 'pendiente', 3);

-- --------------------------------------------------------

--
-- Table structure for table `recreos`
--

CREATE TABLE `recreos` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `dia_semana` enum('lunes','martes','miercoles','jueves','viernes') NOT NULL,
  `hora_inicio` time NOT NULL,
  `duracion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `recreos`
--

INSERT INTO `recreos` (`id`, `curso_id`, `dia_semana`, `hora_inicio`, `duracion`) VALUES
(1, 1, 'lunes', '09:45:00', 15),
(2, 1, 'lunes', '10:45:00', 20);

-- --------------------------------------------------------

--
-- Table structure for table `sesiones_activas`
--

CREATE TABLE `sesiones_activas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_login` datetime DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `colegio_id` int(11) DEFAULT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `rut` varchar(12) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cuota_total` int(11) DEFAULT 0,
  `cuota_gira` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `colegio_id`, `curso_id`, `nombre`, `rut`, `fecha_nac`, `telefono`, `email`, `cuota_total`, `cuota_gira`) VALUES
(1, 1, 1, 'Amanda Arlegui', '12345678-9', '2015-04-21', '987654321', 'amanda@example.com', 70000, 1000000),
(2, 1, 1, 'Manuel', '1-9', '2015-04-14', '912345678', NULL, 0, 0),
(3, 1, 1, 'Florencia', '1-8', '2015-04-14', '912345678', NULL, 0, 0),
(4, 4, 2, 'Emma Elizabeth Cáceres Poblete ', '25352127-9 ', '2016-04-18', '912345678', NULL, 0, 0),
(5, 5, 3, 'Levi Cáceres Poblete', '28022455-9', '2020-04-14', NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `transferencias`
--

CREATE TABLE `transferencias` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) DEFAULT NULL,
  `curso_origen_id` int(11) DEFAULT NULL,
  `curso_destino_id` int(11) DEFAULT NULL,
  `colegio_origen_id` int(11) DEFAULT NULL,
  `colegio_destino_id` int(11) DEFAULT NULL,
  `fecha_transferencia` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `rut` varchar(12) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('admin','admin_curso','tesorero','apoderado') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nombre`, `rut`, `email`, `telefono`, `password`, `rol`) VALUES
(1, 'Admin General', '13549300-7', 'admin@apolink.cl', '912345678', '$2y$10$CtPvZRWkU3NCO0S30n1lx.cETnAIbKm.QfuRSkCUdOhYcp5LCkuJK', 'admin'),
(2, 'Tesorero Curso', '22222222-2', 'tesorero@apolink.cl', '922222222', '$2y$10$.CWngJjh2MGKR1p2VJe.b.anNPDVDUzEqb5rVfgGsvaOx59tFNPOy', 'tesorero'),
(3, 'Oscar Arlegui', '33333333-3', 'apoderado@apolink.cl', '933333333', '$2y$10$yCAgqPsKhTN3XammU8Pohe1vjkFFGgEN2oGQ3FS5g0gWjTKKZiGl2', 'apoderado'),
(4, 'Admin Curso', '1-9', 'ss@gi.com', '444444444', '$2y$10$jlK9iL41dcpChJy70f7VluWbB8l6qBefh5rqZfP.neDlxTW8/gULC', 'admin_curso'),
(5, 'Constanza Sofía Poblete Lara', '18408233-0', 'cpobletelara@gmail.com', '+56 9 5010 7979', '$2y$10$wR8cRKrrPVQPRTe4sUW.mOu8dUUqvd1XFeF.0T5HEHTlA3J0bPG/W', 'apoderado');

-- --------------------------------------------------------

--
-- Table structure for table `user_colegio`
--

CREATE TABLE `user_colegio` (
  `user_id` int(11) NOT NULL,
  `colegio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_colegio`
--

INSERT INTO `user_colegio` (`user_id`, `colegio_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(3, 1),
(4, 1),
(5, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_curso`
--

CREATE TABLE `user_curso` (
  `user_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_curso`
--

INSERT INTO `user_curso` (`user_id`, `curso_id`) VALUES
(2, 1),
(4, 1),
(5, 2),
(5, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumno_apoderado`
--
ALTER TABLE `alumno_apoderado`
  ADD PRIMARY KEY (`alumno_id`,`apoderado_id`),
  ADD KEY `apoderado_id` (`apoderado_id`);

--
-- Indexes for table `aportes`
--
ALTER TABLE `aportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_aportes_alumno` (`alumno_id`);

--
-- Indexes for table `aportes_gira`
--
ALTER TABLE `aportes_gira`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`);

--
-- Indexes for table `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indexes for table `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `bloqueos_ip`
--
ALTER TABLE `bloqueos_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendario_asignaturas`
--
ALTER TABLE `calendario_asignaturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asignatura_id` (`asignatura_id`);

--
-- Indexes for table `colegios`
--
ALTER TABLE `colegios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `colegio_id` (`colegio_id`);

--
-- Indexes for table `curso_modulo`
--
ALTER TABLE `curso_modulo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `curso_id` (`curso_id`,`colegio_id`,`modulo`),
  ADD KEY `colegio_id` (`colegio_id`);

--
-- Indexes for table `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indexes for table `fondos_gira`
--
ALTER TABLE `fondos_gira`
  ADD PRIMARY KEY (`curso_id`);

--
-- Indexes for table `galerias`
--
ALTER TABLE `galerias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `registrado_por` (`registrado_por`);

--
-- Indexes for table `historial_alumno`
--
ALTER TABLE `historial_alumno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indexes for table `imagenes_eventos`
--
ALTER TABLE `imagenes_eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evento_id` (`evento_id`);

--
-- Indexes for table `imagenes_galeria`
--
ALTER TABLE `imagenes_galeria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galeria_id` (`galeria_id`);

--
-- Indexes for table `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indexes for table `recreos`
--
ALTER TABLE `recreos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indexes for table `sesiones_activas`
--
ALTER TABLE `sesiones_activas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `colegio_id` (`colegio_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indexes for table `transferencias`
--
ALTER TABLE `transferencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `curso_origen_id` (`curso_origen_id`),
  ADD KEY `curso_destino_id` (`curso_destino_id`),
  ADD KEY `colegio_origen_id` (`colegio_origen_id`),
  ADD KEY `colegio_destino_id` (`colegio_destino_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rut` (`rut`);

--
-- Indexes for table `user_colegio`
--
ALTER TABLE `user_colegio`
  ADD PRIMARY KEY (`user_id`,`colegio_id`),
  ADD KEY `colegio_id` (`colegio_id`);

--
-- Indexes for table `user_curso`
--
ALTER TABLE `user_curso`
  ADD PRIMARY KEY (`user_id`,`curso_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aportes`
--
ALTER TABLE `aportes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `aportes_gira`
--
ALTER TABLE `aportes_gira`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bloqueos_ip`
--
ALTER TABLE `bloqueos_ip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calendario_asignaturas`
--
ALTER TABLE `calendario_asignaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `colegios`
--
ALTER TABLE `colegios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `curso_modulo`
--
ALTER TABLE `curso_modulo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `galerias`
--
ALTER TABLE `galerias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `historial_alumno`
--
ALTER TABLE `historial_alumno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `imagenes_eventos`
--
ALTER TABLE `imagenes_eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `imagenes_galeria`
--
ALTER TABLE `imagenes_galeria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `recreos`
--
ALTER TABLE `recreos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sesiones_activas`
--
ALTER TABLE `sesiones_activas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transferencias`
--
ALTER TABLE `transferencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alumno_apoderado`
--
ALTER TABLE `alumno_apoderado`
  ADD CONSTRAINT `alumno_apoderado_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `alumno_apoderado_ibfk_2` FOREIGN KEY (`apoderado_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `aportes`
--
ALTER TABLE `aportes`
  ADD CONSTRAINT `fk_aportes_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aportes_gira`
--
ALTER TABLE `aportes_gira`
  ADD CONSTRAINT `aportes_gira_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD CONSTRAINT `asignaturas_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `calendario_asignaturas`
--
ALTER TABLE `calendario_asignaturas`
  ADD CONSTRAINT `calendario_asignaturas_ibfk_1` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`colegio_id`) REFERENCES `colegios` (`id`);

--
-- Constraints for table `curso_modulo`
--
ALTER TABLE `curso_modulo`
  ADD CONSTRAINT `curso_modulo_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `curso_modulo_ibfk_2` FOREIGN KEY (`colegio_id`) REFERENCES `colegios` (`id`);

--
-- Constraints for table `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);

--
-- Constraints for table `fondos_gira`
--
ALTER TABLE `fondos_gira`
  ADD CONSTRAINT `fondos_gira_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);

--
-- Constraints for table `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `gastos_ibfk_2` FOREIGN KEY (`registrado_por`) REFERENCES `users` (`id`);

--
-- Constraints for table `historial_alumno`
--
ALTER TABLE `historial_alumno`
  ADD CONSTRAINT `historial_alumno_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `historial_alumno_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);

--
-- Constraints for table `imagenes_eventos`
--
ALTER TABLE `imagenes_eventos`
  ADD CONSTRAINT `imagenes_eventos_ibfk_1` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`);

--
-- Constraints for table `imagenes_galeria`
--
ALTER TABLE `imagenes_galeria`
  ADD CONSTRAINT `imagenes_galeria_ibfk_1` FOREIGN KEY (`galeria_id`) REFERENCES `galerias` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);

--
-- Constraints for table `recreos`
--
ALTER TABLE `recreos`
  ADD CONSTRAINT `recreos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sesiones_activas`
--
ALTER TABLE `sesiones_activas`
  ADD CONSTRAINT `sesiones_activas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`colegio_id`) REFERENCES `colegios` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);

--
-- Constraints for table `transferencias`
--
ALTER TABLE `transferencias`
  ADD CONSTRAINT `transferencias_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `transferencias_ibfk_2` FOREIGN KEY (`curso_origen_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `transferencias_ibfk_3` FOREIGN KEY (`curso_destino_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `transferencias_ibfk_4` FOREIGN KEY (`colegio_origen_id`) REFERENCES `colegios` (`id`),
  ADD CONSTRAINT `transferencias_ibfk_5` FOREIGN KEY (`colegio_destino_id`) REFERENCES `colegios` (`id`);

--
-- Constraints for table `user_colegio`
--
ALTER TABLE `user_colegio`
  ADD CONSTRAINT `user_colegio_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_colegio_ibfk_2` FOREIGN KEY (`colegio_id`) REFERENCES `colegios` (`id`);

--
-- Constraints for table `user_curso`
--
ALTER TABLE `user_curso`
  ADD CONSTRAINT `user_curso_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_curso_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
