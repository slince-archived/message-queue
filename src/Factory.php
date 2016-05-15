<?php
/**
 * message queue library
 * @author Taosikai
 */
namespace MessageQueue;

use Resque_Worker;

class Factory
{

    /**
     * 创建服务
     * 
     * @param array $queues 要处理的消息队列
     * @param string $connection 连接信息
     * @param string $interval
     * @return \MessageQueue\Server
     */
    static function createServer($queues, $connection = null, $interval = null)
    {
        $worker = new Resque_Worker($queues);
        return new Server($worker, $connection, $interval);
    }
}