<?php


namespace wsy;

use wsy\amqp\Queue as amqp;

use Exception;

class Queue
{

    /**
     * @var null
     */
    protected static $connector = null;


    /**
     * Queue constructor.
     * @param Conf|null $conf
     */
    public function __construct($conf = [])
    {
        if (is_null(self::$connector)) {
            self::$connector = new amqp($conf);
        }
    }

    /**
     * 任务生产
     * @param $job
     * @return int|string
     * @throws Exception
     */
    public function push($job)
    {
        $event = new JobEvent([
            'job' => $job
        ]);
        if (!($event->job instanceof JobInterface)) {
            throw new Exception('Job must be instance of JobInterface.');
        }
        $message = serialize($event->job);
        $event->id = self::$connector->pushMessage($message, $event->ttr);
        return $event->id;
    }

    /**
     *
     * 消费者事件监听
     *
     */
    public function listen()
    {
        self::$connector->listen();
    }

}