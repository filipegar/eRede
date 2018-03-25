<?php

namespace Filipegar\eRede\Acquirer;

/**
 * Class Environment
 *
 * @package Filipegar\eRede\Acquirer
 */
class Environment implements \Filipegar\eRede\Environment
{
    private $api;

    /**
     * Environment constructor.
     *
     * @param $api
     */
    private function __construct($api)
    {
        $this->api = $api;
    }

    /**
     * @return Environment
     */
    public static function sandbox()
    {
        $api = 'https://api.userede.com.br/desenvolvedores/v1/';
        return new Environment($api);
    }

    /**
     * @return Environment
     */
    public static function production()
    {
        $api = 'https://api.userede.com.br/erede/v1/';
        return new Environment($api);
    }

    /**
     * Get Environment API URL
     *
     * @return string API URL
     */
    public function getApiUrl()
    {
        return $this->api;
    }
}
