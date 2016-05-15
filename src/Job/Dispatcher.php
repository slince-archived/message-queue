<?php
namespace MessageQueue\Job;

use MessageQueue\Exception\MissServiceException;
use MessageQueue\Server;

class Dispatcher extends AbstractJob
{

    function perform()
    {
        $arguments = $this->args;
        if (! isset($arguments['job'])) {
            throw new MissServiceException();
        }
        $soapClient = new \SoapClient(null, $this->_getSoapOption());
        $this->out("Start dispatch {$arguments['job']}");
        try{
            $result = $soapClient->__soapCall('perform', array($arguments['job'], $arguments));
            if (! is_null($result) && empty($result)) {
                throw new \Exception("Service perform Fail");
            }
            $this->out("Success dispatch {$arguments['job']}");
        } catch (\SoapFault $e) {
            $this->out("Error dispatch {$arguments['job']}: {$e->getMessage()}");
            throw $e;
        }
    }
    
    /**
     * 获取soap options
     * 
     * @return \MessageQueue\Ambigous
     */
    protected function _getSoapOption()
    {
        return Server::getConfig('soapOption');
    } 
}