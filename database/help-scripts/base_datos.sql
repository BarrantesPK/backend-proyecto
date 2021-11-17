SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
--
-- Base de datos: prematric
--
DROP DATABASE IF EXISTS prematric;
CREATE DATABASE prematric DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE prematric;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla cursos
--
-- DROP TABLE IF EXISTS cursos;
CREATE TABLE cursos (
  codigo int(11) NOT NULL AUTO_INCREMENT ,
  nombre varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla persona
--
-- DROP TABLE IF EXISTS persona;
CREATE TABLE persona (
  id varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  nombre varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  apellido1 varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  apellido2 varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  sexo char(1) NOT NULL,
  barrio varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  nivelAcademico varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  telefonoCasa varchar(8) NULL,
  teleofnoCelular varchar(8) NOT NULL,
  telefonoOtro varchar(8)  NULL,
  referidoIMAS char(1) NOT NULL,
  fechaNac date NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla interes
--
-- DROP TABLE IF EXISTS interes;
CREATE TABLE interes (
  codigo int(11) NOT NULL AUTO_INCREMENT ,
  codCurso int(11) NOT NULL,
  idPersona varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  Fecha date NOT NULL,
  PRIMARY KEY (codigo),
  FOREIGN KEY (codCurso) REFERENCES cursos(codigo),
  FOREIGN KEY (idPersona) REFERENCES persona(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla Preselección
--
-- DROP TABLE IF EXISTS preseleccion;
CREATE TABLE preseleccion (
  codigo int(11) NOT NULL AUTO_INCREMENT ,
  codInteres int(11) NOT NULL,
  fechaInicio date NOT NULL,
  PRIMARY KEY (codigo),
  FOREIGN KEY (codInteres) REFERENCES interes(codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla usuario
--
-- DROP TABLE IF EXISTS usuario;
CREATE TABLE usuario (
  id varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  nombre varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  rol tinyint NOT NULL,
  passw varchar(255) COLLATE utf8_spanish_ci NOT NULL,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

COMMIT;
