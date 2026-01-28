<?php

namespace OpenSID\LaravelCI3\Services;

/**
 * Simple CI3 Instance Wrapper
 */
class CI3Instance
{
    protected $ci;

    public function __construct()
    {
        $this->ci = CI3Bootstrap::getInstance();
    }

    public function getInstance()
    {
        return $this->ci;
    }

    public function __get($name)
    {
        return $this->ci ? $this->ci->$name : null;
    }

    public function __isset($name)
    {
        return $this->ci ? isset($this->ci->$name) : false;
    }

    public function __call($name, $arguments)
    {
        if ($this->ci && method_exists($this->ci, $name)) {
            return call_user_func_array([$this->ci, $name], $arguments);
        }
        throw new \BadMethodCallException("Method {$name} does not exist");
    }
}
