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
    private $oauth;
    private $oauthTokenPath;

    /**
     * Environment constructor.
     *
     * @param $api
     * @param $oauth
     * @param $oauthTokenPath
     */
    private function __construct($api, $oauth, $oauthTokenPath)
    {
        $this->api = $api;
        $this->oauth = $oauth;
        $this->oauthTokenPath = $oauthTokenPath;
    }

    /**
     * @return Environment
     */
    public static function sandbox()
    {
        $api = 'https://sandbox-erede.useredecloud.com.br/v2/';
        $oauth = 'https://rl7-sandbox-api.useredecloud.com.br/';
        return new Environment($api, $oauth, 'oauth2/token');
    }

    /**
     * @return Environment
     */
    public static function production()
    {
        $api = 'https://api.userede.com.br/erede/v2/';
        $oauth = 'https://rl7-sandbox-api.useredecloud.com.br';
        return new Environment($api, $oauth, 'redelabs/oauth2/token');
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

    /**
     * Gets the OAuth base URL used to negotiate access tokens.
     *
     * @return string
     */
    public function getOauthUrl()
    {
        return $this->oauth;
    }

    /**
     * Gets the OAuth token endpoint path.
     *
     * @return string
     */
    public function getOauthTokenPath()
    {
        return $this->oauthTokenPath;
    }
}
