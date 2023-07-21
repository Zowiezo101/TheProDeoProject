CREATE DATABASE  IF NOT EXISTS `bible` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `bible`;
-- MySQL dump 10.13  Distrib 8.0.28, for Win64 (x86_64)
--
-- Host: localhost    Database: bible
-- ------------------------------------------------------
-- Server version	8.0.28

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
-- Table structure for table `activitys`
--

DROP TABLE IF EXISTS `activitys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activitys` (
  `order_id` int NOT NULL COMMENT 'The order we want the activities to be viewed in. This is used in the textfiles for the DatabaseHelper and on the website',
  `id` int NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `descr` text COLLATE utf8mb4_general_ci,
  `length` text COLLATE utf8mb4_general_ci,
  `date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT 'Doesn''t have to be an exact date, can also be a reference to another major event.',
  `level` int DEFAULT '1' COMMENT 'Level is related to timelines and only used there. A level 1 activity is part of the timeline of an event. A level 2 activity is part of the timeline of another activity (sub timeline)',
  `book_start_id` int DEFAULT NULL COMMENT 'Bible book of the first appearance',
  `book_start_chap` int DEFAULT NULL COMMENT 'Bible chapter of the first appearance',
  `book_start_vers` int DEFAULT NULL COMMENT 'Bible vers of the first appearance',
  `book_end_id` int DEFAULT NULL COMMENT 'Bible book of the end appearance',
  `book_end_chap` int DEFAULT NULL COMMENT 'Bible chapter of the last appearance',
  `book_end_vers` int DEFAULT NULL COMMENT 'Bible vers of the last appearance',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Activities are all the acts/events happening in a single event itself. Basically breaking an event down even further into smaller parts with more detail. Where events form the timeline, activities form the event.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activitys`
--

LOCK TABLES `activitys` WRITE;
/*!40000 ALTER TABLE `activitys` DISABLE KEYS */;
INSERT INTO `activitys` VALUES (1,1,'God schiep de Aarde','','','',1,1,1,1,1,1,1),(2,2,'De eerste dag','','dag','',1,1,1,1,1,1,5),(3,3,'De aarde was nog woest en doods','','','',2,1,1,2,1,1,2),(4,4,'Duisternis lag over de oervloed','','','',2,1,1,2,1,1,2),(5,5,'Gods geest zweefde over het water','','','',2,1,1,2,1,1,2),(6,6,'God sprak','','','',2,1,1,3,1,1,3),(7,7,'Er was licht','','','',2,1,1,3,1,1,3),(8,8,'God zag dat het licht goed was','','','',2,1,1,4,1,1,4),(9,9,'Hij scheidde het licht van de duisternis','','','',2,1,1,4,1,1,4),(10,10,'Het licht noemde hij dag','','','',2,1,1,5,1,1,5),(11,11,'De duisternis noemde hij nacht','','','',2,1,1,5,1,1,5),(12,12,'Het werd avond','','','',2,1,1,5,1,1,5),(13,13,'De tweede dag','','dag','',1,1,1,5,1,1,8),(14,14,'Het werd morgen','','','',2,1,1,5,1,1,5),(15,15,'God sprak','','','',2,1,1,6,1,1,6),(16,16,'God maakte het gewelf','','','',2,1,1,7,1,1,7),(17,17,'God scheidde het water onder het gewelf van het water erboven','','','',2,1,1,7,1,1,7),(18,18,'Hij noemde het gewelf hemel','','','',2,1,1,8,1,1,8),(19,19,'Het werd avond','','','',2,1,1,8,1,1,8),(20,20,'De derde dag','','dag','',1,1,1,8,1,1,13),(21,21,'Het werd morgen','','','',2,1,1,8,1,1,8),(22,22,'God sprak','','','',2,1,1,9,1,1,9),(23,23,'Het water onder de hemel stroomde naar één plaats','','','',2,1,1,9,1,1,9),(24,24,'Er ontstond droog land','','','',2,1,1,9,1,1,9),(25,25,'Het droge noemde hij aarde','','','',2,1,1,10,1,1,10),(26,26,'Het samengestroomde water noemde hij zee','','','',2,1,1,10,1,1,10),(27,27,'God zag dat het goed was','','','',2,1,1,10,1,1,10),(28,28,'God sprak','','','',2,1,1,11,1,1,11),(29,29,'De aarde bracht jong groen voort','','','',2,1,1,12,1,1,12),(30,30,'Er groeide allerlei zaadvormende planten','','','',2,1,1,12,1,1,12),(31,31,'Er groeide allerlei bomen die vruchten droegen met zaad erin','','','',2,1,1,12,1,1,12),(32,32,'God zag dat het goed was','','','',2,1,1,12,1,1,12),(33,33,'Het werd avond','','','',2,1,1,13,1,1,13),(34,34,'De vierde dag','','dag','',1,1,1,13,1,1,19),(35,35,'Het werd morgen','','','',2,1,1,13,1,1,13),(36,36,'God sprak','','','',2,1,1,14,1,1,14),(37,37,'God maakte de zon en de maan','','','',2,1,1,16,1,1,16),(38,38,'God maakte de sterren','','','',2,1,1,16,1,1,16),(39,39,'Hij plaatste ze aan het hemelgewelf','','','',2,1,1,17,1,1,17),(40,40,'Ze moesten licht te geven op de aarde','','','',2,1,1,17,1,1,17),(41,41,'Ze moesten heersen over de dag en de nacht','','','',2,1,1,17,1,1,17),(42,42,'Ze moesten het licht te scheiden van de duisternis','','','',2,1,1,17,1,1,17),(43,43,'God zag dat het goed was','','','',2,1,1,18,1,1,18),(44,44,'Het werd avond','','','',2,1,1,19,1,1,19),(45,45,'De vijfde dag','','dag','',1,1,1,19,1,1,23),(46,46,'Het werd morgen','','','',2,1,1,19,1,1,19),(47,47,'God sprak','','','',2,1,1,20,1,1,20),(48,48,'Hij schiep de grote zeemonsters','','','',2,1,1,21,1,1,21),(49,49,'Hij schiep alle soorten levende wezens waarvan het water wemelt en krioelt','','','',2,1,1,21,1,1,21),(50,50,'Hij schiep alles wat vleugels heeft','','','',2,1,1,21,1,1,21),(51,51,'God zag dat het goed was','','','',2,1,1,21,1,1,21),(52,52,'God zegende wat vleugels heeft','','','',2,1,1,22,1,1,22),(53,53,'God zegende wat in het water leeft','','','',2,1,1,22,1,1,22),(54,54,'Het werd avond','','','',2,1,1,23,1,1,23),(55,55,'De zesde dag','','dag','',1,1,1,23,1,1,31),(56,56,'Het werd morgen','','','',2,1,1,23,1,1,23),(57,57,'God sprak','','','',2,1,1,24,1,1,24),(58,58,'God maakte alle soorten in het wild levende dieren','','','',2,1,1,25,1,1,25),(59,59,'God maakte al het ​vee','','','',2,1,1,25,1,1,25),(60,60,'God maakte alles wat op de aardbodem rondkruipt','','','',2,1,1,25,1,1,25),(61,61,'God zag dat het goed was','','','',2,1,1,25,1,1,25),(62,62,'God sprak','','','',2,1,1,26,1,1,26),(63,63,'God schiep de mens als zijn evenbeeld','','','',2,1,1,27,1,1,27),(64,64,'Als evenbeeld van God schiep hij hem','','','',2,1,1,27,1,1,27),(65,65,'Mannelijk en vrouwelijk schiep hij de mensen','','','',2,1,1,27,1,1,27),(66,66,'Hij zegende hen','','','',2,1,1,28,1,1,28),(67,67,'Hij sprak tot hen','','','',2,1,1,28,1,1,28),(68,68,'God gaf hen alle zaaddragende planten','','','',2,1,1,29,1,1,29),(69,69,'God gaf hen alle vruchtbomen op de aarde','','','',2,1,1,29,1,1,29),(70,70,'Dat zal hun voedsel zijn','','','',2,1,1,29,1,1,29),(71,71,'Aan de wilde dieren gaf God alle groene planten tot voedsel','','','',2,1,1,30,1,1,30),(72,72,'Aan de vogels gaf God alle groene planten tot voedsel','','','',2,1,1,30,1,1,30),(73,73,'Aan de kruipende dieren gaf God alle groene planten tot voedsel','','','',2,1,1,30,1,1,30),(74,74,'God keek naar alles wat hij had gemaakt','','','',2,1,1,31,1,1,31),(75,75,'God zag dat het zeer goed was','','','',2,1,1,31,1,1,31),(76,76,'Het werd avond','','','',2,1,1,31,1,1,31),(77,77,'De zevende dag','','dag','',1,1,1,31,1,2,3),(78,78,'Het werd morgen','','','',2,1,1,31,1,1,31),(79,79,'De hemel was in al zijn rijkdom voltooid.','','','',2,1,2,1,1,2,1),(80,80,'Zo aarde was in al zijn rijkdom voltooid.','','','',2,1,2,1,1,2,1),(81,81,'God had zijn werk voltooid.','','','Op de zevende dag',2,1,2,2,1,2,2),(82,82,'Hij rustte van het werk dat hij gedaan had.','','','Op de zevende dag',2,1,2,2,1,2,2),(83,83,'God zegende de zevende dag.','','','',2,1,2,3,1,2,3),(84,84,'God verklaarde de zevende dag ​heilig.','','','',2,1,2,3,1,2,3),(85,85,'Dit is de geschiedenis van de hemel en de aarde.','','','',1,1,2,4,1,2,4),(86,86,'Zo ontstonden ze.','','','',1,1,2,4,1,2,4),(87,87,'Zo werden ze geschapen.','','','',1,1,2,4,1,2,4),(88,88,'Er groeide op de aarde nog geen enkele struik','','','',1,1,2,5,1,2,5),(89,89,'Er was geen enkele plant opgeschoten','','','',1,1,2,5,1,2,5),(90,90,'Het had nog niet geregend','','','',1,1,2,5,1,2,5),(91,91,'Er waren geen mensen om het land te bewerken','','','',1,1,2,5,1,2,5),(92,92,'Er was water dat uit de aarde opwelde.','','','',2,1,2,6,1,2,6),(93,93,'Het bevloeide de aardbodem overal.','','','',2,1,2,6,1,2,6),(94,94,'God maakte de mens.','','','',1,1,2,7,1,2,7),(95,95,'Hij vormde hem uit stof','','','',2,1,2,7,1,2,7),(96,96,'Hij vormde hem uit aarde.','','','',2,1,2,7,1,2,7),(97,97,'Hij blies hem levensadem in de neus.','','','',2,1,2,7,1,2,7),(98,98,'De mens werd een levend wezen.','','','',2,1,2,7,1,2,7),(99,99,'God legde een tuin aan.','','','',1,1,2,8,1,2,8),(100,100,'De bomen zagen aanlokkelijk uit.','','','',2,1,2,9,1,2,9),(101,101,'De bomen hadden heerlijke vruchten.','','','',2,1,2,9,1,2,9),(102,102,'In het midden van de tuin stonden de levensboom en de boom van de kennis van goed en kwaad.','','','',2,1,2,9,1,2,9),(103,103,'Er ontspringt in ​Eden​ een rivier.','','','',2,1,2,10,1,2,10),(104,104,'De rivier bevloeit de tuin.','','','',2,1,2,10,1,2,10),(105,105,'De rivier vertakt zich in vier grote stromen.','','','',2,1,2,10,1,2,10),(106,106,'De rivier vertakt zich in de Pison.','','','',2,1,2,11,1,2,11),(107,107,'De Pison stroomt om heel Chawila heen','','','',2,1,2,11,1,2,11),(108,108,'In Chawila wordt goud gevonden','','','',2,1,2,11,1,2,11),(109,109,'Het goud van Chawila is uitstekend','','','',2,1,2,12,1,2,12),(110,110,'Er is in Chawila ​balsemhars​ en ​onyx.','','','',2,1,2,12,1,2,12),(111,111,'De rivier vertakt zich in de Gichon.','','','',2,1,2,13,1,2,13),(112,112,'De Gichon stroomt om heel Nubië heen.','','','',2,1,2,13,1,2,13),(113,113,'De rivier vertakt zich in de ​Tigris.','','','',2,1,2,14,1,2,14),(114,114,'De Tigris loopt ten oosten van ​Assyrië.','','','',2,1,2,14,1,2,14),(115,115,'De rivier vertakt zich in de ​Eufraat.','','','',2,1,2,14,1,2,14),(116,116,'God bracht de mens in de tuin van ​Eden.','','','',2,1,2,15,1,2,15),(117,117,'De mens moest de tuin van Eden bewerken.','','','',2,1,2,15,1,2,15),(118,118,'De mens moest erover te waken.','','','',2,1,2,15,1,2,15),(119,119,'Hij sprak tot hem','','','',2,1,2,16,1,2,16),(120,120,'Hij verbad hem te eten van de boom van de kennis van goed en kwaad','','','',2,1,2,17,1,2,17),(121,121,'God dacht tot zichzelf.','','','',1,1,2,18,1,2,18),(122,122,'God wilde een helper voor de mens maken die bij hem past.','','','',1,1,2,18,1,2,18),(123,123,'Hij bracht die bij de mens.','','','',2,1,2,19,1,2,19),(124,124,'Hij wilde zien welke namen de mens ze zou geven.','','','',2,1,2,19,1,2,19),(125,125,'Zoals de mens elk levend wezen zou noemen, zo zou het heten.','','','',2,1,2,19,1,2,19),(126,126,'De mens gaf namen aan al het ​vee.','','','',2,1,2,20,1,2,20),(127,127,'De mens gaf namen aan alle vogels.','','','',2,1,2,20,1,2,20),(128,128,'De mens gaf namen aan alle wilde dieren.','','','',2,1,2,20,1,2,20),(129,129,'De mens vond geen helper die bij hem paste.','','','',2,1,2,20,1,2,20),(130,130,'Toen liet God de mens in een diepe slaap vallen.','','','',2,1,2,21,1,2,21),(131,131,'De mens sliep .','','','',2,1,2,21,1,2,21),(132,132,'God nam een van zijn ribben weg.','','','',2,1,2,21,1,2,21),(133,133,'God vulde die plaats weer met vlees.','','','',2,1,2,21,1,2,21),(134,134,'God bouwde een mens uit de rib die hij bij de mens had weggenomen.','','','',2,1,2,22,1,2,22),(135,135,'Het was een vrouw.','','','',2,1,2,22,1,2,22),(136,136,'God bracht haar bij de mens.','','','',2,1,2,22,1,2,22),(137,137,'De mens riep','','','',2,1,2,23,1,2,23),(138,138,'De mens noemde het nieuwe wezen: vrouw','','','',2,1,2,23,1,2,23),(139,139,'Een man maakt zich los van zijn vader en moeder.','','','',2,1,2,24,1,2,24),(140,140,'Hij hecht zich aan zijn vrouw.','','','',2,1,2,24,1,2,24),(141,141,'Hij wordt één van lichaam met zijn vrouw.','','','',2,1,2,24,1,2,24),(142,142,'Beiden waren ze naakt, de mens en zijn vrouw.','','','',2,1,2,25,1,2,25),(143,143,'Ze schaamden zich niet voor elkaar.','','','',2,1,2,25,1,2,25),(144,144,'God, de HEER, heeft alle in het wild levende dieren gemaakt.','','','',1,1,3,1,1,3,1),(145,145,'De slang was het sluwst.','','','',1,1,3,1,1,3,1),(146,146,'Dit dier sprak met de vrouw','','','',1,1,3,1,1,3,1),(147,147,'De vrouw antwoordde','','','',1,1,3,2,1,3,2),(148,148,'De slang antwoordde.','','','',1,1,3,4,1,3,4),(149,149,'De vrouw keek naar de boom.','','','',1,1,3,6,1,3,6),(150,150,'Zijn vruchten zagen er heerlijk uit.','','','',1,1,3,6,1,3,6),(151,151,'De vruchten waren een lust voor het oog.','','','',1,1,3,6,1,3,6),(152,152,'De vrouw vond het aanlokkelijk dat de boom haar wijsheid zou schenken.','','','',1,1,3,6,1,3,6),(153,153,'Ze plukte een paar vruchten.','','','',1,1,3,6,1,3,6),(154,154,'Ze at ervan.','','','',1,1,3,6,1,3,6),(155,155,'Ze gaf ook wat aan haar man.','','','',1,1,3,6,1,3,6),(156,156,'Haar man was bij haar.','','','',1,1,3,6,1,3,6),(157,157,'Ook hij at ervan.','','','',1,1,3,6,1,3,6),(158,158,'Toen gingen hun beiden de ogen open.','','','',1,1,3,7,1,3,7),(159,159,'Ze merkten dat ze naakt waren.','','','',1,1,3,7,1,3,7),(160,160,'Ze regen vijgenbladeren aan elkaar.','','','',1,1,3,7,1,3,7),(161,161,'Ze maakten er lendenschorten van.','','','',1,1,3,7,1,3,7),(162,162,'God, de HEER, wandelde in de koelte van de avondwind door de tuin.','','','',1,1,3,8,1,3,8),(163,163,'De mens en zijn vrouw hoorden dit.','','','',1,1,3,8,1,3,8),(164,164,'Zij verborgen zich voor hem tussen de bomen.','','','',1,1,3,8,1,3,8),(165,165,'God, de HEER, riep de mens','','','',1,1,3,9,1,3,9),(166,166,'De mens antwoordde','','','',1,1,3,10,1,3,10),(167,167,'God, de HEER, antwoordde','','','',1,1,3,11,1,3,11),(168,168,'De mens antwoordde','','','',1,1,3,12,1,3,12),(169,169,'God, de HEER, sprak tot de vrouw.','','','',1,1,3,13,1,3,13),(170,170,'De vrouw antwoordde','','','',1,1,3,13,1,3,13),(171,171,'God, de HEER, sprak tot de slang','','','',1,1,3,14,1,3,14),(172,172,'God, de HEER, sprak tot de vrouw','','','',1,1,3,16,1,3,16),(173,173,'God, de HEER, sprak tot de man','','','',1,1,3,17,1,3,17),(174,174,'De mens noemde zijn vrouw ​Eva.','','','',1,1,3,20,1,3,20),(175,175,'Eva is de moeder van alle levenden geworden.','','','',1,1,3,20,1,3,20),(176,176,'God, de HEER, maakte kleren​ van dierenvellen .','','','',1,1,3,21,1,3,21),(177,177,'God, de HEER, trok de mens en zijn vrouw die aan.','','','',1,1,3,21,1,3,21),(178,178,'Toen dacht God, de HEER, tot zichzelf.','','','',1,1,3,22,1,3,22),(179,179,'De mens is aan God gelijk geworden: hij heeft kennis van goed en kwaad.','','','',1,1,3,22,1,3,22),(180,180,'God wilde voorkomen dat hij ook vruchten van de levensboom plukt.','','','',1,1,3,22,1,3,22),(181,181,'Hij stuurde de mens weg uit de tuin van ​Eden​.','','','',1,1,3,23,1,3,23),(182,182,'De mens moest de aarde gaan bewerken.','','','',1,1,3,23,1,3,23),(183,183,'De mens was gekomen uit aarde.','','','',1,1,3,23,1,3,23),(184,184,'God had hem weggejaagd.','','','',1,1,3,24,1,3,24),(185,185,'Hij plaatste ten oosten van de tuin van ​Eden​ de ​cherubs​ en het heen en weer flitsende, vlammende ​zwaard.','','','',1,1,3,24,1,3,24),(186,186,'Zij moesten de weg naar de levensboom bewaken.','','','',1,1,3,24,1,3,24);
/*!40000 ALTER TABLE `activitys` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-07-21 15:58:00
