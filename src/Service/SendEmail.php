<?php
namespace MessageQueue\Service;

class SendEmail extends AbstractService
{

    function perform($arguments = array())
    {
        $params = array(
            'to' => $arguments['to'],
            'subject' => $arguments['subject'],
            'content' => $arguments['content'],
            'fromName' => $arguments['fromName'],
            'from' => $arguments['from'],
        );
        $result = call_user_func_array(array($this, 'sendEmail'), $params);
        //如果执行失败
        if (! $result) {
            $counter = isset($arguments['_counter']) ? intval($arguments['_counter']) : 0;
            //如果计数器小于2，则重新入队
            if ($counter <= 2) {
                //计数器自增
                $arguments['_counter'] = $counter + 1;
                $this->getClient()->callDispatcherJob('SendEmail', $arguments);
            }
            $this->log("send email  to <{$arguments['to']}> " . ($result ? 'success' : 'fail'));
        }
        return $result;
    }

    /**
     * 发送邮件
     *
     * @param string $to 目标邮箱
     * @param string $subject 邮件subject
     * @param string $content 邮件content
     * @param string $fromName 邮件发件人
     * @param string $from 邮件发件邮箱
     * @return boolean
     */
    function sendEmail($to, $subject, $content, $fromName, $from)
    {
        //todo
    }
}