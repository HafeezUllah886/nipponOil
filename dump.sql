
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
DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounts` (
  `accountID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouseID` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `accountNumber` varchar(255) DEFAULT NULL,
  `initialBalance` double(8,2) unsigned DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `createdBy` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`accountID`),
  KEY `accounts_warehouseid_foreign` (`warehouseID`),
  CONSTRAINT `accounts_warehouseid_foreign` FOREIGN KEY (`warehouseID`) REFERENCES `warehouses` (`warehouseID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,1,'Walk-in Customer','customer',NULL,NULL,'Active','00',NULL,NULL,NULL,NULL,NULL,'System','2024-01-26 02:14:41','2024-01-26 02:14:41'),(2,1,'Cash','business','cash',NULL,'Active','01',NULL,NULL,NULL,NULL,NULL,'System','2024-01-26 02:14:41','2024-01-26 02:14:41'),(3,1,'ABC Bank','business','bank',NULL,'Active','02',NULL,NULL,NULL,NULL,NULL,'System','2024-01-26 02:14:41','2024-01-26 02:14:41'),(4,1,'ABC Customer','customer',NULL,NULL,'Active','04',NULL,NULL,NULL,NULL,NULL,'System','2024-01-26 02:14:41','2024-01-26 02:14:41'),(5,1,'ABC Supplier','supplier',NULL,NULL,'Active','03',NULL,NULL,NULL,NULL,NULL,'System','2024-01-26 02:14:41','2024-01-26 02:14:41');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;
