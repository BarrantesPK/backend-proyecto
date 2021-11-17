<?php 
namespace App\Controllers\sah; 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProofModuleSAH {

    public function consultaPrueba(Request $req, Response $res, $args) {
        $proof = "Rutas de SAH funcionando";

        $res->getBody()->write(json_encode($proof));

        return $res
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

}

?>