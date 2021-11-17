USE prematric;

DELIMITER $$

DROP FUNCTION IF EXISTS `siguienteCurso`$$
CREATE FUNCTION `siguienteCurso`() RETURNS INT(1)
BEGIN
    declare _cant int;
    select AUTO_INCREMENT into _cant from INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA = 'prematric'
    and TABLE_NAME = 'curso';
    RETURN _cant; 
END$$

DELIMITER ;