<?php
namespace MessageQueue\Service;

class LogParam extends AbstractService
{

    function perform($arguments = array())
    {
        $this->log(print_r($arguments, true));
    }
}