-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-12-2025 a las 05:17:04
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
-- Base de datos: `tiendapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos`
--

CREATE TABLE `archivos` (
  `id_archivo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fk_tipo_archivo` int(11) NOT NULL,
  `fk_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `archivos`
--

INSERT INTO `archivos` (`id_archivo`, `nombre`, `fk_tipo_archivo`, `fk_producto`) VALUES
(1, '1765419006_1410-collar-para-perros-polipropileno-rojo-383fd85d793010e98317030902891095-240-0.jpg', 1, 1),
(3, '1765419006_2023-09-28-09-59-34_edit-org1-28276f62b98634598516959060684999-1024-1024.jpg', 1, 1),
(4, '1765419092_Collar azul.jpg', 1, 1),
(5, '1765419413_silla-operativa-flex-baja-1d (1).jpg', 1, 2),
(6, '1765419413_silla-operativa-flex-baja-1d (2).jpg', 1, 2),
(7, '1765419413_silla-operativa-flex-baja-1d.jpg', 1, 2),
(8, '1765419585_Imagen6.jpg', 1, 3),
(9, '1765419585_Imagen7.jpg', 1, 3),
(10, '1765419585_Imagen8.jpg', 1, 3),
(11, '1765419725_istockphoto-497252298-612x612.jpg', 1, 4),
(12, '1765419725_istockphoto-515311485-612x612.jpg', 1, 4),
(13, '1765419725_istockphoto-519176205-612x612.jpg', 1, 4),
(14, '1765419725_istockphoto-515311513-612x612.jpg', 1, 4),
(15, '1765419830_Imagen3.jpg', 1, 5),
(16, '1765419830_Imagen4.jpg', 1, 5),
(17, '1765419974_gettyimages-124049200-612x612.jpg', 1, 6),
(18, '1792729377_termo.jpg', 1, 7),
(19, '1792729378_mate.jpg', 1, 8),
(20, '1792729379_mochila.jpg', 1, 9),
(21, '1792729380_auriculares.jpg', 1, 10),
(22, '1792729381_parlante.jpg', 1, 11),
(23, '1792729382_zapatillas.jpg', 1, 12),
(24, '1792729383_lentes.jpg', 1, 13),
(25, '1792729384_secador.jpg', 1, 14),
(26, '1792729385_mancuernas.jpg', 1, 15),
(27, '1792729386_colchoneta.jpg', 1, 16),
(28, '1792729387_cama-perro.jpg', 1, 17),
(29, '1792729388_rascador.jpg', 1, 18),
(30, '1792729389_monopatin.jpg', 1, 19),
(31, '1792729390_bicicleta.jpg', 1, 20),
(32, '1792729391_casco.jpg', 1, 21),
(33, '1792729392_silla-nordica.jpg', 1, 22),
(34, '1792729393_mesa-ratona.jpg', 1, 23),
(35, '1792729394_manguera.jpg', 1, 24),
(36, '1792729395_palita.jpg', 1, 25),
(37, '1792729396_maceta.jpg', 1, 26),
(38, '1792729397_lampara.jpg', 1, 27),
(39, '1792729398_libretas.jpg', 1, 28),
(40, '1792729399_martin-fierro.jpg', 1, 29),
(41, '1792729400_quijote.jpg', 1, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `categoria` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `categoria`) VALUES
(1, 'General'),
(2, 'Electrónica'),
(3, 'Moda y belleza'),
(4, 'Fitness'),
(5, 'Mascotas'),
(6, 'Vehículos'),
(7, 'Muebles'),
(8, 'Jardinería'),
(9, 'Oficina y papelería'),
(10, 'Libros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `comentario` tinytext NOT NULL,
  `fecha` date NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `fk_producto` int(11) NOT NULL,
  `fk_estado_comentario` int(11) NOT NULL,
  `fk_respuesta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id_comentario`, `comentario`, `fecha`, `fk_usuario`, `fk_producto`, `fk_estado_comentario`, `fk_respuesta`) VALUES
(1, 'Hola! Tenés el collar en negro?', '2025-12-11', 3, 1, 1, NULL),
(2, 'hola?', '2025-12-10', 3, 1, 1, 1),
(3, 'si', '2025-12-10', 2, 1, 1, 1),
(4, 'Hay en negro, blanco y rojo', '2025-12-11', 2, 1, 1, 3),
(5, 'hola :)', '2025-12-11', 2, 1, 1, 2),
(6, 'Genial. Muchas gracias :)', '2025-12-11', 3, 1, 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envios`
--

CREATE TABLE `envios` (
  `id_envio` int(11) NOT NULL,
  `fecha_envio` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `fk_estado_envio` int(11) NOT NULL,
  `fk_transaccion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `envios`
--

INSERT INTO `envios` (`id_envio`, `fecha_envio`, `fecha_entrega`, `fk_estado_envio`, `fk_transaccion`) VALUES
(1, '2025-12-11', '2025-12-11', 4, 1),
(2, NULL, '2025-12-11', 3, 3),
(3, '2025-12-11', NULL, 2, 4),
(4, NULL, NULL, 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_comentarios`
--

CREATE TABLE `estados_comentarios` (
  `id_estado_comentario` int(11) NOT NULL,
  `estado` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estados_comentarios`
--

INSERT INTO `estados_comentarios` (`id_estado_comentario`, `estado`) VALUES
(1, 'Apropiado'),
(2, 'Eliminado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_envios`
--

CREATE TABLE `estados_envios` (
  `id_estado_envio` int(11) NOT NULL,
  `estado` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estados_envios`
--

INSERT INTO `estados_envios` (`id_estado_envio`, `estado`) VALUES
(1, 'A retirar'),
(2, 'En camino'),
(3, 'Retirado'),
(4, 'Entregado'),
(5, 'Cancelado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_productos`
--

CREATE TABLE `estados_productos` (
  `id_estado_producto` int(11) NOT NULL,
  `estado` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estados_productos`
--

INSERT INTO `estados_productos` (`id_estado_producto`, `estado`) VALUES
(1, 'Disponible'),
(2, 'Agotado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_sugerencias`
--

CREATE TABLE `estados_sugerencias` (
  `id_estado_sugerencia` int(11) NOT NULL,
  `estado` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estados_sugerencias`
--

INSERT INTO `estados_sugerencias` (`id_estado_sugerencia`, `estado`) VALUES
(1, 'Pendiente'),
(2, 'Aprobada'),
(3, 'Rechazada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_transacciones`
--

CREATE TABLE `estados_transacciones` (
  `id_estado_transaccion` int(11) NOT NULL,
  `estado` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estados_transacciones`
--

INSERT INTO `estados_transacciones` (`id_estado_transaccion`, `estado`) VALUES
(1, 'Pendiente'),
(2, 'Completada'),
(3, 'Cancelada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pagos`
--

CREATE TABLE `metodos_pagos` (
  `id_metodo_pago` int(11) NOT NULL,
  `metodo_pago` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `metodos_pagos`
--

INSERT INTO `metodos_pagos` (`id_metodo_pago`, `metodo_pago`) VALUES
(1, 'Tarjeta de crédito'),
(2, 'Tarjeta de débito'),
(3, 'Efectivo'),
(4, 'Billetera virtual'),
(5, 'Transferencia bancaria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pagos_usuarios`
--

CREATE TABLE `metodos_pagos_usuarios` (
  `id_metodo_pago_usuario` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `numero_tarjeta` varchar(20) DEFAULT NULL,
  `vencimiento` date DEFAULT NULL,
  `codigo_tarjeta` int(11) DEFAULT NULL,
  `cbu` varchar(22) DEFAULT NULL,
  `fk_metodo_pago` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `metodos_pagos_usuarios`
--

INSERT INTO `metodos_pagos_usuarios` (`id_metodo_pago_usuario`, `nombre`, `numero_tarjeta`, `vencimiento`, `codigo_tarjeta`, `cbu`, `fk_metodo_pago`, `fk_usuario`) VALUES
(1, 'American Express', 2147483647, '2026-06-01', 4115, NULL, 1, 3),
(2, 'Mercado pago', NULL, NULL, NULL, 1234567890, 4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `fecha_publicacion` date NOT NULL,
  `fk_categoria` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `fk_estado_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `fecha_publicacion`, `fk_categoria`, `fk_usuario`, `fk_estado_producto`) VALUES
(1, 'Collar para perros', 'Collar para perros varios colores.', 9800.00, 9, '2025-12-10', 5, 2, 1),
(2, 'Silla operativa ', 'Silla operativa flex baja ideal para oficina.', 189999.00, 5, '2025-12-10', 7, 2, 1),
(3, 'Bicicleta fija', 'Bicicleta fija práctica para el hogar.', 649999.00, 1, '2025-12-10', 4, 2, 1),
(4, 'Reloj inteligente', 'Reloj inteligente varios colores. ', 129999.00, 22, '2025-12-10', 2, 2, 1),
(5, 'Steve Jobs', 'Colección de libros de Steve Jobs. ', 38500.00, 1, '2025-12-10', 10, 2, 1),
(6, 'Kit de escritorio ', 'Kit para el escritorio surtido. ', 18500.00, 15, '2025-12-10', 9, 2, 1),
(7, 'Termo de acero inoxidable 1 L', 'Termo de doble pared de acero inoxidable. Mantiene el agua caliente hasta 24 horas y trae tapón cebador.', 45999.00, 18, '2026-10-02', 1, 2, 1),
(8, 'Mate de calabaza con bombilla', 'Mate de calabaza curado con virola de alpaca y bombilla de acero inoxidable.', 18500.00, 25, '2026-10-05', 1, 3, 1),
(9, 'Mochila urbana 25 L', 'Mochila con compartimento acolchado para notebook de hasta 15,6 pulgadas y bolsillos laterales.', 54900.00, 12, '2026-09-20', 1, 2, 1),
(10, 'Auriculares inalámbricos Bluetooth', 'Auriculares vincha con Bluetooth 5.3, cancelación de ruido y hasta 30 horas de batería.', 59999.00, 15, '2026-10-12', 2, 2, 1),
(11, 'Parlante portátil Bluetooth', 'Parlante portátil con manija, 20 W de potencia, radio FM y batería de 10 horas.', 89999.00, 9, '2026-09-28', 2, 3, 1),
(12, 'Zapatillas urbanas de lona', 'Zapatillas de lona con suela de goma vulcanizada. Talles del 35 al 44.', 79999.00, 20, '2026-10-08', 3, 2, 1),
(13, 'Lentes de sol aviador polarizados', 'Lentes estilo aviador con filtro UV400 y lentes espejadas polarizadas. Incluye estuche.', 38500.00, 14, '2026-09-15', 3, 3, 1),
(14, 'Secador de pelo 2000 W', 'Secador con dos velocidades, tres temperaturas y golpe de aire frío. Mango plegable.', 64999.00, 7, '2026-10-01', 3, 2, 1),
(15, 'Par de mancuernas 5 kg', 'Par de mancuernas regulables con discos de 5 kg cada una y barra con rosca.', 42000.00, 10, '2026-09-25', 4, 2, 1),
(16, 'Colchoneta de yoga', 'Colchoneta antideslizante de 6 mm, liviana y con tira para transportarla.', 24999.00, 30, '2026-10-10', 4, 3, 1),
(17, 'Cama para perro mediana', 'Cama acolchada de 70 cm con borde alto y base antideslizante. Funda lavable.', 39999.00, 8, '2026-10-14', 5, 3, 1),
(18, 'Rascador para gatos con plataformas', 'Rascador de sisal con dos plataformas y cucha. Altura 90 cm.', 72500.00, 5, '2026-09-30', 5, 2, 1),
(19, 'Monopatín eléctrico', 'Monopatín eléctrico plegable, velocidad máxima 25 km/h y autonomía de 30 km.', 649999.00, 3, '2026-10-15', 6, 2, 1),
(20, 'Bicicleta urbana rodado 28', 'Bicicleta de paseo con cuadro de acero, canasto delantero, guardabarros y 6 cambios.', 389999.00, 4, '2026-09-18', 6, 2, 1),
(21, 'Casco integral para moto', 'Casco integral homologado con visor antirrayas y forro interior desmontable.', 95000.00, 6, '2026-10-04', 6, 3, 1),
(22, 'Silla nórdica de diseño', 'Silla con asiento de polipropileno y patas de madera de haya. Ideal para comedor.', 54999.00, 16, '2026-10-09', 7, 2, 1),
(23, 'Mesa ratona de madera y vidrio', 'Mesa ratona de madera maciza con tapa de vidrio templado. 110 x 50 cm.', 119000.00, 3, '2026-09-22', 7, 3, 1),
(24, 'Manguera con enrollador 15 m', 'Manguera de 15 metros con enrollador de pared y pistola de riego de 7 funciones.', 28999.00, 11, '2026-10-06', 8, 2, 1),
(25, 'Palita de jardín de acero', 'Palita de mano de acero con pintura epoxi, ideal para trasplantar y abonar macetas.', 8900.00, 40, '2026-10-11', 8, 3, 1),
(26, 'Maceta de barro con potus', 'Potus variegado en maceta de barro de 14 cm. Planta de interior fácil de cuidar.', 16900.00, 13, '2026-10-13', 8, 2, 1),
(27, 'Lámpara de escritorio flexible', 'Lámpara de escritorio con brazo flexible, rosca E27 y base pesada. Ideal para estudiar.', 36999.00, 9, '2026-09-27', 9, 2, 1),
(28, 'Libretas anilladas A7 (pack x2)', 'Pack de dos libretas anilladas tamaño A7 de 80 hojas rayadas.', 6500.00, 50, '2026-10-07', 9, 3, 1),
(29, 'Martín Fierro - José Hernández', 'Edición con estudio preliminar, notas y vocabulario. Usado en muy buen estado.', 19900.00, 2, '2026-09-12', 10, 2, 1),
(30, 'Don Quijote de la Mancha', 'Edición completa de la obra de Miguel de Cervantes, con ilustraciones y notas.', 34900.00, 6, '2026-10-03', 10, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol`) VALUES
(1, 'Admin'),
(2, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sugerencias`
--

CREATE TABLE `sugerencias` (
  `id_sugerencia` int(11) NOT NULL,
  `sugerencia` text NOT NULL,
  `archivo` varchar(100) DEFAULT NULL,
  `fk_usuario` int(11) NOT NULL,
  `fk_estado_sugerencia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `sugerencias`
--

INSERT INTO `sugerencias` (`id_sugerencia`, `sugerencia`, `archivo`, `fk_usuario`, `fk_estado_sugerencia`) VALUES
(1, 'En el perfil, se podría agregar un imagen qu funcione como ícono ', '1765424218_Sugerencia 1.png', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_archivos`
--

CREATE TABLE `tipos_archivos` (
  `id_tipo_archivo` int(11) NOT NULL,
  `tipo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tipos_archivos`
--

INSERT INTO `tipos_archivos` (`id_tipo_archivo`, `tipo`) VALUES
(1, 'JPG'),
(2, 'JPEG'),
(3, 'PNG'),
(4, 'MP4'),
(5, 'MOV'),
(6, 'MPEG'),
(7, 'AVI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `id_transaccion` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` date DEFAULT NULL,
  `cuotas` int(11) NOT NULL,
  `fk_producto` int(11) NOT NULL,
  `fk_estado_transaccion` int(11) NOT NULL,
  `fk_metodo_pago_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `transacciones`
--

INSERT INTO `transacciones` (`id_transaccion`, `cantidad`, `total`, `fecha`, `cuotas`, `fk_producto`, `fk_estado_transaccion`, `fk_metodo_pago_usuario`) VALUES
(1, 1, 3500.00, '2025-12-11', 2, 1, 2, 1),
(2, 2, 403998.00, '2025-12-11', 1, 2, 1, 2),
(3, 1, 699000.00, '2025-12-11', 1, 3, 2, 2),
(4, 3, 209997.00, '2025-12-11', 3, 4, 2, 1),
(5, 1, 86679.00, '2025-12-11', 1, 5, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `mail` varchar(60) NOT NULL,
  `contrasena` varchar(32) NOT NULL,
  `ubicacion` varchar(80) DEFAULT NULL,
  `fk_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `mail`, `contrasena`, `ubicacion`, `fk_rol`) VALUES
(1, 'Admin', 'admin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', NULL, 1),
(2, 'a', 'a@a.com', '81dc9bdb52d04dc20036dbd8313ed055', NULL, 2),
(3, 'Laura', 'laura@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Callao 1020 - 3ro. D', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `archivos`
--
ALTER TABLE `archivos`
  ADD PRIMARY KEY (`id_archivo`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`);

--
-- Indices de la tabla `envios`
--
ALTER TABLE `envios`
  ADD PRIMARY KEY (`id_envio`);

--
-- Indices de la tabla `estados_comentarios`
--
ALTER TABLE `estados_comentarios`
  ADD PRIMARY KEY (`id_estado_comentario`);

--
-- Indices de la tabla `estados_envios`
--
ALTER TABLE `estados_envios`
  ADD PRIMARY KEY (`id_estado_envio`);

--
-- Indices de la tabla `estados_productos`
--
ALTER TABLE `estados_productos`
  ADD PRIMARY KEY (`id_estado_producto`);

--
-- Indices de la tabla `estados_sugerencias`
--
ALTER TABLE `estados_sugerencias`
  ADD PRIMARY KEY (`id_estado_sugerencia`);

--
-- Indices de la tabla `estados_transacciones`
--
ALTER TABLE `estados_transacciones`
  ADD PRIMARY KEY (`id_estado_transaccion`);

--
-- Indices de la tabla `metodos_pagos`
--
ALTER TABLE `metodos_pagos`
  ADD PRIMARY KEY (`id_metodo_pago`);

--
-- Indices de la tabla `metodos_pagos_usuarios`
--
ALTER TABLE `metodos_pagos_usuarios`
  ADD PRIMARY KEY (`id_metodo_pago_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `sugerencias`
--
ALTER TABLE `sugerencias`
  ADD PRIMARY KEY (`id_sugerencia`),
  ADD KEY `fk_sugerencias_estado` (`fk_estado_sugerencia`),
  ADD KEY `fk_sugerencias_usuario` (`fk_usuario`);

--
-- Indices de la tabla `tipos_archivos`
--
ALTER TABLE `tipos_archivos`
  ADD PRIMARY KEY (`id_tipo_archivo`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`id_transaccion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `mail_UNIQUE` (`mail`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `archivos`
--
ALTER TABLE `archivos`
  MODIFY `id_archivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `envios`
--
ALTER TABLE `envios`
  MODIFY `id_envio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estados_comentarios`
--
ALTER TABLE `estados_comentarios`
  MODIFY `id_estado_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estados_envios`
--
ALTER TABLE `estados_envios`
  MODIFY `id_estado_envio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estados_productos`
--
ALTER TABLE `estados_productos`
  MODIFY `id_estado_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estados_sugerencias`
--
ALTER TABLE `estados_sugerencias`
  MODIFY `id_estado_sugerencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estados_transacciones`
--
ALTER TABLE `estados_transacciones`
  MODIFY `id_estado_transaccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `metodos_pagos`
--
ALTER TABLE `metodos_pagos`
  MODIFY `id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `metodos_pagos_usuarios`
--
ALTER TABLE `metodos_pagos_usuarios`
  MODIFY `id_metodo_pago_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sugerencias`
--
ALTER TABLE `sugerencias`
  MODIFY `id_sugerencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipos_archivos`
--
ALTER TABLE `tipos_archivos`
  MODIFY `id_tipo_archivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `id_transaccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `sugerencias`
--
ALTER TABLE `sugerencias`
  ADD CONSTRAINT `fk_sugerencias_estado` FOREIGN KEY (`fk_estado_sugerencia`) REFERENCES `estados_sugerencias` (`id_estado_sugerencia`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sugerencias_usuario` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
