<?php

namespace wsy;

interface JobInterface
{
    /**
     * @param $queue
     * @return mixed
     */
    public function execute($queue);
}
