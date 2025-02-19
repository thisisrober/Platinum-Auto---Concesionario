-- MySQL dump 10.13  Distrib 8.3.0, for Win64 (x86_64)
--
-- Host: localhost    Database: concesionario
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alquileres`
--

DROP TABLE IF EXISTS `alquileres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alquileres` (
  `id_alquiler` int unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int unsigned DEFAULT NULL,
  `id_coche` int unsigned DEFAULT NULL,
  `prestado` datetime DEFAULT NULL,
  `devuelto` datetime DEFAULT NULL,
  PRIMARY KEY (`id_alquiler`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alquileres`
--

LOCK TABLES `alquileres` WRITE;
/*!40000 ALTER TABLE `alquileres` DISABLE KEYS */;
INSERT INTO `alquileres` VALUES (9,1,11,'2025-02-11 09:09:03',NULL),(10,1,1,'2025-02-11 09:57:59','2025-02-11 10:14:09'),(13,1,12,'2025-02-19 12:46:53',NULL);
/*!40000 ALTER TABLE `alquileres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coches`
--

DROP TABLE IF EXISTS `coches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coches` (
  `id_coche` int unsigned NOT NULL AUTO_INCREMENT,
  `modelo` varchar(50) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `alquilado` tinyint(1) DEFAULT NULL,
  `foto` varchar(300) DEFAULT NULL,
  `id_vendedor` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id_coche`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coches`
--

LOCK TABLES `coches` WRITE;
/*!40000 ALTER TABLE `coches` DISABLE KEYS */;
INSERT INTO `coches` VALUES (1,'TT','Audi','Azul',8995,0,'/src/img/audi-tt.webp',3),(10,'Juke','Nissan','Blanco',4899,0,'src/img/uploads/descarga.jpeg',4),(11,'Serie 5 GT','BMW','Negro',12999,1,'src/img/uploads/imagen_2025-02-10_125652146.png',4),(12,'Serie 1','BMW','Blanco',18999,1,'src/img/uploads/BMW-M135-XDrive-2025-22.jpeg',3);
/*!40000 ALTER TABLE `coches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(100) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellidos` varchar(50) DEFAULT NULL,
  `dni` varchar(9) DEFAULT NULL,
  `saldo` float DEFAULT NULL,
  `tipo_usuario` enum('comprador','vendedor','admin') NOT NULL DEFAULT 'comprador',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'$2y$10$lllqBvIM5kkWMwnbci8AseQwsuHJYB2NWypvi13qkxdRa1T0bjuLS','Usuario','Comprador','00000000C',6102,'comprador'),(2,'$2y$10$lllqBvIM5kkWMwnbci8AseQwsuHJYB2NWypvi13qkxdRa1T0bjuLS','Usuario','Administrador','00000000A',0,'admin'),(3,'$2y$10$lllqBvIM5kkWMwnbci8AseQwsuHJYB2NWypvi13qkxdRa1T0bjuLS','Usuario','Vendedor','00000000V',0,'vendedor');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-19 13:54:37
