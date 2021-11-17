USE prematric;

DELIMITER $$
--
-- Funciones
--
DROP PROCEDURE IF EXISTS todosUsuario$$
CREATE DEFINER=root@localhost PROCEDURE todosUsuario (_pagina TINYINT, _cantRegs TINYINT)
begin
    select * from usuario order by nombre limit _pagina, _cantRegs;
end$$

DROP PROCEDURE IF EXISTS buscarUsuario$$
CREATE DEFINER=root@localhost PROCEDURE buscarUsuario (_id varchar(100))
begin
    select * from usuario where id = _id;
end$$

DROP FUNCTION IF EXISTS nuevoUsuario$$
CREATE DEFINER=root@localhost FUNCTION nuevoUsuario (_id varchar(100), _nombre VARCHAR(50), 
    _rol tinyint, _passw varchar(255) ) RETURNS INT(1)
begin
    declare _cant int;
    select count(_id) into _cant from usuario where id = _id;
    if _cant < 1 then
        insert into usuario(id, nombre, rol, passw) 
			values (_id, _nombre, _rol, _passw);
    end if;
    return _cant;
end$$

DROP FUNCTION IF EXISTS editarUsuario$$
CREATE DEFINER=root@localhost FUNCTION editarUsuario (_id varchar(100), _nombre VARCHAR(50),
                                                      _rol tinyint) RETURNS INT(1) 
begin
    declare _cant int;
    select count(id) into _cant from usuario where id = _id;
    if _cant > 0 then
        update usuario set
			nombre = _nombre,
            rol = _rol
        where id = _id;
    end if;
    return _cant;
end$$

DROP FUNCTION IF EXISTS eliminarUsuario$$
CREATE DEFINER=root@localhost FUNCTION eliminarUsuario (_id varchar(100)) RETURNS INT(1)
begin
    declare _cant int;
    select count(id) into _cant from usuario where id = _id;
    if _cant > 0 then
        delete from usuario where id = _id;
    end if;
    return _cant;
end$$

DROP FUNCTION IF EXISTS cambiarPasswUsuario$$
CREATE DEFINER=root@localhost FUNCTION cambiarPasswUsuario (_id varchar(100), _passw varchar(255)) RETURNS INT(1)
begin
    declare _cant int;
    select count(id) into _cant from usuario where id = _id;
    if _cant > 0 then
        update usuario set
			passw = _passw
        where id = _id;
    end if;
    return _cant;
end$$


DELIMITER ;