<?php


namespace wsy\tests;
use wsy\JobInterface;

class DownloadJob implements JobInterface
{

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function execute($queue)
    {
        // TODO: Implement execute() method.
        file_put_contents($this->data['file'], file_get_contents($this->data['url']));
    }
}