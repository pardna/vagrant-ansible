<?php
require_once __DIR__.'/../../vendor/autoload.php';
$swagger = \Swagger\scan(realpath(__DIR__ . '/..'));
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Atmosphere-tracking-id, X-Atmosphere-Framework, X-Cache-Date, Content-Type, X-Atmosphere-Transport, *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS , PUT');
header('Access-Control-Request-Headers: Origin, X-At2mosphere-tracking-id, X-Atmosphere-Framework, X-Cache-Date, Content-Type, X-Atmosphere-Transport,  *');
header('Content-Type: application/json');
echo $swagger;
