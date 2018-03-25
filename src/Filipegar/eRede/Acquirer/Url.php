<?php

namespace Filipegar\eRede\Acquirer;

class Url implements \JsonSerializable
{
    const URL_SUCCESS = 'threeDSecureSuccess';
    const URL_FAILURE = 'threeDSecureFailure';
    const URL_CALLBACK = 'callback';

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
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $this->url = $url;
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
