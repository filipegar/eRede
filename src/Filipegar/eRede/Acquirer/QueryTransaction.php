<?php
namespace Filipegar\eRede\Acquirer;

class QueryTransaction implements \JsonSerializable
{
    private $transaction;
    private $capture;
    private $refunds;
    private $links;
    private $requestDateTime;

    /**
     * @param $json
     *
     * @return QueryTransaction
     */
    public static function fromJson($json)
    {
        $object = json_decode($json);

        $transaction = new QueryTransaction();
        $transaction->populate($object);

        return $transaction;
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->transaction = new Transaction();
        $this->transaction->populate($data->authorization);

        $this->requestDateTime = isset($data->requestDateTime) ?
            \DateTime::createFromFormat(\DateTime::ISO8601, $data->requestDateTime)
            : null;

        $this->capture = isset($data->capture) ? $data->capture : null;
        if (!empty($this->capture) && $this->capture->dateTime) {
            $this->capture->dateTime = \DateTime::createFromFormat(\DateTime::ISO8601, $this->capture->dateTime);
        }

        if (isset($data->links)) {
            $this->links = [];
            foreach ($data->links as $link) {
                array_push($this->links, $link);
            }
        }

        if (isset($data->refunds)) {
            $this->refunds = [];
            foreach ($data->refunds as $refund) {
                $refundObject = new Refund();
                $refundObject->populate($refund);
                array_push($this->refunds, $refundObject);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     * @return QueryTransaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCapture()
    {
        return $this->capture;
    }

    /**
     * @param mixed $capture
     * @return QueryTransaction
     */
    public function setCapture($capture)
    {
        $this->capture = $capture;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRefunds()
    {
        return $this->refunds;
    }

    /**
     * @param mixed $refunds
     * @return QueryTransaction
     */
    public function setRefunds($refunds)
    {
        $this->refunds = $refunds;

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
     * @return QueryTransaction
     */
    public function setLinks($links)
    {
        $this->links = $links;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestDateTime()
    {
        return $this->requestDateTime;
    }

    /**
     * @param mixed $requestDateTime
     * @return QueryTransaction
     */
    public function setRequestDateTime($requestDateTime)
    {
        $this->requestDateTime = $requestDateTime;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
