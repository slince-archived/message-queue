<?php
/**
 * message queue library
 * @author Taosikai
 */
namespace MessageQueue;

use Resque_Worker;
use Resque;

class Server
{

    /**
     * 日志级别，不记录
     * 
     * @var int
     */
    CONST LOG_NONE = Resque_Worker::LOG_NONE;

    /**
     * 日志级别，记录基本
     * 
     * @var int
     */
    CONST LOG_NORMAL = Resque_Worker::LOG_NORMAL;

    /**
     * 日志级别，记录基本详细
     * 
     * @var int
     */
    CONST LOG_VERBOSE = Resque_Worker::LOG_VERBOSE;
    
    /**
     * 日志记录级别
     * 
     * @var int
     */
    protected $_logLevel = self::LOG_NONE;

    /**
     * Resque_Worker
     *
     * @var Resque_Worker
     */
    protected $_resqueWorker;

    /**
     * 连接信息
     *
     * @var string
     */
    protected $_connection = '127.0.0.1:6379';

    /**
     * 间歇秒数
     * 
     * @var int
     */
    protected $_interval = 5;

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
    
    function __construct(Resque_Worker $resqueWorker, $connection = null, $interval = null)
    {
        $this->_resqueWorker = $resqueWorker;
        if (! is_null($connection)) {
            $this->_connection = $connection;
        }
        Resque::setBackend($this->_connection);
        if (! is_null($interval)) {
            $this->_interval = $interval;
        }
    }

    /**
     * 设置日志记录级别
     * 
     * @param int $level
     */
    function setLogLevel($level)
    {
        $this->_logLevel = $level;
    }
    
    /**
     * 获取resque worker
     * 
     * @return Resque_Worker
     */
    function getResqueWorker()
    {
        return $this->_resqueWorker;
    }
    /**
     * 运行当前server
     *
     * @return void;
     */
    function run()
    {
        $this->_resqueWorker->logLevel = $this->_logLevel;
        $this->_resqueWorker->work($this->_interval);
    }
}