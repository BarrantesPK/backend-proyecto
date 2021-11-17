<?php
use Slim\Factory\AppFactory;
use Tuupola\Middleware\JwtAuthentication;

require __DIR__ . '/../../vendor/autoload.php';
// require '../../env.php';

$auxiliar = new \DI\Container;

AppFactory::setContainer($auxiliar);

$app = AppFactory::create();    
$app->addErrorMiddleware(true, true, true);//Solo se utiliza para desarrollo, no para producción

$container = $app->getContainer();

$app->add(new JwtAuthentication([
    'secure' => false,
    'secret' => "EstoEsUnaClave",
    //'path' => ['/usuario'], //si no asigno rutas, por defecto se van a bloquear todas las rutas
    'ignore' => ['/auth', '/sas', '/sah', '/spr', '/sca', '/']
]));

require 'Routes.php';
require 'Config.php';
require 'Conexion.php';

$app->run();