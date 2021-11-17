<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-headers: X-API-KEY, Origin, X-Requested-with, Content-Type, Accept, Access-Control-Request-Method, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');
$method = $_SERVER['REQUEST_METHOD'];
if($method == 'OPTIONS'){
    die();
}
require __DIR__ . '/../src/app/App.php';