<?php
namespace MessageQueue\Job;
 
interface JobInterface
{
    function perform();
}