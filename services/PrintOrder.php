<?php
namespace MessageQueue\Service;

class PrintOrder extends AbstractService
{

    function perform($arguments = array())
    {
        global $db;
        $billno = strval($arguments['billno']);
        $order = $db->getRow("select * from rs_order where billno = '{$billno}' limit 1");
        $this->log(print_r($order, true));
    }
}