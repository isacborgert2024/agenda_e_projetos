-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- Host: localhost    Database: agenda_db
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.24.04.1

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
-- Table structure for table `agenda`
--

DROP TABLE IF EXISTS `agenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agenda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario_cliente` int NOT NULL,
  `id_usuario_tecnico` int NOT NULL,
  `dados` text NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_agenda` datetime NOT NULL,
  `materiais_necessarios` text NOT NULL,
  `responsavel_local` varchar(255) NOT NULL,
  `fone_responsavel_local` varchar(20) NOT NULL,
  `horas_execucao` int DEFAULT '1',
  `aprovada` varchar(255) DEFAULT 'Aprovação Pendente',
  `conclusao` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario_cliente` (`id_usuario_cliente`),
  KEY `id_usuario_tecnico` (`id_usuario_tecnico`),
  CONSTRAINT `agenda_ibfk_1` FOREIGN KEY (`id_usuario_cliente`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `agenda_ibfk_2` FOREIGN KEY (`id_usuario_tecnico`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agenda`
--

LOCK TABLES `agenda` WRITE;
/*!40000 ALTER TABLE `agenda` DISABLE KEYS */;
INSERT INTO `agenda` VALUES (16,1,2,'config mk isac 777','2025-03-17 21:20:39','2025-03-02 18:00:00','nao precisa','joao','fone 989438943',1,'Rejeitada',NULL),(17,1,2,'bastante tesxto dlkjafsdkljbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdkljbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdkljbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdklj','2025-03-17 21:36:07','2025-03-21 06:00:00','bastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdkljbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdkljbastante tesxto dlkjafsdkljbastante tesxto dlkjafsdkljbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdklj\r\nbastante tesxto dlkjafsdklj\r\n','joão vai acomparnhar','fone 989438943',3,'Aprovada',NULL),(19,4,2,'testes editando minha propria tarefa 88888','2025-03-18 16:10:21','2025-03-19 23:00:00','teste','joao','fone 989438943',1,'Aprovação Pendente',NULL),(25,4,2,'dasdsa','2025-03-19 03:01:13','2025-03-20 21:00:00','dsadsa','dasdsa','dasdsa',1,'Aprovação Pendente',NULL),(27,4,51,'6666666 fsdfdsfsde fsdfdsfsdfffsdf\r\nfsdfdsfsdf sffdsf\r\n\r\nfsdfds\r\n\r\nfsdfsdfdsfsdfsdfsdff','2025-03-19 15:50:40','2025-04-02 11:00:00','6666666666fsdfsd   fsd\r\nfdsfsdfsddfssdfdssfds\r\n fsdfdsfdsf            fsdfdsf fsdfdsfdffffffffffffffffffffff','fdsfds','543543',3,'Em Execução',NULL),(31,4,1,'hkk 7777  Se os  ','2025-03-19 17:33:16','2025-04-02 16:00:00','kjkkSe hkk 7777 ','jao','77789898',5,'Aprovada',''),(32,2,1,'testse','2025-03-20 12:57:27','2025-03-28 03:00:00','testest','testes','teste',1,'Aprovação Pendente',NULL),(33,4,2,'dsadsd','2025-03-20 16:50:26','2025-03-27 10:00:00','sddfdsfsf','fdsfds','fdsfds',1,'Aprovada',NULL),(36,51,2,'ewrewre','2025-03-20 18:29:51','2025-03-25 14:00:00','wrewrew','rewre','rewrwr',1,'Concluída',NULL),(37,51,2,'fdsfsdf','2025-03-20 19:19:05','2025-03-26 05:00:00','fsdfdsfds','fsdfds','fsdfsdf',1,'Aprovação Pendente','fdsfdsfdsffdsfdfs 777'),(38,2,51,'fdfdf','2025-03-20 20:14:16','2025-03-26 06:00:00','fdfdf','fdfd','fdfd',4,'Concluída','fdfdsfdfdf'),(40,4,51,'pppp','2025-03-21 15:05:07','2025-03-28 11:00:00','ppppp','pppp','pppp',7,'Aprovada',''),(41,4,1,'nnn','2025-03-21 15:21:07','2025-04-02 04:00:00','nnn','nnn','nnn',4,'Concluída',''),(42,50,1,'teste dia 21-03-2025 isac','2025-03-21 16:45:23','2025-03-21 21:00:00','teste dia 21-03-2025','teste dia 21-03-2025','teste dia 21-03-2025',1,'Aprovada',''),(43,1,2,'Teste de inserção com um texto maior que 300 caracteres. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Aenean in felis non odio tincidunt bibendum ut in urna. Curabitur sagittis, orci ac bibendum pellentesque, eros nunc varius odio, vitae vehicula nunc justo sed velit. Integer at dui et turpis accumsan fringilla at vel urna.','2025-03-21 17:04:43','2025-03-22 10:00:00','Cabo de fibra, ONU modelo X, Switch 24 portas','João Silva','11999999999',2,'Aprovação Pendente',''),(44,4,1,'fff','2025-03-21 21:03:10','2025-04-03 10:00:00','fff','ff','ff',1,'Aprovada',''),(45,1,1,'fdfd','2025-03-26 18:43:51','2025-04-03 06:00:00','fdf','ff','fff',1,'Aprovação Pendente','');
/*!40000 ALTER TABLE `agenda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `datas_bloqueadas`
--

DROP TABLE IF EXISTS `datas_bloqueadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `datas_bloqueadas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data_bloqueada` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `datas_bloqueadas`
--

LOCK TABLES `datas_bloqueadas` WRITE;
/*!40000 ALTER TABLE `datas_bloqueadas` DISABLE KEYS */;
INSERT INTO `datas_bloqueadas` VALUES (1,'2025-03-26');
/*!40000 ALTER TABLE `datas_bloqueadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historico`
--

DROP TABLE IF EXISTS `historico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `acao` text,
  `data_hora` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historico`
--

LOCK TABLES `historico` WRITE;
/*!40000 ALTER TABLE `historico` DISABLE KEYS */;
INSERT INTO `historico` VALUES (1,1,'Usuário isac.borgert adicionou uma nova agenda, id do técnico é 1','2025-03-19 13:39:50'),(2,1,'Usuário isac.borgert editou a agenda id 26, id do técnico é 1','2025-03-19 13:40:07'),(3,4,'Usuário teste1 editou a agenda id 26, id do técnico é 1','2025-03-19 13:40:39'),(4,4,'Usuário teste1 editou a agenda id 26, id do técnico é 1','2025-03-19 13:40:59'),(5,4,'Usuário teste1 adicionou uma nova agenda, id do técnico é 1','2025-03-19 14:33:16'),(6,4,'Usuário teste1 editou a agenda id 27, id do técnico é 1','2025-03-19 14:33:57'),(7,4,'Usuário teste1 editou a agenda id 24, id do técnico é 2','2025-03-19 16:56:33'),(8,4,'Usuário teste1 editou a agenda id 24, id do técnico é 2','2025-03-19 17:05:10'),(9,4,'Usuário teste1 editou a agenda id 27, id do técnico é 1','2025-03-19 17:07:03'),(10,1,'Usuário isac.borgert editou a agenda id 26, id do técnico é 1','2025-03-19 18:27:35'),(11,1,'Usuário isac.borgert editou a agenda id 26, id do técnico é 1','2025-03-19 18:28:00'),(12,1,'Usuário isac.borgert editou a agenda id 26, id do técnico é 1','2025-03-19 18:28:09'),(13,4,'Usuário teste1 editou a agenda id 24, id do técnico é 2','2025-03-20 09:51:05'),(14,4,'Usuário teste1 editou a agenda id 24, id do técnico é 2','2025-03-20 09:51:51'),(15,2,'Usuário leandro.pacheco adicionou uma nova agenda, id do técnico é 1','2025-03-20 09:57:27'),(16,2,'Usuário leandro.pacheco excluiu a agenda id 24','2025-03-20 10:01:58'),(17,2,'Usuário leandro.pacheco atualizou usuário isac.borgert para nível 5.','2025-03-20 13:03:09'),(18,2,'Usuário leandro.pacheco atualizou usuário leandro.pacheco para nível 5.','2025-03-20 13:03:13'),(19,2,'Usuário leandro.pacheco atualizou usuário teste1 para nível 1.','2025-03-20 13:03:17'),(20,2,'Usuário leandro.pacheco atualizou usuário teste2 para nível 1.','2025-03-20 13:03:20'),(21,2,'Usuário leandro.pacheco cadastrou o usuário teste3 com nível 1.','2025-03-20 13:04:02'),(22,2,'Usuário leandro.pacheco atualizou usuário isac.borgert para nível 5.','2025-03-20 13:13:04'),(23,2,'Usuário leandro.pacheco atualizou usuário leandro.pacheco para nível 5.','2025-03-20 13:13:07'),(24,2,'Usuário leandro.pacheco atualizou usuário teste1 para nível 1.','2025-03-20 13:13:11'),(25,2,'Usuário leandro.pacheco atualizou usuário teste2 para nível 1.','2025-03-20 13:13:14'),(26,2,'Usuário leandro.pacheco atualizou usuário teste3 para nível 1.','2025-03-20 13:13:18'),(27,2,'Usuário leandro.pacheco atualizou usuário isac.borgert para nível 5.','2025-03-20 13:13:53'),(28,2,'Usuário leandro.pacheco atualizou usuário leandro.pacheco para nível 5.','2025-03-20 13:13:56'),(29,2,'Usuário leandro.pacheco atualizou usuário teste1 para nível 1.','2025-03-20 13:13:59'),(30,2,'Usuário leandro.pacheco atualizou usuário teste2 para nível 1.','2025-03-20 13:14:02'),(31,2,'Usuário leandro.pacheco atualizou usuário teste3 para nível 1.','2025-03-20 13:14:04'),(32,2,'Usuário leandro.pacheco cadastrou o usuário teste5 com nível 4.','2025-03-20 13:24:51'),(33,2,'Usuário leandro.pacheco atualizou usuário teste5 para nível 4.','2025-03-20 13:25:22'),(34,4,'Usuário teste1 adicionou uma nova agenda, id do técnico é 51','2025-03-20 13:50:26'),(35,51,'Usuário teste5 atualizou usuário teste5 para nível 3.','2025-03-20 13:54:38'),(36,51,'Usuário teste5 adicionou uma nova agenda, id do técnico é 1','2025-03-20 14:01:21'),(37,51,'Usuário teste5 editou a agenda id 34, id do técnico é 1','2025-03-20 14:01:34'),(38,51,'Usuário teste5 editou a agenda id 34, id do técnico é 1','2025-03-20 14:01:49'),(39,51,'Usuário teste5 editou a agenda id 33, id do técnico é 2','2025-03-20 14:02:27'),(40,4,'Usuário teste1 adicionou uma nova agenda, id do técnico é 51','2025-03-20 14:52:13'),(41,51,'Usuário teste5 editou a agenda id 35, id do técnico é 51','2025-03-20 15:13:33'),(42,51,'Usuário teste5 editou a agenda id 35, id do técnico é 51','2025-03-20 15:14:22'),(43,4,'Usuário teste1 editou a agenda id 27, id do técnico é 51','2025-03-20 15:16:55'),(44,51,'Usuário teste5 adicionou uma nova agenda, id do técnico é 2','2025-03-20 15:29:51'),(45,51,'Usuário teste5 editou a agenda id 27, id do técnico é 51','2025-03-20 15:45:18'),(46,51,'Usuário teste5 editou a agenda id 27, id do técnico é 51','2025-03-20 15:53:32'),(47,4,'Usuário teste1 editou a agenda id 25, id do técnico é 2','2025-03-20 16:09:03'),(48,51,'Usuário teste5 adicionou uma nova agenda, id do técnico é 2','2025-03-20 16:19:05'),(49,51,'Usuário teste5 editou a agenda id 37, id do técnico é 2','2025-03-20 16:19:44'),(50,2,'Usuário leandro.pacheco adicionou uma nova agenda, id do técnico é 51','2025-03-20 17:14:16'),(51,1,'Usuário isac.borgert excluiu a agenda id 26','2025-03-20 20:35:01'),(52,1,'Usuário isac.borgert editou a agenda id 34, id do técnico é 1','2025-03-20 20:39:41'),(53,1,'Usuário isac.borgert excluiu a agenda id 30','2025-03-20 21:02:29'),(54,2,'Usuário leandro.pacheco ação upload ilustrativa-off.png na tela projetos mapeados','2025-03-20 21:36:55'),(55,2,'Usuário leandro.pacheco ação upload projeto-codigo-cliente-345.png na tela projetos mapeados','2025-03-20 21:37:37'),(56,4,'Usuário teste1 ação upload EG8145V5-V2-los.png na tela projetos mapeados','2025-03-20 21:58:42'),(57,1,'Usuário isac.borgert ação upload projeto-codigo-cliente-345.png na tela projetos mapeados','2025-03-20 22:16:05'),(58,1,'Usuário isac.borgert ação upload projeto-codigo-cliente-345.png na tela projetos mapeados','2025-03-20 22:40:27'),(59,1,'Usuário isac.borgert ação upload Adobe Express - file.png na tela projetos mapeados','2025-03-20 22:40:34'),(60,1,'Usuário isac.borgert ação delete ilustrativa-off.png na tela projetos mapeados','2025-03-20 23:15:41'),(61,1,'Usuário isac.borgert ação delete EG8145V5-V2-los.png na tela projetos mapeados','2025-03-20 23:16:50'),(62,1,'Usuário isac.borgert ação upload ilustrativa-off.png na tela projetos mapeados','2025-03-20 23:21:11'),(63,1,'Usuário isac.borgert ação delete ilustrativa-off.png na tela projetos mapeados','2025-03-20 23:22:33'),(64,1,'Usuário isac.borgert ação upload EG8145V5-V2-on.gif na tela projetos mapeados','2025-03-20 23:22:41'),(65,1,'Usuário isac.borgert ação upload EG8145V5-V2-los.gif na tela projetos mapeados','2025-03-20 23:22:55'),(66,1,'Usuário isac.borgert ação upload projeto-codigo-cliente-345 - Copy (2).png na tela projetos mapeados','2025-03-20 23:23:48'),(67,1,'Usuário isac.borgert ação upload projeto-codigo-cliente-345 - Copy.png na tela projetos mapeados','2025-03-20 23:23:58'),(68,4,'Usuário teste1 ação upload EG8145V5-V2-los.png na tela projetos mapeados','2025-03-20 23:31:44'),(69,4,'Usuário teste1 ação delete EG8145V5-V2-los.png na tela projetos mapeados','2025-03-20 23:33:31'),(70,1,'Usuário isac.borgert ação upload Adobe Express - file.png na tela projetos mapeados','2025-03-20 23:40:08'),(71,1,'Usuário isac.borgert editou a agenda id 31, id do técnico é 1','2025-03-21 10:23:51'),(72,1,'Usuário isac.borgert atualizou usuário isac.borgert para nível 5.','2025-03-21 11:02:53'),(73,1,'Usuário isac.borgert atualizou usuário leandro.pacheco para nível 5.','2025-03-21 11:03:10'),(74,1,'Usuário isac.borgert cadastrou o usuário teste6 com nível 3.','2025-03-21 11:05:34'),(75,1,'Usuário isac.borgert atualizou usuário teste1 para nível 1.','2025-03-21 11:06:30'),(76,1,'Usuário isac.borgert atualizou usuário teste2 para nível 1.','2025-03-21 11:06:33'),(77,1,'Usuário isac.borgert atualizou usuário teste3 para nível 1.','2025-03-21 11:06:36'),(78,1,'Usuário isac.borgert atualizou usuário teste5 para nível 3.','2025-03-21 11:06:39'),(79,4,'Usuário teste1 adicionou uma nova agenda, id do técnico é 1','2025-03-21 11:20:39'),(80,4,'Usuário teste1 editou a agenda id 39, id do técnico é 1','2025-03-21 11:23:38'),(81,4,'Usuário teste1 editou a agenda id 39, id do técnico é 1','2025-03-21 11:26:21'),(82,4,'Usuário teste1 editou a agenda id 39, id do técnico é 1','2025-03-21 11:28:52'),(83,1,'Usuário isac.borgert atualizou usuário teste1 para nível 1.','2025-03-21 11:41:29'),(84,1,'Usuário isac.borgert excluiu a agenda id 34','2025-03-21 11:47:53'),(85,4,'Usuário teste1 editou a agenda id 31, id do técnico é 1','2025-03-21 11:54:26'),(86,1,'Usuário isac.borgert editou a agenda id 35, id do técnico é 51','2025-03-21 12:00:09'),(87,1,'Usuário isac.borgert editou a agenda id 35, id do técnico é 51','2025-03-21 12:01:56'),(88,4,'Usuário teste1 adicionou uma nova agenda, id do técnico é 51','2025-03-21 12:05:07'),(89,1,'Usuário isac.borgert excluiu a agenda id 35','2025-03-21 12:10:03'),(90,4,'Usuário teste1 adicionou uma nova agenda, id do técnico é 1','2025-03-21 12:21:07'),(91,1,'Usuário isac.borgert excluiu a agenda id 39','2025-03-21 12:22:25'),(92,1,'Usuário isac.borgert excluiu a agenda id 39','2025-03-21 12:22:57'),(93,1,'Usuário isac.borgert excluiu a agenda id 39','2025-03-21 12:23:13'),(94,1,'Usuário isac.borgert excluiu a agenda id 39','2025-03-21 12:24:59'),(95,1,'Usuário isac.borgert excluiu a agenda id 39','2025-03-21 12:25:45'),(96,1,'Usuário isac.borgert excluiu a agenda id 39','2025-03-21 12:26:04'),(97,1,'Usuário isac.borgert excluiu a agenda id 39','2025-03-21 12:26:15'),(98,1,'Usuário isac.borgert excluiu a agenda id 39','2025-03-21 12:26:37'),(99,1,'Usuário isac.borgert excluiu a agenda id 39','2025-03-21 12:27:09'),(100,1,'Usuário isac.borgert excluiu a agenda id 39','2025-03-21 12:34:55'),(101,1,'Usuário isac.borgert editou a agenda id 41, id do técnico é 1','2025-03-21 12:35:44'),(102,1,'Usuário isac.borgert excluiu a agenda id 31','2025-03-21 12:41:08'),(103,1,'Usuário isac.borgert excluiu a agenda id 31','2025-03-21 12:42:45'),(104,1,'Usuário isac.borgert excluiu a agenda id 31','2025-03-21 12:48:27'),(105,1,'Usuário isac.borgert excluiu a agenda id 31','2025-03-21 13:00:04'),(106,1,'Usuário isac.borgert excluiu a agenda id 41','2025-03-21 13:13:44'),(107,50,'Usuário usuario_de_teste_L3 trocou a senha do usuario_de_teste_L3.','2025-03-21 13:41:50'),(108,4,'Usuário usuario_de_teste_L1 trocou a senha do usuario_de_teste_L1.','2025-03-21 13:42:15'),(109,50,'Usuário usuario_de_teste_L3 adicionou uma nova agenda, id do técnico é 1','2025-03-21 13:45:23'),(110,50,'Usuário usuario_de_teste_L3 editou a agenda id 42, id do técnico é 1','2025-03-21 13:46:45'),(111,1,'Usuário isac.borgert atualizou usuário usuario_de_teste_L3 para nível 3.','2025-03-21 13:47:34'),(112,1,'Usuário isac.borgert atualizou usuário usuario_de_teste_L3 para nível 3.','2025-03-21 13:47:46'),(113,1,'Usuário isac.borgert atualizou usuário usuario_de_teste_L3 para nível 3.','2025-03-21 13:54:28'),(114,1,'Usuário isac.borgert editou a agenda id 31, id do técnico é 1 Dados do serviço: hkk 7777  Materiais: kjkk  Data de execução: 2025-04-02 16:00:00','2025-03-21 13:57:31'),(115,1,'Usuário isac.borgert editou a agenda id 31, id do técnico é 1 Dados do serviço: hkk 7777  Se os    Materiais: kjkkSe hkk 7777   Data de execução: 2025-04-02 16:00:00','2025-03-21 14:02:13'),(116,1,'Usuário isac.borgert editou a agenda id 43, id do técnico é 2 Dados do serviço: Teste de inserção com um texto maior que 300 caracteres. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Aenean in felis non odio tincidunt bibendum ut in urna. Curabitur sagittis, orci ac bibendum pellentesque, eros nunc varius odio, vitae vehicula nunc justo sed velit. Integer at dui et turpis accumsan fringilla at vel urna.  Materiais: Cabo de fibra, ONU modelo X, Switch 24 portas  Data de execução: 2025-03-22 10:00:00','2025-03-21 14:14:45'),(117,1,'Usuário isac.borgert atualizou usuário usuario_de_teste_L3 para nível 3.','2025-03-21 14:34:27'),(118,1,'Usuário isac.borgert ação upload projeto-codigo-cliente-345 - Copy (2).png na tela projetos mapeados','2025-03-21 16:00:30'),(119,1,'Usuário isac.borgert editou a agenda id 42, id do técnico é 1 Dados do serviço: teste dia 21-03-2025  Materiais: teste dia 21-03-2025  Data de execução: 2025-03-21 21:00:00','2025-03-21 16:01:52'),(120,1,'Usuário isac.borgert editou a agenda id 31, id do técnico é 1 Dados do serviço: hkk 7777  Se os    Materiais: kjkkSe hkk 7777   Data de execução: 2025-04-02 16:00:00','2025-03-21 17:59:15'),(121,1,'Usuário isac.borgert editou a agenda id 42, id do técnico é 1 Dados do serviço: teste dia 21-03-2025 isac  Materiais: teste dia 21-03-2025  Data de execução: 2025-03-21 21:00:00','2025-03-21 17:59:56'),(122,4,'Usuário usuario_de_teste_L1 criou uma nova agenda, id do técnico é 1 Dados do serviço: fff  Materiais: fff  Data de execução: 2025-04-03 10:00:00','2025-03-21 18:03:10'),(123,4,'Usuário usuario_de_teste_L1 editou a agenda id 44, id do técnico é 1 Dados do serviço: fff  Materiais: fff  Data de execução: 2025-04-03 10:00:00','2025-03-21 18:03:36'),(124,4,'Usuário usuario_de_teste_L1 editou a agenda id 31, id do técnico é 1 Dados do serviço: hkk 7777  Se os    Materiais: kjkkSe hkk 7777   Data de execução: 2025-04-02 16:00:00','2025-03-24 10:59:34'),(125,4,'Usuário usuario_de_teste_L1 editou a agenda id 31, id do técnico é 1 Dados do serviço: hkk 7777  Se os    Materiais: kjkkSe hkk 7777   Data de execução: 2025-04-02 16:00:00','2025-03-24 11:54:55'),(126,1,'Usuário isac.borgert criou uma nova agenda, id do técnico é 1 Dados do serviço: fdfd  Materiais: fdf  Data de execução: 2025-04-03 06:00:00','2025-03-26 15:43:51'),(127,1,'Usuário isac.borgert editou a agenda id 31, id do técnico é 1 Dados do serviço: hkk 7777  Se os    Materiais: kjkkSe hkk 7777   Data de execução: 2025-04-02 16:00:00','2025-03-26 21:46:37');
/*!40000 ALTER TABLE `historico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `textos_de_idiomas`
--

DROP TABLE IF EXISTS `textos_de_idiomas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `textos_de_idiomas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `texto_ingles` text NOT NULL,
  `texto_portugues` text NOT NULL,
  `texto_espanhol` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `textos_de_idiomas`
--

LOCK TABLES `textos_de_idiomas` WRITE;
/*!40000 ALTER TABLE `textos_de_idiomas` DISABLE KEYS */;
INSERT INTO `textos_de_idiomas` VALUES (1,'remover','Remove','Remover','Remover'),(2,'alterar','Change','Alterar','Cambiar'),(3,'processador','Processor','Processador','Procesador'),(4,'statusdas','Status of','Status das','Estado de las'),(5,'temperatura','Temperature','Temperatura','Temperatura'),(6,'geral','General','Geral','General'),(7,'provisionar','Provision','Provisionar','Provisionar'),(8,'gest_saud',' Management and Health','Gestao e Saude','Gestión y Salud'),(9,'consultar','Consult','Consultar','Consultar'),(10,'listar','List','Listar','Listar'),(11,'reiniciar','Restart','Reiniciar','Reiniciar'),(12,'cadastro_de','Registration of','Cadastro de','Registro de'),(13,'de','of','de','de'),(14,'listagem_de','Listing of ','listagem de ','listado de'),(15,'cod_name_serial','Enter the ONU serial number, name, or client code.','Digite o número de série da ONU, nome ou código do cliente.','Ingrese el número de serie de la ONU, nombre o código del cliente.'),(16,'excluir','Delete','Excluir','Eliminar'),(17,'relatorio','Report','Relatório','Informe'),(18,'onusduplicadas','Duplicated ONUs','ONUs duplicadas','ONUs duplicadas'),(19,'sinal_forte_ou_fraco','Very strong or very weak signal','Sinal muito forte ou muito fraco','Señal muy fuerte o muy débil'),(20,'usuario','User','Usuário','Usuario'),(21,'ferramenta','Tool','Ferramenta','Herramienta'),(22,'projetos_mapeados','Mapped Projects','Projetos Mapeados','Proyectos Mapeados'),(23,'atualizacao','Update','Atualização','Actualización'),(24,'monitoramento','Monitoring','Monitoramento','Monitoreo'),(25,'reiniciarservicos','Restart the services','Reiniciar os serviços','Reiniciar los servicios'),(26,'gest_users','User Management','Gestão de Usuários','Gestión de Usuarios'),(27,'log_users','User action log','Log de ações dos usuários','Registro de acciones de los usuarios'),(28,'trocar_senha','Change Password','Trocar Senha','Cambiar contraseña'),(29,'selecione','Select','Selecione','Seleccione'),(30,'a','the','a','la'),(31,'nome','Name','Nome','Nombre'),(32,'da','of the','da','de la'),(33,'nova','New','Nova','Nueva'),(34,'com','with','com','con'),(35,'excl_sis_e_olt','Delete from system and OLT','Excluir do sistema e da OLT','Eliminar del sistema y de la OLT'),(36,'excl_only_sis','Delete only from the system','Excluir apenas do sistema','Eliminar solo del sistema'),(37,'dig_id_reg','Enter the record ID','Digite o ID do registro','Ingrese el ID del registro'),(38,'fabricante','Manufacturer','Fabricante','Fabricante'),(39,'o','the','o','el'),(40,'senha','Password','Senha','Contraseña'),(41,'modelo','Model','Modelo','Modelo'),(42,'cadastrar','Register','Cadastrar','Registrar'),(43,'cancelar','Cancel','Cancelar','Cancelar'),(44,'acoes','Actions','Ações','Acciones'),(45,'editar','Edit','Editar','Editar'),(46,'lista','List','Lista','Lista'),(47,'cadastro','Registration','Cadastro','Registro'),(48,'cad_user','User Registration','Cadastro de usuário','Registro de usuario'),(49,'list_users','User List','Lista de usuários','Lista de usuarios'),(50,'list_pon','PON Listing','Listagem de PON','Listado de PON'),(51,'parametro','Parameter','Parâmetro','Parámetro'),(52,'observacao','Note','Observação','Observación'),(53,'gerenciar','Manage','Gerenciar','Gestionar'),(54,'perfis','Profiles','Perfis','Perfiles'),(55,'enviar','Send','Enviar','Enviar'),(56,'limpar','Clear','Limpar','Limpiar'),(57,'filtr_olt_pon','OLT and PON Filter','Filtro de OLT e PON','Filtro de OLT y PON'),(58,'nome_onu','ONU Name','Nome da ONU','Nombre de la ONU'),(59,'sinal','Signal','Sinal','Señal'),(60,'resultado','Result','Resultado','Resultado'),(61,'open_guia_excluse','Open this page in an exclusive tab in the browser.','Abrir essa página em guia exclusiva no navegador.','Abrir esta página en una pestaña exclusiva en el navegador.'),(62,'por_serial','By Serial','Por Serial','Por Serial'),(63,'por_nome','By Name','Por Nome','Por Nombre'),(64,'sinal_pior_que','Signal worse than','Sinal pior que','Señal peor que'),(65,'mais_forte_que','Stronger than','Mais forte que','Más fuerte que'),(66,'nivel','Level','Nível','Nivel'),(67,'user_nivel_perm','User and Permission Level','Usuário e Nível de Permissão','Usuario y Nivel de Permiso'),(68,'user_name','Username','Nome de usuário','Nombre de usuario'),(69,'atualizar','Update','Atualizar','Actualizar'),(70,'hist_act_geral','General Action History','Histórico de ações geral','Historial de acciones general'),(71,'inicio','Star','Inicio','Inicio'),(72,'final','End','Final','Final'),(73,'acao','Action','Ação','Acción'),(74,'data_e_hora','Date and Time','Data e Hora','Fecha y Hora'),(75,'no_regist_found','No records found.','Nenhum registro encontrado.','No se encontraron registros.'),(76,'keyword','Enter the keyword','Digite a palavra-chave','Ingrese la palabra clave'),(77,'filtrar','Filter','Filtrar','Filtrar'),(78,'alvo','Target','Alvo','Objetivo'),(79,'select_user','Select a user','Selecione um usuário','Seleccione un usuario'),(80,'antiga','Old','Antiga','Antigua'),(81,'senha_antiga','Old Password','Senha Antiga','Contraseña Antigua'),(82,'nova_senha','New Password','Nova Senha','Nueva Contraseña'),(83,'confirm_senha','Confirm New Password','Confirme a Nova Senha','Confirme la Nueva Contraseña'),(84,'adicionar','Add','Adicionar','Agregar'),(85,'up_sus_with_olt','Update the system with OLT data','Atualizar o sistema com dados da OLT','Actualizar el sistema con datos de la OLT'),(86,'1min_10_cli','It takes 1 minute for every 10 new customers','Leva 1 minuto a cada 10 novos clientes','Toma 1 minuto por cada 10 nuevos clientes'),(87,'no_olt','No OLT available','Nenhuma OLT disponível','No hay OLT disponible'),(88,'monitoramento_onus','ONU Monitoring','Monitoramento de ONUs','Monitoreo de ONUs'),(89,'off_desde','Off since','Off desde','Apagado desde'),(90,'status','Status','Status','Estado'),(91,'all_onus_on','All ONUs are online.','Todas as ONUs estão online.','Todas las ONUs están en línea.'),(92,'ger_server','Server Management','Gerenciamento do Servidor','Gestión del Servidor'),(93,'term_exec','Execution Terminal','Terminal de execução','Terminal de Ejecución'),(94,'proj_maps','Projects in PDF and Images','Projetos em PDF e Imagens','Proyectos en PDF e Imágenes'),(95,'add_pdf_img','Add PDF or Image','Adicionar PDF ou Imagem','Agregar PDF o Imagen'),(96,'pesq_arq','Search for files','Pesquise por arquivos','Buscar archivos'),(97,'visualizar','View','Visualizar','Visualizar'),(98,'para','for','para','para'),(99,'prov_como','Provision as','Provisionar como','Provisionar como'),(100,'abaixo_de','below','abaixo de','por debajo del'),(101,'todas','All','Todas','Todas'),(102,'buscar','Search ','Buscar','Buscar'),(103,'busc_all_or_search','Click on \"List All\" or perform a search.','Clique em \"Listar Todas\" ou faça uma busca.','Haz clic en \"Listar Todas\" o realiza una búsqueda.'),(104,'mudar_vlan','Change ONU VLAN','Mudar a VLAN da ONU','Cambiar VLAN ONU'),(105,'take1or3minutes','It takes 1 to 3 minutes.','Leva de 1 a 3 minutos.','Tarda de 1 a 3 minutos.'),(106,'selct_olt_to_backup','Select an OLT for backup.','Selecione uma OLT para backup.','Seleccione una OLT para copia de seguridad.'),(107,'carregando','Loading...','Carregando...','Cargando...'),(108,'ssh_com_detalhes','View in real-time via SSH with details.','Ver em tempo real por SSH com detalhes.','Ver en tiempo real por SSH con detalles.'),(109,'ver_todos_da_fibra','View all clients on this fiber.','Ver todos os cliente dessa fibra.','Ver todos los clientes de esta fibra.'),(110,'please-wait','Please wait.','Por favor, aguarde.','Por favor, espere.'),(111,'reiniciar_a_onu_certeza','Are you sure you want to restart the ONU?','Tem certeza que deseja reiniciar a ONU?','¿Está seguro de que desea reiniciar la ONU?'),(112,'reinicar_espere_onu_voltar','Restart command sent, please wait for the ONU to come back.','Comando reiniciar enviado, aguarde a ONU voltar.','Comando de reinicio enviado, espere a que la ONU vuelva.'),(113,'sinal_onu','ONU signal','Sinal da ONU','Señal de la ONU'),(114,'grafico_sinal_diario','Daily Signal Chart','Gráfico de Sinal Diário','Gráfico de Señal Diario'),(115,'dia_do_mes','Day of the Month','Dia do Mês','Día del Mes'),(116,'nao_identificado','not identified','não identificado','no identificado'),(117,'porta','Port','Porta','Puerto'),(118,'excluir_certeza','Are you sure you want to delete?','Tem certeza que deseja excluir?','¿Está seguro de que desea eliminar?');
/*!40000 ALTER TABLE `textos_de_idiomas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_permissao` int NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '1',
  `ultimo_login` datetime DEFAULT NULL,
  `idioma` varchar(10) DEFAULT 'portugues',
  `nome_empresa` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'isac.borgert','$2y$10$Els2r7YRucw7.h3sg3NK8.z2GJPc1CeH5xz2LNLTcEniF9NaHXB/W',5,'2025-03-17 21:07:48',1,'2025-04-02 12:48:39','portugues','Provedor-XXX','isacborgert@gmail.com'),(2,'leandro.pacheco','$2y$10$NinoARflfSiY7rhtRJ7j2eFkVhPNhDEP1nfwiFdIy0bc94KGOL5t2',5,'2025-03-17 20:52:23',1,'2025-03-20 21:04:42','portugues','Provedor-XXX','leandro.debetio@gmail.com'),(4,'usuario_de_teste_L1','$2y$10$GqwxVkeCf95W3LlbEP0PC.qRzKQKt1wztAg4kvZz03kEqNJAc.7Ua',1,'2025-03-17 21:06:50',1,'2025-03-26 15:43:12','portugues','Provedor-XXX','santaluziainternet@gmail.com'),(49,'teste2','$2y$10$rtRJX36E2tGVfoArkC4jW.0qZK7GftuctvK6T86IKI5nk6q2xh8nS',1,'2025-03-19 16:38:18',1,NULL,'portugues','Provedor-XXX','testes@gmail.com'),(50,'usuario_de_teste_L3','$2y$10$XDU/z8xlbjyUBEtaGbhDzOGAvuDFwkF1FaY3RORmLfcD.xDKTFgaq',3,'2025-03-20 16:04:02',1,'2025-03-26 15:45:31','portugues','Provedor-XXX','santaluziainternet@gmail.com'),(51,'teste5','$2y$10$oiNM/3UWkJKMYPIrmcAaE.72jXO2QRcFNEpEyf29lmlTkzcmIJT.O',3,'2025-03-20 16:24:51',1,'2025-03-21 08:37:51','portugues','YYprovedor','testes@gmail.com'),(52,'teste6','$2y$10$GWO.tyMuIz2Cgr5bG36p5OcDJBosmvIF5CPxoTcpbnmVfK8Dy30Ia',3,'2025-03-21 14:05:34',1,NULL,'ingles','nomeempresaaqui','testes@gmail.com');
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

-- Dump completed on 2025-04-02 17:21:24
