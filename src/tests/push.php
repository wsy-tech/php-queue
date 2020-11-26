<?php


use wsy\Queue;
use wsy\tests\DownloadJob;

require './vendor/autoload.php';


try {
    (new Queue())->push(new DownloadJob([
        'url' => 'http://image.ngchina.com.cn/2020/1102/20201102104040459.jpg',
        'file' => 'image.jpg',
    ]));
} catch (Exception $e) {
    throw new Exception($e->getMessage());
}


