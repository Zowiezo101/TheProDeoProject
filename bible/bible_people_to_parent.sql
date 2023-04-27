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
-- Table structure for table `people_to_parent`
--

DROP TABLE IF EXISTS `people_to_parent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `people_to_parent` (
  `people_id` int NOT NULL,
  `parent_id` int NOT NULL,
  PRIMARY KEY (`people_id`,`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `people_to_parent`
--

LOCK TABLES `people_to_parent` WRITE;
/*!40000 ALTER TABLE `people_to_parent` DISABLE KEYS */;
INSERT INTO `people_to_parent` VALUES (2,1),(3,1),(4,1),(5,1),(6,2),(6,3),(7,2),(7,3),(8,6),(9,8),(10,9),(11,10),(12,11),(15,12),(15,13),(16,12),(16,13),(17,12),(17,14),(18,12),(18,15),(19,2),(19,3),(20,19),(21,20),(22,21),(23,22),(24,23),(25,24),(26,25),(27,26),(28,27),(29,27),(30,27),(31,29),(32,30),(33,30),(34,30),(35,30),(36,30),(37,30),(38,30),(39,32),(40,32),(41,32),(42,35),(43,35),(44,35),(45,35),(46,29),(47,29),(48,29),(49,46),(50,46),(51,46),(52,46),(53,46),(54,52),(55,52),(56,46),(57,47),(58,47),(59,47),(60,47),(61,47),(62,47),(63,47),(64,47),(65,31),(66,31),(67,31),(68,31),(69,31),(70,31),(71,31),(72,31),(73,31),(74,31),(75,31),(76,86),(77,28),(78,28),(79,28),(80,28),(81,28),(82,81),(83,81),(84,81),(85,81),(86,79),(87,76),(88,76),(89,88),(90,88),(91,88),(92,88),(93,88),(94,88),(95,88),(96,88),(97,88),(98,88),(99,88),(100,88),(101,88),(102,87),(103,102),(104,103),(105,104),(106,105),(107,105),(108,105),(109,108),(112,108),(113,108),(141,106),(141,140),(142,106),(142,111),(143,109),(144,109),(147,107),(147,112),(148,107),(148,112),(149,107),(149,112),(150,149),(151,107),(151,112),(152,107),(152,112),(153,107),(153,112),(154,107),(154,112),(155,107),(155,112),(156,155),(158,107),(158,157),(159,107),(159,157),(160,107),(160,157),(161,107),(161,157),(162,163),(164,155),(166,106),(166,165),(167,106),(167,165),(168,106),(168,165),(169,106),(169,165),(170,106),(170,165),(171,106),(171,165),(172,167),(173,167),(174,173),(175,173),(176,173),(177,169),(178,169),(179,169),(180,169),(181,169),(182,141),(183,141),(184,141),(185,141),(186,141),(187,141),(188,141),(189,141),(190,141),(191,141),(192,141),(193,141),(194,142),(194,156),(195,142),(195,156),(197,198),(199,200),(201,141),(202,164),(203,164),(205,195),(205,203),(206,195),(206,203),(207,195),(207,203),(208,195),(208,203),(210,195),(210,209),(211,195),(211,209),(212,195),(212,204),(213,195),(213,204),(214,195),(214,203),(215,195),(215,203),(216,195),(216,203),(217,195),(217,202),(218,142),(218,156),(220,219),(222,156),(222,195),(223,156),(223,195),(224,200),(225,226),(226,227),(228,194),(228,224),(229,194),(229,201),(230,194),(230,225),(231,194),(231,225),(232,194),(232,225),(233,228),(234,228),(235,228),(236,228),(237,228),(238,244),(239,228),(239,238),(240,229),(241,229),(242,229),(243,229),(245,244),(246,244),(247,244),(248,244),(249,244),(250,244),(251,244),(252,245),(253,245),(254,246),(255,246),(256,246),(257,246),(258,246),(259,247),(260,247),(261,248),(262,248),(263,249),(264,249),(265,249),(266,249),(267,250),(268,250),(269,250),(270,251),(271,251),(272,273),(274,275),(278,279),(282,283),(285,286),(286,287),(301,302),(303,208),(303,301),(304,208),(304,301),(305,208),(305,301),(307,208),(307,306),(308,208),(308,306),(309,195),(309,202),(310,311),(312,217),(312,310),(313,217),(313,310),(314,205),(315,205),(316,205),(317,205),(318,206),(319,206),(320,206),(321,206),(322,206),(323,206),(324,207),(325,207),(326,207),(327,307),(328,307),(329,214),(330,214),(331,214),(332,214),(333,215),(334,215),(335,215),(336,212),(337,212),(338,212),(339,212),(340,212),(341,212),(342,212),(343,213),(344,213),(345,213),(346,213),(347,213),(348,346),(349,346),(350,223),(351,223),(352,223),(353,223),(354,223),(355,223),(356,223),(357,223),(358,223),(359,223),(360,210),(361,211),(362,211),(363,211),(364,211),(365,312),(368,375),(368,379),(370,369),(371,368),(371,370),(372,375),(372,379),(373,324),(374,324),(375,325),(376,325),(377,325),(378,325),(379,207),(380,376),(381,376),(382,376),(383,378),(384,378),(385,378),(386,387),(388,387),(389,372),(389,386),(390,372),(390,386),(391,372),(391,386),(392,372),(392,386),(393,380),(394,380),(395,380),(396,397),(398,391),(398,396),(399,375),(399,379),(400,408),(402,368),(402,370),(403,404),(404,405),(405,1088),(405,1094),(406,406),(409,410),(411,412),(413,414),(415,416),(417,418),(419,420),(421,422),(423,424),(425,426),(427,428),(429,430),(431,432),(433,326),(434,326),(435,436),(437,438),(439,440),(441,369),(444,445),(446,447),(448,449),(450,451),(452,453),(453,1542),(454,455),(456,457),(458,459),(460,461),(462,463),(464,465),(466,467),(468,562),(469,562),(470,562),(471,562),(472,474),(473,474),(474,315),(475,476),(480,481),(482,483),(485,486),(487,488),(489,474),(490,365),(491,490),(492,490),(493,490),(494,490),(495,490),(496,490),(497,496),(498,497),(499,497),(500,497),(501,497),(502,497),(503,313),(504,313),(505,313),(506,503),(507,223),(508,223),(509,223),(510,350),(511,350),(512,380),(519,312),(521,522),(523,524),(525,526),(527,528),(529,530),(531,532),(533,534),(535,536),(537,538),(544,545),(545,546),(546,547),(562,561),(563,448),(564,565),(565,449),(567,207),(569,223),(570,213),(573,574),(575,576),(580,581),(586,584),(592,586),(593,586),(594,586),(595,596),(598,599),(599,600),(603,604),(607,608),(610,609),(614,371),(617,615),(617,616),(618,615),(618,616),(623,543),(623,629),(624,621),(624,623),(625,624),(626,327),(627,626),(628,627),(629,628),(630,631),(631,632),(632,633),(633,634),(637,639),(638,639),(640,630),(640,635),(641,638),(644,643),(645,640),(646,640),(647,648),(648,649),(649,650),(650,651),(652,647),(656,652),(656,663),(657,658),(658,638),(659,652),(659,663),(660,652),(660,663),(661,652),(661,663),(662,652),(662,663),(663,664),(665,666),(666,648),(668,625),(669,625),(670,625),(671,625),(673,786),(674,658),(678,638),(679,674),(683,684),(685,686),(686,625),(687,686),(688,842),(689,688),(693,652),(694,652),(695,686),(696,686),(697,671),(697,682),(698,671),(698,681),(699,671),(699,700),(700,701),(701,760),(702,671),(702,703),(704,671),(704,705),(706,671),(706,707),(708,709),(712,714),(713,714),(715,656),(717,671),(717,752),(718,671),(718,752),(719,671),(719,752),(720,671),(720,752),(721,671),(722,671),(723,671),(724,671),(725,671),(726,671),(727,671),(728,643),(729,643),(733,734),(736,735),(737,738),(739,740),(740,1395),(742,743),(747,748),(749,715),(750,654),(752,753),(756,671),(756,752),(757,671),(757,700),(758,759),(761,699),(764,739),(765,679),(767,768),(769,770),(769,771),(771,625),(771,772),(773,774),(777,778),(779,778),(783,652),(783,708),(784,652),(784,708),(789,790),(792,670),(795,796),(797,798),(800,801),(805,806),(812,813),(814,815),(822,823),(824,825),(826,827),(828,762),(831,832),(843,739),(844,846),(845,846),(847,848),(849,848),(851,852),(857,756),(858,859),(861,862),(864,756),(865,866),(867,868),(869,870),(871,872),(875,874),(876,874),(877,874),(882,879),(882,881),(883,884),(885,886),(885,887),(890,756),(890,896),(894,885),(895,885),(897,890),(897,898),(898,899),(900,897),(901,906),(902,903),(903,904),(905,900),(905,937),(907,908),(909,901),(913,914),(916,912),(917,918),(920,919),(921,919),(925,926),(927,928),(930,931),(932,933),(935,916),(936,916),(937,938),(939,905),(940,916),(944,939),(944,945),(945,912),(946,947),(947,926),(949,950),(951,946),(952,939),(953,944),(953,956),(957,958),(959,960),(961,953),(961,964),(962,924),(963,951),(965,961),(965,970),(966,935),(967,968),(969,966),(971,965),(971,986),(972,973),(974,961),(974,970),(975,976),(978,975),(979,980),(984,985),(986,987),(989,971),(991,989),(991,994),(994,969),(996,997),(999,1000),(1001,1002),(1004,995),(1005,995),(1006,995),(1007,1008),(1009,991),(1009,1010),(1012,1009),(1012,1013),(1013,1014),(1015,1012),(1015,1016),(1016,1017),(1018,1019),(1019,1020),(1022,1023),(1024,1025),(1029,1030),(1030,1031),(1035,1015),(1035,1036),(1036,1037),(1038,1015),(1038,1039),(1039,1040),(1042,1038),(1042,1043),(1043,1044),(1045,1015),(1045,1036),(1049,1022),(1050,1051),(1051,1057),(1052,1053),(1054,1055),(1073,308),(1074,308),(1075,308),(1076,308),(1077,308),(1079,1074),(1080,327),(1081,327),(1083,625),(1084,625),(1085,625),(1090,1088),(1090,1089),(1091,1090),(1092,1090),(1093,1090),(1095,365),(1096,327),(1096,1095),(1097,1096),(1098,1081),(1098,1094),(1099,1080),(1100,1080),(1101,1080),(1102,1080),(1103,1080),(1105,1080),(1105,1104),(1106,1099),(1107,1099),(1108,1099),(1109,1105),(1110,1105),(1111,1109),(1112,1109),(1114,1112),(1114,1113),(1115,1112),(1115,1113),(1116,1111),(1117,1111),(1118,1117),(1119,1118),(1120,1119),(1121,1110),(1122,1110),(1123,1122),(1124,1122),(1126,1125),(1128,1126),(1128,1127),(1129,1128),(1130,1129),(1131,1130),(1132,1131),(1133,1132),(1134,1133),(1135,1134),(1136,1135),(1137,1136),(1138,1137),(1139,1138),(1140,1139),(1141,1081),(1142,1081),(1147,1146),(1148,1147),(1149,1148),(1151,1081),(1151,1150),(1152,1081),(1152,1150),(1153,1081),(1153,1150),(1154,1151),(1156,1155),(1157,1155),(1158,1155),(1159,1155),(1160,1155),(1161,1155),(1163,1081),(1163,1162),(1164,1081),(1164,1162),(1165,1081),(1165,1162),(1166,1081),(1166,1162),(1167,1081),(1168,405),(1169,405),(1170,405),(1183,671),(1185,671),(1187,1015),(1189,1038),(1190,1042),(1191,1190),(1192,1190),(1193,1190),(1194,1190),(1195,1190),(1196,1190),(1197,1190),(1198,1193),(1199,1193),(1200,1198),(1201,1198),(1202,1198),(1203,1198),(1204,1198),(1205,1198),(1206,1198),(1207,1198),(1208,1201),(1209,1201),(1210,1209),(1211,1210),(1212,1211),(1213,1212),(1214,1213),(1215,1214),(1216,1214),(1217,1214),(1218,1214),(1219,1214),(1220,1218),(1221,1218),(1222,1218),(1223,1220),(1224,1220),(1225,1220),(1226,1220),(1227,1220),(1228,1220),(1229,1220),(1231,1168),(1232,1231),(1233,1232),(1234,1232),(1236,1235),(1237,1235),(1238,1235),(1239,1235),(1240,1235),(1241,1235),(1244,1098),(1244,1243),(1245,1098),(1245,1243),(1246,1098),(1246,1243),(1247,1098),(1247,1243),(1248,1098),(1248,1242),(1249,1098),(1249,1242),(1250,1098),(1250,1242),(1252,1251),(1253,1251),(1254,1255),(1259,1257),(1260,1259),(1261,1260),(1262,1260),(1263,1260),(1265,1264),(1266,1264),(1267,1265),(1269,1268),(1271,1270),(1272,448),(1273,448),(1274,448),(1275,1273),(1277,1276),(1278,1276),(1279,1276),(1280,1276),(1282,1281),(1283,1281),(1284,1281),(1285,1281),(1287,1283),(1287,1286),(1288,1283),(1288,1286),(1289,1283),(1289,1286),(1290,1283),(1291,1283),(1292,1283),(1297,1296),(1298,1296),(1299,1296),(1300,1296),(1302,1301),(1303,1301),(1304,305),(1305,305),(1306,305),(1307,305),(1308,305),(1309,305),(1310,323),(1311,1310),(1312,1311),(1313,1312),(1314,1313),(1315,1314),(1318,1319),(1321,1322),(1322,1323),(1323,1324),(1332,1333),(1333,1334),(1334,1335),(1335,1336),(1336,1337),(1339,1338),(1340,1338),(1341,1338),(1342,1338),(1344,1343),(1345,1344),(1346,1345),(1347,1346),(1348,1347),(1349,1348),(1350,1349),(1353,1354),(1354,1355),(1355,1356),(1369,1370),(1370,1371),(1371,1372),(1372,1373),(1373,1374),(1374,1375),(1375,1376),(1377,1378),(1378,1379),(1390,398),(1391,1390),(1392,1391),(1393,1392),(1394,1393),(1395,1394),(1396,764),(1397,1396),(1398,1397),(1399,1398),(1400,1399),(1401,1400),(1402,1401),(1403,1402),(1404,1403),(1405,1404),(1406,1405),(1407,373),(1408,1407),(1409,1408),(1410,1409),(1411,1410),(1412,1411),(1413,325),(1414,1413),(1415,1414),(1416,1415),(1417,1416),(1418,1417),(1419,1418),(1420,1419),(1421,1420),(1422,1421),(1423,1416),(1424,1416),(1425,1416),(1426,1425),(1427,1426),(1428,1427),(1430,433),(1431,1430),(1432,1431),(1433,1432),(1434,1433),(1435,1434),(1436,645),(1437,1438),(1438,634),(1439,1440),(1440,1441),(1441,1442),(1442,1443),(1443,1444),(1444,1445),(1445,1446),(1446,1447),(1447,1448),(1448,1449),(1449,325),(1450,1451),(1451,1452),(1452,1453),(1453,1454),(1454,1455),(1455,1456),(1456,1457),(1457,1458),(1458,1459),(1459,1460),(1460,1461),(1461,1462),(1462,324),(1463,1464),(1464,1465),(1465,1466),(1466,1467),(1467,1468),(1468,1469),(1469,1470),(1470,1471),(1471,1472),(1472,1473),(1473,434),(1474,329),(1475,329),(1476,329),(1477,329),(1478,329),(1479,329),(1480,329),(1481,1474),(1482,1481),(1483,1481),(1484,1481),(1485,1481),(1486,223),(1487,350),(1488,350),(1489,350),(1490,350),(1491,350),(1492,351),(1493,351),(1494,351),(1495,351),(1496,351),(1497,351),(1498,351),(1499,351),(1500,351),(1501,1486),(1502,1501),(1503,1501),(1504,1501),(1505,1501),(1506,1501),(1507,1501),(1508,1501),(1509,312),(1510,312),(1511,312),(1513,365),(1513,1512),(1514,365),(1514,1512),(1515,1514),(1516,1514),(1517,1515),(1518,312),(1519,1518),(1520,1518),(1521,1518),(1523,1522),(1524,1522),(1525,1522),(1526,1522),(1527,503),(1528,1527),(1529,1528),(1530,1529),(1531,1530),(1532,1531),(1533,313),(1534,313),(1535,313),(1536,313),(1537,1536),(1538,1537),(1539,1538),(1540,1539),(1541,1540),(1542,1541),(1543,348),(1544,348),(1545,348),(1546,348),(1547,1543),(1548,1543),(1549,1543),(1551,1550),(1552,1550),(1553,1550),(1554,1550),(1556,1555),(1557,1555),(1558,1555),(1559,1555),(1560,1556),(1561,1556),(1562,1556),(1563,1556),(1564,1556),(1565,1556),(1566,1556),(1567,1556),(1568,1556),(1569,1556),(1570,1556),(1572,1571),(1573,1571),(1574,1571),(1576,1575),(1577,1575),(1578,1575),(1579,223),(1580,223),(1581,223),(1582,350),(1583,350),(1584,350),(1585,350),(1586,350),(1587,350),(1588,350),(1589,350),(1590,350),(1592,1591),(1593,1591),(1594,1591),(1595,1594),(1596,1594),(1601,1597),(1601,1598),(1602,1597),(1602,1598),(1603,1597),(1603,1598),(1604,1597),(1604,1598),(1605,1597),(1605,1598),(1606,1597),(1606,1598),(1607,1597),(1607,1598),(1608,1597),(1608,1599),(1609,1597),(1609,1599),(1610,1609),(1611,1609),(1612,1609),(1613,1609),(1614,1609),(1615,1613),(1616,1613),(1617,1613),(1618,1613),(1619,1613),(1620,1613),(1621,1613),(1622,1613),(1623,1613),(1624,1631),(1625,1631),(1626,1631),(1627,1631),(1628,1631),(1629,1631),(1630,1631),(1632,1641),(1633,1641),(1634,1641),(1635,1641),(1636,1641),(1637,1641),(1638,1641),(1639,1641),(1640,1641),(1642,1653),(1643,1653),(1644,1653),(1645,1653),(1646,1653),(1647,1653),(1648,1653),(1649,1653),(1650,1653),(1651,1653),(1652,1653),(1654,1660),(1655,1660),(1656,1660),(1657,1660),(1658,1660),(1659,1660),(1662,1661),(1662,1769),(1663,1661),(1663,1769),(1664,1661),(1664,1769),(1665,1661),(1665,1769),(1666,1661),(1666,1769),(1667,1661),(1667,1769),(1668,1661),(1668,1769),(1669,1661),(1669,1769),(1670,1769),(1671,1670),(1672,1769),(1673,652),(1674,656),(1675,749),(1676,749),(1677,749),(1678,749),(1679,1678),(1680,1679),(1681,1679),(1682,1679),(1683,1682),(1684,1683),(1685,1684),(1686,1685),(1687,1686),(1688,1687),(1689,1687),(1690,1687),(1691,1687),(1692,1687),(1693,1687),(1694,1686),(1695,1694),(1696,1694),(1697,1694),(1698,1699),(1699,1700),(1700,1701),(1701,1702),(1702,307),(1707,1708),(1708,1709),(1709,1710),(1711,1712),(1713,1714),(1714,1715),(1716,1717),(1717,1718),(1718,1719),(1723,1724),(1724,1725),(1725,1726),(1726,1727),(1727,1728),(1729,1730),(1730,1731),(1731,1732),(1733,1734),(1734,1735),(1735,1736),(1736,1737),(1737,1738),(1739,1740),(1740,1741),(1741,1742),(1746,1747),(1747,1748),(1748,1749),(1750,1751),(1751,1752),(1752,1753),(1754,1755),(1755,1756),(1761,1762),(1762,1763),(1763,1764),(1765,1766),(1767,1761),(1790,1791),(1794,1795),(1796,1797),(1798,1799),(1800,1801),(1804,1806),(1805,1806),(1807,1808),(1809,1808),(1811,1813),(1812,1813),(1818,1819),(1820,1819),(1821,1823),(1822,1823),(1842,1844),(1843,1844),(1869,671),(1882,1883),(1931,1929),(1932,1929),(1933,1929),(1937,1930),(1938,1930),(1939,1930),(1940,1930),(1941,371),(1942,402),(1943,1449),(1944,377),(1945,377),(1946,377),(1947,377),(1948,378),(1949,378),(1950,433),(1951,433),(1952,434),(1953,434),(1954,1955),(1956,1957),(1983,1982),(1984,1983),(1986,1985),(1989,1988),(1990,1988),(1991,1948),(1992,1949),(1993,326),(1994,1993),(1995,1993),(1996,1993),(1997,1951),(1998,434),(2002,1999),(2003,1999),(2004,1999),(2005,1999),(2006,2001),(2007,2001),(2008,2001),(2009,2001),(2010,2001),(2011,2000),(2012,2000),(2013,2000),(2014,2000),(2015,2000),(2016,2000),(2017,2000),(2018,2000),(2019,2000),(2020,2000),(2021,2000),(2022,2000),(2023,2000),(2024,2000),(2031,1762),(2032,2031),(2033,2031),(2034,2031),(2035,2031),(2036,2031),(2037,2031),(2038,2031),(2040,2039),(2041,2039),(2042,2039),(2043,2039),(2044,2039),(2045,2039),(2046,2039),(2047,2039),(2048,2040),(2049,2040),(2050,2040),(2051,2040),(2056,2055),(2057,2055),(2058,2055),(2059,2055),(2061,2060),(2070,2071),(2071,2072),(2072,2073),(2073,2074),(2074,2075),(2080,2081),(2085,742),(2086,696),(2088,2089),(2099,2100),(2101,2102),(2103,2104),(2106,625),(2107,2108),(2109,2110),(2111,2112),(2113,2114),(2115,2116),(2117,2118),(2119,2120),(2121,2122),(2123,2124),(2125,2126),(2127,2128),(2143,623),(2144,2145),(2148,2149),(2157,2158),(2157,2159),(2158,671),(2159,2160),(2160,625),(2161,890),(2161,2157),(2162,890),(2162,2157),(2163,890),(2163,2157),(2165,890),(2165,898),(2166,890),(2166,898),(2167,890),(2167,898),(2168,890),(2168,898),(2176,2177),(2197,2198),(2205,2206),(2206,2207),(2207,2208),(2208,2209),(2210,2211),(2212,905),(2213,905),(2214,905),(2215,905),(2216,905),(2217,905),(2221,2222),(2223,2224),(2225,2226),(2227,2228),(2229,2230),(2231,954),(2232,2233),(2234,2235),(2238,2239),(2242,989),(2246,2247),(2248,2249),(2250,2251),(2252,2253),(2256,2257),(2258,2259),(2260,2261),(2262,2263),(2264,2265),(2266,2267),(2297,2297),(2307,2308),(2326,1038),(2431,2432),(2433,2434),(2438,2439),(2448,2449),(2449,2450),(2453,2454),(2454,2455),(2455,2456),(2456,2457),(2457,2458),(2458,2459),(2459,2460),(2460,2461),(2461,1394),(2472,2473),(2474,2475),(2477,2478),(2480,2481),(2483,2484),(2486,2487),(2488,2489),(2491,2492),(2494,2495),(2518,2519),(2520,2521),(2522,2523),(2524,2525),(2526,2527),(2527,2528),(2529,2530),(2531,2532),(2533,2534),(2537,2538),(2539,2538),(2540,2538),(2541,2538),(2542,2538),(2665,2666),(2667,2666),(2673,2674),(2676,2677),(2677,2678),(2679,2680),(2680,2681),(2682,2683),(2684,2685),(2686,2687),(2690,2691),(2693,2694),(2695,2696),(2697,2698),(2699,2700),(2701,2702),(2703,2704),(2706,2707),(2708,2709),(2710,2711),(2712,2713),(2715,2716),(2717,2718),(2719,2720),(2724,2725),(2725,2726),(2727,2728),(2730,2731),(2732,2733),(2735,2736),(2737,2738),(2739,2740),(2741,2742),(2743,2744),(2748,2749),(2749,2750),(2752,2753),(2754,2670),(2755,2756),(2818,2819),(2821,2822),(2881,2882),(2882,2883),(2883,2884),(2884,2885),(2885,2886),(2887,2888),(2888,2889),(2889,2890),(2890,2891),(2891,2892),(2892,2893),(2893,2894),(2895,2896),(2896,2897),(2897,2898),(2898,2899),(2899,2900),(2900,2901),(2901,2902),(2905,2906),(2907,2908),(2909,2910),(2912,2913),(2913,2914),(2914,2915),(2915,2916),(2916,2917),(2918,2919),(2919,2920),(2920,2921),(2921,2922),(2922,2923),(2923,2924),(2925,2926),(2926,2927),(2927,2928),(2928,2929),(2930,2931),(2932,2933),(2933,2934),(2934,2935),(2935,2936),(2939,2940),(2940,2941),(2941,2942),(2944,2945),(2945,2946),(2946,2947),(2952,2953),(2953,2954),(2954,2955),(2955,2956),(2957,2958),(2990,2989),(2991,2990),(2992,2991),(2993,2992),(2994,2993),(3040,3041),(3044,3045),(3052,3053),(3053,3054),(3054,3055),(3065,3066),(3066,3067),(3067,3068),(3068,3069),(3069,3070),(3070,3071),(3100,3101),(3101,3102),(3103,3104),(3122,3123),(3123,3124),(3124,3125),(3126,3128),(3128,3123),(3131,3132),(3136,3131),(3136,3135),(3137,3131),(3137,3135),(3138,3131),(3138,3135),(3139,3131),(3139,3135),(3140,3131),(3140,3135),(3141,3131),(3141,3135),(3142,3131),(3142,3135),(3143,3131),(3143,3135),(3144,3131),(3144,3135),(3145,3131),(3145,3135),(3152,3153),(3155,1001),(3157,3158),(3159,1001),(3164,3165),(3166,3167),(3169,3170),(3171,3172),(3174,3175),(3176,3177),(3178,3179),(3180,3181),(3182,3183),(3184,3185),(3186,3187),(3188,3189),(3191,3192),(3194,3195),(3196,3197),(3197,3198),(3199,3200),(3200,3201),(3202,3203),(3204,3205),(3206,3207),(3208,3209),(3210,3208),(3212,3213),(3214,3215),(3216,3217),(3218,3219),(3219,3220),(3220,3221),(3223,3224),(3225,3226),(3227,3228),(3229,3230),(3230,3231),(3233,3234),(3235,3236),(3237,3238),(3239,3240),(3247,1053),(3250,3251),(3252,3251),(3255,3197),(3258,3259),(3260,3261),(3262,3263),(3264,3265),(3287,3288),(3289,3290),(3291,3287),(3291,3289),(3292,3287),(3292,3289),(3293,3287),(3293,3289),(3296,3297),(3302,3303),(3307,3308),(3308,3309),(3309,3310),(3310,3311),(3313,3314),(3315,3316),(3321,3322),(3333,3334),(3335,3327),(3336,3327),(3337,3327),(3339,1),(3339,3356),(3344,3343),(3345,3344),(3346,3345),(3347,3346),(3348,3347),(3349,3348),(3350,3349),(3351,3350),(3352,3351),(3353,3352),(3354,3353),(3355,3354),(3356,3422),(3358,3357),(3361,3473),(3363,3364),(3365,3364),(3371,3372),(3375,3477),(3377,3409),(3377,3411),(3379,3356),(3380,3356),(3381,3356),(3382,3356),(3395,3396),(3402,3403),(3404,3388),(3405,3388),(3415,3416),(3422,3423),(3423,3424),(3424,3425),(3425,3426),(3426,3427),(3427,3428),(3428,3429),(3429,3430),(3430,3431),(3431,3432),(3432,3433),(3433,3434),(3434,3435),(3435,3436),(3436,3437),(3437,3438),(3438,3439),(3439,3440),(3440,3441),(3441,3442),(3442,3443),(3443,3444),(3444,3445),(3445,3446),(3446,3447),(3447,3448),(3448,3449),(3449,3450),(3450,3451),(3451,3452),(3452,3453),(3453,3454),(3454,3455),(3455,3456),(3456,3457),(3457,3458),(3458,3459),(3459,719),(3461,3462),(3483,3484),(3557,3558),(3641,3664),(3664,3663);
/*!40000 ALTER TABLE `people_to_parent` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-27 15:46:44
