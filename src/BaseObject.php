<?php


namespace wsy;


class BaseObject
{
    public function __construct($parameter = [])
    {
        foreach ($parameter as $name => $value) {
            $this->$name = $value;
        }
    }
}