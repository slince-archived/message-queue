<?php
date_default_timezone_set('GMT');
require 'bad_job.php';
require 'job.php';
require 'php_error_job.php';

putenv('REDIS_BACKEND=192.168.1.107:6379');
putenv('QUEUE=*');
require '../resque.php';
?>