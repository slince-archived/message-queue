<?php
/**
 * message queue library
 * @author Taosikai
 */
namespace MessageQueue;

use MessageQueue\Exception\MissServiceException;

class ServiceFactory
{

    static function create($name)
    {
        $class = "\\MessageQueue\\Service\\{$name}";
        if (! class_exists($class)) {
            throw new MissServiceException($name);
        }
        return new $class();
    }
}