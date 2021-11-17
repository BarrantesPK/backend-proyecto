<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use \PDO;
use PhpParser\Node\Stmt\Return_;

class BaseBD {
    protected $container;
    private $tabla;
    private $campoCodigo;

    public function __construct(ContainerInterface $c) {
        $this->container = $c;
    }

    public function iniciar($tabla, $campoCodigo){
        $this->tabla = $tabla;
        $this->campoCodigo = $campoCodigo;
    }

    private function cadenaFiltro($campos, $valores){
        $filtro = "";

        for($i = 0; $i < sizeof($campos); $i++){
            $filtro .= $campos[$i] ." LIKE '%". $valores[$i] ."%' and "; 
        }

        $filtro = substr($filtro, 0, -5);

        return $filtro;
    }

    private function generarConsulta($datos, $codigo = null){
        $cadena = "(";


        if($codigo != null){
            $cadena .= ":". $this->campoCodigo .",";
        }

        foreach($datos as $campo => $valor){
            $cadena .= ":". $campo. ",";
        } // (:id, :nombre, :pass,

        $cadena = trim($cadena, ","); // (:id, :nombre, :pass
        $cadena .= ")";// (:id, :nombre, :pass)

        return $cadena;
    }

    private function getConst($valor){
        $tipo = gettype($valor); //obtiene el tipo de dato de la variable
        switch($tipo){
            case "integer":
                return PDO::PARAM_INT;
            break;

            case "string": case "double":
                return PDO::PARAM_STR;
            break;

            default:
                return PDO::PARAM_STR;
        }
    }


    //CRUD CURSO......................................
    public function todos($pag, $limite) {
        $pag = ($pag - 1) * $limite;

        $conexion = $this->container->get('bd');

        $sql = "call todos". $this->tabla ."(:indice, :limite)";
        $consulta = $conexion->prepare($sql);

        $consulta->bindParam(':indice', $pag, PDO::PARAM_INT);
        $consulta->bindParam(':limite', $limite, PDO::PARAM_INT);
        $consulta->execute();

        $datos = [];

        if ($consulta->rowCount() > 0) {
            $i = 0;
            while ($resultado = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $i++;
                foreach ($resultado as $clave => $valor) {
                    $datos[$i][$clave] = $valor; 
                }
            }
        }

        $consulta = null;
        $conexion = null;

        return $datos;
    }

    public function buscar($codigo) {
        $conexion = $this->container->get('bd');

        $sql = "call buscar". $this->tabla ."(:codigo)";
        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':codigo', $codigo, $this->getConst($codigo));
        $consulta->execute();

        $datos = $consulta->fetchAll();

        $consulta = null;
        $conexion = null;

        return $datos;
    }

    public function guardar($datos, $codigo = null) {
        $conexion = $this->container->get('bd');

        if ($codigo != null) {
            $sql = "select editar" . $this->tabla . $this->generarConsulta($datos, $codigo);
        } else {
            $sql = "select nuevo" . $this->tabla . $this->generarConsulta($datos);
        }

        $consulta = $conexion->prepare($sql);

        foreach($datos as $campo => $valor){
            $consulta->bindValue(":$campo", $valor, $this->getConst($valor));
        }

        if($codigo != null){
            $consulta->bindParam(':'.$this->campoCodigo, $codigo, $this->getConst($codigo));
        }
        $consulta->execute();

        $datos = $consulta->fetch(PDO::FETCH_NUM);
        $consulta = null;
        $conexion = null;

        return $datos;
    }

    public function eliminar($codigo){
        $conexion = $this->container->get('bd');

        $sql = "select eliminar". $this->tabla ."(:codigo)";

        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':codigo', $codigo, $this->getConst($codigo));
        $consulta->execute();

        $datos = $consulta->fetch(PDO::FETCH_NUM);

        $consulta = null;
        $conexion = null;

        return $datos;
    }

    public function sigCodigo($tabla){
        $conexion = $this->container->get('bd');
        $sql = 'select siguiente'. $this->tabla . '()';
        $consulta = $conexion->prepare($sql);

        $consulta->execute();
        $datos = $consulta->fetch(PDO::FETCH_NUM);

        $consulta = null;
        $conexion = null;

        return $datos;
    }

    //FILTRO
    public function numRegs($datos = null){
        $sql = "select count(*) as regs from ".$this->tabla;
        if($datos){
            $sql .= " where ";
            foreach($datos as $clave => $valor){
                $sql .= "$clave LIKE '%$valor%' and ";
            }
            $sql = substr($sql, 0, -5);
        }
        $conexion = $this->container->get('bd');
        $consulta = $conexion->prepare($sql);
        $consulta->execute();

        $registro = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta = null;
        $conexion = null;

        return $registro['regs'];
    }

    public function filtrar($pag, $lim, $datos = null){
        $pag = ($pag - 1) * $lim;
        $sql = "select * from ".$this->tabla;
        if($datos){
            $sql .= " where ";
            foreach($datos as $clave => $valor){
                $sql .= "$clave LIKE '%$valor%' and ";
            }
            $sql = substr($sql, 0, -5);
        }

        $sql .= " LIMIT :pag, :lim";
        $conexion = $this->container->get('bd');

        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':pag', $pag, PDO::PARAM_INT);
        $consulta->bindParam(':lim', $lim, PDO::PARAM_INT);
        $consulta->execute();

        $datos = [];

        if ($consulta->rowCount() > 0) {
            $i = 0;
            while ($resultado = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $i++;
                foreach ($resultado as $clave => $valor) {
                    $datos[$i][$clave] = $valor;
                }
            }
        }

        $consulta = null;
        $conexion = null;

        return $datos;
    }

    public function modificarToken($id, $token = ""){
        $conexion = $this->container->get('bd');
        $sql = "select modificarToken(:id, :token)";
        $consulta = $conexion->prepare($sql);

        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':token', $token, PDO::PARAM_STR);

        $consulta->execute();

        $datos = $consulta->fetch(PDO::FETCH_NUM);
        $consulta = null;
        $conexion = null;

        return $datos;
    }

    public function refreshToken($id, $token){
        $conexion = $this->container->get('bd'); 
        $sql = "select * from usuario where id= :id and rfT = :rft";

        $consulta = $conexion->prepare($sql);

        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':rft', $token, PDO::PARAM_STR);

        $consulta->execute();

        $datos = $consulta->fetchAll();
        $consulta = null;
        $conexion = null;

        return $datos;
    }

    //CRUD PERSONA....................................
    public function todosP($indice, $limite){
        $conexion = $this->container->get('bd');
        $sql = "call todosPersona(:indice, :limite)";

        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':indice', $indice, PDO::PARAM_INT);
        $consulta->bindParam(':limite', $limite, PDO::PARAM_INT);
        $consulta-> execute();

        $datos = [];

        if($consulta->rowCount() > 0){
            $i = 0;
            while($result = $consulta->fetch(PDO::FETCH_ASSOC)){
                $i++;
                foreach($result as $clave => $valor){
                    $datos[$i][$clave] = $valor;
                }
            }
        }

        $consulta = null;
        $conexion = null;

        return $datos;
    }

    public function filtrarP($campos, $valores){
        $conexion = $this->container->get('bd');
        $filtro = $this->cadenaFiltro($campos, $valores);

        $sql = "select * from persona where $filtro";
        $consulta = $conexion->prepare($sql);
        $consulta->execute();

        $datos = [];

        if ($consulta->rowCount() > 0) {
            $i = 0;
            while ($resultado = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $i++;
                foreach ($resultado as $clave => $valor) {
                    $datos[$i][$clave] = $valor;
                }
            }
        }

        $consulta = null;
        $conexion = null;

        return $datos;
    }

    public function buscarP($id){
        $conexion = $this->container->get('bd');
        $sql = "call buscarPersona(:id)";

        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        $datos = $consulta->fetchAll();
        $consulta = null;
        $conexion = null;

        return $datos;

    }

    public function guardarP($datos, $id = null){
        $conexion = $this->container->get('bd');

        $sql = "select nuevaPersona(:id, :nombre, :apellido1, :apellido2, :sexo, :barrio, :nivelAcademico, :telefonoCasa, :telefonoCelular, :telefonoOtro, :referidoIMAS, :fechaNac)";

        if($id != null){
            $sql = "select editarPersona(:id, :nombre, :apellido1, :apellido2, :sexo, :barrio, :nivelAcademico, :telefonoCasa, :telefonoCelular, :telefonoOtro, :referidoIMAS, :fechaNac)";
        }

        $consulta = $conexion->prepare($sql);

        if($id != null){
            $consulta->bindParam(':id', $id, PDO::PARAM_STR);
        } else {
            $consulta->bindParam(':id', $datos->id, PDO::PARAM_STR);
        }

        $consulta->bindParam(':nombre', $datos->nombre, PDO::PARAM_STR);
        $consulta->bindParam(':apellido1', $datos->apellido1, PDO::PARAM_STR);
        $consulta->bindParam(':apellido2', $datos->apellido2, PDO::PARAM_STR);
        $consulta->bindParam(':sexo', $datos->sexo, PDO::PARAM_STR_CHAR);
        $consulta->bindParam(':barrio', $datos->barrio, PDO::PARAM_STR);
        $consulta->bindParam(':nivelAcademico', $datos->nivelAcademico, PDO::PARAM_STR);
        $consulta->bindParam(':telefonoCasa', $datos->telefonoCasa, PDO::PARAM_STR);
        $consulta->bindParam(':telefonoCelular', $datos->telefonoCelular, PDO::PARAM_STR);
        $consulta->bindParam(':telefonoOtro', $datos->telefonoOtro, PDO::PARAM_STR);
        $consulta->bindParam(':referidoIMAS', $datos->referidoIMAS, PDO::PARAM_STR_CHAR);
        $consulta->bindParam(':fechaNac', $datos->fechaNac, PDO::PARAM_STR);

        $consulta->execute();

        $datos = $consulta->fetch(PDO::FETCH_NUM);

        $consulta = null;
        $conexion = null;

        return $datos;
    }

    public function eliminarP($id){
        $conexion = $this->container->get('bd');
        $sql = "select eliminarPersona(:id)";

        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        $datos = $consulta->fetch(PDO::FETCH_NUM);

        $consulta = null;
        $conexion = null;

        return $datos;
    }

}
