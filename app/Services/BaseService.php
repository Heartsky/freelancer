<?php
namespace App\Services;

abstract class BaseService {

    protected $config;
    public function __construct()
    {
        $config = config('import');
        $this->config = $config;
    }

    protected function getColumn($key) {
        return @$this->config[$key];
    }


}
