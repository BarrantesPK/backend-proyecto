<?php
namespace App\Controllers; 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;
use \PDO;

class Seguridad extends BaseBD{

    private function identificarse($user, $pass){
        $this->iniciar('Usuario', 'id_usuario');
        $datos = $this->buscar($user);
        return ((sizeof($datos) > 0) && (password_verify($pass, $datos[0]->passw))) ? $datos : null;
    }

    private function generarToken($id_usuario, $id_rol){
        //le daremos un token
        $payload = [
            'iat' => time(),
            'iss' => $_SERVER['SERVER_NAME'],
            'exp' => time() + (3000),
            'sub' => $id_usuario,
            'rol' => $id_rol
        ];

        $token = JWT::encode($payload, "EstoEsUnaClave", 'HS256');

        //$datos = $this->modificarToken($id, $refreshToken);

        $resultado = [
            'token' => $token
        ];

        return $resultado;
    }

    public function inicioSesion(Request $req, Response $res, $args) {
        $body = json_decode($req->getBody());

        $datos = $this->identificarse($body->id_usuario, $body->passw);

        if($datos){
            $resultado = $this->generarToken($body->id_usuario, $datos[0]->id_rol);
        }

        if(isset($resultado)){
            $status = 200;
            $res->getBody()->write(json_encode($resultado));
        } else {
            $status = 401;
        }

        return $res
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($status);
    }

    public function cerrarSesion(Request $req, Response $res, $args) {
        $body = json_decode($req->getBody());
        $datos = $this->modificarToken($body->id);
        return $res
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
    }

    public function cambioPass(Request $req, Response $res, $args) {
        $body = json_decode($req->getBody());

        $datos = $this->identificarse($body->id_usuario, $body->passwV);

        if($datos){
            $opciones = [
                'cost' => 11
            ];

            $passwN = password_hash($body->passwN, PASSWORD_BCRYPT, $opciones); 

            $conexion = $this->container->get('bd');
            $sql = "select cambiarPasswUsuario(:id, :passw);";
            $consulta = $conexion->prepare($sql);

            $consulta->bindParam(':id', $body->id_usuario, PDO::PARAM_STR);
            $consulta->bindParam(':passw', $passwN, PDO::PARAM_STR);

            $status = $consulta->execute() ? 200 : 500; // si todo va bien, devuelve un ok, sino un error en el servidor
        } else {
            $status = 401; //el usuario no se encuentra autenticado
        }

        return $res
                ->withStatus($status);
    }

    public function resetPass(Request $req, Response $res, $args) {
        $body = json_decode($req->getBody());

        $opciones = [
            'cost' => 11
        ];

        $hash = password_hash($body->id_usuario, PASSWORD_BCRYPT, $opciones); 

        $conexion = $this->container->get('bd');
        $sql = "select cambiarPasswUsuario(:id, :passw);";
        $consulta = $conexion->prepare($sql);

        $consulta->bindParam(':id', $body->id_usuario, PDO::PARAM_STR);
        $consulta->bindParam(':passw', $hash, PDO::PARAM_STR);

        $status = $consulta->execute() ? 200 : 500; // si todo va bien, devuelve un ok, sino un error en el servidor

        return $res
                ->withStatus($status);
    }

    public function refrescarToken(Request $req, Response $res){
        $body = json_decode($req->getBody());

        $datos = $this->refreshToken($body->id_usuario, $body->rft);
        if(sizeof($datos) > 0){
            $resultado = $this->generarTokens($body->id_usuario, $datos[0]->id_rol);
        }
        
        if(isset($resultado)){
            $status = 200;
            $res->getBody()->write(json_encode($resultado));
        } else {
            $status = 401;
        }

        return $res
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($status);
    }
}
