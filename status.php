<?php
use MessageQueue\Client;

include 'vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');

$token = $argv[1];

$config = include 'config/client.php';

$client = new Client($config['connection']);

$status = $client->getStatus($token);

if ($status === false) {
    exit("Resque is not tracking the status of this job.\n");
}
echo "Tracking status of {$token}. Press [break] to stop.\n\n";
while (true) {
    $desc = getStatusDesc($status);
    fwrite(STDOUT, "Status of {$token} is: {$desc}\n");
    sleep(1);
}

/**
 * 获取job状态对应的描述
 * 
 * @param int $status
 * @return string
 */
function getStatusDesc($status)
{
    $desc = '';
    switch ($status) {
        case Resque_Job_Status::STATUS_WAITING:
            $desc = 'waiting';
            break;
        case Resque_Job_Status::STATUS_RUNNING:
            $desc = 'running';
            break;
        case Resque_Job_Status::STATUS_FAILED:
            $desc = 'failed';
            break;
        case Resque_Job_Status::STATUS_COMPLETE:
            $desc = 'complete';
            break;
    }
    return $desc;
}
?>
