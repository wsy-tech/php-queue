<?php

namespace wsy\amqp;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use wsy\JobEvent;
use wsy\QueueInterface;

class Queue implements QueueInterface
{

    /**
     * @var
     */
    protected $connection;
    /**
     * @var
     */
    protected $channel;

    /**
     * @var array
     */
    private $config = [
        'host' => 'localhost',
        'port' => 5672,
        'user' => 'guest',
        'password' => 'guest',
        'exchangeName' => 'exchange',
        'queueName' => 'queue',
        'vhost' => '/'
    ];

    /**
     * Queue constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * @return mixed|void
     */
    public function open()
    {
        if ($this->channel) {
            return;
        }
        $conf = $this->config;
        $this->connection = new AMQPStreamConnection(
            $conf['host'],
            $conf['port'],
            $conf['user'],
            $conf['password'],
            $conf['vhost']
        );
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(
            $conf['queueName'],
            false,
            true,
            false,
            false
        );
        $this->channel->exchange_declare(
            $conf['exchangeName'],
            'direct',
            false,
            true,
            false
        );
        $this->channel->queue_bind($conf['queueName'], $conf['exchangeName']);
    }

    /**
     * @param $message
     * @param $ttr
     * @return mixed|string
     */
    public function pushMessage($message, $ttr)
    {
        $conf = $this->config;
        $this->open();
        $id = uniqid('', true);
        $this->channel->basic_publish(
            new AMQPMessage("$ttr;$message", [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_NON_PERSISTENT,
                'message_id' => $id,
            ]),
            $conf['exchangeName']
        );
        return $id;
    }

    /**
     * @return mixed|void
     */
    public function listen()
    {
        $conf = $this->config;
        $this->open();
        $callback = function (AMQPMessage $payload) use (&$conf) {
            $id = $payload->get('message_id');
            list($ttr, $message) = explode(';', $payload->body, 2);
            $job = unserialize($message);
            $event = new JobEvent([
                'id' => $id,
                'job' => $job,
                'ttr' => $ttr
            ]);
            if(call_user_func([$event->job, 'execute'], $conf['queueName'])){
                $payload->delivery_info['channel']->basic_ack($payload->delivery_info['delivery_tag']);
            } 
        };
        $this->channel->basic_qos(null, 1, null);
        $this->channel->basic_consume($conf['queueName'], '', false, false, false, false, $callback);
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    /**
     * @return mixed|void
     */
    public function close()
    {
        if (!$this->channel) {
            return;
        }
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * 关闭连接
     */
    public function __destruct(){
        $this->close();
    }

}
