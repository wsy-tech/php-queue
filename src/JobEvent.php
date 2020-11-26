<?php


namespace wsy;


class JobEvent extends BaseObject
{
    public $id = 0;
    public $job;
    public $ttr = 300;
    public $sender;
    public $delay = 0;

}