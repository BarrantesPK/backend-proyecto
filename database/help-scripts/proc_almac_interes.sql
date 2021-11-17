USE prematric;

DELIMITER $$

DROP PROCEDURE IF EXISTS todosInteres$$
CREATE DEFINER=root@localhost PROCEDURE todosInteres(_inic TINYINT, _fin TINYINT) 
begin
    select * from interes order by codCurso limit _inic, _fin;
end$$

DROP PROCEDURE IF EXISTS buscarInteres$$
CREATE DEFINER=root@localhost PROCEDURE buscarInteres(_cod TINYINT)
begin
    select * from interes where codigo = _cod;
end$$

DROP PROCEDURE IF EXISTS nuevoInteres$$
CRETE DEFINER=root@localhost PROCEDURE nuevoInteres(_codCurso INT(11), _idPersona VARCHAR(15), _fecha DATE)
begin
    insert into interes(codCurso, idPersona, fecha) values (_codCurso, _idPersona, _fecha);
end$$

DROP FUNCTION IF EXISTS editarInteres$$
CREATE DEFINER=root@localhost FUNCTION editarInteres(_codigo INT(11), _codCurso INT(11), _idPersona VARCHAR(15), _fecha DATE) RETURNS INT(1)
begin
    declare _cant int;
    select count(codigo) into _cant from interes where codigo = _codigo;

    if _cant > 0 then
        update interes set codCurso = _codCurso, idPersona = _idPersona, fecha = _fecha where codigo = _codigo;
    end if;

    return _cant;
end$$

DROP FUNCTION IF EXISTS eliminarInteres$$
CREATE DEFINER=root@localhost FUNCTION eliminarInteres(_codigo INT(11)) RETURNS INT(1)
begin
    declare _cant int;
    select count(codigo) into _cant from interes where codigo = _codigo;

    if _cant > 0 then
        delete from interes where codigo = _codigo;
    end if;

    return _cant;
end$$

DELIMITER ;