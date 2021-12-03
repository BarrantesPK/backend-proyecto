<?php 
namespace App\Controllers; 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Usuario extends BaseBD {
    public function consultarUsuarios(Request $req, Response $res, $args) {
        $indice =  $req->getAttribute('indice');
        $limite = $req->getAttribute('limite');

        $this->iniciar('Usuario', 'id_usuario');
        $usuarios = $this->todos($indice, $limite);
        $status = sizeof($usuarios) > 0 ? 200 : 204;

        $res->getBody()->write(json_encode($usuarios));

        return $res
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

    public function consultarUsuario(Request $req, Response $res, $args) {
        $codigo = $req->getAttribute('codigo');

        $this->iniciar('Usuario', 'id');
        $usuario = $this->buscar($codigo);
        $status = sizeof($usuario) > 0 ? 200 : 404;

        $res->getBody()->write(json_encode($usuario));

        return $res
            ->withHeader('Content-Type', 'application\json')
            ->withStatus($status);
    }

    public function nuevoUsuario(Request $req, Response $res, $args) {
        $body = json_decode($req->getBody());
        $opciones = [
            'cost' => 11
        ];

        $body->passw = password_hash($body->passw, PASSWORD_BCRYPT, $opciones);

        $this->iniciar('Usuario', 'id_usuario');
        $datos = $this->guardar($body);
        $status = $datos[0] > 0 ? 409 : 201;

        $res->getBody()->write(json_encode($datos));
        return $res
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

    public function filtrarUsuario(Request $req, Response $res, $args) {
        $campos = explode('&', $req->getAttribute('campos'));
        $valores = explode('&', $req->getAttribute('valores'));

        //campos y valores son arrays
        $this->iniciar('Usuario', 'id');
        $datos = $this->filtrar($campos, $valores);
        $status = sizeof($datos) > 0 ? 200 : 404;

        $res->getBody()->write(json_encode($datos));

        return $res
            ->withHeader('Content-Type', 'application\json')
            ->withStatus($status);
    }

    public function editarUsuario(Request $req, Response $res, $args) {
        $body = json_decode($req->getBody());
        $codigo = $req->getAttribute('codigo');

        $this->iniciar('Usuario', 'id');
        $datos = $this->guardar($body, $codigo);
        $status = $datos[0] == 0 ? 404 : 200;

        $res->getBody()->write(json_encode($datos));
        return $res
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

    public function eliminarUsuario(Request $req, Response $res, $args) {
        $codigo = $req->getAttribute('codigo');

        $this->iniciar('Usuario', 'id');
        $datos = $this->eliminar($codigo);
        $status = $datos[0] == 0 ? 404 : 200;

        $res->getBody()->write(json_encode($datos));
        return $res
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}


?>
