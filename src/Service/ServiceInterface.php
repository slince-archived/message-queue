<?php
namespace MessageQueue\Service;

interface ServiceInterface
{
    function perform($arguments = array());
}