USE prematric;
--
-- Estructura de tabla para la tabla usuario
--
DROP TABLE IF EXISTS usuario;
CREATE TABLE usuario (
  id varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  nombre varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  rol tinyint NOT NULL,
  passw varchar(255) COLLATE utf8_spanish_ci NOT NULL,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO usuario (id, nombre, rol, passw)
      VALUES ('admin', 'Administrador de Sistema', 1, '12345');

COMMIT;
