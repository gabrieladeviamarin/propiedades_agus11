-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3307
-- Tiempo de generación: 03-02-2026 a las 17:03:54
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
-- Base de datos: `agus_11`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contrato`
--

CREATE TABLE `contrato` (
  `nro_contrato` int(11) NOT NULL,
  `archivo_contrato` varchar(500) NOT NULL,
  `id_lugar` int(11) NOT NULL,
  `valor_contrato` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distrito`
--

CREATE TABLE `distrito` (
  `id_distrito` int(11) NOT NULL,
  `cod_distrito` varchar(50) NOT NULL,
  `nom_distrito` varchar(120) NOT NULL,
  `pastor` varchar(500) NOT NULL,
  `telefono_pr` bigint(11) NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `distrito`
--

INSERT INTO `distrito` (`id_distrito`, `cod_distrito`, `nom_distrito`, `pastor`, `telefono_pr`, `id_estado`) VALUES
(24, 'DTUS01', 'Chaparral', 'John Ebert Sanabria', 3233207117, 1),
(25, 'DTUS03', 'Espinal', 'Jander Rivas', 3144470140, 1),
(26, 'DTUS29', 'Filadelfia', 'Kreisler Gamboa', 3228905557, 1),
(27, 'DTUS04', 'Fusa Norte', 'Jhonatan Bravo', 3186659890, 1),
(28, 'DTUS05', 'Fusa Sur', 'Daniel Gutierrez', 3208368776, 1),
(29, 'DTUS08', 'Girardot', 'Oswaldo Prada', 3203063162, 1),
(30, 'DTUS14', 'Ibague 20 de Julio', 'Jhon Fredy Diaz', 3213672573, 1),
(31, 'DTUS11', 'Ibague Central', 'Ineduard Rueda', 3108861797, 1),
(32, 'DTUS06', 'Ibague Galán', 'Fabio Mejía', 3112311013, 1),
(33, 'DTUS07', 'Ibague Getsemaní', 'Ful Ruiz', 3133963615, 1),
(34, 'DTUS15', 'Ibague Jordán', 'Sergio Sana', 3208540929, 1),
(35, 'DTUS12', 'Ibague Norte', 'David Ramirez', 3176484806, 1),
(36, 'DTUS13', 'Ibague Oriente', 'Jesús Rodriguez', 3223050386, 1),
(37, 'DTUS19', 'Ibague Paraiso', 'Jesús Cubides', 3175730668, 1),
(38, 'DTUS02', 'La Dorada', 'Libardo Rivera', 3125618544, 1),
(39, 'DTUS27', 'Lerida', 'Johan Sebastian Delgado', 3125332578, 1),
(40, 'DTUS16', 'Líbano', 'Emilson Rivera', 3116743245, 1),
(41, 'DTUS17', 'Limón', 'Carlos Granados', 3228473790, 1),
(42, 'DTUS18', 'Mariquita', 'David Bedoya', 3144788557, 1),
(43, 'DTUS21', 'Planadas', 'Carlos Perche', 3103724863, 1),
(44, 'DTUS22', 'Suroccidente - Playarrica', 'Mario Ojito', 3145050517, 1),
(45, 'DTUS23', 'Rioblanco', 'Anderson Albarracin', 3108622493, 1),
(46, 'DTUS24', 'Santiago Pérez', 'Cristian Prada', 3133233122, 1),
(47, 'DTUS26', 'SurOriente - Villarrica', 'Edwin Quevedo', 3223054284, 1),
(48, 'DTUS25', 'Tequendama - La Mesa', 'German Guiza', 3103475647, 1),
(49, 'AGUS11', 'AGUS', 'Willard Cano Osorio', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escritura`
--

CREATE TABLE `escritura` (
  `nro_matricula` int(11) NOT NULL,
  `ficha_catastral` varchar(500) NOT NULL,
  `documento_pdf` varchar(300) NOT NULL,
  `valor` int(30) NOT NULL,
  `fecha_registro` date NOT NULL,
  `codigo_contable` varchar(500) NOT NULL,
  `valor_contable` int(11) NOT NULL,
  `valor_contable_lote` int(11) NOT NULL,
  `valor_contable_build` int(11) NOT NULL,
  `valor_avaluo` int(11) NOT NULL,
  `valor_avaluo_lote` int(11) NOT NULL,
  `valor_avaluo_build` int(11) NOT NULL,
  `id_lugar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `escritura`
--

INSERT INTO `escritura` (`nro_matricula`, `ficha_catastral`, `documento_pdf`, `valor`, `fecha_registro`, `codigo_contable`, `valor_contable`, `valor_contable_lote`, `valor_contable_build`, `valor_avaluo`, `valor_avaluo_lote`, `valor_avaluo_build`, `id_lugar`) VALUES
(3073, '01-04-0457-0008-000', '', 0, '2023-05-23', 'L0165', 100000000, 0, 0, 0, 0, 0, 1603),
(30731442, '01-04-0457-0008-000', '', 0, '2023-05-23', 'L0165', 100000000, 0, 0, 0, 0, 0, 1603),
(2147483647, '01-05-0161-0014-000', '', 0, '2019-12-20', '', 0, 0, 0, 0, 0, 0, 1592);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `nom_estado` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `nom_estado`) VALUES
(1, 'Activo'),
(2, 'Desactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propiedades`
--

CREATE TABLE `propiedades` (
  `id_lugar` int(11) NOT NULL,
  `cod_lugar` varchar(20) NOT NULL,
  `nom_lugar` varchar(200) NOT NULL,
  `direccion` varchar(500) NOT NULL,
  `id_tip_prop` int(11) DEFAULT NULL,
  `id_distrito` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `observacion` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `propiedades`
--

INSERT INTO `propiedades` (`id_lugar`, `cod_lugar`, `nom_lugar`, `direccion`, `id_tip_prop`, `id_distrito`, `id_estado`, `observacion`) VALUES
(1348, 'TUS007', 'Iglesia Emanuel Chaparral', 'Crr 3 # 03-18   -   B/Centro', 1, 24, 1, NULL),
(1349, 'TUS203', 'Iglesia luz en la montaña', 'Vereda San Jorge', 3, 24, 1, NULL),
(1350, 'TUS011', 'Iglesia Sarón  San Jorge Chaparral', 'Finca \"El Eden\" V/Da La Cimarrona', 1, 24, 1, NULL),
(1352, 'TUS025', 'Iglesia Altamira Espinal', 'Vereda Altamira', 3, 25, 1, NULL),
(1353, 'TUS031', 'Iglesia Chicoral Espinal', 'Trasversal 4#3-25  -  B/Villa Del Rosario', 1, 25, 1, NULL),
(1354, 'TUS201', 'Iglesia El Piñal Héroes de la Fe', 'Vereda El Piñal ', 3, 25, 1, NULL),
(1355, 'TUS026', 'Iglesia Emmanuel Espinal', 'Carrera 7  16-64  -  B/Santa Margarita Maria', 3, 25, 1, NULL),
(1356, 'TUS022', 'Iglesia Huellas de Jesus', 'Carrera 9B # 9-33  -   B/Bonanza', 1, 25, 1, NULL),
(1357, 'TUS028', 'Iglesia Jerico Montoso Espinal', 'Vereda Montoso', 1, 25, 1, NULL),
(1358, 'TUS027', 'Iglesia Jerusalem Espinal', 'Carrera 10  14-72   -  B/San Martin', 1, 25, 1, NULL),
(1359, 'TUS023', 'Iglesia Maranatha Natagaima', 'Calle 2 Con 4 Esq', 3, 25, 1, NULL),
(1360, 'TUS029', 'Iglesia Paraíso La Arenosa Espinal', 'Vereda La Arenosa', 1, 25, 1, NULL),
(1361, 'TUS030', 'Iglesia Purificación Espinal', 'Carrera 6  8-73  -  B/Antonio Nariño', 1, 25, 1, NULL),
(1362, 'TUS024', 'Iglesia Renacer Espinal', 'Barrio La Esperanza', 1, 25, 1, NULL),
(1364, 'TUS012', 'Iglesia Bethel Tetuancito', 'Vereda Tetuancito', 1, 26, 1, NULL),
(1365, 'TUS109', 'Iglesia Filadelfia', 'Kr 6 # 11-50 -  Barrio Versalles', NULL, 26, 1, NULL),
(1366, 'TUS010', 'Iglesia Jerusalem San Antonio', 'Cll 5 # 08-49  -   B/El Tesoro', NULL, 26, 1, NULL),
(1367, 'TUS198', 'Iglesia Orion', '', NULL, 26, 1, NULL),
(1368, 'TUS009', 'Iglesia Renacer Loma larga', 'Vereda Loma Larga', 1, 26, 1, NULL),
(1370, 'TUS035', 'Iglesia Central Fusa Norte', 'Calle 3  2-57  - Barrio Santander', 1, 27, 2, NULL),
(1371, 'TUS032', 'Iglesia La Cabaña Fusa Norte', 'Barrio La Cabaña', 1, 27, 2, NULL),
(1372, 'TUS033', 'Iglesia Luz en la Montaña', 'Vereda Quebrada Honda', 3, 27, 2, NULL),
(1373, 'TUS036', 'Iglesia Maranatha Silvania Fusa Norte', 'Cl 10 3-38   -  B/Centro', 1, 27, 2, NULL),
(1374, 'TUS038', 'Iglesia Subia Mensajeros Fusa Norte', 'Vereda De Subia Carbonera', 1, 27, 2, NULL),
(1375, 'TUS037', 'Iglesia Redencion Vida Sana Fusa Norte', 'Vida Sana', 3, 27, 2, NULL),
(1376, 'TUS039', 'Iglesia Canaán Guavio', 'Vereda Guavio Alto', 1, 28, 1, NULL),
(1377, 'TUS040', 'Iglesia Emanuel Venecia Fusa Sur', 'Lote 2 -  Buena Vista', 3, 28, 1, NULL),
(1378, 'TUS042', 'Iglesia Esmirna Icononzo Fusa Sur', 'Manzana A Lote 7  -  B/Los Almendros', 1, 28, 1, NULL),
(1379, 'TUS043', 'Iglesia Renacer FusaSur', 'Calle 22 # 3- 52  -  B/Fusacatan', 1, 28, 1, NULL),
(1380, 'TUS044', 'Iglesia Rios de Agua Viva Fusa Sur', 'Calle 23  #  40-08  -  B/Coviprof (15 Mayo)', 1, 28, 1, NULL),
(1381, 'TUS041', 'Iglesia Horeb San Bernando Fusa Sur', 'Calle 2 No 2-40 Barrio Ascencion', 1, 28, 1, NULL),
(1382, 'TUS200', 'Iglesia Agua de Dios', 'Carrera 2 # 20-64  -  Urbanización Primavera', 3, 29, 1, NULL),
(1383, 'TUS060', 'Iglesia Altar de Dios Girardot', 'Calle 14 No. 5-25  -  B/Alto De La Cruz', 3, 29, 1, NULL),
(1384, 'TUS055', 'Iglesia Barzaloza Girardot', 'Vereda Barzaloza', 1, 29, 1, NULL),
(1385, 'TUS057', 'Iglesia Central Girardot', 'Carrera 19 No.20-15   -  B/Barrio Las Quintas', 1, 29, 1, NULL),
(1386, 'TUS056', 'Iglesia Flandes Girardot', 'Carrera  9 No.10-75  -  B/Barrio Centro', 1, 29, 1, NULL),
(1387, 'TUS059', 'Iglesia Los Olivos Girardot', 'Manzana 75 Casa 14  -  B/Barrio Kennedy', 1, 29, 1, NULL),
(1388, 'TUS199', 'Iglesia Nariño Girardot', 'Manzana C Casa 3  -  B/Villas Del Magdalena', 1, 29, 1, NULL),
(1389, 'TUS058', 'Iglesia Roca EternaTocaima Girardot ', 'Calle 5 No.4 -45  -  B/Barrio Kennedy', 1, 29, 1, NULL),
(1390, 'TUS092', 'Iglesia Ammi Kataima Ibague 20 de Julio', 'Vereda Kataima', 3, 30, 1, NULL),
(1391, 'TUS088', 'Iglesia Aposento Alto Ibague 20 de Julio', 'Calle 16A  10-54', 1, 30, 1, NULL),
(1392, 'TUS090', 'Iglesia Honduras Ibague 20 de Julio', 'Vereda Honduras', 3, 30, 1, NULL),
(1393, 'TUS091', 'Iglesia Machin 20 de Julio', 'Vereda Machin', 3, 30, 1, NULL),
(1394, 'TUS093', 'Iglesia Nuevo Eden Ibague 20 de Julio', 'Finca El Brillante A La Entrada De Tapias', 1, 30, 1, NULL),
(1395, 'TUS089', 'Iglesia Santuario Ibague 20 de Julio', 'Vereda Cay 320 Mt2 Carretera Oriente Maria Garzon, Sur Isidoro Espinosa, Occid Fabio Espinosa', 1, 30, 1, NULL),
(1396, 'TUS070', 'Iglesia Central Ibague Central', 'Carrera 6  9-49 B/Centenario', 1, 31, 1, NULL),
(1397, 'TUS071', 'Iglesia La Roca Ibague Central', 'Estadero La Vara / Vereda La Coqueta', 2, 31, 1, NULL),
(1398, 'TUS207', 'Iglesia Nueva Vida Payande', 'Cra 4 No. 108 Local Payande', 2, 31, 1, NULL),
(1399, 'TUS049', 'Iglesia Esperanza Ibague Galán', 'Calle 9 Numero  3 - 41  B/Las Ferias', NULL, 32, 1, NULL),
(1400, 'TUS048', 'Iglesia La Victoria Ibague Galan', 'Calle 20 Sur Numero 28  - 32 B/Miramar', 1, 32, 1, NULL),
(1401, 'TUS045', 'Iglesia Los Andes Ibague Galan', 'Caserio Andes De Rovira / Caserio Andes De Rovira', 3, 32, 1, NULL),
(1402, 'TUS046', 'Iglesia Nueva Jerusalen Ibague Galán', 'Carrera 7 Sur Numero 13 - 45  B/Cerrogordo', 1, 32, 1, NULL),
(1403, 'TUS047', 'Iglesia Tercer Angel Ibague Galan', 'Mz 9 Cll 21 No 6A-21 Sur B/Galan', 1, 32, 1, NULL),
(1404, 'TUS050', 'Iglesia Emmanuel Ibague Getsemani', 'Calle 64  22-48 Ambalá  B/Ambalá', 1, 33, 1, NULL),
(1405, 'TUS051', 'Iglesia Filadelfia Ibague Getsemani', 'Carrera 10 Número 40-28  B/San Carlos', 1, 33, 1, NULL),
(1406, 'TUS052', 'Iglesia Getsemani Ibague Getsemani', 'Calle 37 A  11,A-47  B/Gaitan Parte Baja', 1, 33, 1, NULL),
(1407, 'TUS053', 'Iglesia La Hermosa Ibague Getsemani', 'Mza. C . Casa 4.  B/Vellavista', 2, 33, 1, NULL),
(1408, 'TUS054', 'Iglesia Yeled Ibague Getsemani', 'Carrera 10 # 40 - 28  B/San Carlos', 1, 33, 1, NULL),
(1409, 'TUS096', 'Iglesia Gualanday Ibague Jordan', 'Kilometro 2  Via Gualanday Espinal', 1, 34, 1, NULL),
(1410, 'TUS094', 'Iglesia Jordan Ibague Jordán', 'Manzana 3 Casa 12 Barrio Jordan 4 Etapa', 1, 34, 1, NULL),
(1411, 'TUS069', 'Iglesia Sion Ibague Jordan', 'Ciudadela Simon Bolivar', 4, 34, 1, NULL),
(1412, 'TUS095', 'Iglesia Topacio Ibague Jordan', 'Manzana 46 Casa 19  Barrio Topacio', 1, 34, 1, NULL),
(1413, 'TUS074', 'Iglesia Altos del Eden', 'Vereda China Alta', 3, 35, 1, NULL),
(1414, 'TUS135', 'Iglesia Canaan Gaviota', 'Carrera 20 # 94-07 - B/Gaviota', 1, 35, 1, NULL),
(1415, 'TUS078', 'Iglesia La Esperanza ibague norte', 'Calle 157 Con Carrera 8 Bis  B/Modelia 2', 1, 35, 1, NULL),
(1416, 'TUS077', 'Iglesia Lirio de los Valles Ibague Norte', 'Calle 12 Via Al Pais   B/La Placita', 1, 35, 1, NULL),
(1417, 'TUS079', 'Iglesia Maranatha Ibague Norte', 'Cra 14 # 126-284  B/Monte Carlo', 1, 35, 1, NULL),
(1418, 'TUS080', 'Iglesia Renacer Ibague Norte', 'Calle 145 # 11-36   B/El Salado', 1, 35, 1, NULL),
(1419, 'TUS084', 'Iglesia Jardín del Edén Ibague Oriente', 'Manzana 12 Casa 7  - B/Villa Del Sol', 1, 36, 1, NULL),
(1420, 'TUS085', 'Iglesia Jerusalem Ibague Oriente', 'Mz. 11  Cs. 9  - B/Protecho', 1, 36, 1, NULL),
(1421, 'TUS081', 'Iglesia La Arboleda Ibague Oriente', 'Salon Comunal, Conjunto Gualanday / Arboleda Campestre ', 3, 36, 1, NULL),
(1422, 'TUS082', 'Iglesia La Miel Ibague Oriente', 'Mz E Casa 2, Hacienda La Miel - Vereda Buenos Aires', 3, 36, 1, NULL),
(1423, 'TUS087', 'Iglesia Luz de Esperanza Ibague Oriente', 'Calle 129 N0 44Sur-95 - Picaleña Parte Alta', 1, 36, 1, NULL),
(1424, 'TUS075', 'Iglesia Eden Rodeo', 'Km 9 Via Rovira Finca La  Yerbabuena / Vereda El Rodeo', NULL, 36, 1, NULL),
(1425, 'TUS086', 'Iglesia Shalom Ibague Oriente', 'Spmz 11 Mz 6 Casa 4 - B/Las Americas', 1, 36, 1, NULL),
(1426, 'TUS206', 'Iglesia Calambeo', 'Carrera 9 #20-54 Barrio Calambeo', 3, 37, 1, NULL),
(1427, 'TUS130', 'Iglesia Luz de Libertad Ibague Paraiso', 'Carrera 3Bis Sur N23A - 115  -  B/Arado Bajo', 1, 37, 1, NULL),
(1428, 'TUS132', 'Iglesia Paraiso Ibague Paraiso', 'Carrera 4 Estadio N° 29-02   - B/Claret', 1, 37, 1, NULL),
(1429, 'TUS134', 'Iglesia Peniel', 'Calle 41 N° 4C -28  -  B/Macarena', 1, 37, 1, NULL),
(1430, 'TUS133', 'Iglesia Redención Ibague Paraiso', 'Carrera 3 N° 40 - 31  - B/La Castellana', 1, 37, 1, NULL),
(1432, 'TUS019', 'Iglesia Emanuel Pto Boyaca', 'Carrera 7 # 14-17 Puerto Boyacá   -   B/Centro', 1, 38, 1, NULL),
(1433, 'TUS014', 'Iglesia Emaus Dorada', 'Calle 13  10-34   -   B/San Antonio', 1, 38, 1, NULL),
(1434, 'TUS015', 'Iglesia La Fe Dorada', 'Vereda La Fe, Victoria, Caldas', 3, 38, 1, NULL),
(1435, 'TUS020', 'Iglesia Maranatha Dorada', 'Diagonal 16  6-28 Barrio Tres Esquinas', 1, 38, 1, NULL),
(1436, 'TUS016', 'Iglesia Monte de los Olivos', 'Corregimiento Pradera', 1, 38, 1, NULL),
(1437, 'TUS017', 'Iglesia Norcasia Dorada', '', 3, 38, 1, NULL),
(1438, 'TUS018', 'Iglesia Nueva Jerusalem Dorada', 'Calle 49 Con Carrera 14 Esquina  -   B/Victoria Real', 1, 38, 1, NULL),
(1439, 'TUS021', 'Iglesia Nuevo Amanecer Samaná', 'Carrera 4 #10-11    -  B/Centro', 1, 38, 1, NULL),
(1440, 'TUS075', 'Iglesia Anzoategui', 'Carrera 1Ra # 546  B/Ecuador', 3, 39, 1, NULL),
(1441, 'TUS073', 'Iglesia Bethel Venadillo', 'Carrera 7 # 10-07   B/Puerta Del Area', 1, 39, 1, NULL),
(1442, 'TUS072', 'Iglesia Canaan Malavar', 'Vereda Malavar', 3, 39, 1, NULL),
(1443, 'TUS076', 'Iglesia Junin', 'Corregimiento Junin', 1, 39, 1, NULL),
(1444, 'TUS100', 'Iglesia Renacer', 'Barrio Adra Ofasa, Frente Al Hospital', 1, 39, 1, NULL),
(1445, 'TUS062', 'Iglesia San Juan de Rio Seco', 'Carrera 6 #9-173 - B/El Llano', 1, 39, 1, NULL),
(1446, 'TUS119', 'Iglesia Genezareth Guayabal', 'Carrera 9, Calle 9 Esquina Barrio 7 De Agosto', 1, 39, 1, NULL),
(1447, 'TUS124', 'Iglesia Topacio Mariquita', 'K 1 110 33 Mz 46 Cs 19 Barrio Topacio', 1, 39, 1, NULL),
(1448, 'TUS097', 'Iglesia Bethel Libano', 'Vereda Tierradentro', 3, 40, 1, NULL),
(1449, 'TUS102', 'Iglesia Canaan', '', NULL, 40, 1, NULL),
(1450, 'TUS098', 'Iglesia Orion Convenio Libano', 'Lote 3 Guaquitas 2 De 133 Mts2', 1, 40, 1, NULL),
(1451, 'TUS099', 'Iglesia Emmanuel Libano', 'Calle 10 Numero 12-09  -  B/Pablo Vi', 1, 40, 1, NULL),
(1452, 'TUS104', 'Iglesia San Fernando Horeb Libano', 'Vereda San Fernando', 3, 40, 1, NULL),
(1453, 'TUS103', 'Iglesia Patio Bonito Jerusalen Libano', 'Vereda Patio Bonito', 1, 40, 1, NULL),
(1454, 'TUS106', 'Iglesia Sirpe Maranatha Libano', 'Vereda Sirpe', 1, 40, 1, NULL),
(1455, 'TUS105', 'Iglesia Santa Teresa Sarón Libano', 'Corregimiento Santa Teresa, Libano Tolima', 1, 40, 1, NULL),
(1456, 'TUS101', 'Iglesia Sinai Libano', 'Calle 5  4-23   -  B/San Antonio', 1, 40, 1, NULL),
(1457, 'TUS204', 'Iglesia Tiempo de Dios', 'Calle 3 # 9-36 Barrio La Esperanza', 1, 40, 1, NULL),
(1458, 'TUS210', 'Iglesia Remanente Villahermosa', 'Lote El Contento Villahermosa Tolima ', 1, 40, 1, NULL),
(1459, 'TUS116', 'Iglesia Bethel Limon', 'Vereda El Moral', 1, 41, 1, NULL),
(1460, 'TUS115', 'Iglesia Emanuel Limon', 'Vereda La Marina', NULL, 41, 1, NULL),
(1461, 'TUS110', 'Iglesia Jerusalen Limon', 'Vereda La Ilusión', 3, 41, 1, NULL),
(1462, 'TUS111', 'Iglesia Jordan La Glorieta Limon', 'Vereda Glorieta', 1, 41, 1, NULL),
(1463, 'TUS108', 'Iglesia La Cabaña Limon', 'Vereda La Cabaña', 1, 41, 1, NULL),
(1464, 'TUS113', 'Iglesia Linday Limon', 'Vereda Linday', 1, 41, 1, NULL),
(1465, 'TUS107', 'Iglesia El Bosque Loma Linda Limon', 'Vereda Loma Linda-El Bosque', 1, 41, 1, NULL),
(1466, 'TUS114', 'Iglesia Macedonia Limon', 'Vereda El Mezón', 3, 41, 1, NULL),
(1467, 'TUS117', 'Iglesia Nuevo Horizonte Limon', 'Vereda Horizonte La Ilusion', NULL, 41, 1, NULL),
(1468, 'TUS112', 'Iglesia Palestina Limón', 'El Limón', 1, 41, 1, NULL),
(1469, 'TUS118', 'Iglesia San Pablo Limon', 'Vereda San Pablo', 1, 41, 1, NULL),
(1470, 'TUS120', 'Iglesia Bethel Mariquita', 'Calle 18 Carrera 7 Casa 3 Barrio Pantano Grande', 1, 42, 1, NULL),
(1471, 'TUS125', 'Iglesia Central Mariquita', 'Carrera 4 No 3-21 Barrio Porvenir', NULL, 42, 1, NULL),
(1472, 'TUS122', 'Iglesia Emaus Fresno Mariquita', 'Vereda La Sierra Finca El Horizonte', 3, 42, 1, NULL),
(1473, 'TUS123', 'Iglesia Emanuel Falan Mariquita', 'Carrera 6 #41-0 Barrio Piscina', NULL, 42, 1, NULL),
(1474, 'TUS127', 'Iglesia Fresno Central', '', NULL, 42, 1, NULL),
(1475, 'TUS121', 'Iglesia Canaan La Libertad Mariquita', 'Vereda La Libertad', 3, 42, 1, NULL),
(1476, 'TUS129', 'Iglesia los peregrinos', 'Vereda Terama', NULL, 42, 1, NULL),
(1477, 'TUS126', 'Iglesia Miraflores Renacer Mariquita', 'Vereda Miraflores', 3, 42, 1, NULL),
(1478, 'TUS128', 'Iglesia Palocabildo Mariquita', 'Lote Vereda Paujil Palocabildo', 1, 42, 1, NULL),
(1479, 'TUS065', 'Iglesia Remanente Central Guaduas', 'Carrera 6 #4-54 Barrio Sanrander', 1, 42, 1, NULL),
(1480, 'TUS064', 'Iglesia Gerizin La Peña', 'Vereda Quebrada Honda', NULL, 42, 1, NULL),
(1481, 'TUS067', 'Iglesia La Palma Eden Guaduas', 'Vereda Palomar ', 1, 42, 1, NULL),
(1482, 'TUS066', 'Iglesia Maranatha Honda Guaduas', 'Carrera 12 A Nro 12-42 Piso 2 Antiguio Bch Barrio Centro', 1, 42, 1, NULL),
(1483, 'TUS063', 'Iglesia Salem Caparrapi', 'Vereda La Azauncha', NULL, 42, 1, NULL),
(1484, 'TUS068', 'Iglesia Sion Honda Guaduas', '', 1, 42, 1, NULL),
(1485, 'TUS061', 'Iglesia Yacopi Roca de los Peregrinos', 'Roca De Los Peregrinos -Verada Terama Yacopi-Cundin', 1, 42, 1, NULL),
(1486, 'TUS138', 'Iglesia Bethel Nazareno Planadas', 'Vereda Nazareno', 3, 43, 1, NULL),
(1487, 'TUS148', 'Iglesia Rubyenezer Planadas', 'Vereda El Rubí', 3, 43, 1, NULL),
(1488, 'TUS141', 'Iglesia Horeb Caicedonia Planadas', 'Vereda Caicedonia', 3, 43, 1, NULL),
(1489, 'TUS144', 'Iglesia Montalvo Jahve Nisi Planadas', 'Vereda Montalvo', NULL, 43, 1, NULL),
(1490, 'TUS136', 'Iglesia Los Angeles Planadas', 'Vereda El Higueron', 3, 43, 1, NULL),
(1491, 'TUS142', 'Iglesia Mahanaim Lirio Valles', 'Finca La Esperanza', NULL, 43, 1, NULL),
(1492, 'TUS143', 'Iglesia Maranatha La Cumbre Planadas', 'Vereda La Cumbre', 3, 43, 1, NULL),
(1493, 'TUS137', 'Iglesia Palestina Armenia Planadas', 'Vereda La Estrella', 3, 43, 1, NULL),
(1494, 'TUS140', 'Iglesia Gaitana Peniel Planadas', 'Barrio La Esperanza', 1, 43, 1, NULL),
(1495, 'TUS146', 'Iglesia Primavera Planadas', 'Vereda Primavera', 3, 43, 1, NULL),
(1496, 'TUS205', 'Iglesia Remanente La Aldea', '', NULL, 43, 1, NULL),
(1497, 'TUS147', 'Iglesia Rosa de Saron Planadas', 'Vereda La Orquidea', 3, 43, 1, NULL),
(1498, 'TUS139', 'Iglesia Cañofisto Shalom Planadas', '', 3, 43, 1, NULL),
(1499, 'TUS145', 'Iglesia Sion Planadas', 'Carrera 5 # 8-44  Centro (Planadas)', 1, 43, 1, NULL),
(1500, 'TUS149', 'Iglesia Chili La Selva', 'Chili La Selva - Lotes 1 Y 2, Rovira, Tolima', 1, 44, 1, NULL),
(1501, 'TUS150', 'Iglesia Efeso La Popa', '', 3, 44, 1, NULL),
(1502, 'TUS155', 'Iglesia Rovira Esmirna', 'Lote Calle 7 5-79 Urbana Rovira Tolima', 1, 44, 1, NULL),
(1503, 'TUS151', 'Iglesia Filadelfia Roncesvalle Playarrica', 'Cra 1 5-76 Esquina Roncesvalles ', 3, 44, 1, NULL),
(1504, 'TUS152', 'Iglesia Horeb Playarrica', '', 3, 44, 1, NULL),
(1505, 'TUS153', 'Iglesia Monte de los Olivos playarrica', '', 1, 44, 1, NULL),
(1506, 'TUS154', 'Iglesia Riomanso', '', 1, 44, 1, NULL),
(1507, 'TUS156', 'Iglesia Sardis', '', 1, 44, 1, NULL),
(1508, 'TUS208', 'Iglesia Betania Vereda la Llaneta', '', NULL, 45, 1, NULL),
(1509, 'TUS188', 'Iglesia Eden Alpes Rioblanco', '', NULL, 45, 1, NULL),
(1510, 'TUS158', 'Iglesia Emmanuel Palmichal Rioblanco', '', 3, 45, 1, NULL),
(1511, 'TUS165', 'Iglesia Getsemani Quebrada Rioblanco', 'Vereda La Gallera, Rioblanco', 3, 45, 1, NULL),
(1512, 'TUS157', 'Iglesia Bilbao Jerusalem Rioblanco', '', 1, 45, 1, NULL),
(1513, 'TUS159', 'Iglesia La Estrella Rioblanco', '', NULL, 45, 1, NULL),
(1514, 'TUS162', 'Iglesia Las Juntas', '', 3, 45, 1, NULL),
(1515, 'TUS164', 'Iglesia Nueva Esperanza Rioblanco', '', 3, 45, 1, NULL),
(1516, 'TUS167', 'Iglesia Orion Rioblanco', 'Carretera Chaparral-Rioblanco', 1, 45, 1, NULL),
(1517, 'TUS163', 'Iglesia Pleyades Naranja Rioblanco', '', 1, 45, 1, NULL),
(1518, 'TUS166', 'Iglesia Renacer Rioblanco', '', 3, 45, 1, NULL),
(1519, 'TUS168', 'Iglesia Shaday el Bosque Rioblanco', '', 3, 45, 1, NULL),
(1520, 'TUS160', 'Iglesia Sinai Gaitan Rioblanco', '', NULL, 45, 1, NULL),
(1521, 'TUS169', 'Iglesia Valdenses', '', 3, 45, 1, NULL),
(1522, 'TUS176', 'Iglesia Bethel Pomarroso Santiago Perez', 'Vereda Pomarroso', NULL, 46, 1, NULL),
(1523, 'TUS174', 'Iglesia Esperanza Santiago Pérez', 'Vereda Mesitas', 3, 46, 1, NULL),
(1524, 'TUS172', 'Iglesia Jordan Santiago Pérez', '', NULL, 46, 1, NULL),
(1525, 'TUS175', 'Iglesia Monteloro Saron Santiago Perez', 'Inspeccion Monteloro, Municipio Ataco Vereda Monteloro', 3, 46, 1, NULL),
(1526, 'TUS170', 'Iglesia Nuevo Amanecer Ataco Santiago Perez', 'Cra 2 # 5-72 -  Barrio Las Brisas', 3, 46, 1, NULL),
(1527, 'TUS173', 'Iglesia Renacer Casa Zinc Santiago Perez', 'Caserio Casa De Zinc - Vereda Casa De Zinc', 3, 46, 1, NULL),
(1528, 'TUS171', 'Iglesia Sinai Santiago Perez', 'Local A La Entrada De Santiago Pérez - Inspección Santiago Pérez', 3, 46, 1, NULL),
(1529, 'TUS188', 'Iglesia Eden los Alpes', 'Vereda Los Alpes, Villarica', NULL, 47, 1, NULL),
(1530, 'TUS189', 'Iglesia Maranatha SurOriente', 'Vereda Las Catorce ', 3, 47, 1, NULL),
(1531, 'TUS190', 'Iglesia Getsemani Melgar SurOriente', 'Cra. 36 #4-35 - Sicomoro', 1, 47, 1, NULL),
(1532, 'TUS194', 'Iglesia Orion Tres Esquinas', 'Lote Finca Villa Jobita, Tres Esq. Cunday', 1, 47, 1, NULL),
(1533, 'TUS192', 'Iglesia Renacer SurOriente', 'Vereda Paticuinde ', 3, 47, 1, NULL),
(1534, 'TUS193', 'Iglesia Santa Helena', 'Vereda Alto Moscu La Aurora', 1, 47, 1, NULL),
(1535, 'TUS191', 'Iglesia Mercadilla Sinaí', 'Vereda La Mercadilla', 3, 47, 1, NULL),
(1536, 'TUS195', 'Iglesia Villarrica SurOriente', 'Carrera 5  3-36 Villarrica', 1, 47, 1, NULL),
(1537, 'TUS177', 'Iglesia Anapoima Tequendama', 'Carrera 1 No. 1- 31 Centro', 3, 48, 1, NULL),
(1538, 'TUS178', 'Iglesia Bajo Argentina Tequendama', 'Vereda Bajo Argentina', 3, 48, 1, NULL),
(1539, 'TUS196', 'Iglesia Bethel Anolaima', 'Barrio El Paraiso Sector Planta Vieja', 3, 48, 1, NULL),
(1540, 'TUS181', 'Iglesia Ceilan Tequendama', 'Vereda Las Palmas', 1, 48, 1, NULL),
(1541, 'TUS183', 'Iglesia La Mesa Tequendama', 'Carrera 14 No 5-62  Santa Barbara', 1, 48, 1, NULL),
(1542, 'TUS180', 'Iglesia La Roca Tequendama', 'Vereda El Rosario Sector La Pala', 1, 48, 1, NULL),
(1543, 'TUS209', 'Iglesia La Victoria Tequendama', 'Cr. 1A 5-65 Vereda La Victoria', 1, 48, 1, NULL),
(1544, 'TUS184', 'Iglesia Manantial Tequendama', 'Vereda El Rosario Sector Villa Herrera', 1, 48, 1, NULL),
(1545, 'TUS197', 'Iglesia Maranatha Puerto Brazil', 'Vereda Lagunas Puerto Brazil', 3, 48, 1, NULL),
(1546, 'TUS182', 'Iglesia Emmanuel Mesitas Colegio', 'Lote 12 Mz. 2 B/ Galima ', 1, 48, 1, NULL),
(1547, 'TUS186', 'Iglesia Renacer Tequendama', 'Vereda San Antonio', NULL, 48, 1, NULL),
(1548, 'TUS179', 'Iglesia Sion Cachipay', 'Carrera 3  1-50 Centro', 1, 48, 1, NULL),
(1549, 'TUS187', 'Iglesia Viota Getsemani Tequendama', 'Barrio El Progreso', 1, 48, 1, NULL),
(1570, 'TUS003', 'Iglesia Buenos Aires Chaparral', 'Vereda Santa Barbara\r\n', 1, 24, 1, NULL),
(1571, 'TUS005', 'Iglesia Canaan Chaparral\r\n', 'Vereda Escobal', 3, 24, 1, NULL),
(1572, 'TUS006', 'Iglesia Central Chaparral', 'Calle 7  9-74  -  B/Libertador', 1, 24, 1, NULL),
(1582, 'TUS002', 'Iglesia Shalom Argentina Chaparral', 'Finca \"El Congo\" V/Da Argentina Rionegro', 1, 24, 1, NULL),
(1583, 'TUS001', 'Iglesia Adriel Potrerito\r\n', 'Vereda Potrerito Alto', 3, 26, 1, ' '),
(1584, 'TUS004', 'Iglesia Sion Calarma', 'Vereda  Calarma', 3, 26, 1, NULL),
(1585, 'TUS035', 'Iglesia Central Fusa Norte', 'Calle 3  2-57  - Barrio Santander', 1, 27, 2, NULL),
(1586, 'TUS032', 'Iglesia La Cabaña Fusa Norte', 'Barrio La Cabaña', 1, 27, 2, NULL),
(1587, 'TUS033', 'Iglesia Luz en la Montaña', 'Vereda Quebrada Honda', 3, 27, 2, NULL),
(1588, 'TUS036', 'Iglesia Maranatha Silvania Fusa Norte', 'Cl 10 3-38 - B/Centro', 1, 27, 2, NULL),
(1589, 'TUS038', 'Iglesia Subia Mensajeros Fusa Norte', 'Vereda De Subia Carbonera', 1, 27, 2, NULL),
(1590, 'TUS037', 'Iglesia Redencion Vida Sana Fusa Norte', 'Vida Sana', 3, 27, 2, NULL),
(1591, 'TUS013', 'Iglesia Bethel Dorada', 'Cra 6 # 15 - 104\r\n', 1, 38, 1, NULL),
(1592, 'AGUS11', 'Edificio Sede Asociacion Oficinas\r\n', 'Cra 3 No. 40-45 la Castellana', 1, 49, 1, ''),
(1593, 'SLERID01', 'Colegio Escuela Lerida', 'Lotes Aledaños', 1, 49, 1, NULL),
(1594, 'SLERID01', 'Casa Maestros Escuela Lerida\r\n', 'Cr. 14 1A-05 Sur B/ Adra-Ofasa\r\n', 1, 49, 1, NULL),
(1595, 'PRIOBL01', 'Casa Pastoral Rioblanco\r\n', 'Rioblanco, Tolima', 2, 49, 1, NULL),
(1596, 'PLERID01', 'Casa Pastoral Lerida\r\n', 'Sector 3 Mz. 5 Lote 1 Lérida, Tolima', 1, 49, 1, NULL),
(1597, 'PESPIN01', 'Casa Pastoral Espinal\r\n', 'Casa Lote 16 Manzana I urbanización Arkabal-Espinal\r\n', 1, 49, 1, NULL),
(1598, 'SIBAGU01', 'Casa Pastoral Colegio Ibague\r\n', 'C. Simón Bolivar Mz.41\r\n', 4, 49, 1, NULL),
(1599, 'SIBAGU01', 'Centro Medico Colegio Ibague\r\n', 'C. Simón Bolivar Mz.41\r\n', 4, 49, 1, NULL),
(1600, 'SIBAGU01', 'Centro Medico Adra Ofasa Ibague\r\n', 'C. Simón Bolivar Mz.41\r\n', 4, 49, 1, NULL),
(1601, 'FASURC01', 'Lote 1 Finca Asurcol - Sede Campestre\r\n', 'La Colmena Vereda Martinez Rovira Tolima\r\n', 1, 49, 1, NULL),
(1602, 'FASURC01', 'Lote 2 Finca Asurcol - Vida Sana Y Parqueadero\r\n', 'La Colmena Vereda Martinez Rovira Tolima\r\n', 1, 49, 1, NULL),
(1603, 'AGUS11', 'Casa Triunfo Donación\r\n', 'Cra 3 No. 40-45 la Castellana', 1, 49, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nom_rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nom_rol`) VALUES
(1, 'Administrador'),
(2, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguros`
--

CREATE TABLE `seguros` (
  `id_seguro_` int(11) NOT NULL,
  `tiene_seguro` int(11) NOT NULL,
  `precio` int(255) NOT NULL,
  `id_lugar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seguros`
--

INSERT INTO `seguros` (`id_seguro_`, `tiene_seguro`, `precio`, `id_lugar`) VALUES
(0, 1, 947367000, 1592),
(0, 2, 0, 1603);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_propiedad`
--

CREATE TABLE `tipo_propiedad` (
  `id_tip_prop` int(11) NOT NULL,
  `nom_tipo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_propiedad`
--

INSERT INTO `tipo_propiedad` (`id_tip_prop`, `nom_tipo`) VALUES
(1, 'Propia'),
(2, 'Alquilada'),
(3, 'Prestamo'),
(4, 'Convenio'),
(5, 'Compraventa'),
(6, 'Posesion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_documento` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(5000) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_documento`, `nombre`, `email`, `password`, `id_rol`, `id_estado`) VALUES
(1106227432, 'Gabriela Devia Marin', 'gabrieladeviamarin@gmail.com', '$2y$10$/VR5iyF1JD1AEBswHDHs5elp2/1yzwq6x/bCAiH3aKErP.lA3jZAW', 1, 1),
(1110511876, 'Heidy Lilian Sanchez', 'auxiliarcontable@asurcol.org', '$2y$10$ACw7G7wpN70cF2S2Fc4epubA7yH8Q/OfqWRNZLy8E.hpdtkL.9tOK', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`nro_contrato`),
  ADD KEY `lugar` (`id_lugar`) USING BTREE;

--
-- Indices de la tabla `distrito`
--
ALTER TABLE `distrito`
  ADD PRIMARY KEY (`id_distrito`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `escritura`
--
ALTER TABLE `escritura`
  ADD PRIMARY KEY (`nro_matricula`),
  ADD KEY `lugar` (`id_lugar`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `propiedades`
--
ALTER TABLE `propiedades`
  ADD PRIMARY KEY (`id_lugar`),
  ADD KEY `distrito` (`id_distrito`),
  ADD KEY `tipo_prop` (`id_tip_prop`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `seguros`
--
ALTER TABLE `seguros`
  ADD KEY `seguro_propiedades` (`id_lugar`);

--
-- Indices de la tabla `tipo_propiedad`
--
ALTER TABLE `tipo_propiedad`
  ADD PRIMARY KEY (`id_tip_prop`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `estado` (`id_estado`),
  ADD KEY `rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `distrito`
--
ALTER TABLE `distrito`
  MODIFY `id_distrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `propiedades`
--
ALTER TABLE `propiedades`
  MODIFY `id_lugar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1604;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_propiedad`
--
ALTER TABLE `tipo_propiedad`
  MODIFY `id_tip_prop` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `contrato`
--
ALTER TABLE `contrato`
  ADD CONSTRAINT `fk_contrato_lugar` FOREIGN KEY (`id_lugar`) REFERENCES `propiedades` (`id_lugar`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `distrito`
--
ALTER TABLE `distrito`
  ADD CONSTRAINT `id_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `escritura`
--
ALTER TABLE `escritura`
  ADD CONSTRAINT `lugar` FOREIGN KEY (`id_lugar`) REFERENCES `propiedades` (`id_lugar`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `propiedades`
--
ALTER TABLE `propiedades`
  ADD CONSTRAINT `distrito` FOREIGN KEY (`id_distrito`) REFERENCES `distrito` (`id_distrito`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tipo_prop` FOREIGN KEY (`id_tip_prop`) REFERENCES `tipo_propiedad` (`id_tip_prop`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `seguros`
--
ALTER TABLE `seguros`
  ADD CONSTRAINT `seguro_propiedades` FOREIGN KEY (`id_lugar`) REFERENCES `propiedades` (`id_lugar`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
