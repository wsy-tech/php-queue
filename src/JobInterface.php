<?php

namespace wsy;

interface JobInterface
{
    public function execute($queue);
}
