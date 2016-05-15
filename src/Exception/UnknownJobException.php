<?php
namespace MessageQueue\Exception;

class UnknownJobException extends \Exception
{

    function __construct($jobName)
    {
        $message = sprintf('Jon "%s" is Unknow');
        parent::__construct($message);
    }
}