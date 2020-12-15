<?php
use MessageQueue\Factory;
use MessageQueue\Server;
use MessageQueue\Service\SendEmail;

include 'vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');
// 脚本终止生成文件
register_shutdown_function('shutdownCallback');
function shutdownCallback($errno, $errstr, $errfile = '', $errline = '')
{
    $message = "{$errno}, {$errstr} {$errfile} {$errline} " . date('Y-m-d H:i:s');
    file_put_contents(__DIR__ . '/shutdown.lock', $message);
    $mailService = new SendEmail();
    $mailService->perform(array(
        'to' => 'xxxxx',
        'subject' => '脚本异常结束',
        'content' => $message,
        'fromName' => 'Job Service',
        'from' => 'xxxxx',
    ));
}
//服务端配置
$configs = include __DIR__ . '/config/server.php';
Server::loadConfigs($configs);

$server = Factory::createServer(array('mail'), $configs['connection']);
$server->setLogLevel(Server::LOG_NONE);
$server->run();
