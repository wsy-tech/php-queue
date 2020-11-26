<?php


namespace wsy;


interface QueueInterface
{
    public function open();

    public function push($message, $ttr);

    public function listen();

    public function close();
}