<?php


namespace wsy;


class JobEvent extends BaseObject
{
    /**
     * @var int
     */
    public $id = 0;
    /**
     * @var
     */
    public $job;
    /**
     * @var int
     */
    public $ttr = 300;
    /**
     * @var
     */
    public $sender;
    /**
     * @var int
     */
    public $delay = 0;

}