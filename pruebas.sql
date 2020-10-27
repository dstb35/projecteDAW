-- MySQL dump 10.13  Distrib 8.0.21, for Linux (x86_64)
--
-- Host: localhost    Database: pruebas
-- ------------------------------------------------------
-- Server version	8.0.21-0ubuntu0.20.04.4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Allergens`
--

DROP TABLE IF EXISTS `Allergens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Allergens` (
  `allergenId` int NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`allergenId`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Allergens`
--

LOCK TABLES `Allergens` WRITE;
/*!40000 ALTER TABLE `Allergens` DISABLE KEYS */;
INSERT INTO `Allergens` VALUES (44,'5f916c319c590.png','Soja'),(45,'5f91670c8affa.png','Huevos'),(46,'5f916712ca47b.png','Gluten'),(47,'5f91671c4c9df.png','Lácteos'),(48,'5f916740f324c.png','Pescado'),(49,'5f916bf4b8b71.png','Dióxido de azufre y sulfitos'),(50,'5f916c572a7d0.png','Cacahuetes'),(51,'5f916b259444d.png','Frutos de cáscara'),(52,'5f916b31559d6.png','Apio'),(53,'5f916b3ad8b15.png','Mostaza'),(54,'5f916b4915db7.png','Granos de sésamo'),(55,'5f916b6131d3d.png','Altramuces'),(56,'5f916b6b564b1.png','Moluscos'),(57,'5f916b87b9113.png','Crustáceos');
/*!40000 ALTER TABLE `Allergens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Employees`
--

DROP TABLE IF EXISTS `Employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Employees` (
  `employeeId` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `restaurantId` int NOT NULL,
  PRIMARY KEY (`employeeId`),
  KEY `restaurantId_idx` (`restaurantId`),
  CONSTRAINT `fk_restaurant_employee` FOREIGN KEY (`restaurantId`) REFERENCES `Restaurants` (`restaurantId`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Employees`
--

LOCK TABLES `Employees` WRITE;
/*!40000 ALTER TABLE `Employees` DISABLE KEYS */;
INSERT INTO `Employees` VALUES (1,'Pepe',1),(3,'Antonio',1),(4,'Daniel',1),(10,'Paco',5),(11,'Carlos',5);
/*!40000 ALTER TABLE `Employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `OrderLines`
--

DROP TABLE IF EXISTS `OrderLines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `OrderLines` (
  `orderlineId` int NOT NULL AUTO_INCREMENT,
  `quantity` int DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `orderId` int DEFAULT NULL,
  `productId` int DEFAULT NULL,
  PRIMARY KEY (`orderlineId`),
  KEY `fk_order_orderline_idx` (`orderId`),
  KEY `fk_product_orderline_idx` (`productId`),
  CONSTRAINT `FK_D149EA4E36799605` FOREIGN KEY (`productId`) REFERENCES `Products` (`productId`),
  CONSTRAINT `FK_D149EA4EFA237437` FOREIGN KEY (`orderId`) REFERENCES `Orders` (`orderId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OrderLines`
--

LOCK TABLES `OrderLines` WRITE;
/*!40000 ALTER TABLE `OrderLines` DISABLE KEYS */;
INSERT INTO `OrderLines` VALUES (1,NULL,NULL,NULL,NULL),(2,NULL,NULL,NULL,NULL),(4,1,15,4,47),(5,3,6,4,45),(6,3,6,4,48),(10,1,2,7,45),(17,9,18,12,45),(18,6,90,12,47),(19,9,18,13,45),(20,6,90,13,47),(21,9,18,14,45),(22,6,90,14,47);
/*!40000 ALTER TABLE `OrderLines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Orders`
--

DROP TABLE IF EXISTS `Orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Orders` (
  `orderId` int NOT NULL AUTO_INCREMENT,
  `total` float NOT NULL DEFAULT '0',
  `paid` tinyint NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tableId` int DEFAULT NULL,
  `employeeId` int DEFAULT NULL,
  `restaurantId` int DEFAULT NULL,
  `served` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderId`),
  KEY `fk_employee_order_idx` (`employeeId`),
  KEY `fk_table_order_idx` (`tableId`),
  KEY `fk_restaurant_order_idx` (`restaurantId`),
  CONSTRAINT `FK_E283F8D881DAF313` FOREIGN KEY (`restaurantId`) REFERENCES `Restaurants` (`restaurantId`),
  CONSTRAINT `FK_E283F8D8BDCE1676` FOREIGN KEY (`employeeId`) REFERENCES `Employees` (`employeeId`),
  CONSTRAINT `FK_E283F8D8F199C3E9` FOREIGN KEY (`tableId`) REFERENCES `Tables` (`tableId`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Orders`
--

LOCK TABLES `Orders` WRITE;
/*!40000 ALTER TABLE `Orders` DISABLE KEYS */;
INSERT INTO `Orders` VALUES (4,0,1,'2020-10-15 16:49:49',5,4,5,1),(7,2,1,'2020-10-15 17:36:25',5,3,5,0),(12,108,0,'2020-10-19 13:21:54',2,NULL,5,0),(13,108,0,'2020-10-19 13:21:54',2,NULL,5,0),(14,108,0,'2020-10-19 13:21:55',2,NULL,5,0);
/*!40000 ALTER TABLE `Orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Products`
--

DROP TABLE IF EXISTS `Products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Products` (
  `productId` int NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `price` float NOT NULL,
  `image` varchar(60) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `restaurantId` int DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`productId`),
  KEY `fk_restaurantId_product_idx` (`restaurantId`),
  CONSTRAINT `FK_4ACC380C81DAF313` FOREIGN KEY (`restaurantId`) REFERENCES `Restaurants` (`restaurantId`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Products`
--

LOCK TABLES `Products` WRITE;
/*!40000 ALTER TABLE `Products` DISABLE KEYS */;
INSERT INTO `Products` VALUES (37,'Macarrones Carbonara',12,'carbonara-5f7d84fc506d2.jpeg',NULL,37,1),(45,'Coca cola',2,'cocacola-5f7dd91813587.jpeg',NULL,5,1),(47,'Macarrones Carbonara',15,'1-carbonara-5f85bac6e0c9b.jpeg',NULL,5,1),(48,'Fanta',2,'1-fanta-5f85be7dbbf2f.jpeg',NULL,5,1),(50,'Nestea',2,'5-5f97f5dc872cf.jpeg','Nestea de Limón',5,0);
/*!40000 ALTER TABLE `Products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Restaurants`
--

DROP TABLE IF EXISTS `Restaurants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Restaurants` (
  `restaurantId` int NOT NULL AUTO_INCREMENT,
  `cif` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `password` varchar(60) NOT NULL,
  `address` varchar(60) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL,
  `phone` int NOT NULL,
  `manager` varchar(45) NOT NULL,
  `paid` tinyint NOT NULL DEFAULT '0',
  `paidDate` varchar(45) DEFAULT NULL,
  `roles` varchar(60) NOT NULL,
  `image` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`restaurantId`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Restaurants`
--

LOCK TABLES `Restaurants` WRITE;
/*!40000 ALTER TABLE `Restaurants` DISABLE KEYS */;
INSERT INTO `Restaurants` VALUES (1,'0000000000','Admin','$2y$04$uAuOPRPg6WHPtwVtliOc/uVDWZzqTCzEKxSG0emhkAZZzuvzHnsUa','----','2020-09-05 16:34:08','admin@gmail.com',0,'Daniel',1,NULL,'ROLE_ADMIN',NULL),(2,'123789','Restaurante Pepe','$2y$04$LqWsyvOfRJxMv70.xIt0IuM9HIP1AU5rJFTUVdkR1I2SwIKc/ivg2','BBB','2020-09-05 16:34:48','pepe@gmail.com',23123,'ASDQ',1,NULL,'ROLE_USER','5f8ec1f38aecb.jpeg'),(4,'7898','Casa Tarraco','$2y$04$.Jnqo4plwZ2AJOgmDZkKt.PkRgPlJReFbxEqQTsLb5bxpSu.nfY9O','C/ Tarragona S/N','2020-09-11 10:35:30','antonio@gmail.com',124,'Antonio',1,NULL,'ROLE_USER','5f8ec0e18c429.jpeg'),(5,'71823123','Casa Dani','$2y$04$oDO6pQYuZob8KSpd9yPWcu9y69JEOAjcS70rY5WUly5xTSBqtCYNu','C/ BarcelonaS/N','2020-09-11 10:38:03','dani@gmail.com',124,'Dani',1,NULL,'ROLE_USER','5f8ebfde7d4e6.jpeg'),(7,'71823123','Casa Zhana','$2y$04$G1Xnm7QCHcVtV7xs6BIAPOiHxT5OtJmmtlqugJ2fXN.6fnvcoKa.e','C/ Vilafranca S/N','2020-09-11 10:39:03','zhana@gmail.com',123123,'Zhana',1,NULL,'ROLE_USER','5f8ec1ab0644f.jpeg'),(8,'71823123','Casa Jordi','$2y$04$jlq1WNGkt/aaM5kv9u332.F6nrTTfGgJIW/..MMcV04Y.mTpyddCC','C/ hospitalet S/N','2020-09-11 10:42:34','jordi@gmail.com',123123,'Jordi',1,NULL,'ROLE_USER','5f8ebf91a5586.jpeg'),(11,'8908905','Casa Luís','$2y$04$CQOTw.ZbyBtxq.7g.gsE5eu/4y8FMD2Jfi.N.8BYl7uopoFudJwP.','C/ Terrassa S/N','2020-09-11 11:15:43','luis@gmail.com',89079,'Luis',1,NULL,'ROLE_USER','5f8ec1d282eab.jpeg'),(31,'123123','Casa Juan','$2y$04$buOot9Ix712swH.MLHEBC.xiI2sXr6XcC2ztNf7brjeLwgDE75jSq','Recogida en aeropuerto, por favor.','2020-09-15 10:39:13','juan2@hotmail.com',605076485,'123132',1,NULL,'ROLE_USER','5f8ec0b36a9cd.jpeg'),(34,'123123','Casa Juan','$2a$04$OluW4cvrSPpiliTrn7njx.9AU466hhF8gCzQbVECsfp5ZBO.qLkaG','Recogida en aeropuerto, por favor.','2020-09-15 10:39:45','juan@hotmail.com',605076485,'123132',1,NULL,'ROLE_USER','5f8ec10d42fb5.jpeg'),(35,'7898','Casa Tarraco','$2y$04$1zLv1I6N8FsFWwuEjTvv/euI175bxqlq7bnn6s8rb07aaZPmoczc6','Recogida en aeropuerto, por favor.','2020-09-15 10:54:15','juan3@gmail.com',605076485,'123',1,NULL,'ROLE_USER','5f8ec141e4e53.jpeg'),(36,'7898','Casa Tarraco','$2y$04$bflqU4s0gsyLQC3vqGqG2eVegoyq73IHSJnXdff0FIBYm84HJ9khK','Recogida en aeropuerto, por favor.','2020-09-15 10:54:31','juan4@gmail.com',605076485,'123',1,NULL,'ROLE_USER','5f8ec163edbba.jpeg'),(37,'12145464','Casa Tyrion','$2y$04$VnbSCkqMcjFhYBsHfv9Kv.aiiZPIwJPLREgD4EhC1OVe531sbwdrG','C/ Lannister','2020-10-07 14:20:33','tyrion@gmail.com',123123,'Tyrion',1,NULL,'ROLE_USER','5f8ec32b1d6da.jpeg'),(38,'3478293472834','Casa Jana','$2y$04$4pa8jQjBKWD/arMkQgTwdOpaQfywlXjxTkv61eKYGH1OwXfQoVHhy','c/ Sabadell S/N','2020-10-20 11:39:55','jana@gmail.com',12345,'Jana',1,NULL,'ROLE_USER','5f8ecc8bc3924.jpeg');
/*!40000 ALTER TABLE `Restaurants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tables`
--

DROP TABLE IF EXISTS `Tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Tables` (
  `tableId` int NOT NULL AUTO_INCREMENT,
  `restaurantId` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`tableId`),
  KEY `fk_restaurant_table_idx` (`restaurantId`),
  CONSTRAINT `FK_83EB071781DAF313` FOREIGN KEY (`restaurantId`) REFERENCES `Restaurants` (`restaurantId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tables`
--

LOCK TABLES `Tables` WRITE;
/*!40000 ALTER TABLE `Tables` DISABLE KEYS */;
INSERT INTO `Tables` VALUES (1,5,'Mesa roja modificada'),(2,5,'Mesa 1'),(3,5,'17'),(5,5,'Mesa nueva');
/*!40000 ALTER TABLE `Tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productcontains`
--

DROP TABLE IF EXISTS `productcontains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productcontains` (
  `productid` int NOT NULL,
  `allergenid` int NOT NULL,
  PRIMARY KEY (`productid`,`allergenid`),
  KEY `IDX_2B6B90B3A3FDB2A7` (`productid`),
  KEY `IDX_2B6B90B3E49B301D` (`allergenid`),
  CONSTRAINT `FK_2B6B90B3A3FDB2A7` FOREIGN KEY (`productid`) REFERENCES `Products` (`productId`),
  CONSTRAINT `FK_2B6B90B3E49B301D` FOREIGN KEY (`allergenid`) REFERENCES `Allergens` (`allergenId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productcontains`
--

LOCK TABLES `productcontains` WRITE;
/*!40000 ALTER TABLE `productcontains` DISABLE KEYS */;
INSERT INTO `productcontains` VALUES (50,45),(50,54);
/*!40000 ALTER TABLE `productcontains` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-27 12:29:17
