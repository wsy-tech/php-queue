<?php


namespace wsy;
use Exception;

class Queue
{

    protected static $connector = null;


    public function __construct()
    {
        if (is_null(self::$connector)) {
            self::$connector = new amqp\Queue();
        }
    }

    public function push($job)
    {
        $event = new JobEvent([
            'job' => $job
        ]);
        if (!($event->job instanceof JobInterface)) {
            throw new Exception('Job must be instance of JobInterface.');
        }
        $message = serialize($event->job);
        $event->id = self::$connector->push($message, $event->ttr);
        return $event->id;
    }

    public  function listen()
    {
        self::$connector->listen();
    }

}