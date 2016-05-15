<?php
namespace MessageQueue\Service;

use MessageQueue\ServiceRegistry;
use MessageQueue\Client;

abstract class AbstractService implements ServiceInterface
{

    /**
     * Message Queue Client
     * @var Client
     */
    protected $_client;
    
    /**
     * 记录日志
     *
     * @param string $message            
     */
    function log($message)
    {
        $message = date('Y-m-d H:i:s') . ": {$message} \r\n";
        @file_put_contents($this->_getLogFileName(), $message, FILE_APPEND);
    }

    /**
     * 获取日志文件名，一个service一个文件
     *
     * @return string
     */
    protected function _getLogFileName()
    {
        $logPath = ServiceRegistry::getConfig('logPath', './');
        $logPath = rtrim($logPath, '\\/') . DIRECTORY_SEPARATOR;
        $base = basename(str_replace('\\', '/', get_class($this))) . date('Ymd');
        return "{$logPath}/{$base}.log";
    }

    /**
     * tostring
     *
     * @return string
     */
    function __toString()
    {
        return get_class($this);
    }
    
    /**
     * 获取消息队列Client
     * @return Client
     */
    function getClient()
    {
        if (! $this->_client instanceof Client) {
            $clientConfig = ServiceRegistry::getConfig('clientConfig');
            $this->_client = new Client($clientConfig['connection']);
            $this->_client->jobs($clientConfig['jobs']);
        }
        return $this->_client;
    }
}