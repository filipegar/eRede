<?php

namespace Filipegar\eRede\Acquirer;

use Filipegar\eRede\Acquirer\Requests\Requestable;
use Filipegar\eRede\Acquirer\Traits\DoesRequests;

class Refund implements \JsonSerializable, Requestable
{
    use DoesRequests;

    private $refundId;
    private $tid;
    private $nsu;
    private $refundDateTime;
    private $cancelId;
    private $returnCode;
    private $returnMessage;
    private $links;
    private $urls;
    private $amount;
    private $status;

    /**
     * @param $json
     *
     * @return Refund
     */
    public static function fromJson($json)
    {
        $object = json_decode($json);

        $refund = new Refund();
        $refund->populate($object);

        return $refund;
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->returnCode = isset($data->returnCode) ? $data->returnCode : null;
        $this->returnMessage = isset($data->returnMessage) ? $data->returnMessage : null;
        $this->tid = isset($data->tid) ? $data->tid : null;
        $this->nsu = isset($data->nsu) ? $data->nsu : null;
        $this->refundId = isset($data->refundId) ? $data->refundId : null;
        $this->cancelId = isset($data->cancelId) ? $data->cancelId : null;
        $this->refundDateTime = isset($data->refundDateTime) ?
            \DateTime::createFromFormat(\DateTime::ISO8601, $data->refundDateTime) : null;
        $this->amount = isset($data->amount) ? $data->amount : null;
        $this->status = isset($data->status) ? $data->status : null;

        if (isset($data->links)) {
            $this->links = [];
            foreach ($data->links as $link) {
                array_push($this->links, $link);
            }
        }
    }



    /**
     * @return mixed
     */
    public function getRefundId()
    {
        return $this->refundId;
    }

    /**
     * @param mixed $refundId
     * @return Refund
     */
    public function setRefundId($refundId)
    {
        $this->refundId = $refundId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * @param mixed $tid
     * @return Refund
     */
    public function setTid($tid)
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNsu()
    {
        return $this->nsu;
    }

    /**
     * @param mixed $nsu
     * @return Refund
     */
    public function setNsu($nsu)
    {
        $this->nsu = $nsu;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRefundDateTime()
    {
        return $this->refundDateTime;
    }

    /**
     * @param mixed $refundDateTime
     * @return Refund
     */
    public function setRefundDateTime($refundDateTime)
    {
        $this->refundDateTime = $refundDateTime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCancelId()
    {
        return $this->cancelId;
    }

    /**
     * @param mixed $cancelId
     * @return Refund
     */
    public function setCancelId($cancelId)
    {
        $this->cancelId = $cancelId;

        return $this;
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
     * @return Refund
     */
    public function setReturnCode($returnCode)
    {
        $this->returnCode = $returnCode;

        return $this;
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
     * @return Refund
     */
    public function setReturnMessage($returnMessage)
    {
        $this->returnMessage = $returnMessage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param mixed $links
     * @return Refund
     */
    public function setLinks($links)
    {
        $this->links = $links;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     * @return Refund
     */
    public function setAmount($amount)
    {
        $this->amount = intval($amount);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Refund
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @param Url $url
     * @return Refund
     */
    public function setUrl(Url $url)
    {
        if (empty($this->urls)) {
            $this->urls = [];
        }

        array_push($this->urls, $url);

        return $this;
    }

    public function url($url, $urlKind)
    {
        $link = new Url();
        $link->setKind($urlKind);
        $link->setUrl($url);

        $this->setUrl($link);

        return $this;
    }
}
