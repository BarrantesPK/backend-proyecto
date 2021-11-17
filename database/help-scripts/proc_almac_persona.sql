USE prematric;

DELIMITER $$

DROP PROCEDURE IF EXISTS todosPersona$$
CREATE DEFINER=root@localhost PROCEDURE todosPersona(_pagina TINYINT, _cantRegs TINYINT) 
begin
    select * from persona order by nombre limit _pagina, _cantRegs;
end$$

DROP PROCEDURE IF EXISTS buscarPersona$$
CREATE DEFINER=root@localhost PROCEDURE buscarPersona(_id VARCHAR(15))
begin
    select * from persona where id = _id;
end$$

DROP FUNCTION IF EXISTS nuevaPersona$$
CREATE DEFINER=root@localhost FUNCTION nuevaPersona(_id VARCHAR(15), _nombre VARCHAR(15), _apellido1 VARCHAR(15), _apellido2 VARCHAR(15), _sexo CHAR(1), _barrio VARCHAR(20), _nivelAcademico VARCHAR(30), _telefonoCasa VARCHAR(8), _telefonoCelular VARCHAR(8), _telefonoOtro VARCHAR(8), _referidoIMAS CHAR(1), _fechaNac DATE) RETURNS INT(1)
begin
    declare _cant int;

    select count(id) into _cant from persona where id = _id;

    if _cant < 1 then
        insert into persona(id,	nombre, apellido1, apellido2, sexo, barrio, nivelAcademico, telefonoCasa, telefonoCelular, telefonoOtro, referidoIMAS, fechaNac) values(_id, _nombre, _apellido1, _apellido2, _sexo, _barrio, _nivelAcademico, _telefonoCasa, _telefonoCelular, _telefonoOtro, _referidoIMAS, _fechaNac);
    end if;

    return _cant;
end$$

DROP FUNCTION IF EXISTS editarPersona$$
CREATE DEFINER=root@localhost FUNCTION editarPersona(_id VARCHAR(15), _nombre VARCHAR(15), _apellido1 VARCHAR(15), _apellido2 VARCHAR(15), _sexo CHAR(1), _barrio VARCHAR(20), _nivelAcademico VARCHAR(30), _telefonoCasa VARCHAR(8), _telefonoCelular VARCHAR(8), _telefonoOtro VARCHAR(8), _referidoIMAS CHAR(1), _fechaNac DATE) RETURNS INT(1)
begin
    declare _cant int;
    select count(id) into _cant from persona where id = _id;

    if _cant > 0 then
        update persona set nombre = _nombre, apellido1= _apellido1, apellido2= _apellido2, sexo= _sexo, barrio= _barrio,nivelAcademico= _nivelAcademico, telefonoCasa= _telefonoCasa,telefonoCelular= _telefonoCelular, telefonoOtro= _telefonoOtro, referidoIMAS= _referidoIMAS, fechaNac = _fechaNac where id = _id;
    end if;

    return _cant;
end$$

DROP FUNCTION IF EXISTS eliminarPersona$$
CREATE DEFINER=root@localhost FUNCTION eliminarPersona(_id VARCHAR(15)) RETURNS INT(1)
begin
    declare _cant int;
    select count(id) into _cant from persona where id = _id;

    if _cant > 0 then
        delete from persona where id = _id;
    end if;

    return _cant;

end$$

DELIMITER ;



