<?php


namespace wsy;


class BaseObject
{
    /**
     * BaseObject constructor.
     * @param array $parameter
     */
    public function __construct($parameter = [])
    {
        foreach ($parameter as $name => $value) {
            $this->$name = $value;
        }
    }
}