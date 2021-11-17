USE prematric;
alter table usuario 
    add rfT text null;

DELIMITER $$

-- DROP FUNCTION IF EXISTS modificarToken$
CREATE DEFINER=root@localhost FUNCTION modificarToken (_id VARCHAR(100), _rfT text) RETURNS INT(1) 
begin
    declare _cant int;
    select count(id) into _cant from usuario where id = _id;
    if _cant > 0 then
        update usuario set
                rfT = _rfT
                where id = _id;
    end if;
    return _cant;
end$$

DELIMITER ;


