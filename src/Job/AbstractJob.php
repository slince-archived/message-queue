<?php
namespace MessageQueue\Job;

use MessageQueue\Server;

abstract class AbstractJob implements JobInterface
{

    /**
     * 打印到控制台
     * 
     * @param string $out
     */
    function out($out)
    {
        if (! Server::getConfig('stdOut')) {
            return false;
        }
        $message = date('Y-m-d H:i:s') . ' ' . get_class($this) . ": {$out}:\r\n";
        fwrite(STDOUT, $message);
    }
}