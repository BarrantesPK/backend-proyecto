<?php 
namespace App\Controllers\sca; 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProofCajas {

    public function consultaPrueba(Request $req, Response $res, $args) {
        $proof = "Rutas de SCA funcionando";

        $res->getBody()->write(json_encode($proof));

        return $res
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function inicio(Request $req, Response $res, $args) {
        $proof = "Ruta de inicio del servidor";

        $res->getBody()->write(json_encode($proof));

        return $res
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

}

?>