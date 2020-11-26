<?php

use wsy\JobInterface;
use wsy\Queue;
use wsy\tests\DownloadJob;

require './vendor/autoload.php';




(new Queue())->push(new DownloadJob([
    'url' => 'http://image.ngchina.com.cn/2020/1102/20201102104040459.jpg',
    'file' => 'image.jpg',
]));

