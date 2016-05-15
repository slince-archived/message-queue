<?php
use MessageQueue\ServiceRegistry;

include 'vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');

$configs = include __DIR__ . '/config/service.php';
ServiceRegistry::loadConfigs($configs);

require __DIR__ . '/init/resource.php';

$soapOptions = array(
    'uri' => 'service',
    'encoding' => 'UTF-8'
);
$server = new \SoapServer(null, $soapOptions);
$server->setClass('\\MessageQueue\\ServiceRegistry');
$server->handle();