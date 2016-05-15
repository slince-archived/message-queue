<?php
use MessageQueue\Client;

include 'vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');

$config = include 'config/client.php';

$client = new Client($config['connection']);
$client->jobs($config['jobs']);


$token = $client->callDispatcherJob('SendEmail', array(
    'to' => 'taosikai@dotfashion.cn',
    'subject' => 'Hello world',
    'content' => 'Hello, this is a test email from job service',
    'from' => 'taosikai@dotfashion.cn',
    'fromName' => 'JobService',
));
/*
$token = $client->callDispatcherJob('LogParam', array(
    'to' => 'taosikai@dotfashion.cn',
    'subject' => 'Hello world',
    'content' => 'Hello, this is a test email from job service',
    'from' => 'taosikai@dotfashion.cn',
    'fromName' => 'JobService',
));
$token = $client->callDispatcherJob('PrintOrder', array(
    'billno' => 'YLZ4904',
));*/
echo $token;
