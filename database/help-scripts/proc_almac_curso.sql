USE prematric;

DELIMITER $$
--
-- Funciones
--
DROP PROCEDURE IF EXISTS todosCurso$$
CREATE DEFINER=root@localhost PROCEDURE todosCurso (_pagina TINYINT, _cantRegs TINYINT)
begin
    select * from cursos order by nombre limit _pagina, _cantRegs;
end$$

DROP PROCEDURE IF EXISTS buscarCurso$$
CREATE DEFINER=root@localhost PROCEDURE buscarCurso (_codigo TINYINT)
begin
    select * from cursos where codigo = _codigo;
end$$

DROP FUNCTION IF EXISTS nuevoCurso$$
CREATE DEFINER=root@localhost FUNCTION nuevoCurso (_nombre VARCHAR(50)) RETURNS INT(1) 
begin
    declare _cant int;
    select count(codigo) into _cant from cursos where nombre = _nombre;
    if _cant < 1 then
        insert into cursos(nombre) 
			values (_nombre);
    end if;
    return _cant;
end$$

DROP FUNCTION IF EXISTS editarCurso$$
CREATE DEFINER=root@localhost FUNCTION editarCurso (_codigo INT(11), _nombre VARCHAR(50)) RETURNS INT(1) 
begin
    declare _cant int;
    select count(codigo) into _cant from curso where codigo = _codigo;
    if _cant > 0 then
        select count(codigo) into _cant from curso where nombre = _nombre and codigo <> _codigo;
        
        if _cant = 0 then
            update curso set
			nombre = _nombre
            where codigo = _codigo;

            set _cant = 1;

        else
            set _cant = 2;
        end if;
    end if;
    return _cant;
end$$

DROP FUNCTION IF EXISTS eliminarCurso$$
CREATE DEFINER=root@localhost FUNCTION eliminarCurso (_codigo INT(11)) RETURNS INT(1)
begin
    declare _cant int;
    select count(codigo) into _cant from cursos where codigo = _codigo;
    if _cant > 0 then
        delete from cursos where codigo = _codigo;
    end if;
    return _cant;
end$$

DELIMITER ;