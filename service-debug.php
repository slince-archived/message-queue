<?php
/**
 * service调试文件(建议使用标准的soapclient调式工具)；
 * query参数name表示要调用的服务名；post参数arguments表示传递的参数
 * 如果没有arguments则query参数中除name之外的参数作为传递参数
 * 
 * @author Taosikai
 */
use MessageQueue\ServiceRegistry;

include 'vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');

$configs = include __DIR__ . '/config/service.php';
ServiceRegistry::loadConfigs($configs);

$serviceName = isset($_GET['name']) ? trim($_GET['name']) : '';
//如果没有post的arguments，使用query参数
if (! isset($_POST['arguments'])) {
    $arguments = $_GET;
    unset($arguments['name']);
} else {
    $arguments = $_POST['arguments'];
}

require __DIR__ . '/init/resource.php';
$registry = new ServiceRegistry();
$registry->perform($serviceName, $arguments);