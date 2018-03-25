<?php

namespace Filipegar\eRede\Acquirer;

class ThreeDSecureUrl implements \JsonSerializable
{
    const URL_SUCCESS = 'ThreeDSecureSuccess';
    const URL_FAILURE = 'ThreeDSecureFailure';
    const CALLBACK = 'Callback';

    private $kind;
    private $url;

    /**
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @param $kind
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}