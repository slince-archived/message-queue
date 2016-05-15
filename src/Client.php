<?php
/**
 * message queue library
 * @author Taosikai
 */
namespace MessageQueue;

use Resque;
use Resque_Job_Status;
use MessageQueue\Exception\UnknownJobException;

class Client
{

    /**
     * 连接信息
     * 
     * @var string
     */
    protected $_connection = '127.0.0.1:6379';

    /**
     * 预定义的job
     * 
     * @var array
     */
    protected $_jobs = array();

    /**
     * job别名
     * 
     * @var array
     */
    protected $_aliases = array();
    
    /**
     * job分发类
     * 
     * @var string
     */
    protected $_dispatcherJobClass = '\\MessageQueue\\Job\\Dispatcher';
    
    /**
     * 最近一次的token
     * 
     * @var string
     */
    protected $_lastToken = '';
    
    function __construct($connection = null)
    {
        if (! is_null($connection)) {
            $this->_connection = $connection;
        }
        Resque::setBackend($this->_connection);
    }

    /**
     * 给job设置别名，可以传入数组，表示替换当前的所有配置
     * 
     * @param string|array $alias
     * @param string $jobName
     */
    function alias($alias, $jobName = null)
    {
        if (is_array($alias)) {
            $this->_aliases = $alias;
        } else {
            $this->_aliases[$alias] = $jobName;
        }
    }

    /**
     * 设置预定义的job，可以传入数组，表示替换当前的所有job
     * 
     * @param string|array $jobName
     * @param string $queue
     */
    function jobs($jobName, $queue = null)
    {
        if (is_array($jobName)) {
            $this->_jobs = $jobName;
        } else {
            $this->_jobs[$jobName] = $queue;
        }
    }

    /**
     * 调用job服务,同步调用job
     * 
     * @param string $jobName 服务名
     * @param array $arguments 传入给Job的参数，job通过$this->args获取
     * @return string job id
     */
    function call($jobName, $arguments = array())
    {
        list ($job, $queue) = $this->_getJob($jobName);
        return $this->_lastToken = Resque::enqueue($queue, $jobClass, $arguments, true);
    }
    
    /**
     * 异步job服务，(通过Dispatcher Job代理)
     * 
     * @param string $jobName
     * @param array $arguments
     * @return string
     */
    function callDispatcherJob($jobName, $arguments)
    {
        list ($job, $queue) = $this->_getJob($jobName);
        $arguments['job'] = $job;
        return $this->_lastToken = Resque::enqueue($queue, $this->_dispatcherJobClass, $arguments, true);
    }
    
    /**
     * 设置异步分发job class
     * 
     * @param string $class
     */
    function setDispatcherClass($class)
    {
        $this->_dispatcherJobClass = $class;
    }
    
    /**
     * 获取异步分发job class
     * @return string
     */
    function getDispatcherClass()
    {
        return $this->_dispatcherJobClass;
    }
    
    /**
     * 检查job状态，可以不传入参数表示查询上一次的状态
     * 
     * @param string $token job的id
     */
    function getStatus($token = null)
    {
        if (is_null($token)) {
            $token = $this->_lastToken;
        }
        $status = new Resque_Job_Status($token);
        if (! $status->isTracking()) {
            return false;
        }
        return $status->get();
    }

    /**
     * 最近一次发送的job的token
     * 
     * @return string
     */
    function getLastToken()
    {
        return $this->_lastToken;
    }
    /**
     * 获取job所在队列以及job执行类
     * 
     * @param string $jobName
     * @throws UnknowJobException
     * @return multitype:string
     */
    protected function _getJob($jobName)
    {
        $job = '';
        $queue = '';
        if (isset($this->_jobs[$jobName])) {
            $job = $jobName;
            $queue = $this->_jobs[$jobName];
        } elseif (isset($this->_aliases[$jobName])) {
            $job = $this->_aliases[$jobName];
            $queue = $this->_jobs[$job];
        } else {
            throw new UnknownJobException($jobName);
        }
        return array($job, $queue);
    }
    
}