-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 11-02-2026 a las 12:02:29
-- Versión del servidor: 8.0.43-0ubuntu0.24.04.2
-- Versión de PHP: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `powergym`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int NOT NULL,
  `nombre_categoria` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre_categoria`) VALUES
(1, 'Ropa'),
(2, 'Suplementos'),
(3, 'Accesorios'),
(4, 'Alimentacion'),
(5, 'Creatina');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE `clases` (
  `id` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `fecha_hora` datetime DEFAULT NULL,
  `plazas_totales` int DEFAULT NULL,
  `id_entrenador` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`id`, `nombre`, `descripcion`, `fecha_hora`, `plazas_totales`, `id_entrenador`) VALUES
(2, 'TFG', 'asdadasdshjlkl', '2026-02-18 23:40:00', 19, 2),
(3, 'Zumba', 'Combinación de baile y ejercicio aeróbico con ritmos latinos y populares. Es una clase divertida y eficaz para quemar calorías.', '2026-02-19 18:00:00', 40, 8),
(4, 'Spinning', 'Entrenamiento de alta intensidad sobre bicicletas estacionarias, que simula diferentes terrenos y velocidades, ayudando a mejorar la resistencia cardiovascular.', '2026-02-27 19:30:00', 15, 11),
(5, 'Body Pump', 'Clase que combina ejercicios con pesas ligeras a moderadas y altas repeticiones para tonificar y fortalecer los músculos.', '2026-02-27 21:20:00', 25, 9),
(6, 'Pilates', 'Se centra en el control y fortalecimiento del “core” o centro del cuerpo, mejorando la postura y la flexibilidad.', '2026-02-23 15:30:00', 15, 11),
(7, 'Boxeo', 'Clase que incluye entrenamiento con sacos, guantes y ejercicios de golpeo para mejorar la fuerza, coordinación y resistencia cardiovascular', '2026-02-12 20:15:00', 15, 13),
(8, 'Defensa Personal', 'Clases que enseñan técnicas para defenderse en situaciones de riesgo, a menudo combinadas con acondicionamiento físico.', '2026-03-07 21:10:00', 15, 8),
(9, 'Kickboxing', 'Una combinación de técnicas de boxeo y artes marciales con movimientos aeróbicos que ayudan a mejorar la fuerza y la agilidad.', '2026-03-01 19:30:00', 1, 13),
(10, 'Zumba', 'Como se mencionó antes, es una combinación de baile y ejercicio aeróbico.', '2026-02-25 14:30:00', 15, 9),
(11, 'Aquaeróbic', 'Clases realizadas en el agua, ideales para personas con problemas articulares, ya que el agua reduce el impacto en las articulaciones.', '2026-02-27 14:30:00', 10, 2),
(12, 'Body Pump', 'Cargas ligeras y muchas repeticiones para definir todo el cuerpo a ritmo de música, con un track por zona ', '2026-03-07 20:00:00', 20, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineas_pedidos`
--

CREATE TABLE `lineas_pedidos` (
  `id` int NOT NULL,
  `id_pedido` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lineas_pedidos`
--

INSERT INTO `lineas_pedidos` (`id`, `id_pedido`, `id_producto`, `cantidad`, `precio`) VALUES
(1, 2, 2, 2, 7.00),
(2, 2, 3, 1, 5.00),
(3, 2, 4, 1, 27.00),
(4, 3, 2, 1, 7.00),
(5, 3, 3, 1, 5.00),
(6, 3, 4, 1, 27.00),
(7, 4, 2, 4, 7.00),
(8, 5, 4, 1, 27.00),
(9, 5, 5, 1, 15.00),
(10, 5, 6, 1, 28.00),
(11, 6, 7, 4, 85.00),
(12, 7, 2, 1, 7.00),
(13, 7, 3, 5, 5.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_usuario`, `total`, `fecha`) VALUES
(1, 1, 46.00, '2026-02-07 17:49:30'),
(2, 1, 46.00, '2026-02-07 17:57:08'),
(3, 1, 39.00, '2026-02-07 17:57:15'),
(4, 1, 28.00, '2026-02-07 18:18:53'),
(5, 1, 70.00, '2026-02-07 18:41:29'),
(6, 1, 340.00, '2026-02-07 18:41:58'),
(7, 1, 32.00, '2026-02-11 09:19:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `precio` int NOT NULL,
  `stock` decimal(10,0) NOT NULL,
  `imagen` text COLLATE utf8mb4_general_ci NOT NULL,
  `id_categoria` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `imagen`, `id_categoria`) VALUES
(2, 'Camel blue', 'Tabaco', 7, 4, '1770216350_23e8bcbbe0e0d4b944aa.jpg', 1),
(3, 'Toalla Gimnasio Fitness', 'Suave y absorbente para secarte durante tus sesiones o simplemente para cubrir tu banco o esterilla para evitar las marcas de sudor.', 5, 20, 'toalla.avif', 3),
(4, 'Cinturon', 'Cinturón Austin es un accesorio que contribuye con la seguridad del deportista y/o atleta al momento de realizar las series que involucran peso, ayudando de esta manera a que la ejecución sea perfecta y por consecuencia inmediata, obtener excelentes resultados.', 27, 4, 'cinturon.jpg', 3),
(5, 'Esterilla Negra', 'Esterilla de entrenamiento antideslizante, perfecta para yoga, pilates, estiramientos y rutinas de suelo. Confortable, práctica y fácil de mantener en cualquier espacio fitness.', 15, 6, 'esterilla.jpg', 3),
(6, 'Top Nike', '', 28, 12, 'topnike.jpg', 1),
(7, 'Adidas Dropset Control', 'Zapatillas Cross Training Mujer Negro', 85, 19, 'zapatillasAdidas.jpg', 1),
(8, 'Nike Vapor Fg', 'Guantes Fitness Negro', 25, 24, 'guantesnike.jpg', 3),
(9, 'Botella Doone', 'Botella Gimnasio 0,75 L', 8, 34, 'botellagris.jpg', 3),
(10, 'Bodytone Rope\r\n', 'Comba Ajustable 275 cm', 1, 27, 'comba.jpg', 3),
(11, 'Muñequera Rígida Doone', 'Muñequera Rígida Deporte Negro', 6, 12, 'muniequeranegra.jpg', 3),
(12, 'Creatina HCL', '3g de creatina.', 5, 4, 'creatinahcl.avif', 5),
(13, 'Creatina Monohidrato', 'Perfecto para los que quieren aumentar su rendimiento físico', 19, 45, 'creatinapastillas.avif', 5),
(14, 'Creatina Monohidrato en polvo', 'Se ha probado científicamente que potencia tu rendimiento.', 6, 20, 'creatinapolvo.avif', 5),
(15, 'THE Creatine', 'Pura potencia. Calidad inigualable.', 14, 2, 'sobrescreatina.avif', 5),
(16, 'Monohidrato de creatina en polvo', 'Eficacia probada y fiable para mejorar el rendimiento', 14, 49, 'creatinacaramelo.avif', 5),
(17, 'Botella Lilo & Stitch', 'Bidón 0,5 L Disney Azul', 7, 45, 'liloystitch.jpg', 3),
(18, 'Comba Negra', 'Comba Fitness 2,75 m', 3, 34, 'combanegra.jpg', 3),
(19, 'Banda Elastica', 'Banda Elástica 25 Kg', 12, 32, 'bandaelastica.jpg', 3),
(20, 'Soporte Flexiones Doone', 'Soporte Flexiones Negro', 7, 21, 'soporteflexiones.jpg', 3),
(21, 'Banda Elastica Azul', 'Banda Elástica 30 Kg Azul', 13, 2, 'bandaazul.jpg', 3),
(22, 'Esterilla Morada', 'Colchoneta 173 x 61 x 0,4 cm Morado', 12, 56, 'esterillamorada.jpg', 3),
(23, 'Guantes adidas negros', 'Guantes Fitness Negro', 24, 12, 'guantesadidas.jpg', 3),
(24, 'Ejercitador Manos', 'Hand Grip Ajustable 10-40 Kg Negro', 7, 3, 'ejercitadormanos.jpg', 3),
(25, 'Rodilleras Doone', 'Accesorios Deporte Negro', 7, 16, 'rodillera.jpg', 3),
(26, 'Tobillera', 'Tobillera 22 x 11,25 cm Negro', 7, 14, 'tobillera.jpg', 3),
(27, 'Step 2 Alturas Sveltus', 'Step Fitness Negro', 40, 2, 'step.jpg', 3),
(28, 'Mallas mujer gris', 'Mallas Fitness Mujer Gris', 6, 67, 'mallasgrisesmujer.jpg', 1),
(29, 'Mallas Nike', 'Mallas Fitness Mujer Negro', 20, 23, 'nikepro.jpg', 1),
(30, 'Sudadera Capucha Doone', 'Sudadera Cremallera Mujer Azul', 21, 31, 'sudaderacapucha.jpg', 1),
(31, 'Sudadera Crop Nike', 'Sudadera Crop Mujer Negro', 35, 65, 'sudaderanike.jpg', 1),
(32, 'Mallas Rojo', 'Mallas Fitness Mujer Rojo', 13, 17, 'mallasrosas.jpg', 1),
(33, 'Camiseta Puma', 'Camiseta Fitness Hombre Azul', 20, 32, 'camisetapuma.jpg', 1),
(34, 'Pantalon adidas', 'Pantalón Running Hombre Negro', 30, 12, 'pantalonadidas.jpg', 1),
(35, 'Mallas larga Joma', 'Mallas Largas Hombre Rojo', 25, 21, 'mallaslargajoma.jpg', 1),
(36, 'Camiseta Kappa', 'Ropa Ideal Para El Gim O Entrenar Blanco', 14, 5, 'camisetakappa.jpg', 1),
(37, 'Mallas Largas Joma Amarillo', 'Mallas Largas Hombre Amarillo', 25, 32, 'mallasjomaamarillo.jpg', 1),
(38, 'Sudadera De Hombre Dacciozi Kappa', 'Ropa Ideal Para El Gim O Entrenar Azul', 46, 2, 'sudaderakappa.jpg', 1),
(39, 'Zapatillas Puma', 'Zapatillas Fitness Hombre Negro', 35, 5, 'zapatillaspuma.jpg', 1),
(40, 'Nike Lebron Tr1', 'Zapatillas Baloncesto Hombre Verde', 80, 10, 'zapatillasnikeverde.jpg', 1),
(41, 'Zapatillas Nike Bella 7', 'Zapatillas Fitness Mujer Blanco', 84, 15, 'nikeblancas.jpg', 1),
(42, 'Camiseta Crop Fila', 'Camiseta Mujer Azul', 15, 3, 'camisetafila.jpg', 1),
(43, 'Camiseta Puma Morada', 'Camiseta Tirantes Mujer Azul', 12, 23, 'camisetatirantes.jpg', 1),
(44, 'Camiseta Stitch', 'Top Niña Disney Negro', 10, 25, 'camisetastitch.jpg', 1),
(45, 'Mallas Tobilleras', 'Mallas Tobilleras Niña Negro', 11, 4, 'mallastobillera.jpg', 1),
(46, 'Zapatillas Nike Metcon 10', 'Zapatillas Cross Training Hombre Rojo', 130, 10, 'nikeroja.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_clase` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `id_usuario`, `id_clase`) VALUES
(6, 1, 2),
(8, 1, 9),
(9, 18, 7),
(10, 18, 6),
(11, 18, 8),
(12, 19, 7),
(13, 19, 6),
(14, 19, 2),
(15, 19, 3),
(16, 19, 5),
(17, 19, 4),
(18, 19, 8),
(19, 20, 2),
(20, 20, 4),
(21, 20, 6),
(22, 20, 3),
(23, 21, 3),
(24, 21, 5),
(25, 21, 7),
(26, 21, 4),
(27, 21, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `nombre_rol` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre_rol`) VALUES
(1, 'admin'),
(2, 'entrenador'),
(3, 'cliente'),
(4, 'socio_gym');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `id_rol` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `password`, `fecha_registro`, `id_rol`) VALUES
(1, 'Jose', 'sejo170', 'josehernandopardos@gmail.com', '$2y$10$isyCrpAL9k1OlQ.nzSFzfu9wE1lDoGGiv61DO0EmEDg8Wp/XAti1G', '2026-01-28 13:38:30', 1),
(2, 'Vinicius', 'JR', 'vinijr@gmail.com', '$2y$10$.bM4HrsrGKitlor3KxOIF.YZMXtf7C3B57MpKNX4hi5rfIQqQtw3K', '2026-01-26 14:20:02', 2),
(3, 'laura', 'castellon', 'laura1234@gmail.com', '$2y$10$W2fiV/8gRVpyT1aW1K5y9ew.5q09pYa0Z4n4GAWSqjoMvxnXQWaCW', '2026-01-27 14:52:51', 4),
(4, 'prueba', 'prueba 2', 'prueba12345@gmail.com', '$2y$10$OZpfEBIztmyZquIOc0U8iuK2Uz/TFFzmxJ5R0vzMhqCvR5yzIwzoO', '2026-01-27 15:07:14', 3),
(5, 'admin', 'admin1234', 'admin@gmail.com', '$2y$10$iZhkfZh55ZFqTFR5xx9CLOrdCvWWjDuHiBfIsYHNyGzJjQ.ozHQ3.', '2026-01-27 15:09:44', 1),
(7, 'paconi', 'porras', 'paco1234@gmail.com', '$2y$10$VnJPcfLC2LMILLxmu0nkpeE3sAhSLdO64epbuPfFyQ7XBgUiCa.FK', '2026-01-28 15:19:46', 4),
(8, 'Jose', 'Socuellamos', 'josesocuellamos1234@gmail.com', '$2y$10$iDynGtNlIbdWKSVjYPQAV.XT9FuFvb11p9PZZthXx1mXq6TccZ9ia', '2026-02-09 16:15:41', 2),
(9, 'Lola', 'Lolita', 'lolalolita@gmail.com', '$2y$10$SNhDNrSFKztQmf/cPTpfCOZ6o50tpcQ14/lJvDJpj9nr4o7qyppFy', '2026-02-09 16:16:28', 2),
(10, 'Nol', 'Ojeda', 'nilojeda@gmail.com', '$2y$10$s3CsZCa5DHm8/hDb90jj7.ysYCvBMj0IMP1knT9pe11MGzVLZQwTa', '2026-02-09 16:17:02', 4),
(11, 'Cristiano', 'Ronaldo', 'cristianoronaldo@gmail.com', '$2y$10$QRb1VYZxeYt9QQQF7elm5uEttzorU9EGbnqZFowbjr4p.KkrEMfJ.', '2026-02-09 16:17:35', 2),
(12, 'agustin51', 'titoagus', 'agustin51@gmail.com', '$2y$10$TUvcudNCq5kI60OxbW8EP.WgTkzKACyPWvWyRX6N5b.L6VUXT2/8G', '2026-02-09 16:19:40', 4),
(13, 'Illa', 'Topuria', 'illatopuria@gmail.com', '$2y$10$ywHVFgib5cxUbVV7EjsuIOFANEOLRuFpphpNEh/DqXqRIUP8XZgii', '2026-02-09 16:20:08', 2),
(14, 'Samuel', 'De Luque', 'vegetta777@gmail.com', '$2y$10$vQSRSJD8.raz3zCQOJyuXu6SufPp2w6I1efDCNf3IM2skfLBbhC3C', '2026-02-09 16:20:56', 3),
(15, 'deivi', 'under water', 'deivi@gmail.com', '$2y$10$AM7bV/XVpy74VaeuogYJmu12gQ9pNmutJOlAjJ4X0gtN7y2cWdsiG', '2026-02-09 16:21:32', 4),
(16, 'Eladio', 'Carrion', 'eladiocarrion@gmail.com', '$2y$10$SzS9H/OAsQgvCqeclqjrreSdmeKWNjlvdcejOmdLyK1p5oErfScc.', '2026-02-09 16:21:57', 2),
(17, 'Myke', 'Towers', 'myketowers@gmail.com', '$2y$10$yLO8tb3iKASdWRy00.vucu29/O.nzVMJPcp3aP8HtzlTeHhD2DOVG', '2026-02-09 16:22:25', 3),
(18, 'socio', 'socio1', 'socio1@gmail.com', '$2y$10$zL.4ybpPzGVCqVKJgfjnouYAKpb3ZMgmt1.5RsZDO/JnZCmDpmUaK', '2026-02-09 17:29:50', 4),
(19, 'socio', 'socio2', 'socio2@gmail.com', '$2y$10$DZDk/VJif0NBuFj4QDc.HegW5TfZC5aSdXdSbNr3aCox2Enwo3GsK', '2026-02-09 17:30:13', 4),
(20, 'socio', 'socio3', 'socio3@gamil.com', '$2y$10$k87.GAFqmnssibYJ3C1u6ejyn5lWY3Y5RMQ01DFX7agAyiDu6AmGy', '2026-02-09 17:30:36', 4),
(21, 'socio', 'socio4', 'socio4@gmail.com', '$2y$10$c3UvlBwfVI6Dc9Z6Q/M44OpF.hLrb7gLo6mjJvVc4fEFem2jBvV8q', '2026-02-09 17:30:58', 4),
(22, 'Leto', 'te respeto', 'leto1234@gmail.com', '$2y$12$0aamKl7Be1T6DHknWDoHnuer4QGUc6J66gKy4rYwSf4t2Wq2YDflC', '2026-02-11 08:26:08', 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clases`
--
ALTER TABLE `clases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_entrenador` (`id_entrenador`);

--
-- Indices de la tabla `lineas_pedidos`
--
ALTER TABLE `lineas_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_clase` (`id_clase`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `clases`
--
ALTER TABLE `clases`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `lineas_pedidos`
--
ALTER TABLE `lineas_pedidos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clases`
--
ALTER TABLE `clases`
  ADD CONSTRAINT `clases_ibfk_1` FOREIGN KEY (`id_entrenador`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_clase`) REFERENCES `clases` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
