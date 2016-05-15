<?php
namespace MessageQueue\Exception;

class MissServiceException extends \Exception
{

    function __construct($name)
    {
        $message = sprintf('Miss Service "%s"', $name);
        parent::__construct($message);
    }
}