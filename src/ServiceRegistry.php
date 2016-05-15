<?php
/**
 * message queue library
 * @author Taosikai
 */
namespace MessageQueue;

class ServiceRegistry
{

    /**
     * 配置参数
     * 
     * @var array
     */
    static $configs = array();
    
    /**
     * 加载配置
     * 
     * @param array $configs
     */
    static function loadConfigs($configs)
    {
        self::$configs = $configs;
    }
    
    /**
     * 获取配置参数
     * 
     * @param string $name
     * @param string $default
     * @return Ambigous <string, multitype:>
     */
    static function getConfig($name, $default = null)
    {
        return isset(self::$configs[$name]) ? self::$configs[$name] : $default;
    }
    
    /**
     * 执行service
     * 
     * @param string $serviceName
     * @param array $arguments
     */
    function perform($serviceName, $arguments = array())
    {
        $service = ServiceFactory::create($serviceName);
        return $service->perform($arguments);
    }
}