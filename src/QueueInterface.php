<?php


namespace wsy;


interface QueueInterface
{
    /**
     * @return mixed
     */
    public function open();

    /**
     * @param $message
     * @param $ttr
     * @return mixed
     */
    public function pushMessage($message, $ttr);

    /**
     * @return mixed
     */
    public function listen();

    /**
     * @return mixed
     */
    public function close();
}