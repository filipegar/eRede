<?php

namespace Filipegar\eRede\Acquirer;

class ThreeDSecure implements \JsonSerializable
{
    private $embedded;
    private $onFailure;
    private $eci;
    private $cavv;
    private $xid;
    private $userAgent;

    /**
     * @return string
     */
    public function getEmbedded()
    {
        return $this->embedded;
    }

    /**
     * @param string $embedded
     */
    public function setEmbedded($embedded)
    {
        $this->embedded = $embedded;
    }

    /**
     * @return string (ThreeDSecureOnFailure)
     */
    public function getOnFailure()
    {
        return $this->onFailure;
    }

    /**
     * @param string (ThreeDSecureOnFailure) $onFailure
     */
    public function setOnFailure($onFailure)
    {
        $this->onFailure = $onFailure;
    }

    /**
     * @return string
     */
    public function getEci()
    {
        return $this->eci;
    }

    /**
     * @param string $eci
     */
    public function setEci($eci)
    {
        $this->eci = $eci;
    }

    /**
     * @return string
     */
    public function getCavv()
    {
        return $this->cavv;
    }

    /**
     * @param string $cavv
     */
    public function setCavv($cavv)
    {
        $this->cavv = $cavv;
    }

    /**
     * @return string
     */
    public function getXid()
    {
        return $this->xid;
    }

    /**
     * @param string $xid
     */
    public function setXid($xid)
    {
        $this->xid = $xid;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
