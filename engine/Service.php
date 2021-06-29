<?php

namespace Engine;

abstract class Service
{
    protected $state;
    protected $config;
    protected $validator;
    protected $container;

    public function __construct()
    {
        $this->dir = str_replace("engine", "", __DIR__);
        $this->configDir = $this->dir . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
        $this->config = parse_ini_file($this->configDir . 'config.ini', true);
        $this->validator = new \lib\Validator();
    }

    public function loadModel($model)
    {
        $class = '\\models\\' . $model . 'Model';
        return new $class();
    }

    public function loadService($service)
    {
        $class = '\\services\\' . $service . 'Service';
        return new $class($this->container);
    }
}
