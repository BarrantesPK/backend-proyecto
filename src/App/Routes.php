<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request; 
use Slim\Routing\RouteCollectorProxy;

use App\Controllers\sas\ProofModuleSAS;
use App\Controllers\sah\ProofModuleSAH;
use App\Controllers\spr\ProofModuleSPR;
use App\Controllers\sca\ProofCajas;


$app->get('/', ProofCajas::class. ':inicio');

//Rutas usuarios
$app->group('/usuario', function(RouteCollectorProxy $usuario){   

    $usuario->get('/{indice}/{limite}', 'App\Controllers\Usuario:consultarUsuarios');
    
    $usuario->get('/{codigo}', 'App\Controllers\Usuario:consultarUsuario');

    $usuario->post('/', 'App\Controllers\Usuario:nuevoUsuario');

    $usuario->get('/filtro/{campos}/{valores}', 'App\Controllers\Usuario:filtrarUsuario');

    $usuario->put('/{codigo}', 'App\Controllers\Usuario:editarUsuario');

    $usuario->delete('/{codigo}', 'App\Controllers\Usuario:eliminarUsuario');
});

//Rutas seguridad
$app->group('/auth', function(RouteCollectorProxy $seguridad){

    $seguridad->post('/iniciar', 'App\Controllers\Seguridad:inicioSesion');

    $seguridad->post('/cerrar', 'App\Controllers\Seguridad:cerrarSesion');

    $seguridad->post('/cambiopass', 'App\Controllers\Seguridad:cambioPass');

    $seguridad->post('/resetpass', 'App\Controllers\Seguridad:resetPass');

    $seguridad->post('/refresh', 'App\Controllers\Seguridad:refrescarToken');
    
});


//Rutas de los demas modulos

//SAS
$app->group('/sas', function(RouteCollectorProxy $sas){

    $sas->get('/inicio', ProofModuleSAS::class. ':consultaPrueba');
    
});

//SAH
$app->group('/sah', function(RouteCollectorProxy $sah){

    $sah->get('/inicio', ProofModuleSAH::class. ':consultaPrueba');
    
});


//SPR
$app->group('/spr', function(RouteCollectorProxy $spr){

    $spr->get('/inicio', ProofModuleSPR::class. ':consultaPrueba');
    
});

//SCA
$app->group('/sca', function(RouteCollectorProxy $sca){

    $sca->get('/inicio', ProofCajas::class. ':consultaPrueba');
    
});