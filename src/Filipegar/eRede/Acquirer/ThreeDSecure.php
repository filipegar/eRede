<?php

namespace Filipegar\eRede\Acquirer;

class ThreeDSecure implements \JsonSerializable
{
    const FAILURE_DECLINE = 'decline';
    const FAILURE_CONTINUE = 'continue';
    const MPI_EREDE = true;
    const MPI_CUSTOMER = false;

    private $embedded;
    private $onFailure;
    private $eci;
    private $cavv;
    private $xid;
    private $userAgent;
    private $redirectUrl;
    private $returnCode;
    private $returnMessage;
    private $threeDIndicator;
    private $DirectoryServerTransactionId;

    public static function fromJson($json)
    {
        $object = json_decode($json);

        $payment = new Payment();
        $payment->populate($object);

        return $payment;
    }

    public function populate(\stdClass $data)
    {
        if (isset($data->threeDSecure)) {
            $this->redirectUrl = isset($data->threeDSecure->url) ? $data->threeDSecure->url : null;
            $this->eci = isset($data->threeDSecure->eci) ? $data->threeDSecure->eci : null;
            $this->cavv = isset($data->threeDSecure->cavv) ? $data->threeDSecure->cavv : null;
            $this->xid = isset($data->threeDSecure->xid) ? $data->threeDSecure->xid : null;
            $this->returnCode = isset($data->threeDSecure->returnCode) ? $data->threeDSecure->returnCode : null;
            $this->returnMessage = isset($data->threeDSecure->returnMessage) ? $data->threeDSecure->returnMessage
                : null;
            $this->threeDIndicator = isset($data->threeDSecure->threeDIndicator) ? $data->threeDSecure->threeDIndicator : null;
            $this->DirectoryServerTransactionId = isset($data->threeDSecure->DirectoryServerTransactionId) ? $data->threeDSecure->DirectoryServerTransactionId : null;
        }
    }

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
        $this->embedded = boolval($embedded);
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
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param mixed $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return mixed
     */
    public function getReturnMessage()
    {
        return $this->returnMessage;
    }

    /**
     * @param mixed $returnMessage
     */
    public function setReturnMessage($returnMessage)
    {
        $this->returnMessage = $returnMessage;
    }

    /**
     * @return mixed
     */
    public function getReturnCode()
    {
        return $this->returnCode;
    }

    /**
     * @param mixed $returnCode
     */
    public function setReturnCode($returnCode)
    {
        $this->returnCode = $returnCode;
    }

    /**
     * @return string
     */
    public function getThreeDIndicator()
    {
        return $this->threeDIndicator;
    }

    /**
     * @param string $threeDIndicator
     */
    public function setThreeDIndicator($threeDIndicator)
    {
        $this->threeDIndicator = $threeDIndicator;
    }

    /**
     * @return string
     */
    public function getDirectoryServerTransactionId()
    {
        return $this->DirectoryServerTransactionId;
    }

    /**
     * @param string $DirectoryServerTransactionId
     */
    public function setDirectoryServerTransactionId($DirectoryServerTransactionId)
    {
        $this->DirectoryServerTransactionId = $DirectoryServerTransactionId;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = get_object_vars($this);
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }

        return $data;
    }
}