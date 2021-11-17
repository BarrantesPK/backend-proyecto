<?php
$container->set('config_bd', function() {
    return (object) [
        "host" => "localhost",
        "user" => "root",
        "pass" => "",
        "bd" => "prematric",
        "charset" => "utf8"
    ];
});